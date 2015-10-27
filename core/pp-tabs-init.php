<?php

class PP_Tabs_API {

	function __construct() {

		$this->plugin_inactive = false;

		add_action('init', array(&$this, 'plugin_check'), 1);

		add_action('init', array(&$this, 'init'), 1);

		add_action('init', array(&$this, 'create_tabs_cpt'));

		add_action('admin_menu', array(&$this,'add_tabs_admin_page'));

		add_action('wp', array(&$this,'show_profile_tab_content'));

		add_shortcode( 'pp-tabs', array(&$this,'pp_tabs_output_field' ));

		add_filter('um_profile_tabs', array(&$this,'add_pp_profile_tabs'), 2000 );

	}

	/***
	***	@Check plugin requirements
	***/
	function plugin_check(){

		if ( !class_exists('UM_API') ) {

			$this->add_notice( __('The <strong>Ultimate Member Profile Tabs</strong> plugin requires the Ultimate Member plugin to be activated to work properly. You can download it <a href="https://wordpress.org/plugins/ultimate-member">here</a>','pp-maps') );
			$this->plugin_inactive = true;

		} else if ( !version_compare( ultimatemember_version, PP_TABS_REQUIRES, '>=' ) ) {

			$this->add_notice( __('The <strong>Ultimate Member Profile Tabs</strong> plugin  requires a <a href="https://wordpress.org/plugins/ultimate-member">newer version</a> of Ultimate Member to work properly.','pp-maps') );
			$this->plugin_inactive = true;

		}

	}

	/***
	***	@Add notice
	***/
	function add_notice( $msg ) {

		if ( !is_admin() ) return;

		echo '<div class="error"><p>' . $msg . '</p></div>';

	}

	function create_tabs_cpt() {
			register_post_type( 'um_tab', array(
						'labels' => array(
							'name' => __( 'Profile Tabs' ),
							'singular_name' => __( 'Profile Tab' ),
							'add_new' => __( 'Add New' ),
							'add_new_item' => __('Add New Profile Tab' ),
							'edit_item' => __('Edit Profile Tab'),
							'not_found' => __('You did not create any profile tabs yet'),
							'not_found_in_trash' => __('Nothing found in Trash'),
							'search_items' => __('Search Profile Tabs')
						),
						'show_ui' => true,
						'show_in_menu' => false,
						'public' => false,
						'supports' => array('title','editor')
					)
			);
		}

		function add_tabs_admin_page() {

			add_submenu_page( 'ultimatemember', __('Profile Tabs', 'ultimatemember'), __('Profile Tabs', 'ultimatemember'), 'manage_options', 'edit.php?post_type=um_tab', '', '' );
		}

		function show_profile_tab_content() {


			global $ultimatemember;

			$tab = $ultimatemember->profile->active_tab();
			if ($tab == "main") {
				return false;
			}

			add_action('um_profile_content_' . $tab . '_default', function() use (&$tab) {

				$args = array(
					'name'       => $tab,
					'post_type'  => 'um_tab',
					'numberposts'=> 1,
				);

				$posts = get_posts($args);

				if( $posts ) {
					setup_postdata( $posts[0] );
					the_content();
				}

			} );

		}

