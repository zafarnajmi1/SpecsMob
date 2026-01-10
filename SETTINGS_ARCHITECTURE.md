# Dynamic Settings System - Visual Architecture

## System Flow Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                     YOUR APPLICATION                            │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ├──────────────────────────────┐
                              │                              │
                         ADMIN PANEL                   USER FRONTEND
                              │                              │
                         Settings Page                   All Pages
                              │                              │
                    Edit Logo, Name, etc.             Sidebar, Header, Footer,
                              │                      Contact Form, etc.
                              │                              │
                         ┌────▼──────────────────────────────┴───┐
                         │   SettingsService (app/Services)      │
                         │                                        │
                         │  Cache Handler:                        │
                         │  - Check Cache first                   │
                         │  - If empty → Query DB                 │
                         │  - Store in Cache (1 hour)             │
                         │  - Return to caller                    │
                         └────┬──────────────────────────────┬───┘
                              │                              │
                    ┌─────────▼──────────┐      ┌──────────▼───────┐
                    │  Laravel Cache     │      │  Database        │
                    │  (Memory/File)     │      │  system_settings │
                    │                    │      │                  │
                    │ Key: "system_      │      │  id: 1           │
                    │ settings"          │      │  site_name: "..."│
                    │ TTL: 3600 sec      │      │  site_logo: "..."│
                    │                    │      │  ... (21 fields) │
                    └────────────────────┘      └──────────────────┘
                              │
                              │
                    ┌─────────▼────────────────────────────────────┐
                    │  SettingsHelper Functions                    │
                    │  (app/Helpers/SettingsHelper.php)            │
                    │                                              │
                    │  setting($key, $default)                    │
                    │  settings()                                 │
                    │                                              │
                    │  ✓ Available everywhere in app              │
                    │  ✓ Blade templates, PHP code, etc.          │
                    └───────────┬───────────────────────────────────┘
                                │
                    ┌───────────┴──────────────────────────────────┐
                    │                                              │
                    ▼                                              ▼
          BLADE TEMPLATES                            CONTROLLERS/SERVICES
                    │                                              │
        ┌───────────┴──────────────┐                    ┌─────────┴────────┐
        │                          │                    │                  │
        ▼                          ▼                    ▼                  ▼
   Frontend Views            Admin Views         Contact Handler      Email Sender
   - Header                - Settings Form      - Submit Contact     - From Address
   - Sidebar               - List Settings      - Process Form       - From Name
   - Footer                - Edit Settings      - Send Email         - Host/Port
   - Login                                                           - Username
   - Contact Form                                                    - Password
   - Social Links
   - Ad Sections
   - Etc.
```

## Data Flow on Admin Update

```
STEP 1: Admin Action
┌─────────────────────────────────┐
│ Admin clicks "Save Settings"    │
│ Changes:                         │
│ - Site Logo                      │
│ - Site Name                      │
└──────────────┬──────────────────┘
               │
               ▼
STEP 2: Form Submission
┌─────────────────────────────────┐
│ POST /admin/settings             │
│ SettingsController@update()      │
└──────────────┬──────────────────┘
               │
               ▼
STEP 3: Database Update
┌──────────────────────────────────┐
│ File Upload (if image):          │
│ - Delete old logo from storage   │
│ - Store new logo to storage/...  │
│                                  │
│ Database Update:                 │
│ UPDATE system_settings SET       │
│   site_logo = 'new/path'         │
│   site_name = 'New Name'         │
│ WHERE id = 1                     │
└──────────────┬─────────────────┘
               │
               ▼
STEP 4: Cache Clearing
┌──────────────────────────────────┐
│ app('settings')->clearCache()    │
│                                  │
│ Cache Key Removed:               │
│ - 'system_settings' ✓ DELETED    │
│                                  │
│ Success Toast:                   │
│ "Settings updated successfully"  │
└──────────────┬─────────────────┘
               │
               ▼
STEP 5: User Visits Page
┌──────────────────────────────────┐
│ User navigates to any page:      │
│ - Sidebar                         │
│ - Header                          │
│ - Footer                          │
│ - Login                           │
│ - etc.                            │
└──────────────┬─────────────────┘
               │
               ▼
STEP 6: Setting Access
┌──────────────────────────────────┐
│ Template calls: setting('logo')  │
│                                  │
│ SettingsService checks cache:    │
│ Cache Key 'system_settings'      │
│ Status: NOT FOUND (cleared)      │
└──────────────┬─────────────────┘
               │
               ▼
STEP 7: Database Query
┌──────────────────────────────────┐
│ SELECT * FROM system_settings    │
│ WHERE id = 1                     │
│                                  │
│ Returns: Eloquent Model with     │
│ updated logo and name            │
└──────────────┬─────────────────┘
               │
               ▼
STEP 8: Cache Storage
┌──────────────────────────────────┐
│ Cache::remember()                │
│                                  │
│ Stores in Cache:                 │
│ Key: 'system_settings'           │
│ Value: Model object              │
│ TTL: 3600 seconds (1 hour)       │
└──────────────┬─────────────────┘
               │
               ▼
STEP 9: Return to View
┌──────────────────────────────────┐
│ setting('site_logo') returns:    │
│ "storage/settings/logo.png"      │
│                                  │
│ Blade Renders:                   │
│ <img src="storage/..." />        │
│                                  │
│ ✅ LOGO UPDATED IN SIDEBAR!      │
└──────────────────────────────────┘

