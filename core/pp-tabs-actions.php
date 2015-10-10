<?php

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


add_action( 'init',  'create_tabs_cpt');

function add_tabs_admin_page() {

	add_submenu_page( 'ultimatemember', __('Profile Tabs', 'ultimatemember'), __('Profile Tabs', 'ultimatemember'), 'manage_options', 'edit.php?post_type=um_tab', '', '' );
}

add_action('admin_menu', 'add_tabs_admin_page');

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

add_action('wp', 'show_profile_tab_content');


function pp_tabs_output_field( $atts ) {
		global $ultimatemember;

		$a = shortcode_atts( array(
        'field' => '',
				'label' => '0'
    ), $atts );

		um_fetch_user( um_profile_id() );

		$key = $a['field'];
		$showlabel = $a['label'];

	//	if (um_user($key)){
			//return $ultimatemember->fields->view_field($a['field'],array());
			// return um_user($a['field']);

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
		
				$output .= '<div class="um-field' . $classes . '"' . $conditional . ' data-key="'.$key.'">';

						if ( $showlabel == '1' ) {
							$output .= $ultimatemember->fields->field_label($label, $key, $data);
						}

						$res = stripslashes( $ultimatemember->fields->field_value( $key, $default, $data ) );

						$output .= '<div class="um-field-area">';
						$output .= '<div class="um-field-value">' . $res . '</div>';
						$output .= '</div>';

						$output .= '</div>';

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

}
add_shortcode( 'pp-tabs', 'pp_tabs_output_field' );

?>
