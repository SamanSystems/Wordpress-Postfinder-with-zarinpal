<?php

/**********************************************************************************************************************************
*
* Common functions for pf user system
* 
* Author: Webbu Design
* Please do not modify below functions.
***********************************************************************************************************************************/

function PF_Cancel_recurring_payment($params = array()){
	$defaults = array( 
        'user_id' => '',
        'profile_id' => '',
        'item_post_id' => '',
        'order_post_id' => '',
    );

	$params = array_merge($defaults, $params);

	$method = 'ManageRecurringPaymentsProfileStatus';
	
	$paypal_price_unit = PFSAIssetControl('setup20_paypalsettings_paypal_price_unit','','USD');
	$paypal_sandbox = PFSAIssetControl('setup20_paypalsettings_paypal_sandbox','','0');
	$paypal_api_user = PFSAIssetControl('setup20_paypalsettings_paypal_api_user','','');
	$paypal_api_pwd = PFSAIssetControl('setup20_paypalsettings_paypal_api_pwd','','');
	$paypal_api_signature = PFSAIssetControl('setup20_paypalsettings_paypal_api_signature','','2');

	$infos = array();
	$infos['USER'] = $paypal_api_user;
	$infos['PWD'] = $paypal_api_pwd;
	$infos['SIGNATURE'] = $paypal_api_signature;

	if($paypal_sandbox == 1){$sandstatus = true;}else{$sandstatus = false;}
	
	$paypal = new Paypal($infos,$sandstatus);
	$item_arr_rec = array('PROFILEID' => $params['profile_id'],'Action' => 'Cancel','Note'=>'User Cancelled.'); 

	$response_recurring = $paypal -> request($method,$item_arr_rec);
	
	/*Create a payment record for this process */
	PF_CreatePaymentRecord(
		array(
		'user_id'	=>	$params['user_id'],
		'item_post_id'	=>	$params['item_post_id'],
		'order_post_id'	=> $params['order_post_id'],
		'response'	=>	$response_recurring,
		'processname'	=>	'ManageRecurringPaymentsProfileStatus',
		'status'	=>	$response_recurring['ACK']
		)

	);
}

