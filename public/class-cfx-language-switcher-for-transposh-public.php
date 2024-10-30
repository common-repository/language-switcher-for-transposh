<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://codingfix.com
 * @since      1.0.0
 *
 * @package    Cfx_Language_Switcher_For_Transposh
 * @subpackage Cfx_Language_Switcher_For_Transposh/public
 */

// [ ] change ids and stylesheets for shortcodes and widgtes.
// [ ] move content of the different stylesheets in cfx-language-switcher-for-transposh-public.css.
// [ ] remove Flag style select in admin general tab.
// [ ] allow user to copy or download the style they want to apply so they can put in their custom css and edit what they have to change.
// [ ] allow some very basic customization of styles like flag size (for menu and shorcode), horizontal and vertical alignment (for shortcodes).
// [ ] check filter_input filter option.

/**
 * if Transposh is not installed we show an error message on activation
 */
// if (!file_exists(WP_PLUGIN_DIR . '/transposh-translation-filter-for-wordpress/core/utils.php')) {
// echo "Language Switcher for Transposh can't be activated because Transposh plugin can't be found in WordPress plugins directory. Please, install Transposh plugin before to activate Language Switcher for Transposh. Thank you.";
// }
// require_once WP_PLUGIN_DIR . '/transposh-translation-filter-for-wordpress/core/utils.php';
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Cfx_Language_Switcher_For_Transposh
 * @subpackage Cfx_Language_Switcher_For_Transposh/public
 * @author     Marco Gasi <codingfix@codingfix.com>
 */

