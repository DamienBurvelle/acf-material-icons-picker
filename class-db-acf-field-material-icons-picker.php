<?php
/**
 * Defines the custom field type class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * db_acf_field_material_icons_picker class.
 */
class db_acf_field_material_icons_picker extends \acf_field {

	protected static $enqueued_styles = [
		'filled'    => 'MaterialIcons-Regular.codepoints',
		'outlined'  => 'MaterialIconsOutlined-Regular.codepoints',
		'rounded'   => 'MaterialIconsRound-Regular.codepoints',
		'sharp'     => 'MaterialIconsSharp-Regular.codepoints',
		'two-tone'  => 'MaterialIconsTwoTone-Regular.codepoints',
	];

	public static function get_enqueued_styles() {
		return self::$enqueued_styles;
	}

	public static function register_icon_fonts() {
		$style_urls = [
			'filled'    => 'https://fonts.googleapis.com/icon?family=Material+Icons',
			'outlined'  => 'https://fonts.googleapis.com/icon?family=Material+Icons+Outlined',
			'rounded'   => 'https://fonts.googleapis.com/icon?family=Material+Icons+Round',
			'sharp'     => 'https://fonts.googleapis.com/icon?family=Material+Icons+Sharp',
			'two-tone'  => 'https://fonts.googleapis.com/icon?family=Material+Icons+Two+Tone',
		];

		// $version = $this->env['version'];
		$version = '1.0';
		$base_handle = 'db-material-icons-fonts';

		foreach (self::$enqueued_styles as $style_key => $_) {
			if (isset($style_urls[$style_key])) {
				wp_register_style(
					$base_handle . '-' . $style_key,
					$style_urls[$style_key],
					[],
					$version
				);
				wp_enqueue_style($base_handle . '-' . $style_key);
			}
		}
	}

	/**
	 * Génère le HTML d’une icône Material.
	 *
	 * @param string $icon_name Name of the Material icon (e.g., 'home', 'menu')
	 * @param string $style     Style : 'filled', 'outlined', 'rounded', 'sharp' ou 'two-tone'.
	 * @param array  $attrs     Attributes HTML optional (ex: ['class' => 'my-class', 'aria-hidden' => 'true']).
	 * @return string           Full html.
	 */
	public static function get_material_icon_html(string $icon_name, string $style = 'filled', array $attrs = []): string {
		if($style == 'filled') {
			$base_class = 'material-icons';
		}elseif($field_style == 'rounded') {
			$base_class = 'material-icons-round';
		}else{
			$base_class = 'material-icons-' . $style . '';
		}
		$custom_classes = $attrs['class'] ?? '';
		$full_class = trim($base_class . ' ' . $custom_classes);
		$attrs['class'] = $full_class;

		$attr_str = '';
		foreach ($attrs as $key => $value) {
			$attr_str .= ' ' . esc_attr($key) . '="' . esc_attr($value) . '"';
		}

		return '<span' . $attr_str . '>' . esc_html($icon_name) . '</span>';
	}

	/**
	 *Returns material icon.
	 *
	 * @param string $icon_name  Name of the Material icon
	 * @param string $style      Style
	 * @param array  $attrs      Attributes
	 */
	public static function render_material_icon(string $icon_name, string $style = 'filled', array $attrs = []): void {
		echo self::get_material_icon_html($icon_name, $style, $attrs);
	}

	/**
	 * Controls field type visibilty in REST requests.
	 *
	 * @var bool
	 */
	public $show_in_rest = true;

	/**
	 * Environment values relating to the theme or plugin.
	 *
	 * @var array $env Plugin or theme context such as 'url' and 'version'.
	 */
	private $env;


