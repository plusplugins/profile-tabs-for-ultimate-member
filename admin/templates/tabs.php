<div class="um-admin-metabox">

	<div class="">


		<p>
			<label class="um-admin-half"><?php _e('Tab icon','pp-tabs'); ?> <?php $this->tooltip( __('Enter icon code eg. um-faicon-tags') ); ?></label>
			<span class="um-admin-half">
			
				<input type="text" name="_um_tab_icon" id="_um_tab_icon" value="<?php echo $ultimatemember->query->get_meta_value('_um_tab_icon', null, 'um-faicon-tags'); ?>" />
			
			</span>
		</p><div class="um-admin-clear"></div>

		<p>
			<label class="um-admin-half"><?php _e('Tab position (integer)','pp-tabs'); ?> <?php $this->tooltip( __('Enter an integer to determine the position of the tab. A lower number will move it further left.') ); ?></label>
			<span class="um-admin-half">
			
				<input type="text" name="_um_tab_position" id="_um_tab_position" value="<?php echo $ultimatemember->query->get_meta_value('_um_tab_position', null, 10); ?>" class="small" />
			
			</span>
		</p><div class="um-admin-clear"></div>
		
		<p>
			<label class="um-admin-half"><?php _e('Roles with this tab','pp-tabs'); ?> <?php $this->tooltip( __('Choose which roles should have this tab on their profile. Leave blank to add to all roles.', 'pp-tabs') ); ?></label>
			<span class="um-admin-half">
		
				<select multiple="multiple" name="_um_have_tab_roles[]" id="_um_have_tab_roles" class="umaf-selectjs" style="width: 300px">
					<?php foreach($ultimatemember->query->get_roles() as $key => $value) { ?>
					<option value="<?php echo $key; ?>" <?php selected($key, $ultimatemember->query->get_meta_value('_um_have_tab_roles', $key) ); ?>><?php echo $value; ?></option>
					<?php } ?>	
				</select>
			
			</span>
		</p><div class="um-admin-clear"></div>
		
		<p>
			<label class="um-admin-half"><?php _e('Roles that can view this tab','ultimatemember'); ?> <?php $this->tooltip( __('Choose which role can view this tab on profiles. Leave blank to allow all roles to view this tab.', 'pp-tabs') ); ?></label>
			<span class="um-admin-half">
		
				<select multiple="multiple" name="_um_can_view_roles[]" id="_um_can_view_roles" class="umaf-selectjs" style="width: 300px">
					<?php foreach($ultimatemember->query->get_roles() as $key => $value) { ?>
					<option value="<?php echo $key; ?>" <?php selected($key, $ultimatemember->query->get_meta_value('_um_can_view_roles', $key) ); ?>><?php echo $value; ?></option>
					<?php } ?>	
				</select>
			
			</span>
		</p><div class="um-admin-clear"></div>
	
		<p>
			<label class="um-admin-half"><?php _e('Is this a private tab?','pp-tabs'); ?> <?php $this->tooltip( __('Private tabs are only viewable to users on their own profile, as well as admin users','pp-tabs') ); ?></label>
			<span class="um-admin-half"><?php $this->ui_on_off('_um_is_private_tab'); ?></span>
		</p><div class="um-admin-clear"></div>
		
	</div>
	
	<div class="um-admin-clear"></div>
	
</div>