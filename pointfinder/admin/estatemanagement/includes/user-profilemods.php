<?php
/**********************************************************************************************************************************
*
* User Profile Modifications
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/

function pf_modify_contact_methods($profile_fields) {

	// Add new fields
	$profile_fields['user_twitter'] = esc_html__('Twitter','pointfindert2d');
	$profile_fields['user_facebook'] = esc_html__('Facebook','pointfindert2d');
	$profile_fields['user_googleplus'] = esc_html__('Google+','pointfindert2d');
	$profile_fields['user_linkedin'] = esc_html__('LinkedIn','pointfindert2d');
	$profile_fields['user_phone'] = esc_html__('Telephone','pointfindert2d');
	$profile_fields['user_mobile'] = esc_html__('Mobile','pointfindert2d');

	return $profile_fields;
}
add_filter('user_contactmethods', 'pf_modify_contact_methods');

function pf_custom_user_profile_fields($user) {
	$setup3_pointposttype_pt6_status = PFSAIssetControl('setup3_pointposttype_pt6_status','','1');
?>
<table class="form-table">
<?php if(current_user_can('activate_plugins')){?>
<tr>
	<th>
		<label for="user_photo"><?php esc_html_e('Photo','pointfindert2d'); ?></label>
	</th>
	<td>
		<?php echo wp_get_attachment_image(get_user_meta( $user->ID, 'user_photo', true )); ?>
	</td>
</tr>
<?php } if($setup3_pointposttype_pt6_status == 1 && current_user_can('activate_plugins')){?>
<tr>
	<th>
		<label for="user_photo"><?php esc_html_e('Link User to Agent','pointfindert2d'); ?></label>
	</th>
	<td>
		<label for="user_agent_link"><input type="text" name="user_agent_link" id="user_agent_link" value="<?php 
		echo get_user_meta( $user->ID, 'user_agent_link', true );
		?>" class="regular-text"><br/> <small><?php 
		esc_html_e("You can link an agent to this user. After this action this agent's contact information will seen this user's items.",'pointfindert2d');
		echo '<br/>';
		esc_html_e("This field only accept single agent ID number. And must be numeric.",'pointfindert2d');
		?></small></label>
	</td>
</tr>
<?php } ?>
</table>
<?php
}
add_action('show_user_profile', 'pf_custom_user_profile_fields');
add_action('edit_user_profile', 'pf_custom_user_profile_fields');

function pf_update_extra_profile_fields($user_id) {
	 $setup3_pointposttype_pt6_status = PFSAIssetControl('setup3_pointposttype_pt6_status','','1');
     if ( current_user_can('edit_user',$user_id) && $setup3_pointposttype_pt6_status == 1 ){
         update_user_meta($user_id, 'user_agent_link', $_POST['user_agent_link']);
     }
 }

add_action( 'edit_user_profile_update', 'pf_update_extra_profile_fields' );
add_action( 'personal_options_update', 'pf_update_extra_profile_fields' );
?>