class Cfx_Language_Switcher_For_Transposh_Public {


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
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		if ( file_exists( WP_PLUGIN_DIR . '/transposh-translation-filter-for-wordpress/core/utils.php' ) && file_exists( WP_PLUGIN_DIR . '/transposh-translation-filter-for-wordpress/core/constants.php' ) ) {
			include_once WP_PLUGIN_DIR . '/transposh-translation-filter-for-wordpress/core/utils.php';
			include_once WP_PLUGIN_DIR . '/transposh-translation-filter-for-wordpress/core/constants.php';
			$this->plugin_name = $plugin_name;
			$this->version     = $version;
			$this->options     = get_option( 'cfxlsft_options' );
			$this->style_path  = LSFT_PLUGIN_URL . 'assets/styles/';
			$this->en_flag     = $this->get_en_flag();
			$this->flag_path   = $this->get_flag_path();
			// $this->flag_size = $this->options['flag_size'] . 'px';
			if ( defined( TRANSPOSH_OPTIONS ) ) {
				die();
			}
			$this->transposh_options = get_option( TRANSPOSH_OPTIONS );
			$this->default_lang      = isset( $this->transposh_options['default_language'] ) ? $this->transposh_options['default_language'] : 'en';
			if ( isset( $this->transposh_options['viewable_languages'] ) ) {
				$this->used_languages = explode( ',', $this->transposh_options['viewable_languages'] );
			} else {
				$this->used_languages = array( 'en' );
			}
			if ( ! in_array( $this->default_lang, $this->used_languages ) ) {
				array_unshift( $this->used_languages, $this->default_lang );
			}
			$this->current_lang = $this->get_current_lang();
		}
	}

	public function get_current_lang() {
		$current_lang = transposh_utils::get_language_from_url(
			$_SERVER['REQUEST_URI'],
			isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ? 'https://' : 'http://' .
			$_SERVER['SERVER_NAME']
		);
		if ( empty( $current_lang ) ) {
			$current_lang = $this->default_lang;
		}
		return $current_lang;
	}

	/**
	 * Register shortcodes
	 *
	 * @since version 1.2.0
	 */
	public function register_shortcodes() {
		add_shortcode( 'lsft_horizontal_flags', array( $this, 'shortcode_horizontal_flags' ) );
		add_shortcode( 'lsft_vertical_flags', array( $this, 'shortcode_vertical_flags' ) );
		add_shortcode( 'lsft_custom_dropdown_flags', array( $this, 'shortcode_custom_dropdown_flags' ) );
		add_shortcode( 'lsft_custom_dropdown_flags_names', array( $this, 'shortcode_custom_dropdown_flags_names' ) );
		add_shortcode( 'lsft_custom_dropdown_names', array( $this, 'shortcode_custom_dropdown_names' ) );
		add_shortcode( 'lsft_native_dropdown', array( $this, 'shortcode_native_dropdown' ) );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cfx-language-switcher-for-transposh-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-basic_flags', $this->style_path . 'basic_flags.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-basic_list', $this->style_path . 'basic_list.css', array(), '2.0', 'all' );
		wp_enqueue_style( $this->plugin_name . '-basic_select', $this->style_path . 'basic_select.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-shortcode_horizontal_flags', $this->style_path . 'shortcode_horizontal_flags.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-shortcode_vertical_flags', $this->style_path . 'shortcode_vertical_flags.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-shortcode_custom_dropdown_flags', $this->style_path . 'shortcode_custom_dropdown_flags.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-shortcode_custom_dropdown_names', $this->style_path . 'shortcode_custom_dropdown_names.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-shortcode_custom_dropdown_flags_names', $this->style_path . 'shortcode_custom_dropdown_flags_names.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cfx-language-switcher-for-transposh-public.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Returns the flag which will be used as english flag (UK or USA flag).
	 *
	 * @since    1.0.0
	 */
	public function get_en_flag() {
		$en_flag = 'gb';
		if ( isset( $this->options['usa_flag'] ) && 'on' === $this->options['usa_flag'] ) {
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
		if ( isset( $this->options['flag_type'] ) && 'tp' === $this->options['flag_type'] ) {
			$flag_path = plugins_url() . '/transposh-translation-filter-for-wordpress/' . TRANSPOSH_DIR_IMG . '/flags';
		}
		return $flag_path;
	}

	/**
	 * Returns the name of the flag icon.
	 *
	 * @param string $lang selected language.
	 * @since    1.0.0
	 */
	public function get_flag_name( $lang ) {
		if ( 'tp' === $this->options['flag_type'] ) {
			$flag_name = transposh_consts::get_language_flag( $lang );
		} else {
			if ( $lang === 'en' ) {
				$flag_name = $this->en_flag;
			} else {
				$flag_name = transposh_consts::get_language_flag( $lang );
			}
		}
		return $flag_name;
	}

	/**
	 * Returns the URL the user will be redirected to when he change the website language.
	 *
	 * @param string $lang selected language.
	 * @since    1.0.0
	 * in version 1.0.17 returns the page the user is visiting when he switched to anotger language
	 * last change reverted in version 1.0.20 because it just didn't work.
	 */
	public function get_target_page( $lang ) {
		$site_url     = get_site_url();
		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			$current_page = wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
		}
		if ( 'on' !== $this->options['redirect_to_home'] && is_array( $current_page ) ) {
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
		} else {
			$target = $site_url . '/' . $lang;
			if ( $this->default_lang === $lang ) {
				$target = $site_url;
			}
		}

		return $target;
	}

	/**
	 * Returns the markup of the first list item.
	 *
	 * @since    1.0.0
	 */
	public function get_list_first_item_markup() {
		$flag_name = $this->get_flag_name( $this->get_current_lang() );
		$lang_name = $this->options['original_lang_names'] === 'on' ? ucfirst( transposh_consts::get_language_orig_name( $this->get_current_lang() ) ) : ucfirst( transposh_consts::get_language_name( $this->get_current_lang() ) );
		switch ( $this->options['custom_list_items'] ) {
			case 'flag-only':
				$item = "<a class='menu-link' href='#' id='stylable-list-first-item'><img src='$this->flag_path/" . $flag_name . ".png'><span role='presentation' class='dropdown-menu-toggle'><span class='gp-icon icon-arrow'><svg viewBox='0 0 330 512' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='1em' height='1em'><path d='M305.913 197.085c0 2.266-1.133 4.815-2.833 6.514L171.087 335.593c-1.7 1.7-4.249 2.832-6.515 2.832s-4.815-1.133-6.515-2.832L26.064 203.599c-1.7-1.7-2.832-4.248-2.832-6.514s1.132-4.816 2.832-6.515l14.162-14.163c1.7-1.699 3.966-2.832 6.515-2.832 2.266 0 4.815 1.133 6.515 2.832l111.316 111.317 111.316-111.317c1.7-1.699 4.249-2.832 6.515-2.832s4.815 1.133 6.515 2.832l14.162 14.163c1.7 1.7 2.833 4.249 2.833 6.515z'></path></svg></span></span></a>";
				break;
			case 'text-only':
				$item = "<a class='menu-link' href='#' id='stylable-list-first-item' class='no_translate'>$lang_name<span role='presentation' class='dropdown-menu-toggle'><span class='gp-icon icon-arrow'><svg viewBox='0 0 330 512' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='1em' height='1em'><path d='M305.913 197.085c0 2.266-1.133 4.815-2.833 6.514L171.087 335.593c-1.7 1.7-4.249 2.832-6.515 2.832s-4.815-1.133-6.515-2.832L26.064 203.599c-1.7-1.7-2.832-4.248-2.832-6.514s1.132-4.816 2.832-6.515l14.162-14.163c1.7-1.699 3.966-2.832 6.515-2.832 2.266 0 4.815 1.133 6.515 2.832l111.316 111.317 111.316-111.317c1.7-1.699 4.249-2.832 6.515-2.832s4.815 1.133 6.515 2.832l14.162 14.163c1.7 1.7 2.833 4.249 2.833 6.515z'></path></svg></span></span></a>";
				break;
			case 'flag-and-text':
				$item = "<a class='menu-link' href='#' id='stylable-list-first-item' class='no_translate' style='background: url({$this->flag_path}/$flag_name.png) 0 center no-repeat;'>$lang_name<span role='presentation' class='dropdown-menu-toggle'><span class='gp-icon icon-arrow'><svg viewBox='0 0 330 512' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='1em' height='1em'><path d='M305.913 197.085c0 2.266-1.133 4.815-2.833 6.514L171.087 335.593c-1.7 1.7-4.249 2.832-6.515 2.832s-4.815-1.133-6.515-2.832L26.064 203.599c-1.7-1.7-2.832-4.248-2.832-6.514s1.132-4.816 2.832-6.515l14.162-14.163c1.7-1.699 3.966-2.832 6.515-2.832 2.266 0 4.815 1.133 6.515 2.832l111.316 111.317 111.316-111.317c1.7-1.699 4.249-2.832 6.515-2.832s4.815 1.133 6.515 2.832l14.162 14.163c1.7 1.7 2.833 4.249 2.833 6.515z'></path></svg></span></span></a>";
				break;
			default:
				$item = "<a class='menu-link' href='#' id='stylable-list-first-item' class='no_translate' style='background: url({$this->flag_path}/$flag_name.png) 0 center no-repeat;'>$lang_name<span role='presentation' class='dropdown-menu-toggle'><span class='gp-icon icon-arrow'><svg viewBox='0 0 330 512' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='1em' height='1em'><path d='M305.913 197.085c0 2.266-1.133 4.815-2.833 6.514L171.087 335.593c-1.7 1.7-4.249 2.832-6.515 2.832s-4.815-1.133-6.515-2.832L26.064 203.599c-1.7-1.7-2.832-4.248-2.832-6.514s1.132-4.816 2.832-6.515l14.162-14.163c1.7-1.699 3.966-2.832 6.515-2.832 2.266 0 4.815 1.133 6.515 2.832l111.316 111.317 111.316-111.317c1.7-1.699 4.249-2.832 6.515-2.832s4.815 1.133 6.515 2.832l14.162 14.163c1.7 1.7 2.833 4.249 2.833 6.515z'></path></svg></span></span></a>";

		}
		return $item;
	}

	/**
	 * Returns the markup of the first list item.
	 *
	 * @since    1.0.0
	 */
	public function get_list_first_item_markup_sc_flags() {
		$flag_name = $this->get_flag_name( $this->get_current_lang() );
		return "<a class='menu-link' href='#' id='shortcode-stylable-list-first-item'><img src='$this->flag_path/" . $flag_name . ".png'><span role='presentation' class='dropdown-menu-toggle'><span class='gp-icon icon-arrow'><svg viewBox='0 0 330 512' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='1em' height='1em'><path d='M305.913 197.085c0 2.266-1.133 4.815-2.833 6.514L171.087 335.593c-1.7 1.7-4.249 2.832-6.515 2.832s-4.815-1.133-6.515-2.832L26.064 203.599c-1.7-1.7-2.832-4.248-2.832-6.514s1.132-4.816 2.832-6.515l14.162-14.163c1.7-1.699 3.966-2.832 6.515-2.832 2.266 0 4.815 1.133 6.515 2.832l111.316 111.317 111.316-111.317c1.7-1.699 4.249-2.832 6.515-2.832s4.815 1.133 6.515 2.832l14.162 14.163c1.7 1.7 2.833 4.249 2.833 6.515z'></path></svg></span></span></a>";
	}

		/**
		 * Returns the markup of the first list item.
		 *
		 * @since    1.0.0
		 */
	public function get_list_first_item_markup_sc_names() {
		$lang_name = $this->options['original_lang_names'] === 'on' ? ucfirst( transposh_consts::get_language_orig_name( $this->get_current_lang() ) ) : ucfirst( transposh_consts::get_language_name( $this->get_current_lang() ) );
		return "<a href='#' id='shortcode-stylable-list-first-item' class='no_translate'>$lang_name<span role='presentation' class='dropdown-menu-toggle'><span class='gp-icon icon-arrow'><svg viewBox='0 0 330 512' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='1em' height='1em'><path d='M305.913 197.085c0 2.266-1.133 4.815-2.833 6.514L171.087 335.593c-1.7 1.7-4.249 2.832-6.515 2.832s-4.815-1.133-6.515-2.832L26.064 203.599c-1.7-1.7-2.832-4.248-2.832-6.514s1.132-4.816 2.832-6.515l14.162-14.163c1.7-1.699 3.966-2.832 6.515-2.832 2.266 0 4.815 1.133 6.515 2.832l111.316 111.317 111.316-111.317c1.7-1.699 4.249-2.832 6.515-2.832s4.815 1.133 6.515 2.832l14.162 14.163c1.7 1.7 2.833 4.249 2.833 6.515z'></path></svg></span></span></a>";
	}

		/**
		 * Returns the markup of the first list item.
		 *
		 * @since    1.0.0
		 */
	public function get_list_first_item_markup_sc_flags_names() {
		$flag_name = $this->get_flag_name( $this->get_current_lang() );
		$lang_name = $this->options['original_lang_names'] === 'on' ? ucfirst( transposh_consts::get_language_orig_name( $this->get_current_lang() ) ) : ucfirst( transposh_consts::get_language_name( $this->get_current_lang() ) );
		return "<a href='#' id='shortcode-stylable-list-first-item' class='no_translate' style='background: url($this->flag_path/" . $flag_name . ".png) 0 center no-repeat;'>$lang_name<span role='presentation' class='dropdown-menu-toggle'><span class='gp-icon icon-arrow'><svg viewBox='0 0 330 512' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' width='1em' height='1em'><path d='M305.913 197.085c0 2.266-1.133 4.815-2.833 6.514L171.087 335.593c-1.7 1.7-4.249 2.832-6.515 2.832s-4.815-1.133-6.515-2.832L26.064 203.599c-1.7-1.7-2.832-4.248-2.832-6.514s1.132-4.816 2.832-6.515l14.162-14.163c1.7-1.699 3.966-2.832 6.515-2.832 2.266 0 4.815 1.133 6.515 2.832l111.316 111.317 111.316-111.317c1.7-1.699 4.249-2.832 6.515-2.832s4.815 1.133 6.515 2.832l14.162 14.163c1.7 1.7 2.833 4.249 2.833 6.515z'></path></svg></span></span></a>";
	}

	/**
	 * Returns the markup for the list items.
	 *
	 * @param string $lang selected language.
	 * @since    1.0.0
	 */
	public function get_list_item_markup( $lang ) {
		$flag_name = $this->get_flag_name( $lang );
		$target    = $this->get_target_page( $lang );
		$lang_name = $this->options['original_lang_names'] === 'on' ? ucfirst( transposh_consts::get_language_orig_name( $lang ) ) : ucfirst( transposh_consts::get_language_name( $lang ) );
		switch ( $this->options['custom_list_items'] ) {
			case 'flag-only':
				$item = "<a class='menu-link' href='$target'><img src='{$this->flag_path}/$flag_name.png'></a>";
				break;
			case 'text-only':
				$item = "<a class='menu-link'  href='$target'>$lang_name</a>";
				break;
			case 'flag-and-text':
				$item = "<a class='menu-link' style='background: url({$this->flag_path}/$flag_name.png) 0 center no-repeat;' href='$target'>$lang_name</a>";
				break;
			default:
		}
		return $item;
	}

		/**
		 * Returns the markup for the list items.
		 *
		 * @param string $lang selected language.
		 * @since    1.0.0
		 */

	public function get_list_item_markup_sc_flags( $lang ) {
		$flag_name = $this->get_flag_name( $lang );
		$target    = $this->get_target_page( $lang );
		return "<a href='$target'><img src='" . $this->flag_path . '/' . $flag_name . ".png' /></a>";
	}

		/**
		 * Returns the markup for the list items.
		 *
		 * @param string $lang selected language.
		 * @since    1.0.0
		 */

	public function get_list_item_markup_sc_names( $lang ) {
		$target    = $this->get_target_page( $lang );
		$lang_name = $this->options['original_lang_names'] === 'on' ? ucfirst( transposh_consts::get_language_orig_name( $lang ) ) : ucfirst( transposh_consts::get_language_name( $lang ) );
		return "<a href='$target'>$lang_name</a>";
	}

		/**
		 * Returns the markup for the list items.
		 *
		 * @param string $lang selected language.
		 * @since    1.0.0
		 */

	public function get_list_item_markup_sc_flags_names( $lang ) {
		$flag_name = $this->get_flag_name( $lang );
		$target    = $this->get_target_page( $lang );
		$lang_name = $this->options['original_lang_names'] === 'on' ? ucfirst( transposh_consts::get_language_orig_name( $lang ) ) : ucfirst( transposh_consts::get_language_name( $lang ) );
		return "<a style='background: url($this->flag_path/" . $flag_name . ".png) 0 center no-repeat;' href='$target'>$lang_name</a>";
	}

		/**
		 * Returns the markup for the list items.
		 *
		 * @since    1.0.0
		 */
	public function shortcode_horizontal_flags() {
		$used_languages = $this->used_languages;
		$items          = '';
		if ( ! empty( $used_languages ) && count( $used_languages ) > 1 ) {
			$flag_name = $this->get_flag_name( $this->get_current_lang() );

			$items = '<ul id="sh_lsft_horizontal_flags">';
			foreach ( $used_languages as $lang ) {
				$lang_name = 'on' === $this->options['original_lang_names'] ? ucfirst( transposh_consts::get_language_orig_name( $lang ) ) : ucfirst( transposh_consts::get_language_name( $lang ) );
				$flag_name = $this->get_flag_name( $lang );
				$target    = $this->get_target_page( $lang );
				$items    .= '<li class="switch_lang no_translate""><a class="lsft_sc_h_flags" href="' . $target . '"><img src="' . $this->flag_path . '/' . $flag_name . '.png" alt="' . $lang_name . '"/></a></li>';
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

			/**
			 * Returns the markup for the list items.
			 *
			 * @since    1.0.0
			 */
	public function shortcode_vertical_flags() {
		$used_languages = $this->used_languages;
		$items          = '';
		if ( ! empty( $used_languages ) && count( $used_languages ) > 1 ) {

			$flag_name = $this->get_flag_name( $this->get_current_lang() );
			// $lang_name = $this->options['original_lang_names'] === 'on' ? ucfirst( transposh_consts::get_language_orig_name( $this->get_current_lang() ) ) : ucfirst( transposh_consts::get_language_name( $this->get_current_lang() ) );
			$items = '<ul id="sh_lsft_vertical_flags">';
			// $items .= '<li class="switch_lang no_translate" style="margin-bottom:auto;"><a class="lsft_sc_h_flags" href="' . $target . '"><img src="' . $this->flag_path . '/' . $flag_name . '.png" /></a></li>';
			foreach ( $used_languages as $lang ) {
				$lang_name = $this->options['original_lang_names'] === 'on' ? ucfirst( transposh_consts::get_language_orig_name( $lang ) ) : ucfirst( transposh_consts::get_language_name( $lang ) );
				$flag_name = $this->get_flag_name( $lang );
				$target    = $this->get_target_page( $lang );
				$items    .= '<li class="switch_lang no_translate"><a class="lsft_sc_h_flags" href="' . $target . '"><img src="' . $this->flag_path . '/' . $flag_name . '.png" alt="' . $lang_name . '" /></a></li>';
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

			/**
			 * Returns the markup for the list items.
			 *
			 * @since    1.0.0
			 */
	public function shortcode_native_dropdown() {
		$used_languages = $this->used_languages;
		$items          = '';
		if ( ! empty( $used_languages ) && count( $used_languages ) > 1 ) {
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

			/**
			 * Returns the markup for the list items.
			 *
			 * @since    1.0.0
			 */
	public function shortcode_custom_dropdown_flags() {
		$used_languages = $this->used_languages;
		$items          = '<ul id="sh_lsft_custom_dropdown_flags">';
		$items         .= "<li class='stylable-list'>";
		$items         .= $this->get_list_first_item_markup_sc_flags();
		// if (($key = array_search($this->get_current_lang(), $used_languages)) !== false) {
		// unset($used_languages[$key]);
		// }
		$items .= "<ul id='sh_sc_flags_submenu'>";
		foreach ( $used_languages as $lang ) {
			$items .= "<li class='no_translate'>" . $this->get_list_item_markup_sc_flags( $lang ) . '</li>';
		}
		// if ($this->get_current_lang() != $this->default_lang) {

		$user          = wp_get_current_user();
		$allowed_roles = array( 'editor', 'administrator', 'author' );
		/**this button will be available only to certain user types */
		if ( array_intersect( $allowed_roles, $user->roles ) ) {
			$items .= '<li class="edit_translation no_translate"><a class="lsft_sc_h_flags" href="#">Edit</a></li>';
		}
		// }
		$items .= '</ul></li>';
		$items .= '</ul>';
		return $items;
	}

			/**
			 * Returns the markup for the list items.
			 *
			 * @since    1.0.0
			 */
	public function shortcode_custom_dropdown_names() {
		$used_languages = $this->used_languages;
		// wp_enqueue_style($this->plugin_name . '-shortcode_custom_dropdown_flags', $this->style_path . 'shortcode-custom-dropdown-flags.css', array(), $this->version, 'all');
		$items  = '<ul id="sh_lsft_custom_dropdown_names">';
		$items .= "<li class='stylable-list'>";
		$items .= $this->get_list_first_item_markup_sc_names();
		// if (($key = array_search($this->get_current_lang(), $used_languages)) !== false) {
		// unset($used_languages[$key]);
		// }
		$items .= "<ul id='sh_sc_names_submenu'>";
		foreach ( $used_languages as $lang ) {
			$items .= "<li class='no_translate'>" . $this->get_list_item_markup_sc_names( $lang ) . '</li>';
		}
		// if ($this->get_current_lang() != $this->default_lang) {
		$user          = wp_get_current_user();
		$allowed_roles = array( 'editor', 'administrator', 'author' );
		/**this button will be available only to certain user types */
		if ( array_intersect( $allowed_roles, $user->roles ) ) {
			$items .= "<li class='edit_translation no_translate'><a class='lsft_sc_h_flags' href='#'>Edit</a></li>";
		}
		// }
		$items .= '</ul></li>';
		$items .= '</ul>';
		return $items;
	}

			/**
			 * Returns the markup for the list items.
			 *
			 * @since    1.0.0
			 */
	public function shortcode_custom_dropdown_flags_names() {
		$used_languages = $this->used_languages;
		// wp_enqueue_style($this->plugin_name . '-shortcode_custom_dropdown_flags', $this->style_path . 'shortcode-custom-dropdown-flags.css', array(), $this->version, 'all');
		$items  = '<ul id="sh_lsft_custom_dropdown_flags_names">';
		$items .= "<li class='stylable-list flag-and-text'>";
		$items .= $this->get_list_first_item_markup_sc_flags_names();
		// if (($key = array_search($this->get_current_lang(), $used_languages)) !== false) {
		// unset($used_languages[$key]);
		// }
		$items .= "<ul id='sh_sc_flags_names_submenu'>";
		foreach ( $used_languages as $lang ) {
			$items .= "<li class='no_translate flag-and-text'>" . $this->get_list_item_markup_sc_flags_names( $lang ) . '</li>';
		}
		// if ($this->get_current_lang() != $this->default_lang) {

		$user          = wp_get_current_user();
		$allowed_roles = array( 'editor', 'administrator', 'author' );
		/**this button will be available only to certain user types */
		if ( array_intersect( $allowed_roles, $user->roles ) ) {
			$items .= '<li class="edit_translation no_translate"><a class="lsft_sc_h_flags" href="#">Edit</a></li>';
		}
		// }
		$items .= '</ul></li>';
		$items .= '</ul>';
		return $items;
	}

	/**
	 * Actually adds the language switcher to the main manu.
	 *
	 * @param string $items the comma separated string representing menu items.
	 * @param object $args arguments.
	 * @since    1.0.0
	 */
	public function cfxlsft_add_menu_item( $items, $args ) {
		$used_languages = $this->used_languages;
		if ( 'on' === $this->options['automode'] ) {
			$menu_classes   = str_replace( ',', '', $this->options['menu_classes'] );
			$menu_locations = explode( ',', $this->options['menu_locations'] );
			// if we only use one language nothing has to be added.
			if ( ! empty( $used_languages ) && count( $used_languages ) > 1 ) {
				if ( in_array( $args->theme_location, $menu_locations, true ) ) {
					/**DISPLAY FLAGS */
					if ( $this->options['switcher_type'] == 'flags' ) {
						foreach ( $used_languages as $lang ) {
							$flag_name = $this->get_flag_name( $lang );
							$target    = $this->get_target_page( $lang );
							$lang_name = 'on' === $this->options['original_lang_names'] ? ucfirst( transposh_consts::get_language_orig_name( $lang ) ) : ucfirst( transposh_consts::get_language_name( $lang ) );
							$items    .= '<li class="' . $menu_classes . ' menu-item switch_lang no_translate"><a class="menu-link" href="' . $target . '"><img src="' . $this->flag_path . '/' . $flag_name . '.png" alt="' . $lang_name . '" /></a></li>';
						}
						if ( get_locale() != $this->default_lang ) {

							$user          = wp_get_current_user();
							$allowed_roles = array( 'editor', 'administrator', 'author' );

							// this button will be available only to certain user types.
							if ( array_intersect( $allowed_roles, $user->roles ) ) {
								$items .= '<li class="' . $menu_classes . ' menu-item edit_translation no_translate"><a class="menu-link" href="#"> Edit</a></li>';
							}
						}
					} elseif ( $this->options['switcher_type'] === 'list' ) {
						/** DISPLAY CUSTOM LIST */
						$class = '';
						// if ( 'flag-and-text' === $this->options['custom_list_items'] ) {
						// $class = "style='background: url($this->flag_path/" . $flag_name . ".png) 0 center no-repeat;'";
						// }
						$items .= "<li class='stylable-list menu-item $menu_classes " . $this->options['custom_list_items'] . "'>";
						$items .= $this->get_list_first_item_markup();
						$items .= "<ul id='lsft-sub-menu'>";
						foreach ( $used_languages as $lang ) {
							$items .= "<li class='no_translate $menu_classes'>" . $this->get_list_item_markup( $lang ) . '</li>';
						}
						if ( get_locale() != $this->default_lang ) {
							$user          = wp_get_current_user();
							$allowed_roles = array( 'editor', 'administrator', 'author' );
							/**this button will be available only to certain user types */
							if ( array_intersect( $allowed_roles, $user->roles ) ) {
								$items .= '<li class="' . $menu_classes . ' edit_translation no_translate"><a class="menu-link" href="#"> Edit</a></li>';
							}
						}
						$items .= '</ul>';
						$items .= '</li>';
					} else {
						/** DISPLAY SELECT */
						$items .= '<li class="menu-item ' . $menu_classes . '"><select id="switch_lang_select" class="switch_lang_select stylable-select">';
						foreach ( $used_languages as $lang ) {
							$target    = $this->get_target_page( $lang );
							$lang_name = $this->options['original_lang_names'] === 'on' ? ucfirst( transposh_consts::get_language_orig_name( $lang ) ) : ucfirst( transposh_consts::get_language_name( $lang ) );
							if ( $lang == $this->get_current_lang() ) {
								$selected = 'selected';
							} else {
								$selected = '';
							}
							$items .= '<option data-target="' . $target . '" value="' . $lang . '" ' . $selected . ' class="no_translate">' . $lang_name . ' </option>';
						}
						$items .= '</select></li>';
						// if ($this->get_current_lang() != $this->default_lang) {

						$user          = wp_get_current_user();
						$allowed_roles = array( 'editor', 'administrator', 'author' );
						/**this button will be available only to certain user types */
						if ( array_intersect( $allowed_roles, $user->roles ) ) {
							$items .= '<li class="menu-item ' . $menu_classes . ' edit_translation no_translate"><a class="menu-link" href="#">' . $this->get_current_lang() . ' Edit</a></li>';
						}
						// }
					}
				}
			}
		}
		return $items;
	}

	/**
	 * Actually actyivate or deactivate the Transposh Translation Editor.
	 *
	 * @since    1.0.0
	 */
	public function cfxlsft_edit_button_action() {   ?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
		var urlParam = function(name) {
			var results = new RegExp('[\?&]' + name + '=([^&#]*)')
			.exec(window.location.search);

			return (results !== null) ? results[1] || 0 : false;
		}

		$(document).on('click', '.edit_translation', function(e) {
			e.preventDefault();
			var currentUrl = window.location.href;
			var currentOrigin = window.location.origin;
			var currentPath = window.location.pathname;
			var param = urlParam('tpedit');
			var newUrl = '';
			if (param === false) {
			newUrl = currentOrigin + currentPath + '?tpedit=1';
			$(this).attr('href', newUrl);
			} else {
			newUrl = currentOrigin + currentPath;
			$(this).attr('href', newUrl);
			}
			window.location.href = newUrl;
		})

		})
	</script>
		<?php
	}
}
