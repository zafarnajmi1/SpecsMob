# 🎯 Dynamic Settings Quick Start Guide

## What's New?

You can now manage ALL site configuration from the **Admin Panel** without touching any code. Everything is stored in the database and updates instantly across your entire site.

---

## 🚀 How to Use

### Step 1: Login to Admin Panel
- Go to: `yoursite.com/admin`
- Use your admin credentials

### Step 2: Navigate to Settings
- Look for **Settings** in the admin menu
- You'll see these tabs:
  - **General Settings** - Site name, contact info, footer text
  - **Social Settings** - Social media links (Facebook, Instagram, etc.)
  - **Mail Settings** - Email configuration
  - **Ads Settings** - Advertisement configuration

### Step 3: Update Any Setting
- Change the value
- Click **Save**
- **Done!** Changes appear on your site immediately

---

## 📝 Available Settings

### General Settings

| Setting | Description | Used On Site |
|---------|-------------|--------------|
| **Site Name** | Your website name | Page titles, header, fallback logo text |
| **Site Logo** | Upload your logo image | Header (desktop + mobile), admin sidebar |
| **Site Favicon** | Upload favicon (favico.ico) | Browser tab icon |
| **Contact Email** | Support email address | Footer, contact page |
| **Contact Phone** | Support phone number | Footer contact section |
| **Address** | Physical office address | Footer contact section |
| **Footer Text** | Copyright/footer message | Bottom of page |

### Social Settings

| Setting | Description |
|---------|-------------|
| **Facebook URL** | Your Facebook profile link |
| **Twitter URL** | Your Twitter profile link |
| **Instagram URL** | Your Instagram profile link |
| **YouTube URL** | Your YouTube channel link |
| **LinkedIn URL** | Your LinkedIn profile link |

**Note**: Leave empty to hide that social media icon from footer

---

## 🎨 Where Each Setting Appears

### Site Name
- ✅ Page `<title>` tag
- ✅ Header logo alt text
- ✅ Fallback if no logo uploaded

### Site Logo
- ✅ Desktop header (top left)
- ✅ Mobile header (hamburger menu)
- ✅ Admin sidebar (top left)

### Site Favicon
- ✅ Browser tab icon
- ✅ Bookmarks icon

### Contact Email
- ✅ Footer contact section
- ✅ Contact page form

### Contact Phone
- ✅ Footer contact section (with clickable tel: link)

### Address
- ✅ Footer contact section

### Footer Text
- ✅ Footer copyright text
- ✅ Bottom of every page

### Social Media URLs
- ✅ Footer social icons (5 platforms)
- ✅ Mobile menu social icons

---

## 💡 Pro Tips

### Logo Upload Tips
- Recommended size: 200-300px wide
- Use transparent PNG for best results
- Keep file size under 500KB
- The logo will scale automatically

### Favicon Upload Tips
- Must be square (e.g., 32x32px or 64x64px)
- Use .ico or .png format
- If favicon doesn't update, clear browser cache

### Social Media Links
- Get your profile URL from each platform
- Examples:
  - Facebook: `https://facebook.com/yourpage`
  - Instagram: `https://instagram.com/yourprofile`
  - YouTube: `https://youtube.com/@yourchannel`
  - Twitter: `https://twitter.com/yourhandle`
  - LinkedIn: `https://linkedin.com/company/yourcompany`

### Email Configuration
- To receive contact form submissions properly
- Ensure "Mail From Address" is set correctly
- Test with a small message first

---

## ⚙️ How It Works (Technical)

When you update a setting:

1. **You save in admin panel** → Setting stored in database
2. **Cache is cleared** → Old version removed
3. **Site loads fresh data** → Updated values appear everywhere
4. **Cache is renewed** → Settings cached for 1 hour for performance
5. **Next update** → Repeat process

This means:
- ✅ Changes appear **instantly**
- ✅ No code editing needed
- ✅ No deployment required
- ✅ Performance optimized with caching

---

## 🔍 Verification Checklist

After updating settings, verify these locations:

- [ ] **Page Title** - Check browser tab name
- [ ] **Header Logo** - Check top of page and hamburger menu
- [ ] **Favicon** - Check browser tab icon
- [ ] **Footer Text** - Scroll to bottom
- [ ] **Footer Contact** - Email, phone, address visible
- [ ] **Footer Social Icons** - Icons link to your profiles

---

## ❓ Troubleshooting

### Change doesn't appear?
1. Clear browser cache (Ctrl+Shift+Delete)
2. Clear site cache: Run `php artisan cache:clear` in terminal
3. Or wait 1 minute for cache to refresh automatically

### Logo not showing?
1. Make sure file was uploaded (check file size)
2. Check that logo is in correct format (PNG/JPG)
3. Try deleting and re-uploading

### Social icon missing?
1. Check if URL is filled in
2. Verify URL is complete (starts with https://)
3. Save and refresh page

### Settings revert after save?
1. Check database connection is working
2. Ensure you have edit permissions
3. Check storage folder permissions (755)

---

## 🔐 Security

✅ All settings protected by admin login
✅ File uploads validated for security
✅ No sensitive data exposed in code
✅ Fallback values prevent blank content

---

## 📞 Key File Locations

If you ever need to reference where settings are used:

| File | Path |
|------|------|
| Main layout | `resources/views/layouts/app.blade.php` |
| Contact page | `resources/views/user-views/pages/contact.blade.php` |
| Admin form | `resources/views/admin-views/settings/general.blade.php` |
| Backend service | `app/Services/SettingsService.php` |
| Database table | `system_settings` table in database |

---

## 🎓 Developer Notes

Developers can access settings globally in code:

```blade
<!-- In Blade templates -->
{{ setting('site_name') }}
{{ setting('contact_email', 'fallback@example.com') }}

<!-- In PHP controllers -->
$name = setting('site_name');
```

The `setting()` function is available everywhere and works with caching.

---

## ✅ You're All Set!

Your site is now **100% dynamic**. No hardcoded values anywhere!

**Start managing your site configuration from the admin panel today!**

---

**Last Updated**: Current Session
**System Status**: ✅ Fully Operational
