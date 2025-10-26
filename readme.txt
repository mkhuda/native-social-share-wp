=== Native Social Share ===
Contributors: mkhuda
Donate link: https://mkhuda.com
Tags: share, social, native share, web share api, lightweight
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

A lightweight, performance-focused WordPress plugin using the native Web Share API with graceful fallbacks for Twitter, Facebook, and LinkedIn.

== Description ==
**Native Social Share** adds a clean and simple share button to your posts — powered by the browser’s *native Web Share API* with graceful fallbacks for Twitter (X), Facebook, and LinkedIn.

- Automatically adds share buttons below, above, or both positions in posts  
- Supports **auto dark mode** (via `prefers-color-scheme`)  
- No JavaScript dependencies (zero jQuery)  
- Lightweight (~5KB including CSS & JS)  
- Progressive enhancement: uses native share where available, fallbacks otherwise  

This plugin focuses on simplicity, accessibility, and performance. Perfect for modern WordPress themes that prioritize speed and native UX.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/native-social-share/`
2. Activate the plugin through **Plugins → Installed Plugins**
3. Go to **Settings → Native Social Share** to configure:
   - Position: Above, Below, or Both
   - Enabled platforms (Twitter, Facebook, LinkedIn)
4. Save changes and enjoy native sharing on your posts!

== Frequently Asked Questions ==

= Does it require jQuery or Font Awesome? =
No, it’s written in pure PHP and vanilla JavaScript with inline SVG icons.

= What happens on unsupported browsers? =
When `navigator.share` isn’t available, the plugin shows fallback links for Twitter, Facebook, and LinkedIn.

= Is there a shortcode or block? =
Not yet — planned for a future update.

= Does it support caching and CDNs? =
Yes. All assets are static and safe for full-page caching/CDN optimization.

== Screenshots ==
1. Native Share button with fallback links (light mode)
2. Dark mode appearance (automatic)

== Changelog ==

= 1.1.0 – 2025-10-27 =
* Updated all function and option prefixes from `nss` to `natssh` (per WP.org guidelines)
* Added PHP namespace `Mkhuda\NativeSocialShare`
* Added **“Both (Above & Below)”** position option for more flexibility
* Fixed license declaration mismatch between plugin header and readme
* Improved option migration for smoother upgrade
* Minor code cleanup and standard compliance (PHPCS/WPCS)

= 1.0.0 – 2025-10-15 =
* Initial public release  
* Native Web Share + fallback links  
* Auto Dark Mode support  
* Configurable social buttons  

== Upgrade Notice ==

= 1.1.0 =
Prefix and namespace updates.  
Now also supports **“Both (Above & Below)”** position option.

== License ==

This plugin is licensed under the [GPLv3 or later](https://www.gnu.org/licenses/gpl-3.0.html).  
You are free to modify and redistribute it under the same license.

== Author ==

Developed by **M Khoirul Huda**  
Website: [https://mkhuda.com](https://mkhuda.com)  
GitHub: [https://github.com/mkhuda/native-social-share-wp](https://github.com/mkhuda/native-social-share-wp)