STEP 10: Subsequent Page Loads
┌──────────────────────────────────┐
│ User loads 999 more pages        │
│                                  │
│ setting() called each time       │
│ Cache Key Found: ✓ CACHE HIT     │
│ Returns instantly from cache     │
│ NO DATABASE QUERIES              │
│                                  │
│ All 999 pages use same cached    │
│ data (until 1 hour expires)      │
└──────────────────────────────────┘
```

## Component Relationships

```
┌──────────────────────────────────────────────────────────────┐
│                                                              │
│                   APPLICATION                               │
│                                                              │
│  ┌───────────────────────────────────────────────────────┐  │
│  │  Views (Blade Templates)                              │  │
│  │  └─ @yield('title')                                  │  │
│  │  └─ {{ setting('site_name') }}                       │  │
│  │  └─ <img src="...{{ setting('logo') }}">             │  │
│  └────────────────────┬────────────────────────────────┘  │
│                       │ Use                                 │
│  ┌────────────────────▼────────────────────────────────┐  │
│  │  SettingsHelper                                     │  │
│  │  - setting($key, $default)                          │  │
│  │  - settings()                                       │  │
│  └────────────────────┬────────────────────────────────┘  │
│                       │ Calls                               │
│  ┌────────────────────▼────────────────────────────────┐  │
│  │  SettingsService (Singleton)                        │  │
│  │  - all()                                            │  │
│  │  - get($key, $default)                              │  │
│  │  - clearCache()                                     │  │
│  └────────────────────┬────────────────────────────────┘  │
│                       │                                     │
│       ┌───────────────┼───────────────┐                    │
│       │               │               │                    │
│       ▼               ▼               ▼                    │
│   ┌────────┐   ┌────────────┐   ┌──────────────┐         │
│   │ Cache  │   │ Controller │   │ Database     │         │
│   │        │   │            │   │              │         │
│   │ Memory │   │ Updates    │   │ system_      │         │
│   │ File   │   │ settings   │   │ settings     │         │
│   │        │   │ Clears     │   │ table        │         │
│   │        │   │ cache      │   │              │         │
│   └────────┘   └────────────┘   └──────────────┘         │
│                                                            │
└──────────────────────────────────────────────────────────────┘
```

## Settings Fields and Their Uses

```
┌─────────────────────────────────────────────────────────────┐
│                    SETTINGS FIELDS                          │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ BRANDING                          CONTACT INFO            │
│ ├─ site_name ──────┐              ├─ contact_email    ──┐ │
│ │                  ├──→ Sidebar    │                     │ │
│ │                  ├──→ Page Title │                     │ │
│ │                  └──→ Emails     │                     │ │
│ ├─ site_logo ──────┐              │                     │ │
│ │                  ├──→ Header     │                     │ │
│ │                  ├──→ Footer     │                     │ │
│ │                  └──→ Login      │                     │ │
│ ├─ site_favicon ───┐              │                     │ │
│ │                  └──→ Browser Tab│                     │ │
│ │                                 │                     │ │
│ └─────────────────────────────────┤─ contact_phone  ──┐ │
│                                   │                   │ │
│ SOCIAL MEDIA                       │                   │ │
│ ├─ facebook_url ────┐             │                   │ │
│ ├─ twitter_url ─────├──→ Footer   │                   │ │
│ ├─ instagram_url ────│  Social     │                   │ │
│ ├─ youtube_url ─────│  Links      │                   │ │
│ └─ linkedin_url ────┘             │                   │ │
│                                   ├─ address ──────┐  │ │
│ CONTENT                            │                │  │ │
│ ├─ footer_text ─────────→ Footer   │                │  │ │
│                                   └────────────────┘  │ │
│ ADVERTISING                                           │ │
│ ├─ header_ad_script ─────→ Header Ads               │ │
│ ├─ sidebar_ad_script ─────→ Sidebar Ads             │ │
│ ├─ footer_ad_script ─────→ Footer Ads               │ │
│ └─ article_middle_ad_script → Article Middle Ads    │ │
│                                                     │ │
│ MAIL CONFIGURATION                                 │ │
│ ├─ mail_host ─────────────┐                        │ │
│ ├─ mail_port ──────────────├──→ Email Sending      │ │
│ ├─ mail_username ──────────│     Configuration     │ │
│ ├─ mail_password ──────────│                       │ │
│ ├─ mail_encryption ────────│                       │ │
│ ├─ mail_from_address ──────│                       │ │
│ └─ mail_from_name ────────┘                        │ │
│                                                   │ │
└───────────────────────────────────────────────────┴─┘
```

## Cache Lifecycle

```
MINUTE 0:00
│
├─ Page Load → setting() called
├─ Cache Check → MISS
├─ DB Query → SELECT * FROM system_settings
├─ Store in Cache (TTL: 3600s)
└─ Return data → Page renders

MINUTE 0:01 to 59:59
│
└─ Page Loads (1000 times)
   ├─ setting() called
   ├─ Cache Check → HIT ✓
   ├─ Return from cache
   └─ 0 DB queries per page

MINUTE 60:00
│
├─ Cache Expires
├─ Page Load → setting() called
├─ Cache Check → MISS (expired)
├─ DB Query → SELECT * FROM system_settings
├─ Store in Cache (TTL: 3600s)
└─ Cycle repeats

ADMIN UPDATE (any time)
│
├─ Admin changes setting
├─ Form submits
├─ DB Update (save new data)
├─ Cache Clear → clearCache() called
├─ Cache DELETED immediately
│
└─ Next Page Load (seconds later)
   ├─ setting() called
   ├─ Cache Check → MISS (was cleared)
   ├─ DB Query → SELECT * FROM system_settings (NEW DATA)
   ├─ Store in Cache (TTL: 3600s)
   └─ Page shows UPDATED values
```

---

This visual architecture shows how the entire system works together to keep your settings synchronized and performant across your entire application.