function PF_CreatePaymentRecord($params = array()){

	$defaults = array( 
        'user_id' => '',
        'item_post_id' => '',
        'order_post_id' => '',
        'orderdetails_post_id' => '',
        'response' => array(),
		'token' => '',
		'payerid' => '',
		'processname' => '',
		'status' => '',
		'datetime' => date("Y-m-d H:i:s")
    );
	if(isset($params['response'])){
	    if(count($params['response'])>0){
	  		$response = $params['response'];
		}else{
			$response = '';
		}
	}else{
		$response = '';
	}


	$params = array_merge($defaults, $params);
	global $wpdb;

	if(empty($params['order_post_id'])){
	    $order_post_id = $wpdb->get_var( $wpdb->prepare( 
			"SELECT post_id FROM $wpdb->postmeta WHERE meta_value = %s and meta_key = %s", 
			$params['token'],
			'pointfinder_order_token'
		) );
	    $params['order_post_id'] = $order_post_id;
	}

    
    if (PFcheck_postmeta_exist('pointfinder_order_paymentrecs',$params['order_post_id'])) { 
    	
    	$json_array = get_post_meta($params['order_post_id'], 'pointfinder_order_paymentrecs',true);	
    	
    	if(!empty($json_array)){
    		$json_array = json_decode($json_array,true);
    	}else{
    		$json_array = array();
    	}

		switch ($params['processname']) {
			case 'BankTransferCancel':

				$json_array[] = array(
					'processname' => $params['processname'],
					'datetime'	=> $params['datetime']
					);

				break;
			case 'BankTransfer':

				$json_array[] = array(
					'processname' => $params['processname'],
					'datetime'	=> $params['datetime']
					);

				break;
			case 'SetExpressCheckout':
				
				$json_array[] = array(
					'processname' => $params['processname'],
					'datetime'	=> $params['datetime'],
					'token'	=> $params['token'],
					'status'	=> $params['status']
					);

				break;

			case 'GetExpressCheckoutDetails':
				
				$output_array = array();
				$output_array['processname'] = $params['processname'];
				$output_array['datetime'] = $params['datetime'];
				$output_array['token'] = $params['token'];
				$output_array['status'] = $params['status'];
				
				if(count($response) > 0){
						
						$output_array_response = array(
							'EMAIL'	=>	$response['EMAIL'],	
							'PAYERID'	=>	$response['PAYERID'],
							'PAYERSTATUS'	=>	$response['PAYERSTATUS'],	
							'CHECKOUTSTATUS'  =>	$response['CHECKOUTSTATUS'],	
							'FIRSTNAME'	=>	$response['FIRSTNAME'],	
							'LASTNAME'	=>	$response['LASTNAME'],
							'COUNTRYCODE'	=>	$response['COUNTRYCODE'],
							'SHIPTONAME'	=>	$response['SHIPTONAME'],
							'SHIPTOSTREET'	=>	$response['SHIPTOSTREET'],
							'SHIPTOCITY'	=>	$response['SHIPTOCITY'],
							'SHIPTOSTATE'	=>	$response['SHIPTOSTATE'],
							'SHIPTOZIP'	=>	$response['SHIPTOZIP'],
							'SHIPTOCOUNTRYNAME'	=>	$response['SHIPTOCOUNTRYNAME'],
							'ADDRESSSTATUS'	=>	$response['ADDRESSSTATUS'],
							'CURRENCYCODE'	=>	$response['CURRENCYCODE'],
							'PackagePrice'	=>	$response['PAYMENTREQUEST_0_AMT']
						);

						if (isset($response['L_PAYMENTREQUEST_0_NAME0']) && isset($response['L_PAYMENTREQUEST_0_DESC0'])) {
							$output_array_response['PackageName'] = $response['L_PAYMENTREQUEST_0_NAME0'].'/'.$response['L_PAYMENTREQUEST_0_DESC0'];
						}

					$json_array[] = array_merge($output_array,$output_array_response);
				}else{
					$json_array[] = $output_array;
				}

				break;
			
			case 'CreateRecurringPaymentsProfile':


				$output_array = array();
				$output_array['processname'] = $params['processname'];
				$output_array['datetime'] = $params['datetime'];
				$output_array['token'] = $params['token'];
				$output_array['status'] = $params['status'];

				if(count($response) > 0){

					if(isset($response['PROFILEID'])){$output_array['PROFILEID'] = $response['PROFILEID'];}
					if(isset($response['PROFILESTATUS'])){$output_array['PROFILESTATUS'] = $response['PROFILESTATUS'];}
					if(isset($response['TIMESTAMP'])){$output_array['TIMESTAMP'] = $response['TIMESTAMP'];}

				}

				$json_array[] = $output_array;

				break;
			case 'ManageRecurringPaymentsProfileStatus':
				$output_array = array();
				$output_array['processname'] = $params['processname'];
				$output_array['datetime'] = $params['datetime'];
				$output_array['status'] = $params['status'];

				if(count($response) > 0){

					if(isset($response['PROFILEID'])){$output_array['PROFILEID'] = $response['PROFILEID'];}
					if(isset($response['TIMESTAMP'])){$output_array['TIMESTAMP'] = $response['TIMESTAMP'];}

				}

				$json_array[] = $output_array;

				break;
			case 'DoExpressCheckoutPayment':

				$output_array = array();
				$output_array['processname'] = $params['processname'];
				$output_array['datetime'] = $params['datetime'];
				$output_array['token'] = $params['token'];
				$output_array['status'] = $params['status'];

				if(count($response) > 0){
					

					if(isset($response['PAYMENTINFO_0_TRANSACTIONID'])){$output_array['TRANSACTIONID'] = $response['PAYMENTINFO_0_TRANSACTIONID'];}
					if(isset($response['PAYMENTINFO_0_TRANSACTIONTYPE'])){$output_array['TRANSACTIONTYPE'] = $response['PAYMENTINFO_0_TRANSACTIONTYPE'];}
					if(isset($response['PAYMENTINFO_0_ORDERTIME'])){$output_array['TIMESTAMP'] = $response['PAYMENTINFO_0_ORDERTIME'];}
					if(isset($response['PAYMENTINFO_0_PAYMENTSTATUS'])){$output_array['PAYMENTSTATUS'] = $response['PAYMENTINFO_0_PAYMENTSTATUS'];}
					if(isset($response['L_SHORTMESSAGE0'])){$output_array['SHORTMESSAGE'] = $response['L_SHORTMESSAGE0'];}
					if(isset($response['L_LONGMESSAGE0'])){$output_array['LONGMESSAGE'] = $response['L_LONGMESSAGE0'];}
					if(isset($response['L_ERRORCODE0'])){$output_array['ERRORCODE'] = $response['L_ERRORCODE0'];}

					
				}

				$json_array[] = $output_array;

				break;

			case 'DoExpressCheckoutPaymentStripe':

				$output_array = array();
				$output_array['processname'] = $params['processname'];
				$output_array['datetime'] = $params['datetime'];
				$output_array['status'] = $params['status'];

				$json_array[] = $output_array;

				break;

			case 'CancelPayment':
				
				$output_array = array();
				$output_array['processname'] = $params['processname'];
				$output_array['datetime'] = $params['datetime'];
				$output_array['token'] = $params['token'];
				
				$wpdb->UPDATE($wpdb->posts,array('post_status' => 'pfcancelled'),array('ID' => $params['order_post_id']));

				$json_array[] = $output_array;

				break;

			case 'GetRecurringPaymentsProfileDetails':

				$output_array = array();
				$output_array['processname'] = $params['processname'];
				$output_array['datetime'] = $params['datetime'];
				$output_array['token'] = $params['token'];
				$output_array['status'] = $params['status'];

				if(count($response) > 0){
					

					if(isset($response['STATUS'])){$output_array['STATUS'] = $response['STATUS'];}
					if(isset($response['NEXTBILLINGDATE'])){$output_array['NEXTBILLINGDATE'] = $response['NEXTBILLINGDATE'];}
					if(isset($response['NUMCYCLESCOMPLETED'])){$output_array['NUMCYCLESCOMPLETED'] = $response['NUMCYCLESCOMPLETED'];}
					if(isset($response['LASTPAYMENTDATE'])){$output_array['LASTPAYMENTDATE'] = $response['LASTPAYMENTDATE'];}
					if(isset($response['LASTPAYMENTAMT'])){$output_array['LASTPAYMENTAMT'] = $response['LASTPAYMENTAMT'];}
					if(isset($response['DESC'])){$output_array['DESC'] = $response['DESC'];}
					if(isset($response['PROFILEID'])){$output_array['PROFILEID'] = $response['PROFILEID'];}

					
				}

				$json_array[] = $output_array;

				break;

			case 'RecurringPayment':

				$output_array = array();
				$output_array['processname'] = $params['processname'];
				$output_array['datetime'] = $params['datetime'];


				if(count($response) > 0){
					
					$output_array['response'] = $params['response'];

				}
				$json_array[] = $output_array;
				break;
		}


		$json_array = json_encode($json_array);
		update_post_meta($params['order_post_id'], 'pointfinder_order_paymentrecs', $json_array);	

	}else{

		$json_array = array(array());
		$json_array[0] = array(
			'processname' => $params['processname'],
			'datetime'	=> $params['datetime'],
			'token'	=> $params['token'],
			'status'	=> $params['status']
			);
    	$json_array = json_encode($json_array);
		add_post_meta ($params['order_post_id'], 'pointfinder_order_paymentrecs', $json_array);

	};   
}

