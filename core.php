<?php

class PP_Tabs_Core {

	function __construct() {

		add_action('init', array(&$this, 'create_cpt'));
		add_action('admin_menu', array(&$this,'add_admin_page'));
		add_action('wp', array(&$this,'show_profile_tab_content'));
		add_filter('um_profile_tabs', array(&$this,'add_profile_tabs'), 2000 );

	}

	function create_cpt() {
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
					'supports' => array('title','editor'),
					'publicly_queryable' => false,
				)
		);
	}

	function add_admin_page() {

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

	function add_profile_tabs( $tabs ) {

		global $ultimatemember;

		$args = array(
			'post_type'  => 'um_tab',
			'meta_key'   => '_pp_position',
			'orderby'    => 'meta_value_num',
			'order'      => 'ASC',
			'posts_per_page' => 99,
		);

	
		$posts = get_posts( $args );
		$user_role = get_user_meta(get_current_user_id(), 'role', true);
		$profile_role = get_user_meta(um_profile_id(), 'role', true);

		foreach ($posts as $post) {

			$post_id = $post->ID;
			$meta = get_post_meta( $post_id );
			$have_roles = array();
			$see_roles = array();
			$private_tab = isset($meta['_pp_private'][0]);

			if (isset($meta['_pp_have_roles'])) {
				$have_roles = maybe_unserialize($meta['_pp_have_roles'][0]);
			}

			if (isset( $meta['_pp_view_roles'])) {
				$see_roles = maybe_unserialize($meta['_pp_view_roles'][0]);
			}

			$show = true;

			if ( ! empty($have_roles)) {
				if ( ! in_array($profile_role, $have_roles ) ) {
					$show = false;
				}
			}

			if ( ! empty($see_roles)) {
				if ( ! in_array($user_role, $see_roles ) ) {
					$show = false;
				}
			}

			if ( $private_tab && ( um_profile_id() != get_current_user_id() ) ) {
				$show = false;
			}

			if ( $show ) {

				$tabs[$post->post_name] = array(
					'name' => $post->post_title,
		       		'icon' => $meta['_pp_icon'][0],
		       	);
			}
		} //loop

		return $tabs;
	}

}

?>