	/**
	 * Constructor.
	 */
	public function __construct() {
		/**
		 * Field type reference used in PHP and JS code.
		 *
		 * No spaces. Underscores allowed.
		 */
		$this->name = 'material_icons_picker';

		/**
		 * Field type label.
		 *
		 * For public-facing UI. May contain spaces.
		 */
		$this->label = __( 'material-icons-picker', 'material-icons-picker' );

		/**
		 * The category the field appears within in the field type picker.
		 */
		$this->category = 'choice'; // basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME

		/**
		 * Field type Description.
		 *
		 * For field descriptions. May contain spaces.
		 */
		$this->description = __( 'This field does amazing things.', 'material-icons-picker' );

		/**
		 * Field type Doc URL.
		 *
		 * For linking to a documentation page. Displayed in the field picker modal.
		 */
		$this->doc_url = '';

		/**
		 * Field type Tutorial URL.
		 *
		 * For linking to a tutorial resource. Displayed in the field picker modal.
		 */
		$this->tutorial_url = '';

		/**
		 * Defaults for your custom user-facing settings for this field type.
		 */
		$this->defaults = [];

		/**
		 * Strings used in JavaScript code.
		 *
		 * Allows JS strings to be translated in PHP and loaded in JS via:
		 *
		 * ```js
		 * const errorMessage = acf._e("material_icons_picker", "error");
		 * ```
		 */
		$this->l10n = array(
			'error'	=> __( 'Error!', 'material-icons-picker' ),
		);

		$this->env = array(
			'url'     => site_url( str_replace( ABSPATH, '', __DIR__ ) ), // URL to the acf-material-icons-picker directory.
			'version' => '1.0', // Replace this with your theme or plugin version constant.
		);

		/**
		 * Field type preview image.
		 *
		 * A preview image for the field type in the picker modal.
		 */
		$this->preview_image = $this->env['url'] . '/assets/images/field-preview-custom.png';

		add_action('admin_menu', function() {
				add_submenu_page(
						'tools.php',
						'Générer les icônes Material',
						'Générer les icônes Material',
						'manage_options',
						'generate-material-icons',
						'material_icon_generate_admin_page'
				);
		});

		function material_icon_generate_admin_page() {
				if (isset($_POST['generate_icons']) && check_admin_referer('generate_icons_action')) {
						$result = material_icon_generate_json();
						echo '<div class="notice notice-success"><p>Fichier généré avec succès : <code>' . esc_html($result) . '</code></p></div>';
				}

				echo '<div class="wrap">';
				echo '<h1>Générer le fichier icons.json</h1>';
				echo '<form method="post">';
				wp_nonce_field('generate_icons_action');
				submit_button('Générer icons.json', 'primary', 'generate_icons');
				echo '</form>';
				echo '</div>';
		}

		function material_icon_generate_json() {
				$styles = db_acf_field_material_icons_picker::get_enqueued_styles();

				$data = [];

				foreach ($styles as $style => $filename) {
					$url = "https://raw.githubusercontent.com/google/material-design-icons/master/font/$filename";
					$response = wp_remote_get($url);

					if (is_wp_error($response)) {
							error_log("Erreur lors du téléchargement de $url : " . $response->get_error_message());
							continue;
					}

					$content = wp_remote_retrieve_body($response);
					if (!$content) continue;

					$lines = explode("\n", trim($content));
					$names = array_map(function ($line) {
							return explode(' ', $line)[0];
					}, $lines);

					// Remove duplicates and sort
					$names = array_unique($names);
					sort($names);

					$data[$style] = array_values($names);
				}

				$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
				if (!$json) return false;

				$relative_path = 'assets/icons.json';
				$path = plugin_dir_path(__FILE__) . $relative_path;

				// Creates the folder if does not exist
				wp_mkdir_p(dirname($path));

				file_put_contents($path, $json);

				return $relative_path;
				}

		parent::__construct();
	}

	/**
	 * Settings to display when users configure a field of this type.
	 *
	 * These settings appear on the ACF “Edit Field Group” admin page when
	 * setting up the field.
	 *
	 * @param array $field
	 * @return void
	 */
		public function render_field_settings( $field ) {
		/*
		 * Repeat for each setting you wish to display for this field type.
		 * 
		 */
		// acf_render_field_setting(
		// 	$field,
		// 	array(
		// 		'label'			=> __( 'Font Size','material-icons-picker' ),
		// 		'instructions'	=> __( 'Customise the input font size','material-icons-picker' ),
		// 		'type'			=> 'number',
		// 		'name'			=> 'font_size',
		// 		'append'		=> 'px',
		// 	)
		// );

		// To render field settings on other tabs in ACF 6.0+:
		// https://www.advancedcustomfields.com/resources/adding-custom-settings-fields/#moving-field-setting
	}

