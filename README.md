# Hreflang URL Meta Box WordPress Plugin

Adds meta boxes for x-default, English, and Indonesian hreflang URLs for all public post types (including a custom taxonomy, if desired). This plugin helps you set `alternate` links in the `<head>` for better SEO and multilingual support.

## Description

This plugin:

- Creates meta boxes for any public post type (e.g., posts, pages, custom post types).
- Supports a custom taxonomy (e.g., `car_category`).
- Lets you add an English (`en`) and Indonesian (`id`) version of each post or term.
- Automatically outputs `<link rel="alternate" hreflang="x-default">`, `<link rel="alternate" hreflang="en">`, and `<link rel="alternate" hreflang="id">` tags in the page `<head>`.

## Features

- **Simple Interface**: Easily configure English and Indonesian URLs from the post or taxonomy edit screen.
- **x-default & en**: If an `en` URL is provided, the plugin automatically sets both `x-default` and `en`.
- **Supports Terms**: The plugin also works with taxonomy terms.

## Installation

1. **Download or clone** this repository.
2. **Upload** the folder to the `/wp-content/plugins/` directory or upload the ZIP through the WordPress admin plugin uploader.
3. **Activate** the plugin through the "Plugins" menu in WordPress.

## Usage

1. Navigate to any post, page, or supported custom post type in the WordPress admin.
2. In the **Hreflang URLs** meta box, enter:
   - **English URL (hreflang="en")**  
   - **Indonesian URL (hreflang="id")**
3. Save the post.
4. In the frontend, the plugin automatically outputs the relevant `<link rel="alternate" ...>` tags in the site `<head>`.

## Custom Taxonomy

By default, this plugin demonstrates usage with a custom taxonomy called `car_category`. If you have a different custom taxonomy you'd like to support, you can modify the plugin code accordingly (look for places referencing `car_category`).

## Contributing

- If you find a bug or have a feature request, please open an issue or submit a pull request.
- For major changes, please open an issue first to discuss what you would like to change.

## License

This project is licensed under the [MIT License](LICENSE). Feel free to use and modify this plugin.

---
