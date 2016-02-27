<?php
/**********************************************************************************************************************************
*
* Orders post type detail pages.
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/

/**
*Enqueue Styles
**/
function pointfinder_orders_styles(){
	$screen = get_current_screen();
	if ($screen->post_type == 'pointfinderorders') {
		wp_register_style('metabox-custom.', get_template_directory_uri() . '/admin/core/css/metabox-custom.css', array(), '1.0', 'all');
		wp_enqueue_style('metabox-custom.'); 
	}
}
add_action('admin_enqueue_scripts','pointfinder_orders_styles' );

/**
*Start : Add Metaboxes
**/
	function pointfinder_orders_add_meta_box($post_type) {

		$screen = 'pointfinderorders';
		if ($post_type == $screen) {
			remove_meta_box( 'submitdiv', $screen,'side');
			remove_meta_box( 'slugdiv', $screen,'normal');
			remove_meta_box( 'mymetabox_revslider_0', $screen, 'normal' );
			add_meta_box(
				'pointfinder_orders_info',
				esc_html__( 'ORDER INFO', 'pointfindert2d' ),
				'pointfinder_orders_meta_box_orderinfo',
				$screen,
				'side',
				'high'
			);

			add_meta_box(
				'pointfinder_orders_trans',
				esc_html__( 'TRANSACTION HISTORY', 'pointfindert2d' ),
				'pointfinder_orders_meta_box_ordertrans',
				$screen,
				'normal',
				'core'
			);

			add_meta_box(
				'pointfinder_orders_process',
				esc_html__( 'PROCESS HISTORY', 'pointfindert2d' ),
				'pointfinder_orders_meta_box_orderprocess',
				$screen,
				'normal',
				'core'
			);

			add_meta_box(
				'pointfinder_orders_basicinfo',
				esc_html__( 'LISTING INFO', 'pointfindert2d' ),
				'pointfinder_orders_meta_box_order_basicinfo',
				$screen,
				'side',
				'core'
			);
		}

		
	}
	add_action( 'add_meta_boxes', 'pointfinder_orders_add_meta_box', 10,1);
/**
*End : Add Metaboxes
**/

function PFOrderTransArrW($value,$key){
	if (!is_array($value)) {
		echo '<li class="uppcase">'.$key.' : <div class="pforders-orderdetails-lbltext">'.$value.'</div></li>';
	}else{
		array_walk($value,"PFOrderTransArrW");
	}
	
}


/**
*Start : Order Info Content
**/
	function pointfinder_orders_meta_box_orderinfo( $post ) {

		$prderinfo_itemid = get_post_meta( $post->ID, 'pointfinder_order_itemid', true );
		$prderinfo_user = get_post_meta( $post->ID, 'pointfinder_order_userid', true );

		$current_post_status = get_post_status();

		if($current_post_status == 'completed'){
		    $prderinfo_statusorder = '<span class="pforders-orderdetails-lblcompleted">'.esc_html__('PAYMENT COMPLETED','pointfindert2d').'</span>';
		}elseif($current_post_status == 'pendingpayment'){
			$prderinfo_statusorder = '<span class="pforders-orderdetails-lblpending">'.esc_html__('PENDING PAYMENT','pointfindert2d').'</span>';
		}elseif($current_post_status == 'pfcancelled'){
			$prderinfo_statusorder = '<span class="pforders-orderdetails-lblcancel">'.esc_html__('CANCELLED','pointfindert2d').'</span>';
		}elseif($current_post_status == 'pfsuspended'){
			$prderinfo_statusorder = '<span class="pforders-orderdetails-lblpending">'.esc_html__('SUSPENDED','pointfindert2d').'</span>';
		}
		$itemnamex = get_the_title($prderinfo_itemid);

		$itemname = ($itemnamex!= false)? $itemnamex:esc_html__('Item Deleted','pointfindert2d');

		echo '<ul class="pforders-orderdetails-ul">';
			echo '<li>';
			esc_html_e( 'ORDER ID : ', 'pointfindert2d' );
			echo '<div class="pforders-orderdetails-lbltext">'.get_the_title().'</div>';
			echo '</li> ';

			echo '<li>';
			esc_html_e( 'ORDER STATUS : ', 'pointfindert2d' );
			echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_statusorder.'</div>';
			echo '</li> ';

			$userdata = get_user_by('id',$prderinfo_user);
			echo '<li>';
			esc_html_e( 'USER : ', 'pointfindert2d' );
			echo '<div class="pforders-orderdetails-lbltext"><a href="'.get_edit_user_link($prderinfo_user).'" target="_blank" title="'.esc_html__('Click for user details','pointfindert2d').'">'.$prderinfo_user.' - '.$userdata->nickname.'</a></div>';
			echo '</li> ';

			echo '<li>';
			esc_html_e( 'ITEM : ', 'pointfindert2d' );
			if($itemnamex!= false){
				echo '<div class="pforders-orderdetails-lbltext"><a href="'.get_edit_post_link($prderinfo_itemid).'" target="_blank" title="'.esc_html__('Click for open item','pointfindert2d').'">'.$prderinfo_itemid.' - '.$itemname.'</a></div>';
			}else{
				echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_itemid.' - '.$itemname.'</div>';
			}
			echo '</li> ';

		echo '</ul>';
	}