function PFCreateProcessRecord($params = array()){
	$defaults = array( 
        'user_id' => '',
        'item_post_id' => '',
		'processname' => '',
		'datetime' => date("Y-m-d H:i:s")
    );

	$params = array_merge($defaults, $params);
	$order_post_id = PFU_GetOrderID($params['item_post_id'],1);

    if (PFcheck_postmeta_exist('pointfinder_order_processrecs',$order_post_id)) { 
    	$json_array = get_post_meta($order_post_id, 'pointfinder_order_processrecs',true);	
    	if(!empty($json_array)){
    		$json_array = json_decode($json_array,true);
    		$json_count = count($json_array);
    	}else{
    		$json_array = array();
    	}
    	$json_array[$json_count] = $params;
    	$json_array = json_encode($json_array);
		update_post_meta($order_post_id, 'pointfinder_order_processrecs', $json_array);	
	}else{
		$json_array = array(array());
		$json_array[0] = $params;
    	$json_array = json_encode($json_array);
		add_post_meta ($order_post_id, 'pointfinder_order_processrecs', $json_array);
	};   
}

function PFU_GetPostOrderDate($value) {
	global $wpdb;
	$result = $wpdb->get_var( $wpdb->prepare( 
		"
			SELECT post_date
			FROM $wpdb->posts
			WHERE ID = %d
		", 
		$value
	) );
	return $result;
}

