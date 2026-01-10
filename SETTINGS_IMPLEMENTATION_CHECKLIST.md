# Where to Use Settings - Complete Checklist

## 🎨 Frontend Pages

- [ ] **Header/Navigation Bar**
  ```blade
  <header>
      @if(setting('site_logo'))
          <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="{{ setting('site_name') }}">
      @endif
  </header>
  ```

- [ ] **Page Title**
  ```blade
  <title>{{ setting('site_name') }} | @yield('title')</title>
  ```

- [ ] **Favicon**
  ```blade
  <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . setting('site_favicon')) }}">
  ```

- [ ] **Hero Section**
  ```blade
  <section class="hero">
      <h1>{{ setting('site_name') }}</h1>
  </section>
  ```

- [ ] **Footer Contact Info**
  ```blade
  <footer>
      <p>Email: {{ setting('contact_email') }}</p>
      <p>Phone: {{ setting('contact_phone') }}</p>
      <p>Address: {{ setting('address') }}</p>
      <p>{{ setting('footer_text') }}</p>
  </footer>
  ```

- [ ] **Footer Social Links**
  ```blade
  <div class="social-links">
      @if(setting('facebook_url'))
          <a href="{{ setting('facebook_url') }}"><i class="fab fa-facebook"></i></a>
      @endif
      @if(setting('twitter_url'))
          <a href="{{ setting('twitter_url') }}"><i class="fab fa-twitter"></i></a>
      @endif
      {{-- etc --}}
  </div>
  ```

- [ ] **Contact Form Contact Info**
  ```blade
  <div class="contact-details">
      <p>📧 {{ setting('contact_email') }}</p>
      <p>📞 {{ setting('contact_phone') }}</p>
      <p>📍 {{ setting('address') }}</p>
  </div>
  ```

- [ ] **Advertisement Sections**
  ```blade
  <!-- Header Ads -->
  <div class="header-ads">
      {!! setting('header_ad_script') !!}
  </div>

  <!-- Sidebar Ads -->
  <div class="sidebar-ads">
      {!! setting('sidebar_ad_script') !!}
  </div>

  <!-- Footer Ads -->
  <div class="footer-ads">
      {!! setting('footer_ad_script') !!}
  </div>

  <!-- Article Middle Ads -->
  <div class="article-ads">
      {!! setting('article_middle_ad_script') !!}
  </div>
  ```

## 👤 Admin Pages

- [ ] **Admin Sidebar**
  ```blade
  <div class="logo">
      @if(setting('site_logo'))
          <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="{{ setting('site_name') }}">
      @else
          <span>{{ setting('site_name', 'Admin') }}</span>
      @endif
  </div>
  ```

- [ ] **Admin Header**
  ```blade
  <header class="admin-header">
      <span>{{ setting('site_name') }} Admin Panel</span>
  </header>
  ```

- [ ] **Admin Footer**
  ```blade
  <footer class="admin-footer">
      <p>{{ setting('footer_text') }}</p>
  </footer>
  ```

## 🔐 Auth Pages

- [ ] **Login Page**
  ```blade
  <div class="login-container">
      @if(setting('site_logo'))
          <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="{{ setting('site_name') }}" class="login-logo">
      @endif
      <h2>{{ setting('site_name') }} Admin Login</h2>
  </div>
  ```

- [ ] **Register Page**
  ```blade
  <h2>{{ setting('site_name') }} - Create Account</h2>
  ```

- [ ] **Password Reset Page**
  ```blade
  <h2>Reset Password - {{ setting('site_name') }}</h2>
  ```

## 📧 Email & Notifications

- [ ] **Mail Configuration**
  ```php
  // config/mail.php
  return [
      'from' => [
          'address' => setting('mail_from_address'),
          'name' => setting('mail_from_name'),
      ],
      'host' => setting('mail_host'),
      'port' => setting('mail_port'),
      'username' => setting('mail_username'),
      'password' => setting('mail_password'),
      'encryption' => setting('mail_encryption'),
  ];
  ```