/**
*End : Order Info Content
**/

	function pointfinder_orders_meta_box_order_basicinfo( $post ) {

		$prderinfo_ordertime = PFU_GetPostOrderDate($post->ID);
		$prderinfo_recurring = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_recurring', true ));
		$prderinfo_order_total = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_price', true ));
		$prderinfo_order_totalsign = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_pricesign', true ));
		$prderinfo_order_time = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_listingtime', true ));
		$prderinfo_order_pname = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_listingpname', true ));
		$prderinfo_order_bankcheck = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_bankcheck', true ));
		$prderinfo_order_appdate = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_datetime_approval', true ));
		$prderinfo_order_expdate = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_expiredate', true ));

		$prderinfo_recurring_text = ($prderinfo_recurring == 1) ? esc_html__('Recurring Payment','pointfindert2d') : esc_html__('Direct Payment','pointfindert2d') ;

		if($prderinfo_order_bankcheck == 1){$prderinfo_recurring_text .= ' - '.esc_html__('Bank Transfer','pointfindert2d');}

		$setup20_paypalsettings_decimals = PFSAIssetControl('setup20_paypalsettings_decimals','','2');
		$setup20_paypalsettings_decimalpoint = PFSAIssetControl('setup20_paypalsettings_decimalpoint','','.');
		$setup20_paypalsettings_thousands = PFSAIssetControl('setup20_paypalsettings_thousands','',',');
		

		echo '<ul class="pforders-orderdetails-ul">';


			echo '<li>';
			esc_html_e( 'Order Package : ', 'pointfindert2d' );
			echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_order_pname.'</div>';
			echo '</li> ';

			echo '<li>';
			esc_html_e( 'Order Type : ', 'pointfindert2d' );
			echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_recurring_text.'</div>';
			echo '</li> ';

			echo '<li>';
			esc_html_e( 'Order Date : ', 'pointfindert2d' );
			echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_ordertime.'</div>';
			echo '</li> ';
			

			if ($prderinfo_order_appdate != '') {
				echo '<li>';
				esc_html_e( 'Approval Date : ', 'pointfindert2d' );
				echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_order_appdate.'</div>';
				echo '</li> ';
			}

			if ($prderinfo_order_expdate != '') {
				echo '<li>';
				esc_html_e( 'Expire Date : ', 'pointfindert2d' );
				echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_order_expdate.'</div>';
				echo '</li> ';
			}

			echo '<li>';
			esc_html_e( 'Order Total : ', 'pointfindert2d' );
			echo '<div class="pforders-orderdetails-lbltext">'.number_format($prderinfo_order_total, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands).$prderinfo_order_totalsign.'</div>';
			echo '</li> ';

			echo '<li>';
			esc_html_e( 'Listing Period : ', 'pointfindert2d' );
			echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_order_time.esc_html__(' days','pointfindert2d').'</div>';
			echo '</li> ';
			

		echo '</ul>';
	}

/**
*Start : Basic Listing Pack Content
**/

/**
*End : Basic Listing Pack Content
**/


