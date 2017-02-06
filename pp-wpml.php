<?php

/**
 * Returned the translated post id ( if exists )
 *
 * @param $id tab id
 *
 * @return int translated version tab's id
 */
function pp_lang_tab_id( $id ) {
	$type = 'um_tab';

	if ( intval( $id ) > 0 ) {
		if ( ! defined( 'ICL_SITEPRESS_VERSION' ) ) {
			return $id;
		} else {
			if ( version_compare( ICL_SITEPRESS_VERSION, '3.2', '>' ) ) {
				return apply_filters( 'wpml_object_id', $id, $type, true );
			} else {
				if ( function_exists( 'icl_object_id' ) ) {
					return icl_object_id( $id, 'post', true );
				} else {
					return $id;
				}
			}
		}
	} else {
		return $id;
	}
}