function PFU_GetOrderID($value,$type = 0) {
	global $wpdb;
	
	$meta_key = 'pointfinder_order_itemid';

	$result = $wpdb->get_var( $wpdb->prepare( 
		"
			SELECT post_id
			FROM $wpdb->postmeta 
			WHERE meta_key = %s and meta_value = %d
		", 
		$meta_key,
		$value
	) );

	if($type == 0){
		return get_the_title($result);
	}else{
		return $result;
	}
}

function PFU_CheckOrderID($value) {	
	$meta_key = 'pointfinder_order_itemid';

	if (PFcheck_postmeta_exist($meta_key,$value)) { 
		return true;
	}else{
		return false;
	}; 
}

function PFU_Dateformat($value,$showtime = 0){
	$setup4_membersettings_dateformat = PFSAIssetControl('setup4_membersettings_dateformat','','1');
	/*
	'1' => 'dd/mm/yyyy', 
    '2' => 'mm/dd/yyyy', 
    '3' => 'yyyy/mm/dd',
    '4' => 'yyyy/dd/mm'
	*/
	switch ($setup4_membersettings_dateformat) {
		case '1':
			$datetype = ($showtime != 1)? "d-m-Y" : "d-m-Y H:i:s";
			break;
		
		case '2':
			$datetype = ($showtime != 1)? "m-d-Y" : "m-d-Y H:i:s";
			break;

		case '3':
			$datetype = ($showtime != 1)? "Y-m-d" : "Y-m-d H:i:s";
			break;

		case '4':
			$datetype = ($showtime != 1)? "Y-d-m" : "Y-d-m H:i:s";
			break;
	}

	$newdate = date($datetype,strtotime($value));
	return $newdate;
}

function PFProcessNameFilter($value){
	switch ($value) {
		case 'BankTransferCancel':
			return esc_html__('Bank Transfer Cancellation','pointfindert2d');
			break;
		case 'BankTransfer':
			return esc_html__('Bank Transfer Request','pointfindert2d');
			break;
		case 'CancelPayment':
			return esc_html__('Payment Cancelled by User','pointfindert2d');
			break;
		case 'DoExpressCheckoutPayment':
			return esc_html__('Express Checkout Process End','pointfindert2d');
			break;
		case 'CreateRecurringPaymentsProfile':
			return esc_html__('Recurring Payment Profile Creation','pointfindert2d');
			break;
		case 'ManageRecurringPaymentsProfileStatus':
			return esc_html__('Recurring Payment Profile Cancellation','pointfindert2d');
			break;
		case 'GetExpressCheckoutDetails':
			return esc_html__('Getting Express Checkout Details','pointfindert2d');
			break;
		case 'SetExpressCheckout':
			return esc_html__('Checkout Process Started','pointfindert2d');
			break;
		case 'GetRecurringPaymentsProfileDetails':
			return esc_html__('Recurring Payment Control','pointfindert2d');
			break;
		case 'RecurringPayment':
			return esc_html__('Recurring Payment Received','pointfindert2d');
			break;
		case 'RecurringPaymentPending':
			return esc_html__('Recurring Payment Pending','pointfindert2d');
			break;
	}
}







function pfmake_seed(){
  list($usec, $sec) = explode(' ', microtime());
  return (float) $sec + ((float) $usec * 10000000);
}
?>