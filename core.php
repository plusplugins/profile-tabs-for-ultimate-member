<?php

class PP_Tabs_Core {

	function __construct() {

		add_action( 'init', array( $this, 'create_cpt' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_page' ) );
		add_action( 'template_redirect', array( $this, 'show_profile_tab_content' ), 20 );
		add_filter( 'um_profile_tabs', array( $this, 'add_profile_tabs' ), 9000 );
	}

	function create_cpt() {

		register_post_type( 'um_tab', array(
				'labels'             => array(
					'name'               => __( 'Profile Tabs', 'profile-tabs-for-ultimate-member' ),
					'singular_name'      => __( 'Profile Tab', 'profile-tabs-for-ultimate-member' ),
					'add_new'            => __( 'Add New', 'profile-tabs-for-ultimate-member' ),
					'add_new_item'       => __( 'Add New Profile Tab', 'profile-tabs-for-ultimate-member' ),
					'edit_item'          => __( 'Edit Profile Tab', 'profile-tabs-for-ultimate-member' ),
					'not_found'          => __( 'You did not create any profile tabs yet', 'profile-tabs-for-ultimate-member' ),
					'not_found_in_trash' => __( 'Nothing found in Trash', 'profile-tabs-for-ultimate-member' ),
					'search_items'       => __( 'Search Profile Tabs', 'profile-tabs-for-ultimate-member' ),
				),
				'show_ui'            => true,
				'show_in_menu'       => false,
				'public'             => false,
				'supports'           => array( 'title', 'editor', 'page-attributes' ),
				'publicly_queryable' => false,
				'hierarchical'       => true,

			)
		);
	}

	function add_admin_page() {

		add_submenu_page( 'ultimatemember', __( 'Profile Tabs', 'profile-tabs-for-ultimate-member' ), __( 'Profile Tabs', 'profile-tabs-for-ultimate-member' ), 'manage_options', 'edit.php?post_type=um_tab', '', '' );
	}

	function show_profile_tab_content() {

		$tab = UM()->profile()->active_tab();

		if ( $tab == "main" ) {
			return false;
		}

		$args = array(
			'name'             => $tab,
			'post_type'        => 'um_tab',
			'numberposts'      => 1,
			'suppress_filters' => 0,
		);

		$main_tab = get_posts( $args );

		if ( ! $main_tab ) {
			return;
		}

		add_action( "um_profile_content_{$tab}_default", function () use ( $main_tab ) {

			$tab_content = $main_tab[0]->post_content;

			if ( ( ! defined( 'PP_TABS_PRO_VERSION' ) ) && has_shortcode( $tab_content, 'ultimatemember' ) ) {
				// The content has a [ultimatemember] short code; 
				// strip all shortcodes from the content to avoid recursion & timeout
				$tab_content = strip_shortcodes( $tab_content );
			}

			$tab_content = apply_filters( 'the_content', $tab_content );
			echo $tab_content;

		} );

		$subs_args = array(
			'post_parent'      => pp_lang_tab_id( $main_tab[0]->ID ),
			'post_type'        => 'um_tab',
			'numberposts'      => -1,
			'suppress_filters' => 0,
			'post_status'      => 'any',
		);

		$subs = get_children( $subs_args );

		if ( $subs ) {
			foreach ( $subs as $sub_id => $sub_post ) {
				add_action( "um_profile_content_{$tab}_{$sub_post->post_name}", function () use ( $sub_post ) {
					$tab_content = apply_filters( 'the_content', $sub_post->post_content );
					echo $tab_content;
				} );
			}
		}

	}

	function add_profile_tabs( $tabs ) {

		$args = array(
			'post_type'        => 'um_tab',
			'meta_key'         => '_pp_position',
			'orderby'          => 'meta_value_num',
			'order'            => 'ASC',
			'posts_per_page'   => 99,
			'post_parent'      => 0,
			'suppress_filters' => 0,
		);

		$posts        = get_posts( $args );
		$user_role    = UM()->roles()->get_all_user_roles( get_current_user_id() );
		$profile_role = UM()->roles()->get_all_user_roles( um_get_requested_user() );

		if ( empty( $user_role ) ) {
			$user_role = array();
		}

		if ( empty( $profile_role ) ) {
			$profile_role = array();
		}

		foreach ( $posts as $post ) {

			$post_id     = pp_lang_tab_id( $post->ID );
			$meta        = get_post_meta( $post_id );
			$have_roles  = array();
			$view_roles  = array();
			$private_tab = isset( $meta['_pp_private'][0] );
			$custom_url  = isset( $meta['_pp_url'][0] ) ? $meta['_pp_url'][0] : null;
			$force       = isset( $meta['_pp_force'][0] );

			if ( isset( $meta['_pp_have_roles'] ) ) {
				$have_roles = maybe_unserialize( $meta['_pp_have_roles'][0] );
			}

			if ( isset( $meta['_pp_view_roles'] ) ) {
				$view_roles = maybe_unserialize( $meta['_pp_view_roles'][0] );
			}

			if ( ! empty( $have_roles ) ) {
				if ( count( array_intersect( $profile_role, $have_roles ) ) <= 0 ) {
					continue;
				}
			}

			// if we are here then the profile has that tab - just need to check if user may view 
			if ( $private_tab ) {

				// private tab - user on another profile but can not view
				if ( um_profile_id() != get_current_user_id() && count( array_intersect( $user_role, $view_roles ) ) <= 0 ) {
					continue;
				}

			} else {

				// public tab - user can not view tab
				if ( ! empty( $view_roles ) ) {
					if ( count( array_intersect( $user_role, $view_roles ) ) <= 0 ) {
						continue;
					}
				}

			}

			$subs_args = array(
				'post_parent'      => pp_lang_tab_id( $post->ID ),
				'post_type'        => 'um_tab',
				'numberposts'      => -1,
				'suppress_filters' => 0,
				'post_status'      => 'any',
			);

			$subs = get_children( $subs_args );

			if ( $force ) {

				$tabs = array_reverse( $tabs, true );
			}

			$tabs[ $post->post_name ] = array(

				'name'   => $post->post_title,
				'icon'   => $meta['_pp_icon'][0],
				'custom' => true,
			);

			if ( $force ) {

				$tabs = array_reverse( $tabs, true );
			}

			if ( $subs ) {

				foreach ( $subs as $sub_id => $sub_post ) {

					$tabs[ $post->post_name ]['subnav'][ $sub_post->post_name ] = $sub_post->post_title;
				}

				$tabs[ $post->post_name ]['subnav_default'] = '';

			}

			if ( $custom_url ) {
				add_filter( 'um_profile_menu_link_' . $post->post_name, function ( $nav_link ) use ( $custom_url ) {

					return $custom_url;

				} );
			}
		}

		return $tabs;
	}
}