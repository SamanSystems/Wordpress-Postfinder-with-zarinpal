<?php
/**********************************************************************************************************************************
*
* Item Status Change Actions & Functions
*  - Item status change filter
*  
*
* Author: Webbu Design
*
***********************************************************************************************************************************/

/**
*Start : Delete all images while post deleting.
**/

add_action( 'before_delete_post', 'pointfinder_before_delete_post' );
function pointfinder_before_delete_post( $postid ){
	$post = get_post($postid);
	$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
   
    
    if ( $post->post_type != $setup3_pointposttype_pt1 ) return;

    $admins_ids = get_users( array( 'role'   => 'administrator', 'fields' => 'ID' ) );

    if (in_array($post->post_author, $admins_ids) ) return;

    $post_images = get_post_meta( $postid, 'webbupointfinder_item_images', false );
  	

    if (is_array($post_images)) {
    	foreach ($post_images as $post_image) {
    		wp_delete_attachment( $post_image, true );
    	}
    }

    $post_thumbnail_id = get_post_thumbnail_id( $postid );
    wp_delete_attachment( $post_thumbnail_id, true );

}
/**
*End : Delete all images while post deleting.
**/

/**
*Start: On Publish : Set Expire Date for Item
*	All status change actions in below function.
**/
	function pointfinder_all_item_status_changes($new_status, $old_status, $post) {
		
		$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');

		/**
		*Start : Status going to PUBLISH from PENDINGAPPROVAL / REJECTED
		**/
			if($new_status == 'publish' && $post->post_type == $setup3_pointposttype_pt1){
			
				if($old_status == 'pendingapproval' || $old_status == 'rejected'){
				
					global $wpdb;
					$order_post_id = $wpdb->get_var( $wpdb->prepare(
						"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %d", 
						'pointfinder_order_itemid',
						$post->ID
					) );

					$pointfinder_order_listingtime = get_post_meta( $order_post_id, 'pointfinder_order_listingtime', true );
					$exp_date = date("Y-m-d H:i:s", strtotime("+".$pointfinder_order_listingtime." days"));
					$app_date = date("Y-m-d H:i:s");

					$edit_record_check = PFcheck_postmeta_exist('pointfinder_order_itemedit',$order_post_id);

					if (!$edit_record_check) { 
						update_post_meta( $order_post_id, 'pointfinder_order_expiredate', $exp_date);
						update_post_meta( $order_post_id, 'pointfinder_order_datetime_approval', $app_date);
					}else{
						if (esc_attr(get_post_meta( $order_post_id, 'pointfinder_order_itemedit', true )) == 1) {
							update_post_meta($order_post_id, 'pointfinder_order_itemedit', 0 );
						}
					};


					$old_status_text = ($old_status == 'pendingapproval')? esc_html__('Pending Approval','pointfindert2d'):esc_html__('Rejected','pointfindert2d');
					
					/* - Creating record for process system. */
					PFCreateProcessRecord(
						array( 
					        'user_id' => $post->post_author,
					        'item_post_id' => $post->ID,
							'processname' => sprintf(esc_html__('Item status changed from %s to Publish by admin','pointfindert2d'),$old_status_text)
					    )
					);
					

					$user_info = get_userdata( $post->post_author );
					
					pointfinder_mailsystem_mailsender(
					array(
							'toemail' => $user_info->user_email,
					        'predefined' => 'itemapproved',
					        'data' => array('ID' => $post->ID,'title'=>$post->post_title),
						)
					);

					
					
					
				}
			}
		/**
		*End : Status going to PUBLISH from PENDINGAPPROVAL / REJECTED
		**/



		/**
		*Start : Status going to REJECTED from PENDINGAPPROVAL / PUBLISH
		**/
			if($new_status == 'rejected' && $post->post_type == $setup3_pointposttype_pt1){
			
				if($old_status == 'pendingapproval' || $old_status == 'publish'){


					$old_status_text = ($old_status == 'pendingapproval')? esc_html__('Pending Approval','pointfindert2d'):esc_html__('Publish','pointfindert2d');

					/* - Creating record for process system. */
					PFCreateProcessRecord(
						array( 
					        'user_id' => $post->post_author,
					        'item_post_id' => $post->ID,
							'processname' => sprintf(esc_html__('Item status changed from %s to Rejected by admin','pointfindert2d'),$old_status_text)
					    )
					);


					$user_info = get_userdata( $post->post_author );
					
					pointfinder_mailsystem_mailsender(
					array(
							'toemail' => $user_info->user_email,
					        'predefined' => 'itemrejected',
					        'data' => array('ID' => $post->ID,'title'=>$post->post_title),
						)
					);

					
					
				}
			}

		/**
		*End : Status going to REJECTED from PENDINGAPPROVAL / PUBLISH
		**/



		/**
		*Start : Status going to TRASH from ANY
		**/
			if($new_status == 'trash' && $post->post_type == $setup3_pointposttype_pt1){
				$order_control = PFU_CheckOrderID($post->ID);
				if($order_control){
					switch ($old_status) {
						case 'pendingapproval':
							$old_status_text = esc_html__('Pending Approval','pointfindert2d');
							break;
						
						case 'publish':
							$old_status_text = esc_html__('Publish','pointfindert2d');
							break;

						case 'rejected':
							$old_status_text = esc_html__('Rejected','pointfindert2d');
							break;
					}

					/* - Creating record for process system. */
					PFCreateProcessRecord(
						array( 
					        'user_id' => $post->post_author,
					        'item_post_id' => $post->ID,
							'processname' => sprintf(esc_html__('Item status changed from %s to TRASH by admin','pointfindert2d'),$old_status_text)
					    )
					);

					$setup33_emaillimits_useremailsaftertrash = PFMSIssetControl('setup33_emaillimits_useremailsaftertrash','','1');
					if($setup33_emaillimits_useremailsaftertrash == 1){
						$user_info = get_userdata( $post->post_author );
						pointfinder_mailsystem_mailsender(
						array(
								'toemail' => $user_info->user_email,
						        'predefined' => 'itemdeleted',
						        'data' => array('ID' => $post->ID,'title'=>$post->post_title),
							)
						);
					}
				}
				
			}

		/**
		*End : Status going to TRASH from ANY
		**/



		/**
		*Start : Status going to PUBLISH from PENDINGPAYMENT
		**/
			if($new_status == 'publish' && $post->post_type == $setup3_pointposttype_pt1){
			
				if($old_status == 'pendingpayment'){
				
					global $wpdb;
					$order_post_id = $wpdb->get_var( $wpdb->prepare(
						"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %d", 
						'pointfinder_order_itemid',
						$post->ID
					) );

					$pointfinder_order_listingtime = get_post_meta( $order_post_id, 'pointfinder_order_listingtime', true );
					$exp_date = date("Y-m-d H:i:s", strtotime("+".$pointfinder_order_listingtime." days"));
					$app_date = date("Y-m-d H:i:s");

					update_post_meta( $order_post_id, 'pointfinder_order_expiredate', $exp_date);
					update_post_meta( $order_post_id, 'pointfinder_order_datetime_approval', $app_date);
					

					if (PFcheck_postmeta_exist('pointfinder_order_bankcheck',$order_post_id)) { 
						update_post_meta($order_post_id, 'pointfinder_order_bankcheck', '0');	
					};

					$wpdb->UPDATE($wpdb->posts,array('post_status' => 'completed'),array('ID' => $order_post_id));
					
					/* - Creating record for process system. */
					PFCreateProcessRecord(
						array( 
					        'user_id' => $post->post_author,
					        'item_post_id' => $post->ID,
							'processname' => esc_html__('Item status changed from Pending Payment to Publish by admin','pointfindert2d')
					    )
					);

					/* - Sending an email to user. */
					$user_info = get_userdata( $post->post_author );
					$email_subject = 'itemapproved';

					pointfinder_mailsystem_mailsender(
						array(
							'toemail' => $user_info->user_email,
					        'predefined' => $email_subject,
					        'data' => array('ID' => $post->ID,'title'=>$post->post_title),
							)
						);

					
					
				}
			}
		/**
		*End : Status going to PUBLISH from PENDINGPAYMENT
		**/


	}
	add_action('transition_post_status', 'pointfinder_all_item_status_changes', 10, 3 );
/**
*End : On Publish : Set Expire Date for Item
**/

?>