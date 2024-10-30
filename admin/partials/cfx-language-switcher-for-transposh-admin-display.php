<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://codingfix.com
 * @since      1.0.0
 *
 * @package    Cfx_Language_Switcher_For_Transposh
 * @subpackage Cfx_Language_Switcher_For_Transposh/admin/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
$transposh_installed = true;
if ( ! is_plugin_active( 'transposh-translation-filter-for-wordpress/transposh.php' ) ) {
// if ( ! file_exists( WP_PLUGIN_DIR . '/transposh-translation-filter-for-wordpress/core/utils.php' ) ) {
	$transposh_installed = false;
}
if ( $transposh_installed ) {
	include_once WP_PLUGIN_DIR . '/transposh-translation-filter-for-wordpress/core/constants.php';

	$lsft_default_styles = plugin_dir_path( dirname( __FILE__, 2 ) ) . '/assets/styles';
	$upload_dir          = wp_upload_dir();
	$lsft_custom_styles  = $upload_dir['basedir'] . '/lsft/custom-styles/';
	if ( ! file_exists( $lsft_custom_styles ) ) {
		wp_mkdir_p( $lsft_custom_styles );
	}

	$hidden = false;
	if ( ! class_exists( 'transposh_plugin' ) ) {
		echo '<div class="notice notice-error is-dismissible"><p>
		Language Switcher for Transposh plugin depends on Transposh plugin and can\'t do anything without it: please, install and activate Transposh plugin before to activate Edit Translation Button for Transposh.
		</p></div>';
		$hidden = true;
	}
	$transposh_options = get_option( TRANSPOSH_OPTIONS );
	$used_languages    = explode( ',', $transposh_options['viewable_languages'] );
	$default_lang      = $transposh_options['default_language'];
	$usable_langs      = explode( ',', $transposh_options['viewable_languages'] );
	// if ( ! isset( $transposh_options ) || ! isset( $default_lang ) || empty( $default_lang ) || ! isset( $transposh_options['viewable_languages'] ) || empty( $transposh_options['viewable_languages'] ) || count( $usable_langs ) <= 1 ) {
	if ( count( $usable_langs ) < 1 ) {
		echo '<div class="notice notice-error is-dismissible"><p class="plugin-invalid">
		<strong>Transposh languages not found.</strong> Maybe you still didn\'t set any languages in Transposh Languages settings. You must set at least 2 languages to use in your website in order to use Language Switcher for Transposh.
		</p></div>';
		$hidden = true;
	}

	$options   = get_option( 'cfxlsft_options' );
	$flag_path = plugins_url( '/assets/flags', dirname( __FILE__, 2 ) );
	if ( 'tp' === $options['flag_type'] ) {
		$flag_path = plugins_url() . '/transposh-translation-filter-for-wordpress/' . TRANSPOSH_DIR_IMG . '/flags';
	}
	$en_flag = 'gb';
	if ( isset( $options['usa_flag'] ) && 'on' === $options['usa_flag'] ) {
		$en_flag = 'us';
	}
}
?>
<div id="cfxlsft-general" class="wrap">
	<h2>Language Switcher for Transposh Settings (LSFT)</h2>
	<?php
	if ( false !== $transposh_installed ) {
		$default_tab = 'general';

		$lsft_tab = $default_tab;
		if ( isset( $_GET['tab'] ) ) {
			if ( isset( $_GET['_wpnonce'] ) ) {
				if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'intnavontabs' ) ) {
					$lsft_tab = sanitize_text_field( wp_unslash( $_GET['tab'] ) );
				}
			}
		}

		if ( 'general' !== $lsft_tab && 'styles' !== $lsft_tab ) {
			wp_safe_redirect(
				add_query_arg(
					array(
						'page'    => 'language-switcher-settings',
						'tab'     => $default_tab,
						'message' => '1',
					),
					admin_url( 'options-general.php' )
				)
			);
		}

		?>
	<section id="transposh-settings" style="padding-top:20px;">
		<h3>Languages currently enabled in Transposh settings</h3>
		<div class="default-lang_wrapper">
			<div class="lang-div">
				<?php
				if ( strtolower( $default_lang ) === 'en' ) {
					?>
					<img src="<?php echo esc_html( $flag_path ); ?>/<?php echo esc_html( $en_flag ); ?>.png" alt="English" />
					<?php
				} else {
					?>
					<img src="<?php echo esc_html( $flag_path ); ?>/<?php echo esc_html( transposh_consts::get_language_flag( $default_lang ) ); ?>.png" />
					<?php
				}
				?>
				<span class="locale-code"><?php echo esc_html( $options['original_lang_names'] === 'on' ? transposh_consts::get_language_orig_name( $default_lang ) : transposh_consts::get_language_name( $default_lang ) ); ?> - default</span>
			</div>
			<?php
			foreach ( $usable_langs as $lang ) {
				if ( $lang !== $default_lang ) {
					?>
					<div class="lang-div">
						<?php
						if ( $lang === 'en' ) {
							?>
							<img src="<?php echo esc_html( $flag_path ); ?>/<?php echo esc_html( $en_flag ); ?>.png" />
							<?php
						} else {
							?>
							<img src="<?php echo esc_html( $flag_path ); ?>/<?php echo esc_html( transposh_consts::get_language_flag( $lang ) ); ?>.png" />
							<?php
						}
						?>
						<!-- <span><?php echo esc_html( ucfirst( $lang ) ); ?></span> -->
						<span class="locale-code"><?php echo esc_html( $options['original_lang_names'] === 'on' ? transposh_consts::get_language_orig_name( $lang ) : transposh_consts::get_language_name( $lang ) ); ?> <?php echo esc_html( ( $lang === $default_lang ) ? ' - default' : '' ); ?></span>
					</div>
					<?php
				}
			}
			?>
			<!-- </div> -->
			<!-- </div> -->
		</div>
	</section>
	<br />
	<br />
	<div class="row">
		<div class="col-8">
			<nav class="nav-tab-wrapper lsft">
				<?php
				$url_gen     = admin_url( 'admin.php?page=language-switcher-settings&amp;tab=general' );
				$url_stl     = admin_url( 'admin.php?page=language-switcher-settings&amp;tab=styles' );
				$tab_url_gen = wp_nonce_url( $url_gen, 'intnavontabs', '_wpnonce' );
				$tab_url_stl = wp_nonce_url( $url_stl, 'intnavontabs', '_wpnonce' );
				?>
				<a href="<?php echo esc_html( $tab_url_gen ); ?>" data-target="general" class="nav-tab 
				<?php
				if ( 'general' === $lsft_tab ) :
					?>
					active<?php endif; ?>">General</a>
				<a href="<?php echo esc_html( $tab_url_stl ); ?>" data-target="styles" class="nav-tab 
				<?php
				if ( 'styles' === $lsft_tab ) :
					?>
					active<?php endif; ?>">Styles</a>
			</nav>
			<div class="tab-content ls">
				<div id="general" class="tab-panel 
					<?php
					if ( 'general' === $lsft_tab ) {
						?>
					active
						<?php
					}
					?>
				">
					<form method="post" action="admin-post.php" class="<?php echo $hidden ? 'hidden' : ''; ?>">
						<input type="hidden" name="action" value="save_cfxlsft_options" />
						<input type="hidden" name="activeTab" id="activeTab" value="<?php echo esc_html( $lsft_tab ); ?>" />
						<!-- Adding security through hidden referrer field -->
						<?php wp_nonce_field( 'cfxlsft' ); ?>
						<section id="automode">
							<!-- <div class="postbox"> -->
							<div class="settingsbox">
								<h3>Mode</h3>
								<div class="settings-group">
									<div>
										<p>Set Auto mode On to let Language Swtcher for Transposh automatically append itself to the chosen menu. Default: On. <span style="font-weight: bold;">This won't work in the new FSE themes:</span> set Automode off and use shortcodes instead to put your language switcher in the navigation</p>
									</div>
									<div>
										<div class="switch-holder">
											<div class="switch-label">
												<span>Automode</span>
											</div>
											<div class="switch-toggle">
												<input type="checkbox" id="automode-toggler" name="automode" <?php echo $options['automode'] === 'on' ? 'checked' : ''; ?>>
												<label for="automode-toggler"></label>
											</div>
										</div>
									</div>
								</div>
								<h3>Redirect to home?</h3>
								<div class="settings-group">
									<div>
										<p>If set to On, the user will be redirected to the home page after having changed the language; otherwise he will be redirected to the same page he was visiting. Default: Off</p>
									</div>
									<div>
										<div class="switch-holder">
											<div class="switch-label">
												<span>Redirect to home</span>
											</div>
											<div class="switch-toggle">
												<input type="checkbox" id="redirect-to-home" name="redirect_to_home" <?php echo $options['redirect_to_home'] === 'on' ? 'checked' : ''; ?>>
												<label for="redirect-to-home"></label>
											</div>
										</div>
									</div>
								</div>
								<h3>Original languages' names?</h3>
								<div class="settings-group">
									<div>
										<p>If set to On, original languages names will be used instead of their english names.</p>
									</div>
									<div>
										<div class="switch-holder">
											<div class="switch-label">
												<span>Use original languages' names</span>
											</div>
											<div class="switch-toggle">
												<input type="checkbox" id="original_lang_names" name="original_lang_names" <?php echo 'on' === $options['original_lang_names'] ? 'checked' : ''; ?>>
												<label for="original_lang_names"></label>
											</div>
										</div>
									</div>
								</div>
								<h3>Use USA flag for english language</h3>
								<div class="settings-group">
									<div>
										<p>If set to On, USA flag will be used for english language (even is you're using Transposh flags).</p>
									</div>
									<div>
										<div class="switch-holder">
											<div class="switch-label">
												<span>Use USA flag</span>
											</div>
											<div class="switch-toggle">
												<input type="checkbox" id="usa_flag" name="usa_flag" <?php echo 'on' === $options['usa_flag'] ? 'checked' : ''; ?>>
												<label for="usa_flag"></label>
											</div>
										</div>
									</div>
								</div>
								<h3>Use Transposh flags?</h3>
								<div class="settings-group">
									<div>
										<p>If set to On, Transposh flags set will be used.</p>
									</div>
									<div>
										<div class="switch-holder">
											<div class="switch-label">
												<span>Use Transposh flags</span>
											</div>
											<div class="switch-toggle">
												<input type="checkbox" id="flag_type" name="flag_type" <?php echo 'tp' === $options['flag_type'] ? 'checked' : ''; ?>>
												<label for="flag_type"></label>
											</div>
										</div>
									</div>
								</div>
								<section id="menus">
									<h3>Menus</h3>
									<p>Set where the Language Switcher will show up:</p>
									<br>
									<div id="menu_locations_wrapper">
										<?php
										$menus                   = get_registered_nav_menus();
										$existing_menu_locations = explode( ',', $options['menu_locations'] );
										foreach ( $menus as $key => $value ) {
											$checked = '';
											if ( in_array( $key, $existing_menu_locations ) ) {
												$checked = 'checked';
											}
											?>
											<div class="menu_location"><input type="checkbox" name="menu_locations[]" value="<?php echo esc_html( $key ); ?>" <?php echo esc_html( $checked ); ?> /><label><?php echo esc_html( $value ); ?> <i>(<?php echo esc_html( $key ); ?>)</i></label></div>
											<?php
										}
										?>
									</div>
								</section>
								<br />
								<br />
								<section id="switcher-type">
									<h3>Switcher type</h3>
									<p>Choose the type of Language Switcher you want to use:</p>
									<?php
									$select_options = array(
										'Flags' => 'flags',
										'Native dropdown (select)' => 'select',
										'Custom dropdown (list)' => 'list',
									);
									?>
									<select name="switcher_type" id="switcher_type">
										<?php
										foreach ( $select_options as $label => $value ) {
											$selected = '';
											if ( $value === $options['switcher_type'] ) {
												$selected = 'selected';
											}
											?>
											<option value="<?php echo esc_html( $value ); ?>" <?php echo esc_html( $selected ); ?>><?php echo esc_html( $label ); ?></option>
											<?php
										}
										?>
									</select>

									<?php
									$class = '';
									if ( $options['switcher_type'] != 'dropdown' ) {
										$class = 'hidden';
									}
									?>
									<div id="dropdown_styles" class="<?php echo esc_html( $class ); ?>">

										<br>
										<br>
										<div id="list-styles" class="<?php echo $options['select_as_list'] == 'yes' ? '' : 'hidden'; ?>">
											<h4>List items (flags, text or flags and text)</h4>
											<select name="custom_list_items">
												<?php
												$list_items_options = array(
													'Flag only'     => 'flag-only',
													'Text only'     => 'text-only',
													'Flag and text' => 'flag-and-text',
												);
												foreach ( $list_items_options as $key => $item ) {
													$selected = '';
													if ( $item === $options['custom_list_items'] ) {
														$selected = 'selected';
													}
													?>
													<option value="<?php echo esc_html( $item ); ?>" <?php echo esc_html( $selected ); ?>><?php echo esc_html( $key ); ?></option>
													<?php
												}
												?>
											</select>
											<br>
										</div>
									</div>
									<br>
									<?php
									$class = '';
									// if ( $options['select_as_list'] == 'no' && $options['select_style'] == 'default.css' ) {
									// $class = 'disabled';
									// }
									// if ( $options['switcher_type'] == 'flags' ) {
									// $selected_file = $flags_selected_file;
									// } else {
									// $selected_file = $options['select_as_list'] == 'no' ? $select_selected_file : $list_selected_file;
									// }
									?>
								</section>
								<section id="switcher-classes">
									<br />
									<br />
									<h3>Menu item classes</h3>
									<p>If your theme uses some specific class for navigation menu items, add them here. Be sure to separate the classes only by a comma. LFST will add these classes to the menu items for the language switcher in order they look and feel accordingly to your theme.</p>
									<input type="text" name="menu_classes" placeholder="class1,class2,class3..." value="<?php echo esc_html( $options['menu_classes'] ); ?>" />
								</section>
							</div>
							<!-- </div> -->
						</section>
						<br>
						<br>
						<input type="submit" value="Save changes" class="button-primary" />
					</form>

				</div>

				<div id="styles" class="tab-panel
				<?php
				if ( 'styles' === $lsft_tab ) {
					?>
					active
					<?php
				}
				?>
				">
					<form method="post" action="admin-post.php" class="<?php // echo $hidden ? 'hidden' : ''; ?>">
						<input type="hidden" name="action" value="save_cfxlsft_options" />
						<input type="hidden" name="activeTab" id="activeTab" value="<?php echo esc_html( $lsft_tab ); ?>" />
						<!-- Adding security through hidden referrer field -->
						<?php wp_nonce_field( 'cfxlsft' ); ?>
						<section id="switcher-styles">
							<!-- <div class="postbox"> -->
							<div class="settingsbox">
								<h3>Styles</h3>
								<?php
								$class = '';
								if ( 'flags' !== $options['switcher_type'] ) {
									$class = '';
								}
								?>
								<section id="style-editor">
									<br>
									<br>
									<section id="custom-css-section">
										<div class="switch-holder">
											<div class="switch-label">
												<span>Custom CSS</span>
											</div>
											<div class="switch-toggle">
												<input type="checkbox" id="customCSS" name="customCSS" <?php echo 'on' === $options['customCSS'] ? 'checked' : ''; ?> />
												<label for="customCSS"></label>
											</div>
										</div>
										<div id="custom_css_editor">
											<h3>Language Switcher style editor</h3>
											<p>If you need to customize CSS rules for Language Switcher FT, you can select here one of the basic stylesheets used by default and use it as a starting point to create your unique style. </p>
											<p>Just load the file, click the button "Copy to clipboard and paste it in your custom css file or in your cstomizer section dedicated to the custom CSS and start to edit.</p>
											<br>
											<div id="dropdown_styles2" class="<?php echo esc_html( $class ); ?>">
												<br>
												<div id="select-styles-wrapper">
													<h4>Select styles</h4>
													<?php
													$styles = array();
													$dir    = new DirectoryIterator( $lsft_default_styles );
													foreach ( $dir as $fileinfo ) {
														if ( $fileinfo->getFilename() != '.' && $fileinfo->getFilename() != '..' ) {
															$file_parts                         = explode( '.', $fileinfo->getFilename() );
															$style_name_array                   = explode( '-', $file_parts[0] );
															$styles[ $fileinfo->getFilename() ] = $style_name_array;
														}
													}
													?>

													<select id="select_style" name="select_style">
														<option value='none'>Choose one</option>
														<?php
														asort( $styles );
														foreach ( $styles as $f => $fname ) {
															?>
															<option value="<?php echo esc_html( $f ); ?>" <?php echo esc_html( $selected ); ?>>
																<?php echo esc_html( ucfirst( implode( ' ', $fname ) ) ); ?>
															</option>
															<?php
														}
														?>
													</select><div class="myspinner"></div>
												</div>
											</div>
											<br>
											<br>
											<div id="copy-button">
												<div id="confirmMessage"><span class="dashicons dashicons-saved"></span><span>Styles copied to the clipboard</span></div>
												<button id="copy-to-clipboard" class="button-primary">Copy to clipboard</button>
												<button id="clear-codemirror" class="button">Clear</button>
											</div>
											<textarea id="code_editor_page_css" rows="5" name="custom_style" class="codemirror widefat textarea disabled <?php echo esc_html( $class ); ?>">
			<?php
			// echo file_exists( $selected_file ) ? trim( file_get_contents( $selected_file ) ) : '';
			?>
			</textarea>
										</div>
									</section>
								</section>
							</div>
							<!-- </div> -->
						</section>
						<br><br>
						<input type="submit" value="Save changes" class="button-primary" />
					</form>
				</div>

				<br />
				<br />
			</div>

		</div>
		<div class="col-4">
			<h3>Shortcodes</h3>
			<p>Language Switcher currently support following shortcodes:</p>
			<p>Horizontal flags: [lsft_horizontal_flags]</p>
			<p>Vertical flags: [lsft_vertical_flags]</p>
			<p>Dropdown native select: [lsft_native_dropdown]</p>
			<p>Dropdown flags custom list: [lsft_custom_dropdown_flags]</p>
			<p>Dropdown flags custom list: [lsft_custom_dropdown_names]</p>
			<p>Dropdown flags and names custom list: [lsft_custom_dropdown_flags_names]</p>
		</div>
	</div>

</div>
		<?php
	}
