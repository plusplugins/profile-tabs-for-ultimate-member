<?php

class PP_Tabs_Core {

	function __construct() {

		add_action('init', array($this, 'create_cpt'));
		add_action('admin_menu', array($this, 'add_admin_page'));
		add_action('template_redirect', array($this, 'show_profile_tab_content'), 20);
		add_filter('um_profile_tabs', array($this, 'add_profile_tabs'), 9000);
	}

	function create_cpt() {

		register_post_type('um_tab', array(
			'labels'             => array(
				'name'               => __('Profile Tabs'),
				'singular_name'      => __('Profile Tab'),
				'add_new'            => __('Add New'),
				'add_new_item'       => __('Add New Profile Tab'),
				'edit_item'          => __('Edit Profile Tab'),
				'not_found'          => __('You did not create any profile tabs yet'),
				'not_found_in_trash' => __('Nothing found in Trash'),
				'search_items'       => __('Search Profile Tabs'),
			),
			'show_ui'            => true,
			'show_in_menu'       => false,
			'public'             => false,
			'supports'           => array('title', 'editor', 'page-attributes'),
			'publicly_queryable' => false,
			'hierarchical'       => true,

		)
		);
	}

	function add_admin_page() {

		add_submenu_page('ultimatemember', __('Profile Tabs', 'ultimatemember'), __('Profile Tabs', 'ultimatemember'), 'manage_options', 'edit.php?post_type=um_tab', '', '');
	}

	function show_profile_tab_content() {

		global $ultimatemember;

		$tab = $ultimatemember->profile->active_tab();

		if ($tab == "main") {
			return false;
		}

		$args = array(
			'name'        => $tab,
			'post_type'   => 'um_tab',
			'numberposts' => 1,
		);

		$main_tab = get_posts($args);

		if (!$main_tab) {
			return;
		}

		add_action("um_profile_content_{$tab}_default", function () use ($main_tab) {

			$tab_content = apply_filters('the_content', $main_tab[0]->post_content);
			echo $tab_content;

		});

		$subs_args = array(
			'post_parent' => $main_tab[0]->ID,
			'post_type'   => 'um_tab',
			'numberposts' => -1,
			'post_status' => 'any',
		);

		$subs = get_children($subs_args);

		if ($subs) {
			foreach ($subs as $sub_id => $sub_post) {
				add_action("um_profile_content_{$tab}_{$sub_post->post_name}", function () use ($sub_post) {
					$tab_content = apply_filters('the_content', $sub_post->post_content);
					echo $tab_content;
				});
			}
		}

	}

	function add_profile_tabs($tabs) {

		global $ultimatemember;

		$args = array(
			'post_type'      => 'um_tab',
			'meta_key'       => '_pp_position',
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
			'posts_per_page' => 99,
			'post_parent'    => 0,
		);

		$posts        = get_posts($args);
		$user_role    = get_user_meta(get_current_user_id(), 'role', true);
		$profile_role = get_user_meta(um_profile_id(), 'role', true);

		foreach ($posts as $post) {

			$post_id     = $post->ID;
			$meta        = get_post_meta($post_id);
			$have_roles  = array();
			$view_roles  = array();
			$private_tab = isset($meta['_pp_private'][0]);
			$custom_url  = isset($meta['_pp_url'][0]) ? $meta['_pp_url'][0] : null;
			$force       = isset($meta['_pp_force'][0]);

			if (isset($meta['_pp_have_roles'])) {
				$have_roles = maybe_unserialize($meta['_pp_have_roles'][0]);
			}

			if (isset($meta['_pp_view_roles'])) {
				$view_roles = maybe_unserialize($meta['_pp_view_roles'][0]);
			}

			if (!empty($have_roles)) {
				if (!in_array($profile_role, $have_roles)) {
					continue;
				}
			}

			// if we are here then the profile has that tab - just need to check if user may view

			if ($private_tab) {

				// private tab - user on another profile but can not view
				if (um_profile_id() != get_current_user_id() && !in_array($user_role, $view_roles)) {
					continue;
				}

			} else {
				// public tab - user can not view tab
				if (!empty($view_roles)) {
					if (!in_array($user_role, $view_roles)) {
						continue;
					}
				}

			}

			$subs_args = array(
				'post_parent' => $post->ID,
				'post_type'   => 'um_tab',
				'numberposts' => -1,
				'post_status' => 'any',
			);

			$subs = get_children($subs_args);

			if ($force) {

				$tabs = array_reverse($tabs, true);
			}

			$tabs[$post->post_name] = array(

				'name'   => $post->post_title,
				'icon'   => $meta['_pp_icon'][0],
				'custom' => true,
			);

			if ($force) {

				$tabs = array_reverse($tabs, true);
			}

			if ($subs) {

				foreach ($subs as $sub_id => $sub_post) {

					$tabs[$post->post_name]['subnav'][$sub_post->post_name] = $sub_post->post_title;
				}

				$tabs[$post->post_name]['subnav_default'] = '';

			}

			if ($custom_url) {
				add_filter('um_profile_menu_link_' . $post->post_name, function ($nav_link) use ($custom_url) {

					return $custom_url;

				});
			}

		}

		return $tabs;
	}

}

?>