	/**
	 * HTML content to show when a publisher edits the field on the edit screen.
	 *
	 * @param array $field The field settings and values.
	 * @return void
	 */
	public function render_field( $field ) {
		// Debug output to show what field data is available.
		// echo '<pre>';
		// print_r( $field );
		// echo '</pre>';

		// Display an input field that uses the 'font_size' setting.
		/*
		<input
			type="text"
			class="setting-font-size"
			name="<?php echo esc_attr($field['name']) ?>"
			value="<?php echo esc_attr($field['value']) ?>"
			style="font-size:<?php echo esc_attr( $field['font_size'] ) ?>px;"
		/>
		*/

		$icons_path = plugin_dir_path(__FILE__) . '/assets/icons.json';
        
		if (!file_exists($icons_path)) {
				echo '<p style="color:red;">⚠️ Fichier icons.json manquant.</p>';
				return;
		}

		$icons_by_style = json_decode(file_get_contents($icons_path), true);

		if (!is_array($icons_by_style) || empty($icons_by_style)) {
		echo '<p style="color:red;">⚠️ Fichier icons.json invalide.</p>';
		return;
	}

		$field_id = esc_attr($field['id']);
		$field_icon = esc_attr($field['value']['icon'] ?? '');
		$field_style = esc_attr($field['value']['style'] ?? 'filled');
		$field_name = esc_attr($field['name']);

		// List of available styles, dynamically extracted from icons.json
		$available_styles = [];
		foreach (self::$enqueued_styles as $key => $_) {
			$available_styles[$key] = ucfirst(str_replace('-', ' ', $key));
		}

		$labels = [
			'filled'    => 'Filled',
			'outlined'  => 'Outlined',
			'rounded'   => 'Rounded',
			'sharp'     => 'Sharp',
			'two-tone'  => 'Two Tone',
		];

		$available_styles = array_intersect_key($labels, self::$enqueued_styles);

		echo '<div class="acf-material-icon-wrapper" data-input-id="' . $field_id . '" data-field-name="' . $field_name . '" style="position: relative;">';

				$has_icon = !empty($field_icon);

				if($field_style == 'filled') {
						$style_class = 'material-icons';
					}elseif($field_style == 'rounded') {
						$style_class = 'material-icons-round';
					}else{
						$style_class = 'material-icons-' . $field_style . '';
					}

				echo '<div class="icon-preview">';
						// Trigger preview
						echo '<div class="icon-preview-toggle">';
								echo '<span class="icon-preview-icon ' . esc_html($has_icon ? $style_class : 'material-icons icon-preview-icon-add') . '" data-style="' . esc_attr($field_style) . '">' . esc_html($field_icon ?: 'add') . '</span>';								
						echo '</div>';

						// 
						echo '<button type="button" class="icon-clear-button" title="Effacer" style="display: ' . ($has_icon ? 'inline-block' : 'none') . ';">✕</button>';
				echo '</div>';

				// Popover 
				echo '<div class="acf-icon-popover" style="display:none;">';
						// Tabs
						echo '<div class="acf-icon-tabs">';
						foreach ($available_styles as $style_key => $label) {
								$active = ($style_key === $field_style) ? 'active' : '';
								echo '<button class="tab-button ' . $active . '" data-style="' . esc_attr($style_key) . '">' . esc_html($label) . '</button>';
						}
						echo '</div>';


						// Icon grid
						foreach ($available_styles as $style_key => $label) {
								$active = ($style_key === $field_style) ? 'active' : '';
								
								echo '<div class="icon-grid grid-' . esc_attr($style_key) . ' ' . $active . '" data-style="' . esc_attr($style_key) . '">';
										// searchbar
										echo '<div class="acf-material-icon-search-wrap"><span class="acf-material-icon-search-icon material-icons-outlined">search</span><input type="search" class="acf-material-icon-search" placeholder="Rechercher une icône dans ' . esc_attr($label) . '…"></div>';
										
										echo '<div class="acf-material-icons-grid">';
										foreach ($icons_by_style[$style_key] as $icon) {
											if($style_key == 'filled') {
												$style_class = 'material-icons';
											}elseif($style_key == 'rounded') {
												$style_class = 'material-icons-round';
												$style_key = 'round';
											}else{
												$style_class = 'material-icons-' . $style_key . '';
											}
												$selected = ($icon === $field_icon && $style_key === $field_style) ? 'selected' : '';
												echo '<div class="material-icon-option ' . $selected . '" data-icon="' . esc_attr($icon) . '" data-style="' . esc_attr($style_key) . '" data-name="' . esc_attr($icon) . '">';
												echo '<span class="' . $style_class . '">' . esc_html($icon) . '</span>';
												echo '</div>';
										}
										echo '</div>';
								echo '</div>';
						}
				echo '</div>'; // end popover
		
			// Hidden fields
			echo '<input type="hidden" name="' . $field_name . '[icon]" value="' . esc_attr($field_icon) . '" class="material-icon-value">';
			echo '<input type="hidden" name="' . $field_name . '[style]" value="' . esc_attr($field_style) . '" class="material-icon-style">';

		echo '</div>';
		?>
		
		<?php
	}

	/**
	 * Enqueues CSS and JavaScript needed by HTML in the render_field() method.
	 *
	 * Callback for admin_enqueue_script.
	 *
	 * @return void
	 */
	public function input_admin_enqueue_scripts() {
		$version = $this->env['version'];

		self::register_icon_fonts();

		wp_register_script(
			'db-material-icons-picker',
			plugin_dir_url(__FILE__) . '/assets/js/field.js',
			['acf-input', 'jquery'],
			$version
		);

		wp_register_style(
			'db-material-icons-picker',
			plugin_dir_url(__FILE__) . '/assets/css/field.css',
			array( 'acf-input' ),
			$version
		);

		wp_enqueue_style( 'db-material-icons-picker' );
		wp_enqueue_script( 'db-material-icons-picker' );
	}

	private function get_icons() {
			$json_path = plugin_dir_path(__FILE__) . 'icons.json';
			if (file_exists($json_path)) {
					$icons = json_decode(file_get_contents($json_path), true);
					return is_array($icons) ? $icons : [];
			}
			return [];
	}
}
