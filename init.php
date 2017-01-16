<?php

class PP_Tabs {
	public $notice_messge = '';
	public $plugin_inactive = false;

	function __construct() {
		$this->plugin_inactive = false;

		add_action( 'init', array( $this, 'plugin_check' ), 1 );
		add_action( 'init', array( $this, 'init' ), 1 );
		add_action( 'admin_notices', array( $this, 'add_notice' ), 20 );
	}

	function plugin_check() {
		if ( ! class_exists( 'UM_API' ) ) {
			$this->notice_messge   = __( 'The <strong>Ultimate Member Profile Tabs</strong> plugin requires the Ultimate Member plugin to be activated to work properly. You can download it <a href="https://wordpress.org/plugins/ultimate-member">here</a>', 'pp-maps' );
			$this->plugin_inactive = true;
		} else if ( ! version_compare( ultimatemember_version, PP_TABS_REQUIRES, '>=' ) ) {
			$this->notice_messge   = __( 'The <strong>Ultimate Member Profile Tabs</strong> plugin  requires a <a href="https://wordpress.org/plugins/ultimate-member">newer version</a> of Ultimate Member to work properly.', 'pp-maps' );
			$this->plugin_inactive = true;

		}
	}

	function add_notice() {
		if ( ! is_admin() || empty( $this->notice_messge ) ) {
			return;
		}

		echo '<div class="error"><p>' . $this->notice_messge . '</p></div>';
	}

	function init() {
		if ( $this->plugin_inactive ) {
			return;
		}

		require_once PP_TABS_PLUGIN_DIR . 'core.php';
		require_once PP_TABS_PLUGIN_DIR . 'metabox.php';
		require_once PP_TABS_PLUGIN_DIR . 'shortcode.php';

		$this->core      = new PP_Tabs_Core();
		$this->metabox   = new PP_Tabs_Metabox();
		$this->shortcode = new PP_Tabs_Shortcode();
	}
}

$pp_tabs = new PP_Tabs();
