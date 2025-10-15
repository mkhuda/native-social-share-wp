# 🧩 Native Social Share | Wordpress Plugin

![Native Social Share WP Image](https://cdn.mkhuda.com/wp-content/uploads/2025/10/native-social-share-wp-1.jpg)

> A lightweight, performance-focused WordPress plugin using the **native Web Share API** with graceful fallbacks for Twitter, Facebook, and LinkedIn.

![WordPress Tested](https://img.shields.io/badge/WordPress-6.7%20tested-brightgreen)
![License GPLv2](https://img.shields.io/badge/license-GPLv2-blue)
![Version](https://img.shields.io/badge/version-1.0-lightgrey)

---

## 🚀 Features

- 🧠 Uses **native Web Share API** (if supported)
- 🌗 **Auto Dark Mode** support (via `prefers-color-scheme`)
- 💨 No dependencies (zero jQuery)
- ⚙️ Configurable from WordPress Dashboard:
  - Button position (above or below content)
  - Enable/disable specific social platforms (Twitter, Facebook, LinkedIn)
- 🧱 Lightweight: ~10KB including CSS + JS

---

## 🛠️ Installation

1. Download the latest release from [Releases](https://github.com/mkhuda/native-social-share-wp/releases).
2. Upload the folder to your WordPress site at:  
   `/wp-content/plugins/native-social-share-wp/`
3. Activate the plugin from **Plugins → Installed Plugins**.
4. Go to **Settings → Native Social Share** to configure:
   - Position: Above or below post content
   - Which social buttons to show

---

## 🧩 Plugin Structure

```
/native-social-share-wp/
├── assets/
│ ├── css/
│ │ └── style.css
│ └── js/
│ └── share.js
├── native-social-share.php
└── readme.txt
```

---

## ⚙️ Configuration Options

| Option | Description | Default |
|--------|--------------|----------|
| **Position** | Where to show buttons (Above / Below content) | Below |
| **Enabled Buttons** | Choose which social buttons appear | All enabled |
| **Auto Dark Mode** | Adapts to system theme automatically | Enabled |

---

## 🧪 Browser Support

| Feature | Supported Browsers |
|----------|--------------------|
| Native Share (`navigator.share`) | Chrome, Edge, Safari, Android WebView |
| Fallback Links | All browsers |

![Native Social Share WP Image](https://cdn.mkhuda.com/wp-content/uploads/2025/10/native-social-share-wp-2.jpg)

---

## 📦 Changelog

### v1.0 (2025-10-15)
- Initial public release with native share + fallback buttons
- ✨ Added **Auto Dark Mode** support
- 🧰 Added configurable social buttons
- 🪶 Simplified CSS for cleaner look

## 💡 Developer Notes

- Written in pure PHP + Vanilla JS  
- Safe to use with caching/CDN  
- 100% compliant with WordPress coding standards  
- License: [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html)

---

## 🧑‍💻 Author

**M Khoirul Huda**  
[https://mkhuda.com](https://mkhuda.com)

---

### ⭐ Show support
If you like this plugin, give it a ⭐ on [GitHub](https://github.com/mkhuda/native-social-share-wp) or mention it on your blog!
