<?php

class PP_Tabs_API {

	function __construct() {

		$this->plugin_inactive = false;
		
		add_action('init', array(&$this, 'plugin_check'), 1);
		
		add_action('init', array(&$this, 'init'), 1);

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