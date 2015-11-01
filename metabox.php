<?php

class PP_Tabs_Metabox {

	function __construct() {

		require_once dirname( __FILE__ ) . '/includes/cmb2/init.php';
		require_once dirname( __FILE__ ) . '/includes/cmb_field_select2/cmb-field-select2.php';

		add_action( 'cmb2_admin_init', array(&$this, 'cmb2_pp_tabs_metabox') );

	}

	function cmb2_pp_tabs_metabox() {

		global $ultimatemember;

	    $prefix = '_pp_';

	    $cmb = new_cmb2_box( array(
	        'id'			=> 'pp_tabs_metabox',
	        'title'			=> __( 'Options', 'pp-tabs' ),
	        'object_types'	=> array( 'um_tab', ),
	        'context'		=> 'normal',
	        'priority'		=> 'high',
	        'show_names'	=> true, 
	    ) );

	    $cmb->add_field( array(
			'name'		=> __( 'Roles with this tab', 'pp-tabs' ),
			'desc'		=> __( 'Select roles (leave blank to show on all profiles)', 'pp-tabs' ),
			'id'		=> $prefix . 'have_roles',
			'type'		=> 'pw_multiselect',
			'options'	=> $ultimatemember->query->get_roles(),
			
		) );

		$cmb->add_field( array(
			'name'		=> __( 'Roles that can view this tab', 'pp-tabs' ),
			'desc'		=> __( 'Select roles (leave blank to make visible to all roles)', 'pp-tabs' ),
			'id'		=> $prefix . 'view_roles',
			'type'		=> 'pw_multiselect',
			'options'	=> $ultimatemember->query->get_roles(),
			
		) );

	    $cmb->add_field( array(
			'name'		=> __( 'Private Tab', 'pp-tabs' ),
			'desc'		=> __( 'Private tabs are only visible to the profile owner', 'pp-tabs' ),
			'id'		=> $prefix . 'private',
			'type'		=> 'checkbox',
		) );

		$cmb->add_field( array(
			'name'		=> __( 'Icon', 'pp-tabs' ),
			'desc'		=> __( 'Enter an icon code to appear on the tab', 'pp-tabs' ),
			'id'		=> $prefix . 'icon',
			'default'	=> 'um-faicon-tags',
			'type'		=> 'text_medium'
		) );

		$cmb->add_field( array(
			'name'		=> __( 'Position', 'pp-tabs' ),
			'desc'		=> __( 'A smaller number moves the tab further left on the profile bar', 'pp-tabs' ),
			'id'		=> $prefix . 'position',
			'default'	=> '10',
			'type'		=> 'text_small'
		) );
		
	}
}