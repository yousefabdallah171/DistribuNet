# 🚀 DistribuNet - WordPress Distributor Directory Theme

![DistribuNet Logo](https://img.shields.io/badge/DistribuNet-WordPress%20Theme-blue?style=for-the-badge)

---

## 🌟 Overview

**DistribuNet** is a modern, RTL-ready WordPress theme for managing and showcasing a distributor directory. Built for flexibility, speed, and ease of use, it supports advanced search, filtering, custom post types, and a beautiful, responsive design.

---

## ✨ Features

- 🗂️ **Distributor Directory**: List, search, and filter distributors by type, governorate, and more.
- 🔍 **AJAX Search & Filter**: Instant results with advanced filtering (type, governorate, delivery, etc.).
- 🏷️ **Custom Post Types**: Wholesale, Retail, and Mixed distributors.
- 🗺️ **Governorate Taxonomy**: Organize distributors by region/governorate.
- 📝 **Frontend Registration**: Distributors can register via a custom form.
- 📱 **Responsive & RTL**: Fully responsive and Arabic RTL support.
- 🎨 **Modular CSS**: Modern, maintainable, and split into logical files.
- 🧩 **Template Parts**: Reusable PHP snippets for cards, forms, hero, etc.
- 🛠️ **Admin Enhancements**: Custom columns, filters, and dashboard widgets.
- ⚡ **Performance**: Optimized asset loading, minimal dependencies.
- 🔒 **Security**: Nonces, sanitization, and best practices throughout.
- 🌈 **Customizable**: Easily extend with your own styles and functions.

---

## 📁 Folder Structure

```text
distribunet/
│
├── assets/
│   ├── css/           # Modular CSS (main, header, forms, responsive, etc.)
│   ├── js/            # Modular JS (main, forms, search-filter, etc.)
│   └── img/           # Images, logos, icons
│
├── inc/               # PHP includes (CPTs, taxonomies, AJAX, shortcodes, etc.)
├── template-parts/    # Reusable template snippets (cards, forms, hero, etc.)
├── archive-*.php      # Archive templates for each distributor type
├── single-*.php       # Single templates for each distributor type
├── page-*.php         # Custom page templates
├── header.php         # Header markup
├── footer.php         # Footer markup
├── sidebar.php        # Sidebar markup
├── functions.php      # Main theme functions (includes, enqueues, etc.)
├── style.css          # Theme info and root styles
├── README.md          # This file
└── ...
```

---

## ⚙️ Installation & Setup

1. **Clone or Download** this repo:
   ```bash
   git clone https://github.com/yourusername/distribunet.git
   ```
2. **Copy** the theme folder to `wp-content/themes/` in your WordPress installation.
3. **Activate** the theme from the WordPress admin panel.
4. **Install Required Plugins:**
   - Advanced Custom Fields (ACF)
   - Contact Form 7
   - WooCommerce (optional, for e-commerce features)
   - Any other plugin as needed
5. **Import Demo Content** (optional):
   - Use the WordPress Importer or your preferred tool.

---

## 🧑‍💻 Usage

- **Distributor Directory:**
  - Use the `[distributor_search]` shortcode to display the main search/filter form.
  - Use `[distributor_search_filter]` for a compact filter form (e.g., in the header).
  - Use `[distributors_by_governorate gov="cairo"]` to show distributors by region.
  - Use `[featured_distributors]` to highlight featured entries.
- **Frontend Registration:**
  - Distributors can register via the custom registration page (`page-register.php`).
- **Admin Area:**
  - Manage distributors, governorates, and custom fields from the WP admin.

---

## 🛠️ Customization

- **CSS:**
  - Edit or add files in `assets/css/` (e.g., `_header.css`, `main.css`).
  - Use CSS variables and modular partials for easy theming.
- **JS:**
  - Add or modify scripts in `assets/js/`.
- **Template Parts:**
  - Reuse or extend snippets in `template-parts/` for custom layouts.
- **PHP Includes:**
  - Add new features in `inc/` and require them in `functions.php`.

---

## 🔌 Plugin Requirements

- **Advanced Custom Fields (ACF)**: For custom fields and meta.
- **Contact Form 7**: For contact/registration forms.
- **WooCommerce**: (Optional) For e-commerce features.

---

## 📝 Contribution & GitHub Workflow

- Fork this repo and create a new branch for your feature or fix.
- Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/).
- Use clear, descriptive commit messages.
- Submit a pull request with a detailed description.
- For major changes, open an issue first to discuss what you’d like to change.

---

## 💡 Best Practices

- Use child themes for heavy customizations.
- Keep plugins and WordPress core up to date.
- Regularly back up your site.
- Test on both RTL and LTR languages.
- Use the theme’s modular structure for easy maintenance and scalability.

---

## 🙏 Credits & License

- Theme developed by [Your Name].
- Licensed under GPL v2 or later.
- Icons by [FontAwesome](https://fontawesome.com/) and [EmojiOne](https://www.emojione.com/).

---

## 📣 Support & Feedback

For support, feature requests, or bug reports, please open an issue on GitHub or contact the maintainer.

---

> **DistribuNet** — The smart way to connect distributors and customers! 🌍 