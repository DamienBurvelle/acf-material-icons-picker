# Material Icons Picker for ACF

A custom Advanced Custom Fields (ACF) field type to select and render [Google Material Icons](https://fonts.google.com/icons) in your WordPress admin and frontend.

## Features

- Custom ACF field type for selecting Material Icons.
- Support for multiple icon styles: `filled`, `outlined`, `rounded`, `sharp`, `two-tone`.
- Icons loaded directly from Google Fonts (no assets bundled).
- PHP helper functions for easy rendering in frontend or themes.

## Installation

1. Place the plugin folder in your WordPress plugin directory: `wp-content/plugins/material-icons-selector`
2. Activate it via the WordPress admin.
3. The field will appear as `Material Icon Picker` in ACF when adding custom fields.

Requires:
- WordPress 5.8+
- ACF Pro 6.x+

Tested up to:
- WordPress 6.8.2
- ACF Pro 6.4.2

---

## Usage in ACF

Once the plugin is activated, you can add a **Material Icon Picker** field to your field groups.

The field will return an array like:

```php
[
    'icon' => 'home',
    'style' => 'outlined'
]
```

You can use this return to render the icon wherever needed.

---

## Usage in your theme or plugin

This plugin exposes two helper functions globally for convenience:

### `render_material_icon()`

Renders an icon directly in the HTML output.

```php
render_material_icon(string $icon_name, string $style = 'filled', array $attrs = []);
```

**Parameters:**

| Param      | Type   | Description |
|------------|--------|-------------|
| `$icon_name` | string | Name of the Material icon (e.g., `'home'`, `'menu'`) |
| `$style`     | string | One of: `'filled'`, `'outlined'`, `'rounded'`, `'sharp'`, `'two-tone'` |
| `$attrs`     | array  | Optional associative array of HTML attributes (`class`, `aria-hidden`, etc.) |

**Example:**

```php
render_material_icon('favorite', 'outlined');
render_material_icon('menu', 'sharp', ['class' => 'nav-icon']);
```

---

### `get_material_icon_html()`

Returns the HTML string of the icon (instead of printing it).

```php
$html = get_material_icon_html('home', 'rounded', ['class' => 'icon-lg']);
echo $html;
```

---

## Icon Styles Supported

These styles are available and loaded automatically when used:

- `filled`
- `outlined`
- `rounded`
- `sharp`
- `two-tone`

---

## Development

To regenerate the list of available icons (`icons.json`), run:

```php
material_icon_generate_json();
```
in the admin page > tools > generate-material-icons

This function downloads `.codepoints` files from the Material Icons GitHub repo.

---

## Last Updated

August 08, 2025
