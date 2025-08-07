=== Material Icons Picker for ACF ===
Contributors: DamienBurvelle
Tags: acf, icons, material design, icon picker, custom field
Requires at least: 5.8
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add a custom ACF field to select and display Google Material Icons.

== Description ==

This plugin provides a custom field type for Advanced Custom Fields (ACF) to choose Google Material Icons. It supports multiple styles (filled, outlined, rounded, sharp, two-tone), and loads the fonts dynamically.

= Features =
* Custom ACF field to pick a Material Icon.
* Supports 5 styles of Material Icons.
* Dynamic loading of fonts via Google Fonts.
* Reusable PHP functions to render icons in your theme.

== Installation ==

1. Upload the plugin to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the "Material Icon Picker" field in your ACF field groups.

== Usage ==

Returned value:

```
[
  'icon' => 'home',
  'style' => 'outlined'
]
```

Use `render_material_icon( $icon, $style )` or `get_material_icon_html()` in your theme or plugin.

== Frequently Asked Questions ==

= Can I use this with ACF Free? =
No. This plugin requires ACF Pro 6.x+ to support custom field types.

= Which styles are supported? =
Filled, Outlined, Rounded, Sharp, Two Tone.

== Changelog ==

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.0 =
First version available.
