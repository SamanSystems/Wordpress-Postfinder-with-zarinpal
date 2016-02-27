<?php 
/**********************************************************************************************************************************
*
* User Dashboard Page - Functions
* 
* Author: Webbu Design
***********************************************************************************************************************************/


/**
*Start: Update & Add function for new item
**/
	function PFU_AddorUpdateRecord($params = array())
	{	

		$defaults = array( 
	        'post_id' => '',
	        'order_post_id' => '',
	        'order_title' => '',
			'vars' => array(),
			'user_id' => ''
	    );

	    $params = array_merge($defaults, $params);


	    $vars = $params['vars'];

	    $user_id = $params['user_id'];
	    $returnval = array();
	    $returnval['sccval'] = '';
	    $returnval['errorval'] = '';
	    $returnval['post_id'] = '';

		$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
		$setup31_userlimits_userpublish = PFSAIssetControl('setup31_userlimits_userpublish','','0');
		$setup31_userpayments_priceperitem = PFSAIssetControl('setup31_userpayments_priceperitem','','0');
		$setup31_userlimits_userpublishonedit = PFSAIssetControl('setup31_userlimits_userpublishonedit','','0');
		$setup31_userpayments_pricefeatured = PFSAIssetControl('setup31_userpayments_pricefeatured','','0');
		$setup31_userpayments_featuredoffer = PFSAIssetControl('setup31_userpayments_featuredoffer','','0');

		$autoexpire_create = 0;

		if($params['post_id'] == ''){
			$userpublish = ($setup31_userlimits_userpublish == 0) ? 'pendingapproval' : 'publish' ;

			if ($setup31_userpayments_priceperitem == 0) {
				if ($setup31_userpayments_featuredoffer == 1) {
					$featured_itemboxfield = (!empty($vars['featureditembox']))? $vars['featureditembox']: 'off';
					if ($setup31_userpayments_pricefeatured == 0) {
						$pricestatus = 'publish';
					} else {
						if($featured_itemboxfield == 'on'){
							$pricestatus = 'pendingpayment';
						}else{
							$pricestatus = 'publish';
							$autoexpire_create = 1;
						}
					}
					
				} else {
					$pricestatus = 'publish';
				}
				
			} else {
				$pricestatus = 'pendingpayment';
			}


			if($userpublish == 'publish' && $pricestatus == 'publish'){
				$post_status = 'publish';
			}elseif($userpublish == 'publish' && $pricestatus == 'pendingpayment'){
				$post_status = 'pendingpayment';
			}elseif($userpublish == 'pendingapproval' && $pricestatus == 'publish'){
				$post_status = 'pendingapproval';
			}elseif($userpublish == 'pendingapproval' && $pricestatus == 'pendingpayment'){
				$post_status = 'pendingpayment';
			}



		}else{
			/**
			*Rules;
			*	- If post editing
			*	- If post status not pending payment create a post meta item edited.
			*		- If post status pending approval and not approved before. don't create edit record for order meta.
			*	- If post status pending payment don't change status and not create record for edit.
			**/
			$checkemail_poststatus = get_post_status( $params['post_id']);
			if($checkemail_poststatus != 'pendingpayment'){
				if($checkemail_poststatus != 'pendingapproval'){
					$post_status = ($setup31_userlimits_userpublishonedit == 0) ? 'pendingapproval' : 'publish' ;
				}else{
					$post_status = 'pendingapproval';
					/* - Creating record for process system. */
					PFCreateProcessRecord(
						array( 
					        'user_id' => $user_id,
					        'item_post_id' => $params['post_id'],
							'processname' => esc_html__('Pending Approval post edited by USER.','pointfindert2d')
					    )
					);
				}

				$myorder_id = PFU_GetOrderID($params['post_id'],1);

				if (PFcheck_postmeta_exist('pointfinder_order_itemedit',$myorder_id)) { 
					update_post_meta($myorder_id, 'pointfinder_order_itemedit', 1 );	
				}else{
					if(PFcheck_postmeta_exist('pointfinder_order_datetime_approval',$myorder_id)){
						add_post_meta($myorder_id, 'pointfinder_order_itemedit', 1 );
					}
				};

			}else{
				$post_status = 'pendingpayment';
				/* - Creating record for process system. */
				PFCreateProcessRecord(
					array( 
				        'user_id' => $user_id,
				        'item_post_id' => $params['post_id'],
						'processname' => esc_html__('Pending Payment post edited by USER.','pointfindert2d')
				    )
				);

			}
			if($checkemail_poststatus == 'publish'){
				/* - Creating record for process system. */
				PFCreateProcessRecord(
					array( 
				        'user_id' => $user_id,
				        'item_post_id' => $params['post_id'],
						'processname' => esc_html__('Published post edited by USER.','pointfindert2d')
				    )
				);
			}

		}


		$arg = array(
		  'ID'=> $params['post_id'],
		  'post_type'    => $setup3_pointposttype_pt1,
		  'post_title'    => esc_html($vars['item_title']),
		  'post_content'  => esc_html($vars['item_desc']),
		  'post_status'   => $post_status,
		  'post_author'   => $user_id,
		);

		if ($params['post_id']!='') {
			$update_work = "ok";
			wp_update_post($arg);
			$post_id = $params['post_id'];
		}else{
			$update_work = "not";
			$post_id = wp_insert_post($arg);
		}

		
		/** 
		*Send email to the user;
		*	- Check $post_id for edit
		*	- Don't send email if direct publish enabled on edit.
		*	- Don't send email if edited post status pendingpayment & pendingapproval
		**/
			if ($params['post_id'] != '') {
				
				if($checkemail_poststatus != 'pendingpayment' && $checkemail_poststatus != 'pendingapproval'){
					if ($setup31_userlimits_userpublishonedit == 0) {
						$user_email_action = 'send';
					}else{
						$user_email_action = 'cancel';
					}
				}else{
					$user_email_action = 'cancel';
				}
				
			}elseif ($params['post_id'] == '') {
				$user_email_action = 'send';
			}

			if($user_email_action == 'send'){

				if ($post_status == 'publish') {
					$email_subject = 'itemapproved';
				}elseif ($post_status == 'pendingpayment') {
					$email_subject = 'waitingpayment';
				}elseif ($post_status == 'pendingapproval') {
					$email_subject = 'waitingapproval';
				}
				$user_info = get_userdata( $user_id );
				
				pointfinder_mailsystem_mailsender(
					array(
						'toemail' => $user_info->user_email,
				        'predefined' => $email_subject,
				        'data' => array('ID' => $post_id,'title'=>esc_html($vars['item_title'])),
						)
					);
			}
		

		/**
		*Send email to the admin;
		*	- System will not send email if disabled by PF Mail System
		*	- Don't send email if edited post status pendingpayment & pendingapproval
		**/

			 $admin_email = get_option( 'admin_email' );
			 $setup33_emailsettings_mainemail = PFMSIssetControl('setup33_emailsettings_mainemail','',$admin_email);
			 

			 if ($setup33_emailsettings_mainemail != '') {
			 	
			 	if ($params['post_id']!='') {
			 		$adminemail_subject = 'updateditemsubmission';
			 		$setup33_emaillimits_adminemailsafteredit = PFMSIssetControl('setup33_emaillimits_adminemailsafteredit','','1');
			 		if($checkemail_poststatus != 'pendingpayment' && $checkemail_poststatus != 'pendingapproval'){
				 		if ($setup33_emaillimits_adminemailsafteredit == 1) {
				 			$admin_email_action = 'send';
				 		}else{
				 			$admin_email_action = 'cancel';
				 		}
				 	}else{
				 		$admin_email_action = 'cancel';
				 	}
			 	}else{
			 		$adminemail_subject = 'newitemsubmission';
			 		$setup33_emaillimits_adminemailsafterupload = PFMSIssetControl('setup33_emaillimits_adminemailsafterupload','','1');
			 		if ($setup33_emaillimits_adminemailsafterupload == 1) {
			 			$admin_email_action = 'send';
			 		}else{
			 			$admin_email_action = 'cancel';
			 		}
			 	}

			 	if ($admin_email_action == 'send') {
			 		
			 		pointfinder_mailsystem_mailsender(
					array(
						'toemail' => $setup33_emailsettings_mainemail,
				        'predefined' => $adminemail_subject,
				        'data' => array('ID' => $post_id,'title'=>esc_html($vars['item_title'])),
						)
					);
			 	}
			 }
		
		$returnval['post_id'] = $post_id;

		/** Start: Taxonomies **/

			/*Listing Types*/
			if(isset($vars['pfupload_listingtypes'])){
				if(PFControlEmptyArr($vars['pfupload_listingtypes'])){
					$pftax_terms = $vars['pfupload_listingtypes'];
				}else if(!PFControlEmptyArr($vars['pfupload_listingtypes']) && isset($vars['pfupload_listingtypes'])){
					$pftax_terms = array($vars['pfupload_listingtypes']);
				}
				wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderltypes');
			}


			/*Item Types*/
			if(isset($vars['pfupload_itemtypes'])){
				if(PFControlEmptyArr($vars['pfupload_itemtypes'])){
					$pftax_terms = $vars['pfupload_itemtypes'];
				}else if(!PFControlEmptyArr($vars['pfupload_itemtypes']) && isset($vars['pfupload_itemtypes'])){
					$pftax_terms = array($vars['pfupload_itemtypes']);
				}
				wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderitypes');
			}


			/*Locations Types*/
			if(isset($vars['pfupload_locations'])){
				if(PFControlEmptyArr($vars['pfupload_locations'])){
					$pftax_terms = $vars['pfupload_locations'];
				}else if(!PFControlEmptyArr($vars['pfupload_locations']) && isset($vars['pfupload_locations'])){
					$pftax_terms = array($vars['pfupload_locations']);
				}
				wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderlocations');
			}


			/*Features Types*/
			if(isset($vars['pffeature'])){				
				if(PFControlEmptyArr($vars['pffeature'])){
					$pftax_terms = $vars['pffeature'];
				}else if(!PFControlEmptyArr($vars['pffeature']) && isset($vars['pffeature'])){
					$pftax_terms = array($vars['pffeature']);
				}
				wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderfeatures');
			}

		/** End: Taxonomies **/


		/** Start: Opening Hours **/
			$setup3_modulessetup_openinghours = PFSAIssetControl('setup3_modulessetup_openinghours','','0');
			$setup3_modulessetup_openinghours_ex = PFSAIssetControl('setup3_modulessetup_openinghours_ex','','1');

			if($setup3_modulessetup_openinghours == 1 && $setup3_modulessetup_openinghours_ex == 0){

				$i = 1;
				while ( $i <= 7) {
					if(isset($vars['o'.$i])){
						update_post_meta($post_id, 'webbupointfinder_items_o_o'.$i, $vars['o'.$i]);	
					}
					$i++;
				}

			}elseif($setup3_modulessetup_openinghours == 1 && $setup3_modulessetup_openinghours_ex == 1){

				$i = 1;
				while ( $i <= 1) {
					if(isset($vars['o'.$i])){
						update_post_meta($post_id, 'webbupointfinder_items_o_o'.$i, $vars['o'.$i]);	
					}
					$i++;
				}

			}elseif($setup3_modulessetup_openinghours == 1 && $setup3_modulessetup_openinghours_ex == 2){

				$i = 1;
				while ( $i <= 7) {
					if(isset($vars['o'.$i.'_1']) && isset($vars['o'.$i.'_2'])){
						update_post_meta($post_id, 'webbupointfinder_items_o_o'.$i, $vars['o'.$i.'_1'].'-'.$vars['o'.$i.'_2']);	
					}
					$i++;
				}

			}
		/** End: Opening Hours **/


		
		/** Start: Post Meta **/

			/*Featured*/
				if(!empty($vars['featureditembox'])){
					
					if($vars['featureditembox'] == 'on'){
						update_post_meta($post_id, 'webbupointfinder_item_featuredmarker', 1);	
					}else{
						update_post_meta($post_id, 'webbupointfinder_item_featuredmarker', 0);	
					}
				}else{
					if (!PFcheck_postmeta_exist('webbupointfinder_item_featuredmarker',$post_id)) { 
						add_post_meta ($post_id, 'webbupointfinder_item_featuredmarker', 0);
					}; 
				}
			

			/*Location*/
			if(isset($vars['pfupload_lat']) && isset($vars['pfupload_lng'])){
				update_post_meta($post_id, 'webbupointfinder_items_location', $vars['pfupload_lat'].','.$vars['pfupload_lng']);	
			}

			/*Addrress*/
			if(isset($vars['pfupload_address'])){
				update_post_meta($post_id, 'webbupointfinder_items_address', $vars['pfupload_address']);	
			}


			/*Message to Reviewer*/
			if (isset($vars['item_mesrev'])) {
				if (PFcheck_postmeta_exist('webbupointfinder_items_mesrev',$post_id)) { 
					$old_mesrev = get_post_meta($post_id, 'webbupointfinder_items_mesrev', true);
					$old_mesrev = json_decode($old_mesrev,true);

					if (is_array($old_mesrev)) {
						$old_mesrev = PFCleanArrayAttr('PFCleanFilters',$old_mesrev);
					} 

					$old_mesrev[] = array('message' => $vars['item_mesrev'], 'date' => date("Y-m-d H:i:s"));
					$old_mesrev = json_encode($old_mesrev);

					update_post_meta($post_id, 'webbupointfinder_items_mesrev', $old_mesrev);	
				}else{

					$old_mesrev = array();
					$old_mesrev[] = array('message' => $vars['item_mesrev'], 'date' => date("Y-m-d H:i:s"));
					$old_mesrev = json_encode($old_mesrev);

					add_post_meta ($post_id, 'webbupointfinder_items_mesrev', $old_mesrev);
				}; 
			}

			/** Start: Featured Video **/
			if(isset($_POST['pfuploadfeaturedvideo'])){
				update_post_meta($post_id, 'webbupointfinder_item_video', esc_textarea($_POST['pfuploadfeaturedvideo']));	
			}
			/** End: Featured Video **/

			/*Custom fields loop*/
				$pfstart = PFCheckStatusofVar('setup1_slides');
				$setup1_slides = PFSAIssetControl('setup1_slides','','');

				if($pfstart == true){

					foreach ($setup1_slides as &$value) {

			          $customfield_statuscheck = PFCFIssetControl('setupcustomfields_'.$value['url'].'_frontupload','','0');
			          $available_fields = array(1,2,3,4,5,7,8,9,14);
			          
			          if(in_array($value['select'], $available_fields) && $customfield_statuscheck != 0){
			           	 
						if(isset($vars[''.$value['url'].''])){
						
							if(!is_array($vars[''.$value['url'].''])){ 
								update_post_meta($post_id, 'webbupointfinder_item_'.$value['url'], $vars[''.$value['url'].'']);
							}else{
								if(PFcheck_postmeta_exist('webbupointfinder_item_'.$value['url'],$post_id)){
									delete_post_meta($post_id, 'webbupointfinder_item_'.$value['url']);
								};

								foreach ($vars[''.$value['url'].''] as $val) {
									add_post_meta ($post_id, 'webbupointfinder_item_'.$value['url'], $val);
								};

							};
						}else{
							if (PFcheck_postmeta_exist('webbupointfinder_item_'.$value['url'],$post_id)) { 
								delete_post_meta($post_id, 'webbupointfinder_item_'.$value['url']);
							}; 
						};

			          };
			          
			        };
				};
			

		/** End: Post Meta **/
		$setup4_submitpage_status_old = PFSAIssetControl('setup4_submitpage_status_old','','0');

		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9') !== false || $setup4_submitpage_status_old == 1) {
			/** Start: Post Featured Image & Other Images (IF This is old IE) **/
				$allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');

				foreach ($_FILES as $key => $array) {
					
					if ( isset($_FILES[$key])) {   
						if ( $_FILES[$key]['error'] <= 0) {      
						    if(in_array($_FILES[$key]['type'], $allowed_file_types)) {
						      $newupload = pft_insert_attachment($key);
						      if($key != 'pfuploadfeaturedimg'){
						      	 add_post_meta($post_id, 'webbupointfinder_item_images', $newupload);	
						      }else{
							 	 set_post_thumbnail( $post_id, $newupload );
							  }
							}
						}
					}
					
				}
			
			/** End: Post Featured Image & Other Images **/
		}elseif ($setup4_submitpage_status_old == 0){
			if (!empty($vars['pfuploadimagesrc'])) {
				if ($params['order_post_id'] == '') {
					$uploadimages = pfstring2BasicArray($vars['pfuploadimagesrc']);
					$i = 0;
					foreach ($uploadimages as $uploadimage) {
						delete_post_meta( $uploadimage, 'pointfinder_delete_unused');
						if($i != 0){
							 add_post_meta($post_id, 'webbupointfinder_item_images', $uploadimage);	
						}else{
							 set_post_thumbnail( $post_id, $uploadimage );
						}
						$i++;
					}
				}
			}
		}
		


		/** Orders: Post Info **/
		if ($params['order_post_id'] == '') {

			srand(pfmake_seed());

			$setup31_userpayments_orderprefix = PFSAIssetControl('setup31_userpayments_orderprefix','','PF');
			
			$order_post_title = ($params['order_title'] != '') ? $params['order_title'] : $setup31_userpayments_orderprefix.rand();

			if ($setup31_userpayments_priceperitem != 0) {
				$order_post_status = 'pendingpayment';
			}elseif($setup31_userpayments_priceperitem == 0){
				$order_post_status = 'completed';
			}else{
				$order_post_status = 'pendingpayment';
			}

			$arg_order = array(
			  'post_type'    => 'pointfinderorders',
			  'post_title'	=> $order_post_title,
			  'post_status'   => $order_post_status,
			  'post_author'   => $user_id,
			);

			$order_post_id = wp_insert_post($arg_order);


			/*Order Meta*/
			$ordered_package_name = '';
			$ordered_package_id = 1;
			$order_total_price = $setup31_userpayments_priceperitem;

			$ordered_package_name = $setup20_paypalsettings_paypal_api_packagename_basic = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename_basic','',esc_html__('Basic Listing Payment','pointfindert2d'));

			$order_detailed_price = array($setup20_paypalsettings_paypal_api_packagename_basic => $setup31_userpayments_priceperitem);

			$setup31_userpayments_timeperitem = PFSAIssetControl('setup31_userpayments_timeperitem','','');
			$order_recurring = (!empty($vars['recurringlistingitem']) && $setup31_userpayments_priceperitem != 0 ) ? '1' : '0' ;

			if(!empty($vars['featureditembox'])){
				if($vars['featureditembox'] == 'on'){
					$setup31_userpayments_pricefeatured = PFSAIssetControl('setup31_userpayments_pricefeatured','','');
					$setup31_userpayments_featuredoffer = PFSAIssetControl('setup31_userpayments_featuredoffer','','1');
					$setup31_userpayments_titlefeatured = PFSAIssetControl('setup31_userpayments_titlefeatured','',esc_html__('Featured Item','pointfindert2d'));
					$ordered_package_name = $setup20_paypalsettings_paypal_api_packagename_featured = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename_featured','',esc_html__('Featured Listing Payment','pointfindert2d'));
					$ordered_package_id = 2;
					$order_total_price = $order_total_price + $setup31_userpayments_pricefeatured;
					$order_detailed_price[$setup20_paypalsettings_paypal_api_packagename_featured] = $setup31_userpayments_pricefeatured;

					$order_recurring = (!empty($vars['recurringlistingitem']) && ($setup31_userpayments_priceperitem != 0 || $setup31_userpayments_pricefeatured != 0)) ? '1' : '0' ;
				}

			}
			

			$setup20_paypalsettings_paypal_price_short = PFSAIssetControl('setup20_paypalsettings_paypal_price_short','','');

			

			add_post_meta($order_post_id, 'pointfinder_order_itemid', $post_id, true );	
			add_post_meta($order_post_id, 'pointfinder_order_userid', $user_id, true );	
			add_post_meta($order_post_id, 'pointfinder_order_recurring', $order_recurring, true );	
			add_post_meta($order_post_id, 'pointfinder_order_price', $order_total_price, true );	
			add_post_meta($order_post_id, 'pointfinder_order_detailedprice', json_encode($order_detailed_price), true );	
			add_post_meta($order_post_id, 'pointfinder_order_listingtime', $setup31_userpayments_timeperitem, true );	
			add_post_meta($order_post_id, 'pointfinder_order_listingpname', $ordered_package_name, true );	
			add_post_meta($order_post_id, 'pointfinder_order_listingpid', $ordered_package_id, true );	
			add_post_meta($order_post_id, 'pointfinder_order_pricesign', $setup20_paypalsettings_paypal_price_short, true );
			add_post_meta($order_post_id, 'pointfinder_order_bankcheck', '0');	


			/* Start: Add expire date if this item is ready to publish (free listing) */
			if($autoexpire_create == 1){
				$exp_date = date("Y-m-d H:i:s", strtotime("+".$setup31_userpayments_timeperitem." days"));
				$app_date = date("Y-m-d H:i:s");

				update_post_meta( $order_post_id, 'pointfinder_order_expiredate', $exp_date);
				update_post_meta( $order_post_id, 'pointfinder_order_datetime_approval', $app_date);

				if (PFcheck_postmeta_exist('pointfinder_order_bankcheck',$order_post_id)) { 
					update_post_meta($order_post_id, 'pointfinder_order_bankcheck', '0');	
				};
				global $wpdb;
				$wpdb->UPDATE($wpdb->posts,array('post_status' => 'completed'),array('ID' => $order_post_id));
				
				/* - Creating record for process system. */
				PFCreateProcessRecord(
					array( 
				        'user_id' => $user_id,
				        'item_post_id' => $post_id,
						'processname' => esc_html__('Item status changed to Publish by Autosystem','pointfindert2d')
				    )
				);

			
			}
			/* End: Add expire date if this item is ready to publish (free listing) */



			/* - Creating record for process system. */
			PFCreateProcessRecord(
				array( 
			        'user_id' => $user_id,
			        'item_post_id' => $post_id,
					'processname' => esc_html__('A new item uploaded by USER.','pointfindert2d')
			    )
			);	
			
		}
			
		/** Orders: Post Info **/
		if ($params['post_id'] == '') {
			$returnval['sccval'] = esc_html__('New item successfuly added.','pointfindert2d');
		}else{
			$returnval['sccval'] = esc_html__('Your item successfuly updated.','pointfindert2d');
		}
		
		

		return $returnval;
	}
/**
*End: Update & Add function for new item
**/
?>