/**
*Start : Order Transaction Content
**/
function pointfinder_orders_meta_box_ordertrans( $post ) {
	global $wpdb;

	$prdertrans_itemid = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_itemid', true ));
	$prderstans_paymentrecs = get_post_meta( $post->ID, 'pointfinder_order_paymentrecs', true );
	
	if($prderstans_paymentrecs != ''){
		

		$transaction_idlist = json_decode($prderstans_paymentrecs,true);

		if (PFControlEmptyArr($transaction_idlist)) {
			echo '<div class="accordion vertical">';
			
			$i = 0;
			$transaction_idlist = array_reverse($transaction_idlist);

			$uncheckarr = array('BankTransferCancel','BankTransfer', 'RecurringPayment','RecurringPaymentPending','ManageRecurringPaymentsProfileStatus','DoExpressCheckoutPaymentStripe');

			foreach ($transaction_idlist as $transaction) {

				echo '<section id="'.$i.'">';
				if(!in_array($transaction['processname'], $uncheckarr)){
					echo '<h2><a href="#'.$i.'">'.esc_html__('Date : ','pointfindert2d').''.$transaction['datetime'].' / '.PFProcessNameFilter($transaction['processname']).' ('.$transaction['token'].')</a></h2>';
				}elseif ($transaction['processname'] == 'DoExpressCheckoutPaymentStripe') {
					echo '<h2><a href="#'.$i.'">'.esc_html__('Date : ','pointfindert2d').''.$transaction['datetime'].' / '.PFProcessNameFilter($transaction['processname']).' ('.esc_html__('STRIPE PAYMENT','pointfindert2d').')</a></h2>';
				}else{
					echo '<h2><a href="#'.$i.'">'.esc_html__('Date : ','pointfindert2d').''.$transaction['datetime'].' / '.PFProcessNameFilter($transaction['processname']).'</a></h2>';
				}
				echo '<p>';
						
						echo '<ul class="pforders-orderdetails-ul">';

						switch ($transaction['processname']) {
							case 'BankTransferCancel':
								echo '<li class="uppcase"><div class="pforders-orderdetails-lbltext">'.esc_html__('Bank transfer cancelled by user.','pointfindert2d').'</div></li>';
								break;
							case 'BankTransfer':
								echo '<li class="uppcase"><div class="pforders-orderdetails-lbltext">'.esc_html__('Bank transfer waiting.','pointfindert2d').'</div></li>';
								break;
							case 'CancelPayment':
								echo '<li class="uppcase"><div class="pforders-orderdetails-lbltext">'.esc_html__('User cancelled this transaction. There is no extra information.','pointfindert2d').'</div></li>';
								break;
							case 'DoExpressCheckoutPayment':
							case 'DoExpressCheckoutPaymentStripe':
							case 'CreateRecurringPaymentsProfile':
							case 'ManageRecurringPaymentsProfileStatus':
							case 'GetExpressCheckoutDetails':
							case 'SetExpressCheckout':
							case 'GetRecurringPaymentsProfileDetails':
							case 'RecurringPayment':
							case 'RecurringPaymentPending':
								array_walk($transaction,"PFOrderTransArrW");
								break;

						}
						
						echo '</ul>';
					
				echo '</p>';
				echo '</section>'; 
				$i++;
			}
			echo '</div>';
		}
	}

}
/**
*End : Order Transaction Content
**/


/**
*Start : Order Process Content
**/
function pointfinder_orders_meta_box_orderprocess( $post ) {
	global $wpdb;

	$prdertrans_itemid = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_itemid', true ));
	$prderstans_processrecs = get_post_meta( $post->ID, 'pointfinder_order_processrecs', true );
	
	if($prderstans_processrecs != ''){
		

		$transaction_idlist = json_decode($prderstans_processrecs,true);

		if (PFControlEmptyArr($transaction_idlist)) {
			echo '<div class="accordion vertical">';
			
			$i = 0;
			$transaction_idlist = array_reverse($transaction_idlist);
			foreach ($transaction_idlist as $transaction) {

				echo '<section id="x'.$i.'">';
				echo '<h2><a href="#k'.$i.'">'.esc_html__('Date : ','pointfindert2d').''.$transaction['datetime'].' / '.$transaction['processname'].'</a></h2>';
				echo '</section>'; 
				$i++;
			}
			echo '</div>';
		}
	}

}
/**
*End : Order Process Content
**/
?>