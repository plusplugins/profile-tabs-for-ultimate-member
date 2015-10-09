<?php 

class PP_Tabs_Metabox {

	function __construct() {
		
		$this->in_edit = false;
		$this->edit_mode_value = null;

		add_action('admin_head', array(&$this, 'admin_head'), 9);
		add_action( 'load-post.php', array(&$this, 'add_metabox'), 9 );
		add_action( 'load-post-new.php', array(&$this, 'add_metabox'), 9 );
		
	}

	function is_UM_admin() {
		global $current_screen;
		$screen_id = $current_screen->id;
		if ( is_admin() && ( strstr( $screen_id, 'ultimatemember') || strstr( $screen_id, 'um_') || strstr($screen_id, 'user') || strstr($screen_id, 'profile') ) )
			return true;
		return false;
	}

	function is_plugin_post_type() {
		if (isset($_REQUEST['post_type'])) {
			$post_type = $_REQUEST['post_type'];
			if ( in_array($post_type, array('um_tab'))) {
				return true;
			}
		} else if ( isset($_REQUEST['action'] ) && $_REQUEST['action'] == 'edit') {
			$post_type = get_post_type();
			if ( in_array($post_type, array('um_tab'))) {
				return true;
			}
		}
		return false;
	}

	/***
	***	@Gets the role meta
	***/
	function get_custom_post_meta($id){
		$all_meta = get_post_custom($id);
		foreach($all_meta as $k=>$v){
			if (strstr($k, '_um_')){
				$um_meta[$k] = $v;
			}
		}
		if (isset($um_meta))
			return $um_meta;
	}

	/***
	***	@Runs on admin head
	***/
	function admin_head(){
		global $post;
		if ( $this->is_plugin_post_type() && isset($post->ID) ){
			$this->postmeta = $this->get_custom_post_meta($post->ID);
		}
	}

	/***
	***	@add a helper tooltip
	***/
	function _tooltip( $text ){

		$output = '<span class="um-admin-tip n">';
		$output .= '<span class="um-admin-tipsy-n" title="'.$text.'"><i class="dashicons dashicons-editor-help"></i></span>';
		$output .= '</span>';

		return $output;

	}

	/***
	***	@add a helper tooltip
	***/
	function tooltip( $text, $e = false ){

		?>

		<span class="um-admin-tip">
			<?php if ($e == 'e' ) { ?>
			<span class="um-admin-tipsy-e" title="<?php echo $text; ?>"><i class="dashicons dashicons-editor-help"></i></span>
			<?php } else { ?>
			<span class="um-admin-tipsy-w" title="<?php echo $text; ?>"><i class="dashicons dashicons-editor-help"></i></span>
			<?php } ?>
		</span>

		<?php

	}

	/***
	***	@on/off UI
	***/
	function ui_on_off( $id, $default=0, $is_conditional=false, $cond1='', $cond1_show='', $cond1_hide='', $yes='', $no='' ) {

		$meta = (string)get_post_meta( get_the_ID(), $id, true );
		if ( $meta === '0' && $default > 0 ) {
			$default = $meta;
		}

		$yes = ( !empty( $yes ) ) ? $yes : __('Yes');
		$no = ( !empty( $no ) ) ? $no : __('No');

		if (isset($this->postmeta[$id][0]) || $meta ) {
			$active = ( isset( $this->postmeta[$id][0] ) ) ? $this->postmeta[$id][0] : $meta;
		} else {
			$active = $default;
		}

		if ($is_conditional == true) {
			$is_conditional = ' class="um-adm-conditional" data-cond1="'.$cond1.'" data-cond1-show="'.$cond1_show.'" data-cond1-hide="'.$cond1_hide.'"';
		}

		?>

		<span class="um-admin-yesno">
			<span class="btn pos-<?php echo $active; ?>"></span>
			<span class="yes" data-value="1"><?php echo $yes; ?></span>
			<span class="no" data-value="0"><?php echo $no; ?></span>
			<input type="hidden" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo $active; ?>" <?php echo $is_conditional; ?> />
		</span>

		<?php
	}


	function add_metabox() {
		global $current_screen;

		if( $current_screen->id == 'um_tab'){
			add_action( 'add_meta_boxes', array(&$this, 'add_metabox_tab'), 1 );
			add_action( 'save_post', array(&$this, 'save_metabox_tab'), 10, 2 );
		}

	}

	

	function add_metabox_tab() {

		add_meta_box('um-admin-form-tabs', __('Tab Options','ultimatemember'), array(&$this, 'load_metabox_tab'), 'um_tab', 'normal', 'default');
		do_action('um_admin_custom_tab_metaboxes');

	}

	function load_metabox_tab( $object, $box ) {
		global $ultimatemember, $post;

		$box['id'] = str_replace('um-admin-form-','', $box['id']);

		preg_match('#\{.*?\}#s', $box['id'], $matches);

		if ( isset($matches[0]) ){
			$path = $matches[0];
			$box['id'] = preg_replace('~(\\{[^}]+\\})~','', $box['id'] );
		} else {
			$path = um_path;
		}

		$path = str_replace('{','', $path );
		$path = str_replace('}','', $path );

		include_once PP_TABS_PLUGIN_DIR . 'admin/templates/'. $box['id'] . '.php';
		wp_nonce_field( basename( __FILE__ ), 'um_admin_save_metabox_tab_nonce' );
	}

	function save_metabox_tab( $post_id, $post ) {
		global $wpdb;

		// validate nonce
		if ( !isset( $_POST['um_admin_save_metabox_tab_nonce'] ) || !wp_verify_nonce( $_POST['um_admin_save_metabox_tab_nonce'], basename( __FILE__ ) ) ) return $post_id;

		// validate post type
		if ( $post->post_type != 'um_tab' ) return $post_id;

		// validate user
		$post_type = get_post_type_object( $post->post_type );
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) return $post_id;

		$where = array( 'ID' => $post_id );
		if (empty($_POST['post_title'])) $_POST['post_title'] = 'Tab #'.$post_id;
	    $wpdb->update( $wpdb->posts, array( 'post_title' => $_POST['post_title'], 'post_name' => sanitize_title( $_POST['post_title'] ), 'post_content' => $_POST['post_content'] ), $where );

		// save
		delete_post_meta( $post_id, '_um_can_view_tabs' );
		delete_post_meta( $post_id, '_um_can_edit_tabs' );
		delete_post_meta( $post_id, '_um_can_delete_tabs' );

		do_action('um_admin_before_saving_tab_meta', $post_id );

		do_action('um_admin_before_save_tab', $post_id, $post );

		foreach( $_POST as $k => $v ) {
			if (strstr($k, '_um_')){
				update_post_meta( $post_id, $k, $v);
			}
		}

		do_action('um_admin_after_editing_tab', $post_id, $post);

		do_action('um_admin_after_save_tab', $post_id, $post );

	}
}