<?php
/**
 * The widget of Language Switcher for Transposh
 *
 * @link       https://codingfix.com
 * @since      1.0.0
 *
 * @package    Cfx_Language_Switcher_For_Transposh
 */
class Language_Switcher_Widget extends WP_Widget {


	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The options of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $options    The options of this plugin.
	 */
	private $options;

	/**
	 * The english flag which will be used by this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $en_flag    The english flag which will be used by this plugin..
	 */
	private $en_flag;

	/**
	 * The path of the flag icons used by this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $flag_path    The path of the flag icons used by this plugin.
	 */
	private $flag_path;

	/**
	 * The options of Transposh Translation Filter plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $transposh_options    The options of Transposh Translation Filter plugin.
	 */
	private $transposh_options;

	/**
	 * The default language set in Transposh Translation Filter plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $default_lang    The default language set in Transposh Translation Filter plugin.
	 */
	private $default_lang;

	/**
	 * The current language set in Transposh Translation Filter plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $current_lang    The current language set in Transposh Translation Filter plugin.
	 */
	private $current_lang;

	/**
	 * The options of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $used_languages    The languages actually used in current website.
	 */
	private $used_languages;

	/**
	 * The size of the flag.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $flag_size    The size in pixels fo the flag icon.
	 */
	private $flag_size;

	/**
	 * The path to the LSFT stylesheets.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $style_path    he path to the LSFT stylesheets.
	 */
	private $style_path;



	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.2.7
	 */
	public function __construct() {
		parent::__construct(
			'language-switcher-for-transposh-widget',
			'Language Switcher Widget',
			array(
				'customize_selective_refresh' => true,
				'description'                 => __( 'Add a language switcher where you want', 'language-switcher-for-transposh-widget' ),
			)
		);
		if ( file_exists( WP_PLUGIN_DIR . '/transposh-translation-filter-for-wordpress/core/utils.php' ) && file_exists( WP_PLUGIN_DIR . '/transposh-translation-filter-for-wordpress/core/constants.php' ) ) {
			$this->transposh_options = array();
			$this->used_languages    = array();
			$this->default_lang      = '';
			include_once WP_PLUGIN_DIR . '/transposh-translation-filter-for-wordpress/core/utils.php';
			$this->transposh_options = get_option( TRANSPOSH_OPTIONS );
			if ( ! is_array( $this->transposh_options ) || 0 === count( $this->transposh_options ) ) {
				$this->default_lang = 'en_US';
				$this->used_languages = 'en_US';
			} else {
				$this->default_lang   = $this->transposh_options['default_language'];
				$this->used_languages = explode( ',', $this->transposh_options['viewable_languages'] );
			}
			$this->plugin_name  = 'Language Switcher for Transposh';
			$this->version      = '1.2.8';
			$this->options      = get_option( 'cfxlsft_options' );
			$this->style_path   = LSFT_PLUGIN_URL . 'assets/styles/';
			$this->en_flag      = $this->get_en_flag();
			$this->flag_path    = $this->get_flag_path();
			$this->current_lang = $this->get_current_lang();
		}
	}

	/**
	 * Get current language
	 *
	 * @since version 1.2.8
	 */
	public function get_current_lang() {
		$current_lang = transposh_utils::get_language_from_url(
			$_SERVER['REQUEST_URI'],
			isset( $_SERVER['HTTPS'] ) && 'off' !== $_SERVER['HTTPS'] ? 'https://' : 'http://' .
				$_SERVER['SERVER_NAME']
		);
		if ( empty( $current_lang ) ) {
			$current_lang = $this->default_lang;
		}
		return $current_lang;
	}

	/**
	 * Returns the flag which will be used as english flag (UK or USA flag).
	 *
	 * @since    1.0.0
	 */
	public function get_en_flag() {
		$en_flag = 'gb';
		if ( $this->options['flag_type'] == 'tp' ) {
			$en_flag = 'us';
		}
		return $en_flag;
	}

	/**
	 * Returns the path to the flag icons depending on the flags chosen by the developer.
	 *
	 * @since    1.0.0
	 */
	public function get_flag_path() {
		$flag_path = LSFT_PLUGIN_URL . 'assets/flags';
		if ( $this->options['flag_type'] == 'tp' ) {
			$flag_path = plugins_url() . '/transposh-translation-filter-for-wordpress/' . TRANSPOSH_DIR_IMG . '/flags';
		}
		return $flag_path;
	}

