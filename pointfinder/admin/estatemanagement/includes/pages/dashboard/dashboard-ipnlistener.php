<?php 
/**********************************************************************************************************************************
*
* User Dashboard Page - IPN Listener
* 
* Author: Webbu Design
***********************************************************************************************************************************/


if (!empty($_POST)) {
    if (isset($_POST['txn_type'])) {

        $paypal_host = (PFSAIssetControl('setup20_paypalsettings_paypal_sandbox','','0') == 0)? 'www.paypal.com' : 'www.sandbox.paypal.com';

        $url = 'https://'.$paypal_host.'/cgi-bin/webscr';

        $post_data = $_POST;
        $encoded_data = 'cmd=_notify-validate';
        foreach ($post_data as $key => $value) {
            $encoded_data .= "&$key=".urlencode($value);
        }

        $response = wp_remote_get( $url.'?'.$encoded_data, array(
              'method' => 'GET',
              'sslverify'   => true,
              'redirection' => 5,
              'httpversion' => '1.0',
              'headers' => array('Expect:'),
              'body' => $encoded_data,
            )
        );
       
        $verified = (isset($response['body']))? ($response['body'] == 'VERIFIED')? true : false : false;
        if ($verified) {

        	switch ($_POST['txn_type']) {
        		case 'recurring_payment_profile_cancel':
        		case 'recurring_payment_failed':
        		case 'recurring_payment_expired':
        			/** 
        			*Start : Cancel Recurring Payment Profile
        			**/
	        			if (isset($_POST['recurring_payment_id'])) {
	        				
	        				$setup33_emaillimits_listingexpired = PFMSIssetControl('setup33_emaillimits_listingexpired','','1');
	        				/*Find Item & Order by using profile ID */
		        			global $wpdb;
							
							$order_id = $wpdb->get_var( $wpdb->prepare(
								"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pointfinder_order_recurringid' and meta_value = '%s'", 
								$_POST['recurring_payment_id']
							));

							$recurring_status = esc_attr(get_post_meta( $order_id, 'pointfinder_order_recurring',true));

							if (!empty($order_id) && $recurring_status == 1) {
								
								$item_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_itemid', true ));
								$pointfinder_order_expiredate = get_post_meta( $order_id, 'pointfinder_order_expiredate', true );
								
								$post_author = $wpdb->get_var( $wpdb->prepare(
									"SELECT post_author FROM $wpdb->posts WHERE ID = %d", 
									$item_id
								));

								update_post_meta( $order_id, 'pointfinder_order_recurring', 0 );
								
								PFCreateProcessRecord(
									array( 
								        'user_id' => $post_author,
								        'item_post_id' => $item_id,
										'processname' => esc_html__('Recurring Payment Profile Cancelled','pointfindert2d')
								    )
								);

								if ($setup33_emaillimits_listingexpired == 1) {
									$user_info = get_userdata( $post_author);
								 	pointfinder_mailsystem_mailsender(
										array(
										'toemail' => $user_info->user_email,
								        'predefined' => 'expiredrecpayment',
								        'data' => array('ID' => $item_id, 'expiredate' => $pointfinder_order_expiredate,'orderid' => $order_id),
										)
									);
								}
							}
	        			}
        			/** 
        			*End : Cancel Recurring Payment Profile
        			**/
        			break;


        		case 'recurring_payment_suspended':
        		case 'recurring_payment_suspended_due_to_max_failed_payment':
        		
        			/** 
        			*Start : Suspended Recurring Payment Profile
        			**/
	        			if (isset($_POST['recurring_payment_id'])) {
	        				
	        				/*Find Item & Order by using profile ID */
		        			global $wpdb;
							
							$order_id = $wpdb->get_var( $wpdb->prepare(
								"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pointfinder_order_recurringid' and meta_value = '%s'", 
								$_POST['recurring_payment_id']
							));

							$recurring_status = esc_attr(get_post_meta( $order_id, 'pointfinder_order_recurring',true));

							if (!empty($order_id) && $recurring_status == 1) {
								
								$item_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_itemid', true ));
								$pointfinder_order_expiredate = esc_attr(get_post_meta( $order_id, 'pointfinder_order_expiredate', true ));
								
								$post_author = $wpdb->get_var( $wpdb->prepare(
									"SELECT post_author FROM $wpdb->posts WHERE ID = %d", 
									$item_id
								));


								update_post_meta( $order_id, 'pointfinder_order_recurring', 0 );
								
								PF_Cancel_recurring_payment(
								 array( 
								        'user_id' => $post_author,
								        'profile_id' => $_POST['recurring_payment_id'],
								        'item_post_id' => $item_id,
								        'order_post_id' => $order_id,
								    )
								 );

								PFCreateProcessRecord(
									array( 
								        'user_id' => $post_author,
								        'item_post_id' => $item_id,
										'processname' => esc_html__('Recurring Payment Profile Cancelled by IPN (Failed Payment)','pointfindert2d')
								    )
								);

								$setup33_emaillimits_listingexpired = PFMSIssetControl('setup33_emaillimits_listingexpired','','1');

								if ($setup33_emaillimits_listingexpired == 1) {
									$user_info = get_userdata( $post_author);
								 	pointfinder_mailsystem_mailsender(
										array(
										'toemail' => $user_info->user_email,
								        'predefined' => 'expiredrecpayment',
								        'data' => array('ID' => $item_id, 'expiredate' => $pointfinder_order_expiredate,'orderid' => $order_id),
										)
									);
								}

								
							}
	        			}
        			/** 
        			*End : Suspended Recurring Payment Profile
        			**/
        			break;


        		case 'recurring_payment':
        			/** 
        			*Start : Extend Recurring Payed Item 
        			**/
	        			if ($_POST['payment_status'] == 'Completed') {
	        				
	        				
		        			/*Find Item & Order by using profile ID */
		        			global $wpdb;
							
							$order_id = $wpdb->get_var( $wpdb->prepare(
								"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pointfinder_order_recurringid' and meta_value = '%s'", 
								$_POST['recurring_payment_id']
							));

							if (!empty($order_id)) {

								$item_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_itemid', true ));
			        			$pointfinder_order_listingtime = esc_attr(get_post_meta( $order_id, 'pointfinder_order_listingtime', true ));
			        			$old_expire_date = get_post_meta( $order_id, 'pointfinder_order_expiredate', true);

			        			$exp_date = date("Y-m-d H:i:s",strtotime($old_expire_date .'+'.$pointfinder_order_listingtime.' day'));
								$app_date = date("Y-m-d H:i:s");
							
								update_post_meta( $order_id, 'pointfinder_order_expiredate', $exp_date);
								update_post_meta( $order_id, 'pointfinder_order_datetime_approval', $app_date);

								$post_author = $wpdb->get_var( $wpdb->prepare(
									"SELECT post_author FROM $wpdb->posts WHERE ID = %d", 
									$item_id
								));

								
								PF_CreatePaymentRecord(
									array(
									'user_id'	=>	$post_author,
									'item_post_id'	=>	$item_id,
									'order_post_id'	=>	$order_id,
									'processname'	=>	'RecurringPayment',
									'response' => $post_data,
									)
								);

								PFCreateProcessRecord(
									array( 
							        'user_id' => $post_author,
							        'item_post_id' => $item_id,
									'processname' => sprintf(esc_html__('Expire date extended by IPN System: (Order Date: %s / Expire Date: %s)','pointfindert2d'),
										$app_date,
										$exp_date
										)
								    )
								);
							}
							
						}elseif ($_POST['payment_status'] == 'Pending') {
							/*Find Item & Order by using profile ID */
		        			global $wpdb;
							
							$order_id = $wpdb->get_var( $wpdb->prepare(
								"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pointfinder_order_recurringid' and meta_value = '%s'", 
								$_POST['recurring_payment_id']
							));

							if (!empty($order_id)) {
								$item_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_itemid', true ));
			        			
								$post_author = $wpdb->get_var( $wpdb->prepare(
									"SELECT post_author FROM $wpdb->posts WHERE ID = %d", 
									$item_id
								));

								PF_CreatePaymentRecord(
									array(
									'user_id'	=>	$post_author,
									'item_post_id'	=>	$item_id,
									'order_post_id'	=>	$order_id,
									'processname'	=>	'RecurringPaymentPending',
									'response' => $post_data,
									)
								);

							}
						}
					/** 
        			*End : Extend Recurring Payed Item 
        			**/
        			break;

        		case 'web_accept':

        			/** 
        			*Start : Refund & reversals
        			**/
	        			if($_POST["payment_status"] == "Refunded" || $_POST["payment_status"] == "Reversed"){
						    if (isset($_POST['custom'])) {
						    	$setup33_emaillimits_listingexpired = PFMSIssetControl('setup33_emaillimits_listingexpired','','1');
		        				/*Find Item & Order by using profile ID */
			        			global $wpdb;
								
								$order_id = $wpdb->get_var( $wpdb->prepare(
									"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pointfinder_order_recurringid' and meta_value = '%s'", 
									$_POST['custom']
								));

								$post_author = $wpdb->get_var( $wpdb->prepare(
									"SELECT post_author FROM $wpdb->posts WHERE ID = %d", 
									$_POST['custom']
								));



								/* This is direct payment */
								PFExpireItemManual(
									array( 
								        'order_id' => $order_id,
								        'post_id' => $_POST['custom'],
								        'post_author' => $post_author,
										'payment_type' => 'web_accept',
										'payment_err' => $_POST["payment_status"]
								    )
								 );

								 if ($setup33_emaillimits_listingexpired == 1) {
								 	$user_info = get_userdata( $post_author);
								 	$pointfinder_order_expiredate = get_post_meta( $order_id, 'pointfinder_order_expiredate', true );
								 	pointfinder_mailsystem_mailsender(
										array(
										'toemail' => $user_info->user_email,
								        'predefined' => 'directafterexpire',
								        'data' => array('ID' => $_POST['custom'], 'expiredate' => $pointfinder_order_expiredate,'orderid' => $order_id),
										)
									);
								 }

						    } 
						    
						}
					/** 
        			*End : Refund & reversals
        			**/
        			break;
        		
        	}
        } else {
           /*wp_mail( 'mail', 'not verified', $encoded_data);*/
        }
    }
}
/**
*End: Update & Add function for new item
**/
?>