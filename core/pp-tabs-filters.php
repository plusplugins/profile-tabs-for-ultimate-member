<?php

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

add_filter('um_profile_tabs', 'add_pp_profile_tabs', 2000 );


?>
