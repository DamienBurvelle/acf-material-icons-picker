<?php
/**
 * Plugin Name: ACF Material Icons Picker
 * Description: A custom Advanced Custom Fields (ACF) field type to select and render [Google Material Icons]
 * Version: 1.0.0
 * Requires at least:
 * Requires PHP:
 * Author: Damien Burvelle
 * Author URI: https://github.com/DamienBurvelle
 * Plugin URI: https://github.com/DamienBurvelle/acf-material-icons-picker
 * Requires Plugins: ACF
 * Copyright: Damien Burvelle
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action('acf/include_field_types', function($version = false) {
    require_once __DIR__ . '/class-db-acf-field-material-icons-picker.php';
		acf_register_field_type( 'db_acf_field_material_icons_picker' );
});

add_action('wp_enqueue_scripts', ['db_acf_field_material_icons_picker', 'register_icon_fonts']);

if (!function_exists('render_material_icon')) {
	/**
	 * Displays a Material icon (global function usable in any theme/plugin).
	 *
	 * @see db_acf_field_material_icons_picker::render_material_icon()
	 *
	 * @param string $icon_name    Name of the Material icon (e.g., 'home', 'menu')
	 * @param string $style        Style : 'filled', 'outlined', 'rounded', 'sharp' ou 'two-tone'.
	 * @param array  $attrs        Attributes HTML ptional (class, aria-hidden, etc.).
	 */
	function render_material_icon(string $icon_name, string $style = 'filled', array $attrs = []): void {
		if (class_exists('db_acf_field_material_icons_picker')) {
			db_acf_field_material_icons_picker::render_material_icon($icon_name, $style, $attrs);
		}
	}
}

if (!function_exists('get_material_icon_html')) {
	/**
	 * Returns the HTML string of the icon (global function).
	 *
	 * @see db_acf_field_material_icons_picker::get_material_icon_html()
	 *
	 * @param string $icon_name   Name of the Material icon
	 * @param string $style       Style.
	 * @param array  $attrs       Attributs HTML.
	 * @return string HTML <span> 
	 */
	function get_material_icon_html(string $icon_name, string $style = 'filled', array $attrs = []): string {
		if (class_exists('db_acf_field_material_icons_picker')) {
			return db_acf_field_material_icons_picker::get_material_icon_html($icon_name, $style, $attrs);
		}
		return '';
	}
}