	/**
	 * Returns the name of the flag icon.
	 *
	 * @since    1.0.0
	 */
	public function get_flag_name( $lang ) {
		$flag_name = '';
		if ( $lang === 'en' ) {
			$flag_name = $this->en_flag;
		} else {
			$flag_name = transposh_consts::get_language_flag( $lang );
		}
		return $flag_name;
	}

	/**
	 * Returns the URL the user will be redirected to when he change the website language.
	 *
	 * @since    1.0.0
	 * in version 1.0.17 returns the page the user is visiting when he switched to anotger language
	 * last change reverted in version 1.0.20 because it just didn't work.
	 */
	public function get_target_page( $lang ) {
		$site_url     = get_site_url();
		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			$current_page = wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
		}
		if ( $this->options['redirect_to_home'] === 'on' ) {
			$target = $site_url . '/' . $lang;
			if ( $this->default_lang === $lang ) {
				$target = $site_url;
			}
		} else {
			if ( $this->get_current_lang() === $this->default_lang ) {
				$slug = $current_page['path'];
			} else {
				$lang_length = strlen( $this->current_lang );
				$slug        = substr( $current_page['path'], $lang_length + 1 );
			}
			if ( $this->default_lang !== $lang ) {
				$target = $site_url . '/' . $lang . $slug;
			} else {
				$target = $site_url . $slug;
			}
		}

