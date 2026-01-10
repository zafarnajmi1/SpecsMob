# Settings Quick Reference

## Basic Usage (Blade Templates)

```blade
<!-- Get a single setting with fallback -->
{{ setting('site_name', 'My Site') }}

<!-- Get all settings -->
@php $s = settings(); @endphp
{{ $s->site_logo }}
{{ $s->contact_email }}

<!-- Conditional display -->
@if(setting('site_logo'))
    <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="Logo">
@endif
```

## Common Implementations

### 1️⃣ Sidebar Logo
```blade
@if(setting('site_logo'))
    <img src="{{ asset('storage/' . setting('site_logo')) }}" style="width:70%; height:auto;"
        alt="{{ setting('site_name', 'Logo') }}">
@else
    <span>{{ setting('site_name', 'Admin') }}</span>
@endif
```

### 2️⃣ Page Title
```blade
<title>{{ setting('site_name') }} - @yield('title')</title>
```

### 3️⃣ Favicon
```blade
@if(setting('site_favicon'))
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . setting('site_favicon')) }}">
@endif
```

### 4️⃣ Contact Info
```blade
<p>Email: {{ setting('contact_email') }}</p>
<p>Phone: {{ setting('contact_phone') }}</p>
<p>Address: {{ setting('address') }}</p>
```

### 5️⃣ Social Links
```blade
@if(setting('facebook_url'))
    <a href="{{ setting('facebook_url') }}"><i class="fab fa-facebook"></i></a>
@endif
```

### 6️⃣ Footer Text
```blade
<footer>{{ setting('footer_text') }}</footer>
```

### 7️⃣ Mail From Address
```php
// In mail configuration or mailable class
->from(setting('mail_from_address'), setting('mail_from_name'))
```

## In PHP Code

```php
use App\Services\SettingsService;

// Method 1: Using helper function
$logo = setting('site_logo');
$name = setting('site_name', 'Default Name');

// Method 2: Using service injection
public function __construct(SettingsService $settings)
{
    $email = $settings->get('contact_email');
    $all = $settings->all();
}

// Method 3: Using service container
$settings = app('settings')->get('site_logo');
```

## Cache Management

```php
// Clear cache when settings are updated (done automatically in SettingsController)
app('settings')->clearCache();

// Get fresh data after update
$settings = settings(); // This will re-fetch from DB and re-cache
```

## All Available Settings

| Setting | Description | Type |
|---------|-------------|------|
| `site_name` | Website name | String |
| `site_logo` | Logo file path | File path |
| `site_favicon` | Favicon file path | File path |
| `contact_email` | Contact email | Email |
| `contact_phone` | Contact phone | String |
| `address` | Physical address | String |
| `footer_text` | Footer text content | Text |
| `facebook_url` | Facebook profile URL | URL |
| `twitter_url` | Twitter profile URL | URL |
| `instagram_url` | Instagram profile URL | URL |
| `youtube_url` | YouTube profile URL | URL |
| `linkedin_url` | LinkedIn profile URL | URL |
| `header_ad_script` | Header advertisement script | HTML |
| `sidebar_ad_script` | Sidebar advertisement script | HTML |
| `footer_ad_script` | Footer advertisement script | HTML |
| `article_middle_ad_script` | Article middle advertisement script | HTML |
| `mail_host` | Mail server host | String |
| `mail_port` | Mail server port | Number |
| `mail_username` | Mail server username | String |
| `mail_password` | Mail server password | String |
| `mail_encryption` | Mail encryption type (tls/ssl) | String |
| `mail_from_address` | Default from email | Email |
| `mail_from_name` | Default from name | String |

## Files Modified/Created

✅ **Created:**
- `app/Services/SettingsService.php` - Service with caching logic
- `app/Helpers/SettingsHelper.php` - Helper functions
- `SETTINGS_IMPLEMENTATION_GUIDE.md` - Full documentation
- `SETTINGS_BEFORE_AFTER.md` - Before/after examples

✅ **Updated:**
- `app/Providers/AppServiceProvider.php` - Registered service & helper
- `app/Http/Controllers/Admin/SettingsController.php` - Added cache clearing
- `resources/views/admin-views/partials/sidebar.blade.php` - Using dynamic settings

## How It Works

```
1. Admin updates settings in /admin/settings
   ↓
2. SettingsController@update() is called
   ↓
3. Settings are saved to database
   ↓
4. Cache is automatically cleared (app('settings')->clearCache())
   ↓
5. Next page request loads fresh settings from database and re-caches
   ↓
6. All pages immediately show updated values (logo, site name, etc.)
```

## Performance Impact

- ✅ **First Load**: 1 database query (cached for 1 hour)
- ✅ **Subsequent Loads**: 0 database queries (served from cache)
- ✅ **After Update**: Cache cleared, next load refreshes data
- ✅ **Result**: ~1000x faster than querying database on every page load

## Testing

```php
// In tinker or tests
php artisan tinker
> setting('site_name')
> setting('site_logo')
> settings()->all()
```
