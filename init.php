<?php

class PP_Tabs {
	public $notice_messge = '';
	public $plugin_inactive = false;

	function __construct() {
		$this->plugin_inactive = false;

		add_action( 'admin_notices', array( $this, 'add_notice' ), 20 );
		add_action( 'init', array( $this, 'plugin_check' ), 1 );
		add_action( 'init', array( $this, 'init' ), 1 );
		add_action( 'plugins_loaded', array( $this, 'load_language_textdomain' ), 1 );
	}

	function plugin_check() {
		if ( ! class_exists( 'UM' ) ) {
			$this->notice_messge   = __( 'The <strong>Ultimate Member Profile Tabs</strong> plugin requires the Ultimate Member plugin to be activated to work properly. You can download it <a href="https://wordpress.org/plugins/ultimate-member">here</a>', 'profile-tabs-for-ultimate-member' );
			$this->plugin_inactive = true;
		} else if ( ! version_compare( ultimatemember_version, PP_TABS_REQUIRES, '>=' ) ) {
			$this->notice_messge   = __( 'The <strong>Ultimate Member Profile Tabs</strong> plugin  requires a <a href="https://wordpress.org/plugins/ultimate-member">newer version</a> of Ultimate Member to work properly.', 'profile-tabs-for-ultimate-member' );
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
		require_once PP_TABS_PLUGIN_DIR . 'pp-wpml.php';

		$this->core      = new PP_Tabs_Core();
		$this->metabox   = new PP_Tabs_Metabox();
		$this->shortcode = new PP_Tabs_Shortcode();
	}

	function load_language_textdomain() {
		$loaded = load_plugin_textdomain( 'profile-tabs-for-ultimate-member', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		if ( ! $loaded ) {
			load_muplugin_textdomain( 'profile-tabs-for-ultimate-member', '/languages/' );
		}
	}
}

$pp_tabs = new PP_Tabs();