- [ ] **Mailable Classes**
  ```php
  class ContactMail extends Mailable
  {
      public function build()
      {
          return $this->from(setting('mail_from_address'), setting('mail_from_name'))
                      ->view('emails.contact');
      }
  }
  ```

- [ ] **Welcome Email**
  ```blade
  <p>Welcome to {{ setting('site_name') }}!</p>
  <p>Contact us: {{ setting('contact_email') }}</p>
  ```

- [ ] **Contact Form Reply Email**
  ```blade
  <p>Thank you for contacting {{ setting('site_name') }}.</p>
  <p>We'll respond to you at: {{ $contact_email }}</p>
  ```

## 🔍 SEO & Meta Tags

- [ ] **Meta Description**
  ```blade
  <meta name="description" content="Welcome to {{ setting('site_name') }}">
  ```

- [ ] **Open Graph Tags**
  ```blade
  <meta property="og:title" content="{{ setting('site_name') }}">
  <meta property="og:image" content="{{ asset('storage/' . setting('site_logo')) }}">
  ```

- [ ] **Twitter Card**
  ```blade
  <meta name="twitter:title" content="{{ setting('site_name') }}">
  <meta name="twitter:creator" content="{{ setting('twitter_url') }}">
  ```

## 🤖 Controllers

- [ ] **Contact Form Handler**
  ```php
  public function submitContact(Request $request)
  {
      Mail::to(setting('contact_email'))
          ->send(new ContactMail($request->all()));
  }
  ```

- [ ] **Newsletter Handler**
  ```php
  public function subscribe(Request $request)
  {
      Mail::to(setting('contact_email'))
          ->send(new NewsletterSubscription($request->email));
  }
  ```

- [ ] **Error Notification**
  ```php
  public function reportError(Request $request)
  {
      Mail::to(setting('contact_email'))
          ->send(new ErrorReport($request->all()));
  }
  ```

## 🛠️ Configuration Files

- [ ] **app.php**
  ```php
  'name' => setting('site_name', env('APP_NAME', 'Laravel')),
  ```

- [ ] **mail.php**
  ```php
  'from' => [
      'address' => setting('mail_from_address'),
      'name' => setting('mail_from_name'),
  ],
  ```

- [ ] **services.php**
  ```php
  'site_logo' => setting('site_logo'),
  'contact_email' => setting('contact_email'),
  ```

## 📊 API Responses

- [ ] **Site Info Endpoint**
  ```php
  public function siteInfo()
  {
      return response()->json([
          'name' => setting('site_name'),
          'logo' => setting('site_logo'),
          'contact_email' => setting('contact_email'),
      ]);
  }
  ```

## 🧪 Testing

- [ ] **Test Settings Access**
  ```php
  public function test_settings_are_accessible()
  {
      $this->assertNotNull(setting('site_name'));
      $this->assertIsString(setting('site_name'));
  }
  ```

- [ ] **Test Settings Update**
  ```php
  public function test_settings_update_clears_cache()
  {
      setting('site_name'); // Cache it
      // ... update setting
      app('settings')->clearCache();
      // ... verify fresh data
  }
  ```

## 📋 Implementation Checklist

After creating this system:

- [ ] Test `setting('site_name')` works in tinker
- [ ] Update sidebar with dynamic logo
- [ ] Update app layout with site name in title
- [ ] Update footer with footer_text from settings
- [ ] Add social links to footer
- [ ] Test admin can change logo and see it in sidebar immediately
- [ ] Test cache is cleared after update
- [ ] Document for your team

## 💡 Tips

1. **Always use fallback values**: `setting('field', 'Default')`
2. **Use conditional rendering**: `@if(setting('logo'))...@endif`
3. **Cache is automatically managed**: Don't worry about manual clearing
4. **Test with tinker**: `php artisan tinker` → `setting('site_name')`
5. **Monitor performance**: Settings should load in <1ms from cache