		function pp_tabs_output_field( $atts ) {
				global $ultimatemember;

				$a = shortcode_atts( array(
		        'field' => '',
						'label' => '0'
		    ), $atts );

				um_fetch_user( um_profile_id() );

				$key = $a['field'];
				$showlabel = $a['label'];

				if (um_user($key)){

					$output = null;

				// get whole field data
					$data = $ultimatemember->fields->get_field($key);
					extract($data);


				if ( !isset( $data['type'] ) ) return;

				if ( isset( $data['in_group'] ) && $data['in_group'] != '' && $rule != 'group' ) return;

				if ( in_array( $type, array('block','shortcode','spacing','divider','group') ) ) {

				} else {
					if ( ! $ultimatemember->fields->field_value( $key, $default, $data ) ) return;
				}

				if ( !um_can_view_field( $data ) ) return;

				if ( !um_field_conditions_are_met( $data ) ) return;

				switch( $type ) {

					/* Default */
					default:

					//	$output .= '<div class="um-field' . $classes . '"' . $conditional . ' data-key="'.$key.'">';

								if ( $showlabel == '1' ) {
									$output .= $ultimatemember->fields->field_label($label, $key, $data);
								}

								$res = stripslashes( $ultimatemember->fields->field_value( $key, $default, $data ) );

								//$output .= '<div class="um-field-area">';
								$output .= $res; //'<div class="um-field-value">' . $res . '</div>';
								//$output .= '</div>';
								//$output .= '</div>';

						break;

					/* HTML */
					case 'block':
						$output .= '<div class="um-field' . $classes . '"' . $conditional . ' data-key="'.$key.'">
										<div class="um-field-block">'.$content.'</div>
									</div>';
						break;

					/* Shortcode */
					case 'shortcode':

						$content = str_replace('{profile_id}', um_profile_id(), $content );

						$output .= '<div class="um-field' . $classes . '"' . $conditional . ' data-key="'.$key.'">
										<div class="um-field-shortcode">' . do_shortcode($content) . '</div>
									</div>';
						break;

					/* Gap/Space */
					case 'spacing':
						$output .= '<div class="um-field um-field-spacing' . $classes . '"' . $conditional . ' style="height: '.$spacing.'"></div>';
						break;

					/* A line divider */
					case 'divider':
						$output .= '<div class="um-field um-field-divider' . $classes . '"' . $conditional . ' style="border-bottom: '.$borderwidth.'px '.$borderstyle.' '.$bordercolor.'">';
						if ( $divider_text ) {
							$output .= '<div class="um-field-divider-text"><span>' . $divider_text . '</span></div>';
						}
						$output .= '</div>';
						break;

					/* Rating */
					case 'rating':

						$output .= '<div class="um-field' . $classes . '"' . $conditional . ' data-key="'.$key.'">';

								if ( isset( $data['label'] ) ) {
									$output .= $ultimatemember->fields->field_label($label, $key, $data);
								}

								$output .= '<div class="um-field-area">';
								$output .= '<div class="um-field-value">
												<div class="um-rating-readonly um-raty" id="'.$key.'" data-key="'.$key.'" data-number="'.$data['number'].'" data-score="' .  $ultimatemember->fields->field_value( $key, $default, $data ) . '"></div>
											</div>';
								$output .= '</div>';

								$output .= '</div>';

						break;

				}

				// Custom filter for field output
				if ( isset( $ultimatemember->fields->set_mode ) ) {
					$output = apply_filters("um_{$key}_form_show_field", $output, $ultimatemember->fields->set_mode);
				}

				return $output;

			} else {
		    return '';
			}

		}

		function add_pp_profile_tabs( $tabs ) {

			global $ultimatemember;

			$args = array(
				'post_type'  => 'um_tab',
				'meta_key'   => '_um_tab_position',
				'orderby'    => 'meta_value_num',
				'order'      => 'ASC',
			);

			$posts = get_posts( $args );
			$user_role = get_user_meta(get_current_user_id(), 'role', true);
			$profile_role = get_user_meta(um_profile_id(), 'role', true);

			foreach ($posts as $post) {

				$post_id = $post->ID;
				$meta = get_post_meta( $post_id );
				$have_tab_roles = array();
				$see_tab_roles = array();
				$private_tab = $meta['_um_is_private_tab'][0];

				if (isset($meta['_um_have_tab_roles'])) {
					$have_tab_roles = maybe_unserialize($meta['_um_have_tab_roles'][0]);
				}

				if (isset( $meta['_um_can_view_roles'])) {
					$see_tab_roles = maybe_unserialize($meta['_um_can_view_roles'][0]);
				}

				$show = true;

				if ( ! empty($have_tab_roles)) {
					if ( ! in_array($profile_role, $have_tab_roles ) ) {
						$show = false;
					}
				}

				if ( ! empty($see_tab_roles)) {
					if ( ! in_array($user_role, $see_tab_roles ) ) {
						$show = false;
					}
				}

				if ( $private_tab == 1 && ( um_profile_id() != get_current_user_id() ) ) {
					$show = false;
				}

				if ( $show ) {

					$tabs[$post->post_name] = array(
						'name' => $post->post_title,
			       		'icon' => $meta['_um_tab_icon'][0]
			       	);
				}
			} //loop

			return $tabs;
		}

	/***
	***	@Init
	***/
	function init() {

		if ( $this->plugin_inactive ) return;

		require_once PP_TABS_PLUGIN_DIR . 'core/pp-tabs-metabox.php';
		require_once PP_TABS_PLUGIN_DIR . 'core/pp-tabs-actions.php';
		require_once PP_TABS_PLUGIN_DIR . 'core/pp-tabs-filters.php';

		$this->metabox = new PP_Tabs_Metabox();

	}

}

$pp_tabs = new PP_Tabs_API();
