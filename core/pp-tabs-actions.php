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

?>