		return $target;
	}

	/**
	 * Returns the markup of the first list item for shortcode only flags.
	 *
	 * @since    1.2.3
	 */
	public function get_list_first_item_markup_sc_flags() {
		$flag_name = $this->get_flag_name( $this->get_current_lang() );
		return "<a class='menu-link' href='#' id='shortcode-stylable-list-first-item'><img src='{$this->flag_path}/$flag_name.png'><span role='presentation' class='dropdown-menu-toggle' style='display:inline-block;'><span class='gp-icon icon-arrow'><svg viewBox='0 0 330 512' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='1em' height='1em'><path d='M305.913 197.085c0 2.266-1.133 4.815-2.833 6.514L171.087 335.593c-1.7 1.7-4.249 2.832-6.515 2.832s-4.815-1.133-6.515-2.832L26.064 203.599c-1.7-1.7-2.832-4.248-2.832-6.514s1.132-4.816 2.832-6.515l14.162-14.163c1.7-1.699 3.966-2.832 6.515-2.832 2.266 0 4.815 1.133 6.515 2.832l111.316 111.317 111.316-111.317c1.7-1.699 4.249-2.832 6.515-2.832s4.815 1.133 6.515 2.832l14.162 14.163c1.7 1.7 2.833 4.249 2.833 6.515z'></path></svg></span></span></a>";
	}

	/**
	 * Returns the markup of the first list item for shortcode only text.
	 *
	 * @since    1.2.3
	 */
	public function get_list_first_item_markup_sc_names() {
		$lang_name = $this->options['original_lang_names'] === 'on' ? ucfirst( transposh_consts::get_language_orig_name( $this->get_current_lang() ) ) : ucfirst( transposh_consts::get_language_name( $this->get_current_lang() ) );
		return "<a href='#' id='shortcode-stylable-list-first-item' class='no_translate'>$lang_name<span role='presentation' class='dropdown-menu-toggle' style='display:inline-block;'><span class='gp-icon icon-arrow'><svg viewBox='0 0 330 512' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='1em' height='1em'><path d='M305.913 197.085c0 2.266-1.133 4.815-2.833 6.514L171.087 335.593c-1.7 1.7-4.249 2.832-6.515 2.832s-4.815-1.133-6.515-2.832L26.064 203.599c-1.7-1.7-2.832-4.248-2.832-6.514s1.132-4.816 2.832-6.515l14.162-14.163c1.7-1.699 3.966-2.832 6.515-2.832 2.266 0 4.815 1.133 6.515 2.832l111.316 111.317 111.316-111.317c1.7-1.699 4.249-2.832 6.515-2.832s4.815 1.133 6.515 2.832l14.162 14.163c1.7 1.7 2.833 4.249 2.833 6.515z'></path></svg></span></span></a>";
	}

	/**
	 * Returns the markup of the first list item for shortcode only flags and text.
	 *
	 * @since    1.2.3
	 */
	public function get_list_first_item_markup_sc_flags_names() {
		$flag_name = $this->get_flag_name( $this->get_current_lang() );
		$lang_name = $this->options['original_lang_names'] === 'on' ? ucfirst( transposh_consts::get_language_orig_name( $this->get_current_lang() ) ) : ucfirst( transposh_consts::get_language_name( $this->get_current_lang() ) );
		return "<a href='#' id='shortcode-stylable-list-first-item' class='no_translate' style='background: url({$this->flag_path}/$flag_name.png) 0 center no-repeat;'>$lang_name<span role='presentation' class='dropdown-menu-toggle' style='display:inline-block;'><span class='gp-icon icon-arrow'><svg viewBox='0 0 330 512' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='1em' height='1em'><path d='M305.913 197.085c0 2.266-1.133 4.815-2.833 6.514L171.087 335.593c-1.7 1.7-4.249 2.832-6.515 2.832s-4.815-1.133-6.515-2.832L26.064 203.599c-1.7-1.7-2.832-4.248-2.832-6.514s1.132-4.816 2.832-6.515l14.162-14.163c1.7-1.699 3.966-2.832 6.515-2.832 2.266 0 4.815 1.133 6.515 2.832l111.316 111.317 111.316-111.317c1.7-1.699 4.249-2.832 6.515-2.832s4.815 1.133 6.515 2.832l14.162 14.163c1.7 1.7 2.833 4.249 2.833 6.515z'></path></svg></span></span></a>";
	}

	/**
	 * Returns the markup of thelist item for shortcode only flags.
	 *
	 * @since    1.2.3
	 */
	public function get_list_item_markup_sc_flags( $lang ) {
		$flag_name = $this->get_flag_name( $lang );
		$target    = $this->get_target_page( $lang );
		return "<a href='$target'><img src='" . $this->flag_path . '/' . $flag_name . ".png' /></a>";
	}

	/**
	 * Returns the markup of thelist item for shortcode only text.
	 *
	 * @since    1.2.3
	 */
	public function get_list_item_markup_sc_names( $lang ) {
		$target    = $this->get_target_page( $lang );
		$lang_name = $this->options['original_lang_names'] === 'on' ? ucfirst( transposh_consts::get_language_orig_name( $lang ) ) : ucfirst( transposh_consts::get_language_name( $lang ) );
		return "<a href='$target'>$lang_name</a>";
	}

	/**
	 * Returns the markup of thelist item for shortcode only flags and text.
	 *
	 * @since    1.2.3
	 */
	public function get_list_item_markup_sc_flags_names( $lang ) {
		$flag_name = $this->get_flag_name( $lang );
		$target    = $this->get_target_page( $lang );
		$lang_name = $this->options['original_lang_names'] === 'on' ? ucfirst( transposh_consts::get_language_orig_name( $lang ) ) : ucfirst( transposh_consts::get_language_name( $lang ) );
		return "<a style='background: url({$this->flag_path}/$flag_name.png) 0 center no-repeat;' href='$target'>$lang_name</a>";
	}


	// The widget form (for the backend )
	public function form( $instance ) {
		$defaults = array(
			'select' => '',
		);
		extract( wp_parse_args( (array) $instance, $defaults ) );
		?>
		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'select' ) ); ?>"><?php esc_html_e( 'Select', 'text_domain' ); ?></label>
			<select name="<?php echo esc_html( $this->get_field_name( 'select' ) ); ?>" id="<?php echo esc_html( $this->get_field_id( 'select' ) ); ?>" class="widefat">
				<?php
				$options = array(
					''               => __( 'Select', 'cfx-language-switcher-for-transposh' ),
					'h_flags'        => __( 'Horizontal flags', 'cfx-language-switcher-for-transposh' ),
					'v_flags'        => __( 'Vertical Flags', 'cfx-language-switcher-for-transposh' ),
					'list_flags'     => __( 'Dropdown Flags', 'cfx-language-switcher-for-transposh' ),
					'list_text'      => __( 'Dropdown Text', 'cfx-language-switcher-for-transposh' ),
					'list_flag_text' => __( 'Dropdown Flags and Text', 'cfx-language-switcher-for-transposh' ),
					'list_native'    => __( 'Dropdown native', 'cfx-language-switcher-for-transposh' ),
				);

				// Loop through options and add each one to the select dropdown
				foreach ( $options as $key => $name ) {
					echo esc_html( '<option value="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" ' . selected( $select, $key, false ) . '>' . $name . '</option>' );
				}
				?>
			</select>
		</p>
		<?php
	}

	// Update widget settings
	public function update( $new_instance, $old_instance ) {
		$instance           = $old_instance;
		$instance['select'] = isset( $new_instance['select'] ) ? wp_strip_all_tags( $new_instance['select'] ) : '';
		return $instance;
	}

	// Display the widget
	public function widget( $args, $instance ) {
		extract( $args );
		$select = isset( $instance['select'] ) ? $instance['select'] : '';

		// WordPress core before_widget hook (always include )
		echo esc_html( $before_widget );

		switch ( $select ) {
			case 'h_flags':
				echo esc_html( $this->display_horizontal_flags() );
				break;
			case 'v_flags':
				echo esc_html( $this->display_vertical_flags() );
				break;
			case 'list_flags':
				echo esc_html( $this->display_list_flags() );
				break;
			case 'list_text':
				echo esc_html( $this->display_list_text() );
				break;
			case 'list_flag_text':
				echo esc_html( $this->display_list_flag_and_text() );
				break;
			case 'list_native':
				echo esc_html( $this->display_list_native() );
				break;
		}

		// WordPress core after_widget hook (always include )
		echo esc_html( $after_widget );
	}

	private function display_horizontal_flags() {
		wp_enqueue_style( $this->plugin_name . '-shortcode_horizontal_flags', $this->style_path . 'shortcode_horizontal_flags.css', array(), $this->version, 'all' );
		$items = '';
		if ( ! empty( $this->used_languages ) && count( $this->used_languages ) > 1 ) {
			$flag_name = $this->get_flag_name( $this->get_current_lang() );
			$lang_name = $this->options['original_lang_names'] === 'on' ? ucfirst( transposh_consts::get_language_orig_name( $this->get_current_lang() ) ) : ucfirst( transposh_consts::get_language_name( $this->get_current_lang() ) );
			$items     = '<ul id="lsft_horizontal_flags">';
			foreach ( $this->used_languages as $lang ) {
				$flag_name = $this->get_flag_name( $lang );
				$target    = $this->get_target_page( $lang );
				$items    .= '<li class="switch_lang no_translate" style="margin-bottom:auto;"><a class="lsft_sc_h_flags" href="' . $target . '"><img src="' . $this->flag_path . '/' . $flag_name . '.png" /></a></li>';
			}
			if ( $this->get_current_lang() != $this->default_lang ) {

				$user          = wp_get_current_user();
				$allowed_roles = array( 'editor', 'administrator', 'author' );
				/**this button will be available only to certain user types */
				if ( array_intersect( $allowed_roles, $user->roles ) ) {
					$items .= '<li class="edit_translation no_translate"><a class="lsft_sc_h_flags" href="#"> Edit</a></li>';
				}
			}
			$items .= '</ul>';
		}
		return $items;
	}

	private function display_vertical_flags() {
		wp_enqueue_style( $this->plugin_name . '-shortcode_vertical_flags', $this->style_path . 'shortcode_vertical_flags.css', array(), $this->version, 'all' );
		$items = '';
		if ( ! empty( $this->used_languages ) ) {
			if ( count( $this->used_languages ) > 1 ) {
				$flag_name = $this->get_flag_name( $this->get_current_lang() );
				$lang_name = $this->options['original_lang_names'] === 'on' ? ucfirst( transposh_consts::get_language_orig_name( $this->get_current_lang() ) ) : ucfirst( transposh_consts::get_language_name( $this->get_current_lang() ) );
				$items     = '<ul id="lsft_vertical_flags">';
				// $items .= '<li class="switch_lang no_translate" style="margin-bottom:auto;"><a class="lsft_sc_h_flags" href="' . $target . '"><img src="' . $this->flag_path . '/' . $flag_name . '.png" /></a></li>';
				foreach ( $this->used_languages as $lang ) {
					$flag_name = $this->get_flag_name( $lang );
					$target    = $this->get_target_page( $lang );
					$items    .= '<li class="switch_lang no_translate"><a class="lsft_sc_h_flags" href="' . $target . '"><img src="' . $this->flag_path . '/' . $flag_name . '.png" /></a></li>';
				}
				if ( $this->get_current_lang() != $this->default_lang ) {

					$user          = wp_get_current_user();
					$allowed_roles = array( 'editor', 'administrator', 'author' );
					/**this button will be available only to certain user types */
					if ( array_intersect( $allowed_roles, $user->roles ) ) {
						$items .= '<li class="edit_translation no_translate"><a class="lsft_sc_h_flags" href="#"> Edit</a></li>';
					}
				}
				$items .= '</ul>';
			}
		}
		return $items;
	}

	private function display_list_flags() {
		wp_enqueue_style( $this->plugin_name . '-shortcode_custom_dropdown_flags', $this->style_path . 'shortcode_custom_dropdown_flags.css', array(), $this->version, 'all' );
		$items  = '';
		$items  = '<ul id="lsft_custom_dropdown_flags">';
		$items .= "<li class='stylable-list'>";
		$items .= $this->get_list_first_item_markup_sc_flags();
		if ( ( $key = array_search( $this->get_current_lang(), $this->used_languages ) ) !== false ) {
			unset( $this->used_languages[ $key ] );
		}
		$items .= "<ul id='sc_flags_submenu'>";
		foreach ( $this->used_languages as $lang ) {
			$items .= "<li class='no_translate'>" . $this->get_list_item_markup_sc_flags( $lang ) . '</li>';
		}
		if ( $this->get_current_lang() != $this->default_lang ) {

			$user          = wp_get_current_user();
			$allowed_roles = array( 'editor', 'administrator', 'author' );
			/**this button will be available only to certain user types */
			if ( array_intersect( $allowed_roles, $user->roles ) ) {
				$items .= '<li class="edit_translation no_translate"><a class="lsft_sc_h_flags" href="#">Edit</a></li>';
			}
		}
		$items .= '</ul></li>';
		$items .= '</ul>';
		return $items;
	}

	private function display_list_text() {
		$items  = '';
		$items  = '<ul id="lsft_custom_dropdown_names">';
		$items .= "<li class='stylable-list'>";
		$items .= $this->get_list_first_item_markup_sc_names();
		if ( ( $key = array_search( $this->get_current_lang(), $this->used_languages ) ) !== false ) {
			unset( $this->used_languages[ $key ] );
		}
		$items .= "<ul id='sc_names_submenu'>";
		foreach ( $this->used_languages as $lang ) {
			$items .= "<li class='no_translate'>" . $this->get_list_item_markup_sc_names( $lang ) . '</li>';
		}
		if ( $this->get_current_lang() != $this->default_lang ) {
			$user          = wp_get_current_user();
			$allowed_roles = array( 'editor', 'administrator', 'author' );
			/**this button will be available only to certain user types */
			if ( array_intersect( $allowed_roles, $user->roles ) ) {
				$items .= "<li class='edit_translation no_translate'><a class='lsft_sc_h_flags' href='#'>Edit</a></li>";
			}
		}
		$items .= '</ul></li>';
		$items .= '</ul>';
		return $items;
	}

	private function display_list_flag_and_text() {
		$items  = '';
		$items  = '<ul id="lsft_custom_dropdown_flags_names">';
		$items .= "<li class='stylable-list flag-and-text'>";
		$items .= $this->get_list_first_item_markup_sc_flags_names();
		if ( ( $key = array_search( $this->get_current_lang(), $this->used_languages ) ) !== false ) {
			unset( $this->used_languages[ $key ] );
		}
		$items .= "<ul id='sc_flags_names_submenu'>";
		foreach ( $this->used_languages as $lang ) {
			$items .= "<li class='no_translate flag-and-text'>" . $this->get_list_item_markup_sc_flags_names( $lang ) . '</li>';
		}
		if ( $this->get_current_lang() != $this->default_lang ) {

			$user          = wp_get_current_user();
			$allowed_roles = array( 'editor', 'administrator', 'author' );
			/**this button will be available only to certain user types */
			if ( array_intersect( $allowed_roles, $user->roles ) ) {
				$items .= '<li class="edit_translation no_translate"><a class="lsft_sc_h_flags" href="#">Edit</a></li>';
			}
		}
		$items .= '</ul></li>';
		$items .= '</ul>';
		return $items;
	}

	private function display_list_native() {
		$items = '';
		if ( ! empty( $this->used_languages ) && count( $this->used_languages ) > 1 ) {
			$items .= '<select id="switch_lang_select" class="switch_lang_select stylable-select">';
			foreach ( $this->used_languages as $lang ) {
				$target    = $this->get_target_page( $lang );
				$lang_name = $this->options['original_lang_names'] === 'on' ? ucfirst( transposh_consts::get_language_orig_name( $lang ) ) : ucfirst( transposh_consts::get_language_name( $lang ) );
				if ( $lang == $this->get_current_lang() ) {
					$selected = 'selected';
				} else {
					$selected = '';
				}
				$items .= '<option data-target="' . $target . '" value="' . $lang . '" ' . $selected . ' class="no_translate">' . $lang_name . ' </option>';
			}
			$items .= '</select>';
			if ( $this->get_current_lang() != $this->default_lang ) {
				$user          = wp_get_current_user();
				$allowed_roles = array( 'editor', 'administrator', 'author' );
				/**this button will be available only to certain user types */
				if ( array_intersect( $allowed_roles, $user->roles ) ) {
					$items .= '<a class="edit_translation no_translate" href="#">Edit</a>';
				}
			}
		}
		return $items;
	}
}
