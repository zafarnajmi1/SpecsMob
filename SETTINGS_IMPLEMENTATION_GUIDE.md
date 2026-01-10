# Dynamic Settings Implementation Guide

## Overview
Settings are now cached and automatically cleared on update. Use the `setting()` or `settings()` helper functions throughout your application.

## Usage Examples

### 1. In Blade Templates (Most Common)

```blade
<!-- Get single setting -->
<img src="{{ asset('storage/' . setting('site_logo')) }}" alt="{{ setting('site_name') }}">

<!-- With fallback/default value -->
<h1>{{ setting('site_name', 'My Site') }}</h1>

<!-- Get all settings at once -->
@php
    $settings = settings();
@endphp

<footer>
    <p>{{ $settings->footer_text }}</p>
    <p>{{ $settings->address }}</p>
    <p>Contact: {{ $settings->contact_email }}</p>
</footer>
```

### 2. In Blade Files - Social Links Example

```blade
<div class="social-links">
    @if(setting('facebook_url'))
        <a href="{{ setting('facebook_url') }}" target="_blank">
            <i class="fab fa-facebook"></i>
        </a>
    @endif
    
    @if(setting('twitter_url'))
        <a href="{{ setting('twitter_url') }}" target="_blank">
            <i class="fab fa-twitter"></i>
        </a>
    @endif
    
    @if(setting('instagram_url'))
        <a href="{{ setting('instagram_url') }}" target="_blank">
            <i class="fab fa-instagram"></i>
        </a>
    @endif
</div>
```

### 3. In Blade Files - App Layout Head Section

```blade
<!-- In resources/views/layouts/app.blade.php -->
<head>
    <meta charset="utf-8">
    <title>{{ setting('site_name', 'My Site') }} - @yield('title')</title>
    
    <!-- Favicon -->
    @if(setting('site_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . setting('site_favicon')) }}">
    @endif
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', 'Welcome to ' . setting('site_name'))">
    <meta name="keywords" content="@yield('keywords')">
</head>
```

### 4. In Controllers (PHP)

```php
use App\Services\SettingsService;

class MyController extends Controller
{
    public function __construct(SettingsService $settings)
    {
        $this->settings = $settings;
    }

    public function show()
    {
        // Get specific setting
        $siteName = $this->settings->get('site_name');
        
        // Get all settings
        $allSettings = $this->settings->all();
        
        return view('my-view', compact('allSettings'));
    }
}

// Or using the helper function
public function contact()
{
    $contactEmail = setting('contact_email');
    $contactPhone = setting('contact_phone');
    
    // Send email logic...
}
```

### 5. In Mail Classes

```php
// app/Mail/ContactMail.php
class ContactMail extends Mailable
{
    public function build()
    {
        return $this->view('emails.contact')
                    ->from(setting('contact_email'))
                    ->subject('New Contact Message');
    }
}
```

### 6. In Login/Auth Views

```blade
<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('content')
<div class="login-container">
    @if(setting('site_logo'))
        <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="{{ setting('site_name') }}" class="logo">
    @else
        <h2>{{ setting('site_name', 'Admin') }}</h2>
    @endif
    
    <h3>Welcome</h3>
    <!-- Login form... -->
</div>
@endsection
```

### 7. In Admin Sidebar

```blade
<!-- Already updated in resources/views/admin-views/partials/sidebar.blade.php -->
<div class="logo">
    <a href="{{ route('admin.dashboard') }}">
        @if(setting('site_logo'))
            <img src="{{ asset('storage/' . setting('site_logo')) }}" style="width:70%; height:auto;"
                alt="{{ setting('site_name', 'Logo') }}">
        @else
            <span style="font-weight: bold;">{{ setting('site_name', 'Admin') }}</span>
        @endif
    </a>
</div>
```

### 8. In Config Files (PHP)

```php
// config/mail.php
return [
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', function_exists('setting') ? setting('mail_from_address') : 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', function_exists('setting') ? setting('mail_from_name') : 'Example'),
    ],
];
```

## How Caching Works

1. **First Access**: Setting is fetched from database and cached for 1 hour
2. **Subsequent Accesses**: Setting is retrieved from cache (very fast, no DB queries)
3. **On Update**: Cache is automatically cleared in `SettingsController@update()`
4. **Next Access**: Fresh data is fetched and re-cached

## Cache Key
- **Cache Key**: `system_settings`
- **Cache Duration**: 3600 seconds (1 hour)

## Clearing Cache Manually (if needed)

```php
// In a controller or command
app('settings')->clearCache();

// Or using the service directly
$settings = app(SettingsService::class);
$settings->clearCache();
```

## Available Settings

All of these can be accessed via `setting()`:

- `site_name` - Website name
- `site_logo` - Logo path
- `site_favicon` - Favicon path
- `contact_email` - Contact email
- `contact_phone` - Contact phone
- `address` - Address
- `footer_text` - Footer text
- `facebook_url` - Facebook URL
- `twitter_url` - Twitter URL
- `instagram_url` - Instagram URL
- `youtube_url` - YouTube URL
- `linkedin_url` - LinkedIn URL
- `header_ad_script` - Header ad script
- `sidebar_ad_script` - Sidebar ad script
- `footer_ad_script` - Footer ad script
- `article_middle_ad_script` - Article middle ad script
- `mail_host`, `mail_port`, `mail_username`, `mail_password`, `mail_encryption`, `mail_from_address`, `mail_from_name` - Mail settings

## Benefits

✅ **Automatic Updates**: Changes in admin panel immediately reflect everywhere
✅ **Performance**: Cached queries reduce database load
✅ **Simplicity**: Single `setting()` function instead of database queries
✅ **Consistency**: One source of truth for settings
✅ **Type-Safe**: Returns model instance with proper attributes
