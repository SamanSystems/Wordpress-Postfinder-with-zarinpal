<?php
/**********************************************************************************************************************************
*
* Ajax Payment System
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/


add_action( 'PF_AJAX_HANDLER_pfget_paymentsystem', 'pf_ajax_paymentsystem' );
add_action( 'PF_AJAX_HANDLER_nopriv_pfget_paymentsystem', 'pf_ajax_paymentsystem' );

function pf_ajax_paymentsystem(){
  
	//Security
  check_ajax_referer( 'pfget_paymentsystem', 'security');
  
	header('Content-Type: application/json; charset=UTF-8;');

	//Get form type
  if(isset($_POST['formtype']) && $_POST['formtype']!=''){
    $formtype = esc_attr($_POST['formtype']);
  }

  //Get item id
  if(isset($_POST['itemid']) && $_POST['itemid']!=''){
    $item_post_id = esc_attr($_POST['itemid']);
  }else{
    $item_post_id = '';
  }


	switch($formtype){
/**
*Paypal Request Work
**/
		case 'zarinweb':
      //62 olumlu 485 olumsuz
      $icon_processout = 62;
      $msg_output = $pfreturn_url = '';
      $current_user = wp_get_current_user();
      $user_id = $current_user->ID;

      if($user_id != 0){

        if ($item_post_id != '') {

          $setup4_membersettings_dashboard = PFSAIssetControl('setup4_membersettings_dashboard','',site_url());
          $setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
          $pfmenu_perout = PFPermalinkCheck();
          $setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');

          /*Check if item user s item*/
          global $wpdb;

          $result = $wpdb->get_results( $wpdb->prepare( 
            "SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s", 
            $item_post_id,
            $user_id,
            $setup3_pointposttype_pt1
          ) );

          
          if (is_array($result) && count($result)>0) {  
            
            if ($result[0]->ID == $item_post_id) {

              $paypal_price_unit = PFSAIssetControl('setup20_paypalsettings_paypal_price_unit','','USD');
              $paypal_sandbox = PFSAIssetControl('setup20_paypalsettings_paypal_sandbox','','0');
              $paypal_api_user = PFSAIssetControl('setup20_paypalsettings_paypal_api_user','','');
              $paypal_api_pwd = PFSAIssetControl('setup20_paypalsettings_paypal_api_pwd','','');
              $paypal_api_signature = PFSAIssetControl('setup20_paypalsettings_paypal_api_signature','','2');

              $setup20_paypalsettings_decimals = PFSAIssetControl('setup20_paypalsettings_decimals','','2');
              $setup20_paypalsettings_decimalpoint = PFSAIssetControl('setup20_paypalsettings_decimalpoint','','.');
              $setup20_paypalsettings_thousands = PFSAIssetControl('setup20_paypalsettings_thousands','',',');

              /*Meta for order*/
              global $wpdb;
              $result_id = $wpdb->get_var( $wpdb->prepare(
                "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s", 
                'pointfinder_order_itemid',
                $item_post_id
              ) );
              

              
              $pointfinder_order_pricesign = esc_attr(get_post_meta( $result_id, 'pointfinder_order_pricesign', true ));
              $pointfinder_order_listingtime = esc_attr(get_post_meta( $result_id, 'pointfinder_order_listingtime', true ));
              $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
              $pointfinder_order_recurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_recurring', true ));
              $pointfinder_order_listingtime = ($pointfinder_order_listingtime == '') ? 0 : $pointfinder_order_listingtime ;

              $pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpid', true )); 

            
              $total_package_price =  number_format($pointfinder_order_price, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands);

              
              $paymentName = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindert2d'));

              $setup20_paypalsettings_paypal_api_packagename_basic = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename_basic','',esc_html__('Basic Listing Payment','pointfindert2d'));
              $setup20_paypalsettings_paypal_api_packagename_featured = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename_featured','',esc_html__('Featured Listing Payment','pointfindert2d'));

              if($pointfinder_order_listingpid == 2){
                $packtype = 'featured';
              }else{
                $packtype = 'basic';
              }
              
              $apipackage_name = ($packtype == 'basic')? $setup20_paypalsettings_paypal_api_packagename_basic : $setup20_paypalsettings_paypal_api_packagename_featured;

              
              

              

              $requestParams = array(
                 'RETURNURL' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_rec', 
                 'CANCELURL' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_cancel'
              );

              

              $orderParams = array(
                   'PAYMENTREQUEST_0_AMT' => $total_package_price,
                   'PAYMENTREQUEST_0_CURRENCYCODE' => $paypal_price_unit,
                   'PAYMENTREQUEST_0_ITEMAMT' => $total_package_price,
                   'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
                   'PAYMENTREQUEST_0_CUSTOM' => $item_post_id,
                );

              if ($pointfinder_order_recurring == 1) {
                $orderParams['L_BILLINGTYPE0'] = 'RecurringPayments';
                $orderParams['L_BILLINGAGREEMENTDESCRIPTION0'] = sprintf(
                  esc_html__('%s / %s / Recurring: %s%s per %s days / For: (%s) %s','pointfindert2d'),
                  $paymentName,
                  $apipackage_name,
                  $total_package_price,
                  $pointfinder_order_pricesign,
                  $pointfinder_order_listingtime,
                  $item_post_id,
                  get_the_title($item_post_id)
                  );
              }

                
              $item_arr = array(
                 'L_PAYMENTREQUEST_0_NAME0' => $paymentName,
                 'L_PAYMENTREQUEST_0_DESC0' => $apipackage_name,
                 'L_PAYMENTREQUEST_0_AMT0' => $total_package_price,
                 'L_PAYMENTREQUEST_0_QTY0' => '1',
                 'L_PAYMENTREQUEST_0_ITEMCATEGORY0' => 'Digital',
              );
              
              
              $infos = array();
              $infos['USER'] = $paypal_api_user;
              $infos['PWD'] = $paypal_api_pwd;
              $infos['SIGNATURE'] = $paypal_api_signature;
              if($paypal_sandbox == 1){$sandstatus = true;}else{$sandstatus = false;}
                
              $paypal = new Paypal($infos,$sandstatus);
              $response = $paypal -> request('SetExpressCheckout',$requestParams + $orderParams + $item_arr);
				
				$Amount = $pointfinder_order_price;
				
				$MerchantID = $paypal_api_user;

				$Description = 'بابت ثبت آگهی';  // Required
				
				$CallbackURL = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_rec&PAYERID='.$user_id;  // Required
				
				
				// URL also Can be https://ir.zarinpal.com/pg/services/WebGate/wsdl
				$client = new SoapClient('https://de.zarinpal.com/pg/services/WebGate/wsdl', array('encoding' => 'UTF-8')); 
				
				$response = $client->PaymentRequest(
									array(
											'MerchantID' 	=> $MerchantID,
											'Amount' 	=> $Amount,
											'Description' 	=> $Description,
											'CallbackURL' 	=> $CallbackURL
										)
				);
				
				//Redirect to URL You can do it also by creating a form
				/*if($response->Status == 100)
				{
					Header('Location: https://www.zarinpal.com/pg/StartPay/'.$response->Authority);
				} else {
					echo'ERR: '.$response->Status;
				}*/
				
				

              unset($paypal);
              if(!$response){ 
                $msg_output .= esc_html__( 'Error: No Response', 'pointfindert2d' ).'<br>';
                $icon_processout = 485;
                /*$errorval .= $paypal->getErrors();*/
              }
              


              
              if($response->Status == 100) { 
                $token = $response->Authority; 
                
                update_post_meta($result_id, 'pointfinder_order_token', $token ); 

                /*Create a payment record for this process */
                PF_CreatePaymentRecord(
                    array(
                    'user_id' =>  $user_id,
                    'item_post_id'  =>  $item_post_id,
                    'order_post_id' =>  $result_id,
                    'response'  =>  $response,
                    'token' =>  $token,
                    'processname' =>  'SetExpressCheckout',
                    'status'  =>  100,
                    )
                  );
              

					//if($paypal_sandbox == 1)
						//$pfreturn_url = 'https://www.zarinpal.com/pg/StartPay/' . $token .'/ZarinGate';
					//else
						$pfreturn_url = 'https://www.zarinpal.com/pg/StartPay/' . $token;
               
                
                $msg_output .= esc_html__('Payment process is ok. Please wait redirection.','pointfindert2d');

              }else{
                /*Create a payment record for this process */
           
                PF_CreatePaymentRecord(
                    array(
                    'user_id' =>  $user_id,
                    'item_post_id'  =>  $item_post_id,
                    'order_post_id' =>  $result_id,
                    'response'  =>  $response,
                    'token' =>  '',
                    'processname' =>  'SetExpressCheckout',
                    'status'  =>  $response->Status,
                    )
                  );
                $msg_output .= esc_html__( 'Error: Not Success', 'pointfindert2d' ).'<br>';
                if (isset($response['L_SHORTMESSAGE0'])) {
                 $msg_output .= '<small>'.$response['L_SHORTMESSAGE0'].'</small><br/>';
                }
                if (isset($response['L_LONGMESSAGE0'])) {
                 $msg_output .= '<small>'.$response['L_LONGMESSAGE0'].'</small><br/>';
                }
                $icon_processout = 485;
                
              }

            }else{
              $msg_output .= esc_html__('Wrong item ID (It is not your item!). Payment process is stopped.','pointfindert2d');
              $icon_processout = 485;
            }
          }
        }else{
          $msg_output .= esc_html__('Wrong item ID.','pointfindert2d');
          $icon_processout = 485;
        }
      }else{
        $msg_output .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
        $icon_processout = 485;
      }

      $output_html = '';
      $output_html .= '<div class="golden-forms wrapper mini" style="height:200px">';
      $output_html .= '<div id="pfmdcontainer-overlay" class="pftrwcontainer-overlay">';
      
      $output_html .= "<div class='pf-overlay-close'><i class='pfadmicon-glyph-707'></i></div>";
      $output_html .= "<div class='pfrevoverlaytext'><i class='pfadmicon-glyph-".$icon_processout."'></i><span>".$msg_output."</span></div>";
      
      $output_html .= '</div>';
      $output_html .= '</div>';    
      if ($icon_processout == 485) {  
        echo json_encode( array( 'process'=>false, 'mes'=>$output_html, 'returnurl' => $pfreturn_url));
      }else{
        echo json_encode( array( 'process'=>true, 'mes'=>'', 'returnurl' => $pfreturn_url));
      }
		break;
		
	case 'zaringate':
      //62 olumlu 485 olumsuz
      $icon_processout = 62;
      $msg_output = $pfreturn_url = '';
      $current_user = wp_get_current_user();
      $user_id = $current_user->ID;

      if($user_id != 0){

        if ($item_post_id != '') {

          $setup4_membersettings_dashboard = PFSAIssetControl('setup4_membersettings_dashboard','',site_url());
          $setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
          $pfmenu_perout = PFPermalinkCheck();
          $setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');

          /*Check if item user s item*/
          global $wpdb;

          $result = $wpdb->get_results( $wpdb->prepare( 
            "SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s", 
            $item_post_id,
            $user_id,
            $setup3_pointposttype_pt1
          ) );

          
          if (is_array($result) && count($result)>0) {  
            
            if ($result[0]->ID == $item_post_id) {

              $paypal_price_unit = PFSAIssetControl('setup20_paypalsettings_paypal_price_unit','','USD');
              $paypal_sandbox = PFSAIssetControl('setup20_paypalsettings_paypal_sandbox','','0');
              $paypal_api_user = PFSAIssetControl('setup20_paypalsettings_paypal_api_user','','');
              $paypal_api_pwd = PFSAIssetControl('setup20_paypalsettings_paypal_api_pwd','','');
              $paypal_api_signature = PFSAIssetControl('setup20_paypalsettings_paypal_api_signature','','2');

              $setup20_paypalsettings_decimals = PFSAIssetControl('setup20_paypalsettings_decimals','','2');
              $setup20_paypalsettings_decimalpoint = PFSAIssetControl('setup20_paypalsettings_decimalpoint','','.');
              $setup20_paypalsettings_thousands = PFSAIssetControl('setup20_paypalsettings_thousands','',',');

              /*Meta for order*/
              global $wpdb;
              $result_id = $wpdb->get_var( $wpdb->prepare(
                "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s", 
                'pointfinder_order_itemid',
                $item_post_id
              ) );
              

              
              $pointfinder_order_pricesign = esc_attr(get_post_meta( $result_id, 'pointfinder_order_pricesign', true ));
              $pointfinder_order_listingtime = esc_attr(get_post_meta( $result_id, 'pointfinder_order_listingtime', true ));
              $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
              $pointfinder_order_recurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_recurring', true ));
              $pointfinder_order_listingtime = ($pointfinder_order_listingtime == '') ? 0 : $pointfinder_order_listingtime ;

              $pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpid', true )); 

            
              $total_package_price =  number_format($pointfinder_order_price, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands);

              
              $paymentName = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindert2d'));

              $setup20_paypalsettings_paypal_api_packagename_basic = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename_basic','',esc_html__('Basic Listing Payment','pointfindert2d'));
              $setup20_paypalsettings_paypal_api_packagename_featured = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename_featured','',esc_html__('Featured Listing Payment','pointfindert2d'));

              if($pointfinder_order_listingpid == 2){
                $packtype = 'featured';
              }else{
                $packtype = 'basic';
              }
              
              $apipackage_name = ($packtype == 'basic')? $setup20_paypalsettings_paypal_api_packagename_basic : $setup20_paypalsettings_paypal_api_packagename_featured;

              
              

              

              $requestParams = array(
                 'RETURNURL' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_rec', 
                 'CANCELURL' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_cancel'
              );

              

              $orderParams = array(
                   'PAYMENTREQUEST_0_AMT' => $total_package_price,
                   'PAYMENTREQUEST_0_CURRENCYCODE' => $paypal_price_unit,
                   'PAYMENTREQUEST_0_ITEMAMT' => $total_package_price,
                   'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
                   'PAYMENTREQUEST_0_CUSTOM' => $item_post_id,
                );

              if ($pointfinder_order_recurring == 1) {
                $orderParams['L_BILLINGTYPE0'] = 'RecurringPayments';
                $orderParams['L_BILLINGAGREEMENTDESCRIPTION0'] = sprintf(
                  esc_html__('%s / %s / Recurring: %s%s per %s days / For: (%s) %s','pointfindert2d'),
                  $paymentName,
                  $apipackage_name,
                  $total_package_price,
                  $pointfinder_order_pricesign,
                  $pointfinder_order_listingtime,
                  $item_post_id,
                  get_the_title($item_post_id)
                  );
              }

                
              $item_arr = array(
                 'L_PAYMENTREQUEST_0_NAME0' => $paymentName,
                 'L_PAYMENTREQUEST_0_DESC0' => $apipackage_name,
                 'L_PAYMENTREQUEST_0_AMT0' => $total_package_price,
                 'L_PAYMENTREQUEST_0_QTY0' => '1',
                 'L_PAYMENTREQUEST_0_ITEMCATEGORY0' => 'Digital',
              );
              
              
              $infos = array();
              $infos['USER'] = $paypal_api_user;
              $infos['PWD'] = $paypal_api_pwd;
              $infos['SIGNATURE'] = $paypal_api_signature;
              if($paypal_sandbox == 1){$sandstatus = true;}else{$sandstatus = false;}
                
              $paypal = new Paypal($infos,$sandstatus);
              $response = $paypal -> request('SetExpressCheckout',$requestParams + $orderParams + $item_arr);
				
				$Amount = $pointfinder_order_price;
				
				$MerchantID = $paypal_api_user;

				$Description = 'بابت ثبت آگهی';  // Required
				
				$CallbackURL = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_rec&PAYERID='.$user_id;  // Required
				
				
				// URL also Can be https://ir.zarinpal.com/pg/services/WebGate/wsdl
				$client = new SoapClient('https://de.zarinpal.com/pg/services/WebGate/wsdl', array('encoding' => 'UTF-8')); 
				
				$response = $client->PaymentRequest(
									array(
											'MerchantID' 	=> $MerchantID,
											'Amount' 	=> $Amount,
											'Description' 	=> $Description,
											'CallbackURL' 	=> $CallbackURL
										)
				);
				
				//Redirect to URL You can do it also by creating a form
				/*if($response->Status == 100)
				{
					Header('Location: https://www.zarinpal.com/pg/StartPay/'.$response->Authority);
				} else {
					echo'ERR: '.$response->Status;
				}*/
				
				

              unset($paypal);
              if(!$response){ 
                $msg_output .= esc_html__( 'Error: No Response', 'pointfindert2d' ).'<br>';
                $icon_processout = 485;
                /*$errorval .= $paypal->getErrors();*/
              }
              


              
              if($response->Status == 100) { 
                $token = $response->Authority; 
                
                update_post_meta($result_id, 'pointfinder_order_token', $token ); 

                /*Create a payment record for this process */
                PF_CreatePaymentRecord(
                    array(
                    'user_id' =>  $user_id,
                    'item_post_id'  =>  $item_post_id,
                    'order_post_id' =>  $result_id,
                    'response'  =>  $response,
                    'token' =>  $token,
                    'processname' =>  'SetExpressCheckout',
                    'status'  =>  100,
                    )
                  );
              

					//if($paypal_sandbox == 1)
						$pfreturn_url = 'https://www.zarinpal.com/pg/StartPay/' . $token .'/ZarinGate';
					//else
						//$pfreturn_url = 'https://www.zarinpal.com/pg/StartPay/' . $token;
               
                
                $msg_output .= esc_html__('Payment process is ok. Please wait redirection.','pointfindert2d');

              }else{
                /*Create a payment record for this process */
           
                PF_CreatePaymentRecord(
                    array(
                    'user_id' =>  $user_id,
                    'item_post_id'  =>  $item_post_id,
                    'order_post_id' =>  $result_id,
                    'response'  =>  $response,
                    'token' =>  '',
                    'processname' =>  'SetExpressCheckout',
                    'status'  =>  $response->Status,
                    )
                  );
                $msg_output .= esc_html__( 'Error: Not Success', 'pointfindert2d' ).'<br>';
                if (isset($response['L_SHORTMESSAGE0'])) {
                 $msg_output .= '<small>'.$response['L_SHORTMESSAGE0'].'</small><br/>';
                }
                if (isset($response['L_LONGMESSAGE0'])) {
                 $msg_output .= '<small>'.$response['L_LONGMESSAGE0'].'</small><br/>';
                }
                $icon_processout = 485;
                
              }

            }else{
              $msg_output .= esc_html__('Wrong item ID (It is not your item!). Payment process is stopped.','pointfindert2d');
              $icon_processout = 485;
            }
          }
        }else{
          $msg_output .= esc_html__('Wrong item ID.','pointfindert2d');
          $icon_processout = 485;
        }
      }else{
        $msg_output .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
        $icon_processout = 485;
      }

      $output_html = '';
      $output_html .= '<div class="golden-forms wrapper mini" style="height:200px">';
      $output_html .= '<div id="pfmdcontainer-overlay" class="pftrwcontainer-overlay">';
      
      $output_html .= "<div class='pf-overlay-close'><i class='pfadmicon-glyph-707'></i></div>";
      $output_html .= "<div class='pfrevoverlaytext'><i class='pfadmicon-glyph-".$icon_processout."'></i><span>".$msg_output."</span></div>";
      
      $output_html .= '</div>';
      $output_html .= '</div>';    
      if ($icon_processout == 485) {  
        echo json_encode( array( 'process'=>false, 'mes'=>$output_html, 'returnurl' => $pfreturn_url));
      }else{
        echo json_encode( array( 'process'=>true, 'mes'=>'', 'returnurl' => $pfreturn_url));
      }
		break;

    case 'creditcardstripe':
       
      $icon_processout = 62;
      $msg_output = $pfreturn_url = '';
      $current_user = wp_get_current_user();
      $user_id = $current_user->ID;

      if($user_id != 0){

        if ($item_post_id != '') {

          $setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');

          /*Check if item user s item*/
          global $wpdb;

          $result = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s", $item_post_id,$user_id,$setup3_pointposttype_pt1) );
          
          if (is_array($result) && count($result)>0) {  
            
            if ($result[0]->ID == $item_post_id) {

              $setup20_stripesettings_decimals = PFSAIssetControl('setup20_stripesettings_decimals','','2');
              $setup20_stripesettings_publishkey = PFSAIssetControl('setup20_stripesettings_publishkey','','');
              $setup20_stripesettings_currency = PFSAIssetControl('setup20_stripesettings_currency','','USD');
              $setup20_stripesettings_sitename = PFSAIssetControl('setup20_stripesettings_sitename','','');
              $user_email = $current_user->user_email;

              /*Meta for order*/
              $result_id = $wpdb->get_var( $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s", 'pointfinder_order_itemid',$item_post_id) );

              $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));

              $pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpid', true )); 

              if ($setup20_stripesettings_decimals == 0) {
                $total_package_price =  $pointfinder_order_price;
              }else{
                $total_package_price =  $pointfinder_order_price.'00';
              }
              
              
              $paymentName = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindert2d'));

              $setup20_paypalsettings_paypal_api_packagename_basic = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename_basic','',esc_html__('Basic Listing Payment','pointfindert2d'));
              $setup20_paypalsettings_paypal_api_packagename_featured = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename_featured','',esc_html__('Featured Listing Payment','pointfindert2d'));

              if($pointfinder_order_listingpid == 2){
                $packtype = 'featured';
              }else{
                $packtype = 'basic';
              }
              
              $apipackage_name = ($packtype == 'basic')? $setup20_paypalsettings_paypal_api_packagename_basic : $setup20_paypalsettings_paypal_api_packagename_featured;

            }else{
              $msg_output .= esc_html__('Wrong item ID (It is not your item!). Payment process is stopped.','pointfindert2d');
              $icon_processout = 485;
            }
          }
        }else{
          $msg_output .= esc_html__('Wrong item ID.','pointfindert2d');
          $icon_processout = 485;
        }
      }else{
        $msg_output .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
        $icon_processout = 485;
      }


      $output_html = '';
      $output_html .= '<div class="golden-forms wrapper mini" style="height:200px">';
      $output_html .= '<div id="pfmdcontainer-overlay" class="pftrwcontainer-overlay">';
      
      $output_html .= "<div class='pf-overlay-close'><i class='pfadmicon-glyph-707'></i></div>";
      $output_html .= "<div class='pfrevoverlaytext'><i class='pfadmicon-glyph-".$icon_processout."'></i><span>".$msg_output."</span></div>";
      
      $output_html .= '</div>';
      $output_html .= '</div>';    

      if ($icon_processout == 485) {  
        echo json_encode( array( 'process'=>false, 'mes'=>$output_html, 'returnurl' => ''));
      }else{
        echo json_encode( array( 'process'=>true, 'name'=>$setup20_stripesettings_sitename, 'description'=>$apipackage_name, 'amount' => $total_package_price,'key'=>$setup20_stripesettings_publishkey,'email'=>$user_email,'currency'=>$setup20_stripesettings_currency));
      }
    break;

    case 'stripepayment':
      
      $icon_processout = 62;
      $msg_output = $pfreturn_url = '';
      $current_user = wp_get_current_user();
      $user_id = $current_user->ID;

      if($user_id != 0){

        if ($item_post_id != '') {

          $setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');

          /*Check if item user s item*/
          global $wpdb;

          $result = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s", $item_post_id,$user_id,$setup3_pointposttype_pt1) );
          
          if (is_array($result) && count($result)>0) {  
            
            if ($result[0]->ID == $item_post_id) {

              $setup4_membersettings_dashboard = PFSAIssetControl('setup4_membersettings_dashboard','',site_url());
              $setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
              $pfmenu_perout = PFPermalinkCheck();

              $order_post_id = $wpdb->get_var( $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %d", 'pointfinder_order_itemid',$item_post_id) );

              $setup20_stripesettings_decimals = PFSAIssetControl('setup20_stripesettings_decimals','','2');
              $user_email = $current_user->user_email;

              /*Meta for order*/
              $result_id = $wpdb->get_var( $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s", 'pointfinder_order_itemid',$item_post_id) );

              $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));

              $pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpid', true )); 

              if ($setup20_stripesettings_decimals == 0) {
                $total_package_price =  $pointfinder_order_price;
                $total_package_price_ex =  $pointfinder_order_price;
              }else{
                $total_package_price =  $pointfinder_order_price.'00';
                $total_package_price_ex =  $pointfinder_order_price.'.00';
              }
              
              
              $paymentName = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindert2d'));

              $setup20_paypalsettings_paypal_api_packagename_basic = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename_basic','',esc_html__('Basic Listing Payment','pointfindert2d'));
              $setup20_paypalsettings_paypal_api_packagename_featured = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename_featured','',esc_html__('Featured Listing Payment','pointfindert2d'));

              if($pointfinder_order_listingpid == 2){
                $packtype = 'featured';
              }else{
                $packtype = 'basic';
              }
              
              $apipackage_name = ($packtype == 'basic')? $setup20_paypalsettings_paypal_api_packagename_basic : $setup20_paypalsettings_paypal_api_packagename_featured;

              $setup20_stripesettings_secretkey = PFSAIssetControl('setup20_stripesettings_secretkey','','');
              $setup20_stripesettings_publishkey = PFSAIssetControl('setup20_stripesettings_publishkey','','');
              $setup20_stripesettings_currency = PFSAIssetControl('setup20_stripesettings_currency','','USD');

              require_once( get_template_directory().'/admin/core/stripe/init.php');

              $stripe = array(
                "secret_key"      => $setup20_stripesettings_secretkey,
                "publishable_key" => $setup20_stripesettings_publishkey
              );

              \Stripe\Stripe::setApiKey($stripe['secret_key']);
              

              $token  = $_POST['token'];
              $token = PFCleanArrayAttr('PFCleanFilters',$token);
         
              $charge = '';
              if ($total_package_price != 0) {
                try {

                  $charge = \Stripe\Charge::create(array(
                    'amount'   => $total_package_price,
                    'currency' => ''.$setup20_stripesettings_currency.'',
                    'source'  => $token['id'],
                    'description' => "Charge for ".$apipackage_name.'(ItemID: '.$item_post_id.' / UserID: '.$user_id.')'
                  ));

                  if ($charge->status == 'succeeded') {
                    PF_CreatePaymentRecord(
                      array(
                      'user_id' =>  $user_id,
                      'item_post_id'  =>  $item_post_id,
                      'order_post_id' => $order_post_id,
                      'processname' =>  'DoExpressCheckoutPaymentStripe',
                      'status'  =>  $charge->status
                      )
                    );

                    $setup31_userlimits_userpublish = PFSAIssetControl('setup31_userlimits_userpublish','','0');
                    $publishstatus = ($setup31_userlimits_userpublish == 1) ? 'publish' : 'pendingapproval' ;

                    wp_update_post(array('ID' => $item_post_id,'post_status' => $publishstatus) );
                    wp_reset_postdata();
                    wp_update_post(array('ID' => $order_post_id,'post_status' => 'completed') );
                    wp_reset_postdata();

                    $admin_email = get_option( 'admin_email' );
                    $setup33_emailsettings_mainemail = PFMSIssetControl('setup33_emailsettings_mainemail','',$admin_email);
                    $mail_item_title = get_the_title($item_post_id);
                    
                    pointfinder_mailsystem_mailsender(
                      array(
                        'toemail' => $user_email,
                            'predefined' => 'paymentcompleted',
                            'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price_ex.'('.$setup20_stripesettings_currency.')','packagename' => $apipackage_name),
                        )
                      );

                    pointfinder_mailsystem_mailsender(
                      array(
                        'toemail' => $setup33_emailsettings_mainemail,
                            'predefined' => 'newpaymentreceived',
                            'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price_ex.'('.$setup20_stripesettings_currency.')','packagename' => $apipackage_name),
                        )
                      );


                    $msg_output .= esc_html__('Payment is successful.','pointfindert2d');
                  }

                } catch(\Stripe\Error\Card $e) {
                  if(isset($e)){
                    $error_mes = json_decode($e->httpBody,true);
                    $icon_processout = 485;
                    $msg_output = (isset($error_mes['error']['message']))? $error_mes['error']['message']:'';
                    if (empty($msg_output)) {
                      $msg_output .= esc_html__('Payment not completed.','pointfindert2d');
                    }
                  }
                }
              }else{
                $msg_output .= esc_html__('Price can not be 0!). Payment process is stopped.','pointfindert2d');
                $icon_processout = 485;
              }
              
              

            }else{
              $msg_output .= esc_html__('Wrong item ID (It is not your item!). Payment process is stopped.','pointfindert2d');
              $icon_processout = 485;
            }
          }
        }else{
          $msg_output .= esc_html__('Wrong item ID.','pointfindert2d');
          $icon_processout = 485;
        }
      }else{
        $msg_output .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
        $icon_processout = 485;
      }

      if ($icon_processout != 485) {
        $overlar_class = ' pfoverlayapprove';
      }else{
        $overlar_class = '';
      }

      $output_html = '';
      $output_html .= '<div class="golden-forms wrapper mini" style="height:200px">';
      $output_html .= '<div id="pfmdcontainer-overlay" class="pftrwcontainer-overlay">';
      
      $output_html .= "<div class='pf-overlay-close'><i class='pfadmicon-glyph-707'></i></div>";
      $output_html .= "<div class='pfrevoverlaytext".$overlar_class."'><i class='pfadmicon-glyph-".$icon_processout."'></i><span>".$msg_output."</span></div>";
      
      $output_html .= '</div>';
      $output_html .= '</div>';    

      if ($icon_processout == 485) {  
        echo json_encode( array( 'process'=>false, 'mes'=>$output_html, 'returnurl' => ''));
      }else{
        echo json_encode( array( 'process'=>true, 'mes'=>$output_html, 'returnurl' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems'));
      }

    break;
	}
die();
}

?>