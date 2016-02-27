<?php

/**********************************************************************************************************************************
*
* User Dashboard Actions
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/

//Get form type
if(isset($_GET['ua']) && $_GET['ua']!=''){
$ua_action = esc_attr($_GET['ua']);
}
$setup4_membersettings_dashboard = PFSAIssetControl('setup4_membersettings_dashboard','','');

if(isset($ua_action)){
	$setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
	$pfmenu_perout = PFPermalinkCheck();

	if(is_user_logged_in()){

		if($setup4_membersettings_dashboard != 0){
				$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
				$current_user = wp_get_current_user();
				$user_id = $current_user->ID;
				/**
				*Start: Member Page Actions
				**/
				if (is_page($setup4_membersettings_dashboard)) {

					get_template_part('admin/estatemanagement/includes/pages/dashboard/dashboard','functions');

					/** 
					*Start: Sidebar cart and menu
					**/
						$setup31_userpayments_featuredoffer = PFSAIssetControl('setup31_userpayments_featuredoffer','','1');
						$sidebar_output = '';
						/** 
						*Start: CART
						**/
							if (isset($_POST['action'])) {
								if ($_POST['action'] == 'pfget_uploaditem') {
									$permit = 'cancel';
								}else{
									$permit = 'ok';
								}
							}else{
								$permit = 'ok';
							}
							if($ua_action == 'newitem' && $permit == 'ok'){
								
								$setup20_paypalsettings_paypal_price_short = PFSAIssetControl('setup20_paypalsettings_paypal_price_short','','$');
								$setup31_userpayments_recurringitem = (PFSAIssetControl('setup31_userpayments_recurringitem','','1') == 1) ? ' checked' : '' ;
								$setup31_userpayments_priceperitem = PFSAIssetControl('setup31_userpayments_priceperitem','','');
								$setup31_userpayments_timeperitem = PFSAIssetControl('setup31_userpayments_timeperitem','','');
								$setup31_userpayments_recurringoption = PFSAIssetControl('setup31_userpayments_recurringoption','','1');
								$setup31_userpayments_pricefeatured = PFSAIssetControl('setup31_userpayments_pricefeatured','','');

								$setup20_paypalsettings_decimals = PFSAIssetControl('setup20_paypalsettings_decimals','','2');
								$setup20_paypalsettings_decimalpoint = PFSAIssetControl('setup20_paypalsettings_decimalpoint','','.');
								$setup20_paypalsettings_thousands = PFSAIssetControl('setup20_paypalsettings_thousands','',',');
								$setup20_paypalsettings_paypal_price_pref = PFSAIssetControl('setup20_paypalsettings_paypal_price_pref','',1);
								
								if ($setup20_paypalsettings_paypal_price_pref == 1) {
									$setup31_userpayments_priceperitem_text = ($setup31_userpayments_priceperitem == 0) ? esc_html__('Free','pointfindert2d').' ('.$setup20_paypalsettings_paypal_price_short.' '.$setup31_userpayments_priceperitem.')' : $setup20_paypalsettings_paypal_price_short.' '.number_format($setup31_userpayments_priceperitem, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands) ;

									$setup31_userpayments_pricefeatured_text = ($setup31_userpayments_pricefeatured == 0) ? esc_html__('Free','pointfindert2d').' ('.$setup20_paypalsettings_paypal_price_short.' '.$setup31_userpayments_pricefeatured.')' : $setup20_paypalsettings_paypal_price_short.' '.number_format($setup31_userpayments_pricefeatured, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands) ;
								}else{
									$setup31_userpayments_priceperitem_text = ($setup31_userpayments_priceperitem == 0) ? esc_html__('Free','pointfindert2d').' ('.$setup31_userpayments_priceperitem.' '.$setup20_paypalsettings_paypal_price_short.')' : number_format($setup31_userpayments_priceperitem, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands).' '.$setup20_paypalsettings_paypal_price_short ;

									$setup31_userpayments_pricefeatured_text = ($setup31_userpayments_pricefeatured == 0) ? esc_html__('Free','pointfindert2d').' ('.$setup31_userpayments_pricefeatured.' '.$setup20_paypalsettings_paypal_price_short.')' : number_format($setup31_userpayments_pricefeatured, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands).' '.$setup20_paypalsettings_paypal_price_short ;
								}


								$totalfor_js = number_format(($setup31_userpayments_priceperitem+$setup31_userpayments_pricefeatured), $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands);
								

								$sidebar_output .= '
								<div id="pfuaformsidebar">
									<div class="pfuaformsidebar pfuaformcart pfwidgettitle">';
									$sidebar_output .= '
										<div class="pf-sidebar-header lbl-text widgetheader"><i class="pfadmicon-glyph-453"></i> ';
										$sidebar_output .= esc_html__('Cart','pointfindert2d');
										$sidebar_output .= '</div>';
										$sidebar_output .= '
										<div class="pf-inner">
											<ul class="pf-sidebar-cartitems lbl-text">';

											

											$sidebar_output .= '
											<li class="pf-sidebar-basicpackage" data-pf-active="1" data-pf-price="'.$setup31_userpayments_priceperitem.'">
												<span class="pf-sidebar-cartitems-title"><i class="pfadmicon-glyph-553"></i>'.esc_html__('Basic Listing','pointfindert2d').' <span class="pf-sidebar-cartitems-price">'.$setup31_userpayments_priceperitem_text.'</span></span>';
												
												if($setup31_userpayments_timeperitem != 0){
													$sidebar_output .= '
													<span class="pf-sidebar-cartitems-subtext">'.sprintf(esc_html__('For %s days','pointfindert2d'), $setup31_userpayments_timeperitem).'</span>';
												}

												$sidebar_output .= '
											</li>';

								if($setup31_userpayments_featuredoffer == 1){
								

									$sidebar_output .= '
											<li class="pf-sidebar-featuredpackage" data-pf-active="0" data-pf-price="'.$setup31_userpayments_pricefeatured.'">
												<span class="pf-sidebar-cartitems-title"><i class="pfadmicon-glyph-553"></i>'.PFSAIssetControl('setup31_userpayments_titlefeatured','','Featured Item').' <span class="pf-sidebar-cartitems-price">'.$setup31_userpayments_pricefeatured_text.'</span></span>';
												
												if($setup31_userpayments_timeperitem != 0){
													$sidebar_output .= '
													<span class="pf-sidebar-cartitems-subtext">'.sprintf(esc_html__('For %s days','pointfindert2d'), $setup31_userpayments_timeperitem).'</span>';
												}

												$sidebar_output .= '
											</li>
											';

								}
								

								$sidebar_output .= '
										<li class="pftotal">
											<span class="pf-sidebar-cartitems-title">
												<span class="pf-sidebar-cartitems-price">'.esc_html__('TOTAL','pointfindert2d').' : <span id="pftotalitemcalc"></span></span>
											</span>
										</li>';
								if($setup31_userpayments_priceperitem != 0 || $setup31_userpayments_pricefeatured != 0){
									

 									if($setup31_userpayments_recurringoption != 0 && PFSAIssetControl('setup20_paypalsettings_paypal_status','','1') == 1){
 										$sidebar_output .= '<li class="pfrecitemdescription">';
										$sidebar_output .= '
										<span class="pf-sidebar-cartitems-recurring golden-forms">
										    <span class="goption">
										        <label class="options">
										            <input type="checkbox" name="recurringlistingb" value="1"'.$setup31_userpayments_recurringitem.' />
										            <span class="checkbox"></span>
										        </label>
										        <label for="check1">'.esc_html__('Enable Paypal Recurring Payment','pointfindert2d').'</label>
										   </span>                        
										</span>';
										$sidebar_output .=	esc_html__('If you want to renew this order automatically at the expiry date. Please enable this option.','pointfindert2d').'</li>';
									}

									
								}
								$sidebar_output .= '
									</ul>
									</div>
								</div>
								</div>
								
								<script type="text/javascript">
								(function($) {
									"use strict";
									';
								
								if($setup31_userpayments_priceperitem == 0 && $setup31_userpayments_pricefeatured != 0){
									$sidebar_output .= "
									$(function(){

										$('.pfrecitemdescription').hide();
										$('input[name=\"recurringlistingb\"]').attr( 'checked', false );
										
										$('#featureditembox').live('change',function(){
											if($(this).val() == 'on' && (featureditemstatus == 1)){
												";
												if(PFSAIssetControl('setup31_userpayments_recurringitem','','1') == 1){
													$sidebar_output .= "$('input[name=\"recurringlistingb\"]').attr( 'checked', true );";
												};
												$sidebar_output .= "
												$('.pfrecitemdescription').show();
												
											}else if($(this).val() == 'on' && featureditemstatus == 0){
												$('.pfrecitemdescription').hide();
												$('input[name=\"recurringlistingb\"]').attr( 'checked', false );
											}
										});	
										
									});";
								}
								
								if($setup31_userpayments_featuredoffer == 1){
									$sidebar_output .= '
									var featureditemprice = $(".pf-sidebar-featuredpackage").data("pf-price");
									var basicitemprice = $(".pf-sidebar-basicpackage").data("pf-price");
									var featureditemstatus = $(".pf-sidebar-featuredpackage").data("pf-active");
									var basicitemstatus = $(".pf-sidebar-basicpackage").data("pf-active");
									var totalitemcontainer = $("#pftotalitemcalc");
									var pricesign = "'.$setup20_paypalsettings_paypal_price_short.'";

									$(document).ready(function(){
										$(".pf-sidebar-featuredpackage").hide();
										if(basicitemstatus == 1 && featureditemstatus == 1){
											var totalcalc = "'.$totalfor_js.'";
											';
											if ($setup20_paypalsettings_paypal_price_pref == 1) {
												$sidebar_output .= 'totalitemcontainer.html(pricesign+totalcalc);';
											}else{
												$sidebar_output .= 'totalitemcontainer.html(totalcalc+pricesign);';
											}
											$sidebar_output .= '
										}else if(basicitemstatus == 1 && featureditemstatus == 0){
											totalitemcontainer.html("'.$setup31_userpayments_priceperitem_text.'");
										}
									});
									
									$("#featureditembox").live("change",function(){
										if($(this).val() == "on" && featureditemstatus == 0){
											$(".pf-sidebar-featuredpackage").show();
											featureditemstatus = 1;
											var totalcalc = "'.$totalfor_js.'";
											';
											if ($setup20_paypalsettings_paypal_price_pref == 1) {
												$sidebar_output .= 'totalitemcontainer.html(pricesign+totalcalc);';
											}else{
												$sidebar_output .= 'totalitemcontainer.html(totalcalc+pricesign);';
											}
											$sidebar_output .= '
										}else if($(this).val() == "on" && featureditemstatus == 1){
											$(".pf-sidebar-featuredpackage").hide();
											featureditemstatus = 0;
											totalitemcontainer.html("'.$setup31_userpayments_priceperitem_text.'");
										}
									});
									
									var rfcheckbox2 = $("#pfuaformsidebar").find("input[name=recurringlistingb]");
									
									rfcheckbox2.on("click",function(){
										if (rfcheckbox2.is(":checked") == true) {
											$("#pfuaprofileform").find("input[name=recurringlistingitem]").val("1");
										}else{
											$("#pfuaprofileform").find("input[name=recurringlistingitem]").val("0");
										};
									});

									';
								}else{
									$sidebar_output .= '
									var basicitemprice = $(".pf-sidebar-basicpackage").data("pf-price");
									var totalitemcontainer = $("#pftotalitemcalc");
									var pricesign = "'.$setup20_paypalsettings_paypal_price_short.'";

									$(document).ready(function(){
										';
										if ($setup20_paypalsettings_paypal_price_pref == 1) {
											$sidebar_output .= 'totalitemcontainer.html(pricesign+basicitemprice);';
										}else{
											$sidebar_output .= 'totalitemcontainer.html(basicitemprice+pricesign);';
										}
										$sidebar_output .= '
										
									})
									
									var rfcheckbox2 = $("#pfuaformsidebar").find("input[name=recurringlistingb]");
									
									rfcheckbox2.live("click",function(){
										if (rfcheckbox2.is(":checked") == true) {
											$("#pfuaprofileform").find("input[name=recurringlistingitem]").val("1");
										}else{
											$("#pfuaprofileform").find("input[name=recurringlistingitem]").val("0");
										};
									});
									';

								}

								

								$sidebar_output .= '	
								})(jQuery);</script>
							';
							}
						/** 
						*End: CART
						**/


						/**
						*Start: Menu
						**/
							$item_count = $favorite_count = $review_count = 0;

							global $wpdb;

							$item_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM $wpdb->posts where post_author = %d and post_type = %s and post_status IN (%s,%s,%s)",$user_id,$setup3_pointposttype_pt1,"publish","pendingpayment","pendingapproved")  );
							$item_count = (empty($item_count)) ? 0 : $item_count ;

							$favorite_count = pfcalculatefavs($user_id);

							/** Prepare Menu Output **/
							$setup4_membersettings_favorites = PFSAIssetControl('setup4_membersettings_favorites','','1');
							$setup11_reviewsystem_check = PFREVSIssetControl('setup11_reviewsystem_check','','0');
							$setup4_membersettings_frontend = PFSAIssetControl('setup4_membersettings_frontend','','0');
							$setup4_membersettings_loginregister = PFSAIssetControl('setup4_membersettings_loginregister','','1');
							

							$setup29_dashboard_contents_my_page_menuname = PFSAIssetControl('setup29_dashboard_contents_my_page_menuname','','');
							$setup29_dashboard_contents_favs_page_menuname = PFSAIssetControl('setup29_dashboard_contents_favs_page_menuname','','');
							$setup29_dashboard_contents_profile_page_menuname = PFSAIssetControl('setup29_dashboard_contents_profile_page_menuname','','');
							$setup29_dashboard_contents_submit_page_menuname = PFSAIssetControl('setup29_dashboard_contents_submit_page_menuname','','');
							$setup29_dashboard_contents_rev_page_menuname = PFSAIssetControl('setup29_dashboard_contents_rev_page_menuname','','');

							$pfmenu_output = '';

							$pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=profile"><i class="pfadmicon-glyph-406"></i> '. $setup29_dashboard_contents_profile_page_menuname.'</a></li>';
							$pfmenu_output .= ($setup4_membersettings_frontend == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=newitem"><i class="pfadmicon-glyph-475"></i> '. $setup29_dashboard_contents_submit_page_menuname.'</a></li>' : '' ;
							$pfmenu_output .= ($setup4_membersettings_frontend == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems"><i class="pfadmicon-glyph-460"></i> '. $setup29_dashboard_contents_my_page_menuname.'<span class="pfbadge">'.$item_count.'</span></a></li>' : '' ;
							$pfmenu_output .= ($setup4_membersettings_favorites == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=favorites"><i class="pfadmicon-glyph-375"></i> '. $setup29_dashboard_contents_favs_page_menuname.'<span class="pfbadge">'.$favorite_count.'</span></a></li>' : '';
							$pfmenu_output .= ($setup11_reviewsystem_check == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=reviews"><i class="pfadmicon-glyph-377"></i> '. $setup29_dashboard_contents_rev_page_menuname.'</a></li>' : '';
							$pfmenu_output .= '<li><a href="'.esc_url(wp_logout_url( home_url() )).'"><i class="pfadmicon-glyph-476"></i> '. esc_html__('Logout','pointfindert2d').'</a></li>';
							
							$sidebar_output .= '
								<div class="pfwidgettitle pfaccountmenu"><div class="widgetheader">'.esc_html__('My Account','pointfindert2d').'</div></div>	
								<div class="pfuaformsidebar ">
								<ul class="pf-sidebar-menu">
									'.$pfmenu_output.'
								</ul>
								</div>

								<div class="sidebar-widget"></div>
							';
						/** 
						*End: Menu
						**/
					
					/**
					*End: Sidebar cart and menu
					**/



					/**
					*Start: Page Start Actions / Divs etc...
					**/
						switch ($ua_action) {
							case 'profile':
								$case_text = 'profile';
							break;
							case 'favorites':
								$case_text = 'favs';
							break;
							case 'newitem':
							case 'edititem':
								$case_text = 'submit';
							break;
							case 'reviews':
								$case_text = 'rev';
							break;
							case 'myitems':
								$case_text = 'my';
							break;
							default:
								$case_text = 'my';
							break;

						}

						$setup29_dashboard_contents_my_page = PFSAIssetControl('setup29_dashboard_contents_'.$case_text.'_page','','');
						$setup29_dashboard_contents_my_page_pos = PFSAIssetControl('setup29_dashboard_contents_'.$case_text.'_page_pos','','1');
						$setup29_dashboard_contents_my_page_layout = PFSAIssetControl('setup29_dashboard_contents_'.$case_text.'_page_layout','','3');
						if ($ua_action != 'edititem') {
							$setup29_dashboard_contents_my_page_title = PFSAIssetControl('setup29_dashboard_contents_'.$case_text.'_page_menuname','','');
						}else{
							$setup29_dashboard_contents_my_page_title = PFSAIssetControl('setup29_dashboard_contents_'.$case_text.'_page_titlee','','');
						}
						

						
						$pf_ua_col_codes = '<div class="col-lg-9 col-md-9">';
						$pf_ua_col_codes .= do_shortcode('[pftext_separator title="'.$setup29_dashboard_contents_my_page_title.'" title_align="separator_align_left"]');
						$pf_ua_col_close = '</div>';
						$pf_ua_prefix_codes = '<section role="main"><div class="pf-container clearfix"><div class="pf-row clearfix"><div class="pf-uadashboard-container clearfix">';
						$pf_ua_suffix_codes = '</div></div></div></section>';
						$pf_ua_sidebar_codes = '<div class="col-lg-3 col-md-3">';
						$pf_ua_sidebar_close = '</div>';
						

						PFGetHeaderBar('',$setup29_dashboard_contents_my_page_title);

						$content_of_section = '';
						if ($setup29_dashboard_contents_my_page != '') {	
							$content_of_section = do_shortcode(get_post_field( 'post_content', $setup29_dashboard_contents_my_page, 'raw' ));
						}
						if ($setup29_dashboard_contents_my_page_pos == 1 && $setup29_dashboard_contents_my_page != '') {
							echo $content_of_section;
						}


						switch($setup29_dashboard_contents_my_page_layout) {
							case '3':
							echo $pf_ua_prefix_codes.$pf_ua_col_codes;	
							break;
							case '2':
							echo $pf_ua_prefix_codes.$pf_ua_sidebar_codes.$sidebar_output;
							echo $pf_ua_sidebar_close.$pf_ua_col_codes;	
							break;
						}
					/**
					*End: Page Start Actions / Divs etc...
					**/

					
					get_template_part('admin/estatemanagement/includes/pages/dashboard/dashboard','frontend');
				
					$errorval = '';
					$sccval = '';

					

					

					switch ($ua_action) {

						case 'profile':
							/**
							*Start: Profile Form Request
							**/
								get_template_part('admin/estatemanagement/includes/pages/dashboard/form','profilereq');
							/**
	 						*End: Profile Form Request
							**/

							
							break;

						case 'newitem':
						case 'edititem':

							
							/**
							*Start: New/Edit Item Form Request
							**/ 
								$setup20_stripesettings_status = PFSAIssetControl('setup20_stripesettings_status','','0');
								
								if($setup20_stripesettings_status == 1){
									echo '<script src="https://checkout.stripe.com/checkout.js"></script>';
									
								}
								$returnval = '';
								if(isset($_POST) && $_POST!='' && count($_POST)>0){

									if (esc_attr($_POST['action']) == 'pfget_uploaditem' || esc_attr($_POST['action']) == 'pfget_edititem') {

										$nonce = esc_attr($_POST['security']);

										if($ua_action == 'newitem'){
											if ( ! wp_verify_nonce( $nonce, 'pfget_uploaditem' ) ) {
												die( 'Security check' ); 
											}
										}else{
											if ( ! wp_verify_nonce( $nonce, 'pfget_edititem' ) ) {
												die( 'Security check' ); 
											}
										}

										if($user_id != 0){

											if($ua_action == 'edititem'){
												$edit_postid = (is_numeric($_GET['i']))? esc_attr($_GET['i']):'';
												if ($edit_postid != '') {
													
													
													$result = $wpdb->get_results( $wpdb->prepare( 
														"
															SELECT ID, post_author
															FROM $wpdb->posts 
															WHERE ID = %s and post_author = %s and post_type = %s
														", 
														$edit_postid,
														$user_id,
														$setup3_pointposttype_pt1
													) );


													if (is_array($result) && count($result)>0) {

														if ($result[0]->ID == $edit_postid) {


															$vars = $_POST;
															
															$vars = PFCleanArrayAttr('PFCleanFilters',$vars);

															$returnval = PFU_AddorUpdateRecord(
																array(
																	'post_id' => $edit_postid,
															        'order_post_id' => PFU_GetOrderID($edit_postid,1),
															        'order_title' => PFU_GetOrderID($edit_postid,0),
																	'vars' => $vars,
																	'user_id' => $user_id
																)
															);
														}else{
															$errorval .= esc_html__('This is not your item.','pointfindert2d');
														}
													}else{
														$errorval .= esc_html__('Wrong Item ID','pointfindert2d');
													}

												}else{
													$errorval .= esc_html__('There is no item ID to edit.','pointfindert2d');
												}
											}elseif ($ua_action == 'newitem') {

												$vars = $_POST;
												
											    $vars = PFCleanArrayAttr('PFCleanFilters',$vars);
											   
												$returnval = PFU_AddorUpdateRecord(
													array(
														'post_id' => '',
												        'order_post_id' => '',
												        'order_title' => '',
														'vars' => $vars,
														'user_id' => $user_id
													)
												);
														
											}

										}else{
										    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
									  	}
									}
								}
								if ($errorval != '') {
							  		$output = new PF_Frontend_Fields(
										array(
											'formtype' => 'errorview',
											'errorval' => $errorval
											)
										);

									echo $output->FieldOutput;											
							  	}
							/**
							*End: New/Edit Item Form Request
							**/


							/**
							*Start: Item Added/Updated Page
							**/
							if(PFControlEmptyArr($returnval)){
								if($returnval['sccval'] != ''){
									$output = new PF_Frontend_Fields(
											array(
												'formtype' => 'myitems',
												'sccval' => $returnval['sccval'],
												'post_id' => $returnval['post_id'],
												'sheader' => 'hide',
												'sheadermes' => ''
											)
										);
									echo $output->FieldOutput;
									echo '<script type="text/javascript">
									(function($) {
										"use strict";
										'.$output->ScriptOutput.'
									})(jQuery);</script>';
									unset($output);
									break;
								}
							}
							/**
							*End: Item Added/Updated Page
							**/



							/**
							*Start: New/Edit Item Page Content
							**/
								$confirmed_postid = '';
								$formtype = 'upload';
								$dontshowpage = 0;
								if ($ua_action == 'edititem') {
									if (!empty($_GET['i'])) {
										$edit_postid = (is_numeric($_GET['i']))? esc_attr($_GET['i']):'';
										if(!empty($edit_postid)){
											$result = $wpdb->get_results( $wpdb->prepare( 
												"
													SELECT ID, post_author
													FROM $wpdb->posts 
													WHERE ID = %s and post_author = %s and post_type = %s
												", 
												$edit_postid,
												$user_id,
												$setup3_pointposttype_pt1
											) );


											if (is_array($result) && count($result)>0) {

												if ($result[0]->ID == $edit_postid) {
													$confirmed_postid = $edit_postid;
													$formtype = 'edititem';
												}else{
													$dontshowpage = 1;
													$errorval .= esc_html__('This is not your item.','pointfindert2d');
												}
											}else{
												$dontshowpage = 1;
												$errorval .= esc_html__('This is not your item.','pointfindert2d');
											}
										}else{
											$dontshowpage = 1;
											$errorval .= esc_html__('Please select an item for edit.','pointfindert2d');
										}
									} else{
										$dontshowpage = 1;
										$errorval .= esc_html__('Please select an item for edit.','pointfindert2d');
									}
									
									
								}

								/**
								*Start : Item Image & Featured Image Delete
								**/
									if($formtype == 'edititem'){
										if(isset($_GET) && isset($_GET['action'])){
											if (esc_attr($_GET['action']) == 'delfimg') {
												wp_delete_attachment(get_post_thumbnail_id( $confirmed_postid ),true);
												delete_post_thumbnail( $confirmed_postid );
												$sccval .= esc_html__('Featured image removed. Redirecting to item details...','pointfindert2d');

										  		$output = new PF_Frontend_Fields(
													array(
														'formtype' => 'errorview',
														'sccval' => $sccval
														)
													);

												echo $output->FieldOutput;											
											  	
												echo '<script type="text/javascript">
													<!--
													window.location = "'.$setup4_membersettings_dashboard_link.'/?ua=edititem&i='.$confirmed_postid.'"
													//-->
													</script>';
												break;
											}elseif (esc_attr($_GET['action']) == 'delimg') {
												$delimg_id = '';
												$delimg_id = esc_attr($_GET['ii']);

												if($delimg_id != ''){
													delete_post_meta( $confirmed_postid, 'webbupointfinder_item_images', $delimg_id );
													if(isset($confirmed_postid)){
														wp_delete_attachment( $delimg_id, true );
													}

													$sccval .= esc_html__('Image removed. Redirecting item details...','pointfindert2d');

											  		$output = new PF_Frontend_Fields(
														array(
															'formtype' => 'errorview',
															'sccval' => $sccval
															)
														);

													echo $output->FieldOutput;											
												  	
													echo '<script type="text/javascript">
														<!--
														window.location = "'.$setup4_membersettings_dashboard_link.'/?ua=edititem&i='.$confirmed_postid.'"
														//-->
														</script>';
													break;
												}
											}
										}
									}
								/**
								*End : Item Image & Featured Image Delete
								**/								
							
								$output = new PF_Frontend_Fields(
									array(
										'fields'=>'', 
										'formtype' => $formtype,
										'sccval' => $sccval,
										'post_id' => $confirmed_postid,
										'errorval' => $errorval,
										'dontshowpage' => $dontshowpage
										)
									);

								echo $output->FieldOutput;
								echo '<script type="text/javascript">
								(function($) {
									"use strict";
									$(function(){
									'.$output->ScriptOutput;
									echo '
									
									var pfsearchformerrors = $(".pfsearchformerrors");
										$("#pfuaprofileform").validate({
											  debug:false,
											  onfocus: false,
											  onfocusout: false,
											  onkeyup: false,
											  rules:{'.$output->VSORules.'},messages:{'.$output->VSOMessages.'},
											  ignore: ".select2-input, .select2-focusser, .pfignorevalidation",
											  validClass: "pfvalid",
											  errorClass: "pfnotvalid pfadmicon-glyph-858",
											  errorElement: "li",
											  errorContainer: pfsearchformerrors,
											  errorLabelContainer: $("ul", pfsearchformerrors),
											  invalidHandler: function(event, validator) {
												var errors = validator.numberOfInvalids();
												if (errors) {
													pfsearchformerrors.show("slide",{direction : "up"},100)
													$(".pfsearch-err-button").click(function(){
														pfsearchformerrors.hide("slide",{direction : "up"},100)
														return false;
													});
												}else{
													pfsearchformerrors.hide("fade",300)
												}
											  }
										});
									});'.$output->ScriptOutputDocReady;
								
								echo '	
								})(jQuery);
								</script>';
								unset($output);
							/**
							*End: New/Edit Item Page Content
							**/
							break;

						case 'myitems':
							/**
							*Start: My Items Form Request
							**/

							$setup20_stripesettings_status = PFSAIssetControl('setup20_stripesettings_status','','0');
								
							if($setup20_stripesettings_status == 1){
								echo '<script src="https://checkout.stripe.com/checkout.js"></script>';
								
							}

								if(isset($_GET)){
									if (isset($_GET['action'])) {
										$action_ofpage = esc_attr($_GET['action']);
										/**
										*Delete
										**/
											
												if ($action_ofpage == 'pf_del') {
													
													if($user_id != 0){

														$delete_postid = (is_numeric($_GET['i']))? esc_attr($_GET['i']):'';

														if ($delete_postid != '') {
															/*Check if item user s item*/
															global $wpdb;
										
															$result = $wpdb->get_results( $wpdb->prepare( 
																"SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s", 
																$delete_postid,
																$user_id,
																$setup3_pointposttype_pt1
															) );


															$result_id = $wpdb->get_var( $wpdb->prepare(
																"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s", 
																'pointfinder_order_itemid',
																$delete_postid
															) );

															$pointfinder_order_recurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_recurring', true ));

															if($pointfinder_order_recurring == 1){

																$pointfinder_order_recurringid = esc_attr(get_post_meta( $result_id, 'pointfinder_order_recurringid', true ));
																PF_Cancel_recurring_payment(
																 array( 
																        'user_id' => $user_id,
																        'profile_id' => $pointfinder_order_recurringid,
																        'item_post_id' => $delete_postid,
																        'order_post_id' => $result_id,
																    )
																 );
															}

															
															if (is_array($result) && count($result)>0) {	
																if ($result[0]->ID == $delete_postid) {
																	$delete_item_images = get_post_meta($delete_postid, 'webbupointfinder_item_images');
																	if (!empty($delete_item_images)) {
																		foreach ($delete_item_images as $item_image) {
																			wp_delete_attachment(esc_attr($item_image),true);
																		}
																	}
																	wp_delete_attachment(get_post_thumbnail_id( $delete_postid ),true);
																	wp_delete_post($delete_postid);

																	/* - Creating record for process system. */
																	PFCreateProcessRecord(
																		array( 
																	        'user_id' => $user_id,
																	        'item_post_id' => $delete_postid,
																			'processname' => esc_html__('Item deleted by USER.','pointfindert2d')
																	    )
																	);

																	/* - Create a record for payment system. */
																
																	$sccval .= esc_html__('Item successfully deleted.','pointfindert2d');
																}

															}else{
																$errorval .= esc_html__('Wrong item ID (Not your item!). Item can not delete.','pointfindert2d');
															}
														}else{
															$errorval .= esc_html__('Wrong item ID.','pointfindert2d');
														}
													}else{
													    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
												  	}
												}
											
										/**
										*Delete
										**/

										/**
										*Payment Process for Basic Listing
										**/

											/**
											*Extend free listing
											**/
												
												if ($action_ofpage == 'pf_extend') {

													if($user_id != 0){

														$item_post_id = (is_numeric($_GET['i']))? esc_attr($_GET['i']):'';

														if ($item_post_id != '') {

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

																	/*Meta for order*/
																	global $wpdb;
																	$result_id = $wpdb->get_var( $wpdb->prepare(
																		"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s", 
																		'pointfinder_order_itemid',
																		$item_post_id
																	) );

																	$status_of_post = get_post_status($item_post_id);

																	$pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
																	if ($status_of_post == 'pendingpayment' && $pointfinder_order_price == 0) {
																		/*Extend listing*/
																		$pointfinder_order_listingtime = esc_attr(get_post_meta( $result_id, 'pointfinder_order_listingtime', true ));
																		

													        			$old_expire_date = get_post_meta( $result_id, 'pointfinder_order_expiredate', true);

													        			$exp_date = date("Y-m-d H:i:s",strtotime($old_expire_date .'+'.$pointfinder_order_listingtime.' day'));
																		$app_date = date("Y-m-d H:i:s");
																	
																		update_post_meta( $result_id, 'pointfinder_order_expiredate', $exp_date);
																		update_post_meta( $result_id, 'pointfinder_order_datetime_approval', $app_date);

																		$wpdb->update($wpdb->posts,array('post_status'=>'publish'),array('ID'=>$item_post_id));
																		$wpdb->update($wpdb->posts,array('post_status'=>'completed'),array('ID'=>$result_id));

																		PFCreateProcessRecord(
																			array( 
																	        'user_id' => $user_id,
																	        'item_post_id' => $item_post_id,
																			'processname' => sprintf(esc_html__('Expire date extended by User (Free Listing): (Order Date: %s / Expire Date: %s)','pointfindert2d'),
																				$app_date,
																				$exp_date
																				)
																		    )
																		);
																		$sccval .= esc_html__('Item expire date extended.','pointfindert2d');
																	}else{
																		$errorval .= esc_html__('Item could not extend.','pointfindert2d');
																	}

																	
																}else{
																	$errorval .= esc_html__('Wrong item ID (It is not your item!). Payment process is stopped.','pointfindert2d');
																}
															}
														}else{
															$errorval .= esc_html__('Wrong item ID.','pointfindert2d');
														}
													}else{
													    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
												  	}

												
												}
											/**
											*Extend Free Listing
											**/


											/**
											*Bank Transfer
											**/
												
												if ($action_ofpage == 'pf_pay2') {

													if($user_id != 0){

														$item_post_id = (is_numeric($_GET['i']))? esc_attr($_GET['i']):'';

														if ($item_post_id != '') {

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

																	/*Meta for order*/
																	global $wpdb;
																	$result_id = $wpdb->get_var( $wpdb->prepare(
																		"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s", 
																		'pointfinder_order_itemid',
																		$item_post_id
																	) );

																	$pointfinder_order_recurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_recurring', true ));

																	if($pointfinder_order_recurring != 1){
												
																		update_post_meta($result_id, 'pointfinder_order_bankcheck', '1');	

																		/*Create a payment record for this process */
																		PF_CreatePaymentRecord(
																			array(
																			'user_id'	=>	$user_id,
																			'item_post_id'	=>	$item_post_id,
																			'order_post_id'	=>	$result_id,
																			'processname'	=>	'BankTransfer',
																			)
																		);

																		/*Create email record for this*/
																		$user_info = get_userdata( $user_id );
																		$mail_item_title = get_the_title($item_post_id);

																		$setup20_paypalsettings_decimals = PFSAIssetControl('setup20_paypalsettings_decimals','','2');
																		$setup20_paypalsettings_decimalpoint = PFSAIssetControl('setup20_paypalsettings_decimalpoint','','.');
																		$setup20_paypalsettings_thousands = PFSAIssetControl('setup20_paypalsettings_thousands','',',');
																		$pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));

																		$total_package_price =  number_format($pointfinder_order_price, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands);

																		$pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpid', true ));	
																		$paymentName = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindert2d'));

																		$setup20_paypalsettings_paypal_api_packagename_basic = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename_basic','',esc_html__('Basic Listing Payment','pointfindert2d'));
																		$setup20_paypalsettings_paypal_api_packagename_featured = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename_featured','',esc_html__('Featured Listing Payment','pointfindert2d'));

																		if($pointfinder_order_listingpid == 2){
																			$packtype = 'featured';
																		}else{
																			$packtype = 'basic';
																		}
																	
																		$apipackage_name = ($packtype == 'basic')? $setup20_paypalsettings_paypal_api_packagename_basic : $setup20_paypalsettings_paypal_api_packagename_featured;


																		pointfinder_mailsystem_mailsender(
																			array(
																			'toemail' => $user_info->user_email,
																	        'predefined' => 'bankpaymentwaiting',
																	        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name),
																			)
																		);

																		$admin_email = get_option( 'admin_email' );
											 							$setup33_emailsettings_mainemail = PFMSIssetControl('setup33_emailsettings_mainemail','',$admin_email);
																		pointfinder_mailsystem_mailsender(
																			array(
																				'toemail' => $setup33_emailsettings_mainemail,
																		        'predefined' => 'newbankpreceived',
																		        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name),
																				)
																			);

																		$sccval .= esc_html__('Bank Transfer Process; Completed','pointfindert2d');
																	}else{
																		$errorval .= esc_html__('Recurring Payment Orders not accepted for bank transfer.','pointfindert2d');
																	}
																}else{
																	$errorval .= esc_html__('Wrong item ID (It is not your item!). Payment process is stopped.','pointfindert2d');
																}
															}
														}else{
															$errorval .= esc_html__('Wrong item ID.','pointfindert2d');
														}
													}else{
													    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
												  	}

												  	/**
													*Start: Bank Transfer Page Content
													**/

														$output = new PF_Frontend_Fields(
																array(
																	'formtype' => 'banktransfer',
																	'sccval' => $sccval,
																	'errorval' => $errorval,
																	'post_id' => $item_post_id
																)
															);
														echo $output->FieldOutput;
														break;
													/**
													*End: Bank Transfer Page Content
													**/
												}


												
											/**
											*Bank Transfer
											**/

											/**
											*Cancel Bank Transfer
											**/
												
												if ($action_ofpage == 'pf_pay2c') {

													if($user_id != 0){

														$item_post_id = (is_numeric($_GET['i']))? esc_attr($_GET['i']):'';

														if ($item_post_id != '') {

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

																	/*Meta for order*/
																	global $wpdb;
																	$result_id = $wpdb->get_var( $wpdb->prepare(
																		"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s", 
																		'pointfinder_order_itemid',
																		$item_post_id
																	) );

																	update_post_meta($result_id, 'pointfinder_order_bankcheck', '0');	
																	
																	/*Create a payment record for this process */
																	PF_CreatePaymentRecord(
																			array(
																			'user_id'	=>	$user_id,
																			'item_post_id'	=>	$item_post_id,
																			'order_post_id'	=>	$result_id,
																			'processname'	=>	'BankTransferCancel',
																			)
																		);

																	/*Create email record for this*/
																	$user_info = get_userdata( $user_id );
																	$mail_item_title = get_the_title($item_post_id);
																	pointfinder_mailsystem_mailsender(
																		array(
																			'toemail' => $user_info->user_email,
																	        'predefined' => 'bankpaymentcancel',
																	        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title),
																			)
																		);


																	$sccval .= esc_html__('Bank Transfer Process; Cancelled','pointfindert2d');

																}else{
																	$errorval .= esc_html__('Wrong item ID (It is not your item!). Payment process is stopped.','pointfindert2d');
																}
															}
														}else{
															$errorval .= esc_html__('Wrong item ID.','pointfindert2d');
														}
													}else{
													    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
												  	}

												  	
												}

											/**
											*Cancel Bank Transfer
											**/


											
											/**
											*Response Basic Listing
											**/
												
												if ($action_ofpage == 'pf_rec') {

													
													if($user_id != 0){

														if (isset($_GET['Authority'])) {
															global $wpdb;

															/*Check token*/
															$order_post_id = $wpdb->get_var( $wpdb->prepare( 
																"SELECT post_id FROM $wpdb->postmeta WHERE meta_value = %s and meta_key = %s", 
																esc_attr($_GET['Authority']),
																'pointfinder_order_token'
															) );

															
															$item_post_id = $wpdb->get_var( $wpdb->prepare(
																"SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s and post_id = %s", 
																'pointfinder_order_itemid',
																$order_post_id
															) );
										
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

																	$infos = array();
																	$infos['USER'] = $paypal_api_user;
																	$infos['PWD'] = $paypal_api_pwd;
																	$infos['SIGNATURE'] = $paypal_api_signature;

																	if($paypal_sandbox == 1){$sandstatus = true;}else{$sandstatus = false;}
																	
																	//$paypal = new Paypal($infos,$sandstatus);

																	$tokenparams = array(
																	   'TOKEN' => esc_attr($_GET['Authority']), 
																	);

																	//$response = $paypal -> request('GetExpressCheckoutDetails',$tokenparams);
																	/*print_r($response);*/
																	
																	$result_id = $order_post_id;
																	
																	$pointfinder_order_pricesign = esc_attr(get_post_meta( $result_id, 'pointfinder_order_pricesign', true ));
																	$pointfinder_order_listingtime = esc_attr(get_post_meta( $result_id, 'pointfinder_order_listingtime', true ));
																	$pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
																	$pointfinder_order_recurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_recurring', true ));
																	$pointfinder_order_listingtime = ($pointfinder_order_listingtime == '') ? 0 : $pointfinder_order_listingtime ;
																	
																	$pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpid', true ));	
																	
																	$total_package_price = number_format($pointfinder_order_price, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands);
																	
																	$Amount = str_replace(',','',$pointfinder_order_price);
																	$MerchantID = $paypal_api_user;
																	
																	$Authority = $_GET['Authority'];
																	
																	if($_GET['Status'] == 'OK'){
																		// URL also Can be https://ir.zarinpal.com/pg/services/WebGate/wsdl
																		$client = new SoapClient('https://de.zarinpal.com/pg/services/WebGate/wsdl', array('encoding' => 'UTF-8')); 
																		
																		$response = $client->PaymentVerification(
																							array(
																									'MerchantID'	 => $MerchantID,
																									'Authority' 	 => $Authority,
																									'Amount'	 => $Amount
																								)
																		);
																		
																		if($response->Status == 100){
																			echo 'Transation success. RefID:'. $response->RefID;
																		} else {
																			echo 'Transation failed. Status:'. $response->Status;
																		}

																	} else {
																		echo 'Transaction canceled by user';
																	}
																	
																	
																	
																	
																	if ($_GET['Status'] == 'OK') {

																			/*Check token*/
																			$orderdetails_post_id = $wpdb->get_var( $wpdb->prepare( 
																				"SELECT post_id FROM $wpdb->postmeta WHERE meta_value = %s and meta_key = %s", 
																				$order_post_id,
																				'pointfinder_orderdetails_orderid'
																			) );
																			if(1 == 1){

																				if(1 == 1){
																					/*Create a payment record for this process */
																					PF_CreatePaymentRecord(
																						array(
																							'user_id'	=>	$user_id,
																							'item_post_id'	=>	$item_post_id,
																							'order_post_id'	=> $order_post_id,
																							'response'	=>	$response->Status,
																							'token'	=>	$_GET['Authority'],
																							'payerid'	=>	$_GET['PAYERID'],
																							'processname'	=>	'GetExpressCheckoutDetails',
																							'status'	=>	$_GET['Status']
																							)
																					);

																			
																			

																					/*Check Payer id check for hack*/
																					if($_GET['Status'] == 'OK'){

																						$setup20_paypalsettings_paypal_verified = PFSAIssetControl('setup20_paypalsettings_paypal_verified','','0');

																						if ($setup20_paypalsettings_paypal_verified == 1) {
																							if($response->Status == 100){
																								$work_status = 'accepted';
																							}else{
																								$work_status = 'declined';
																							}
																						}else{
																							$work_status = 'accepted';
																						}

																						if ($work_status == 'accepted') {
																							
																							$result_id = $order_post_id;

																							$pointfinder_order_pricesign = esc_attr(get_post_meta( $result_id, 'pointfinder_order_pricesign', true ));
																							$pointfinder_order_listingtime = esc_attr(get_post_meta( $result_id, 'pointfinder_order_listingtime', true ));
																							$pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
																							$pointfinder_order_recurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_recurring', true ));
																							$pointfinder_order_listingtime = ($pointfinder_order_listingtime == '') ? 0 : $pointfinder_order_listingtime ;

																							$pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpid', true ));	


																							$total_package_price = number_format($pointfinder_order_price, $setup20_paypalsettings_decimals, $setup20_paypalsettings_decimalpoint, $setup20_paypalsettings_thousands);
																							
																							$paymentName = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindert2d'));
																							$setup20_paypalsettings_paypal_api_packagename_basic = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename_basic','',esc_html__('Basic Listing Payment','pointfindert2d'));
																							$setup20_paypalsettings_paypal_api_packagename_featured = PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename_featured','',esc_html__('Featured Listing Payment','pointfindert2d'));


																							if($pointfinder_order_listingpid == 2){
																								$packtype = 'featured';
																							}else{
																								$packtype = 'basic';
																							}
																							$apipackage_name = ($packtype == 'basic')? $setup20_paypalsettings_paypal_api_packagename_basic : $setup20_paypalsettings_paypal_api_packagename_featured;

																							$setup31_userlimits_userpublish = PFSAIssetControl('setup31_userlimits_userpublish','','0');
																							$publishstatus = ($setup31_userlimits_userpublish == 1) ? 'publish' : 'pendingapproval' ;
																							
																							$user_info = get_userdata( $user_id );
																							$mail_item_title = get_the_title($item_post_id);

																							$admin_email = get_option( 'admin_email' );
											 												$setup33_emailsettings_mainemail = PFMSIssetControl('setup33_emailsettings_mainemail','',$admin_email);

																							if ($pointfinder_order_recurring == 1) {
																								/**
																								*Start : Recurring Payment Process
																								**/
																									/** Express Checkout **/
																									$expresspay_paramsr = array(
																										'TOKEN' => $_GET['Authority'],
																										'PAYERID' => $_GET['PAYERID'],
																										'PAYMENTREQUEST_0_AMT' => $total_package_price,
																										'PAYMENTREQUEST_0_CURRENCYCODE' => $paypal_price_unit,
																										'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
																									);
																									
																									//$response_expressr = $paypal -> request('DoExpressCheckoutPayment',$expresspay_paramsr);
																									
																									if (isset($response_expressr['TOKEN'])) {
																										$tokenr = $response_expressr['TOKEN'];
																									}else{
																										$tokenr = '';
																									}
																									/*Create a payment record for this process */
																									PF_CreatePaymentRecord(
																											array(
																											'user_id'	=>	$user_id,
																											'item_post_id'	=>	$item_post_id,
																											'order_post_id'	=> $order_post_id,
																											'response'	=>	$response->Status ,
																											'token'	=>	$_GET['Authority'],
																											'processname'	=>	'DoExpressCheckoutPayment',
																											'status'	=>	$_GET['Status']
																											)
																										);
																									/*print_r($response_express);*/
																								

																									if($response->Status == 100){
																										
																															
																													wp_update_post(array('ID' => $item_post_id,'post_status' => $publishstatus) );
																													wp_reset_postdata();
																													wp_update_post(array('ID' => $order_post_id,'post_status' => 'completed') );
																													wp_reset_postdata();
																											

																										pointfinder_mailsystem_mailsender(
																											array(
																												'toemail' => $user_info->user_email,
																										        'predefined' => 'paymentcompleted',
																										        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name),
																												)
																											);

																										pointfinder_mailsystem_mailsender(
																											array(
																												'toemail' => $setup33_emailsettings_mainemail,
																										        'predefined' => 'newpaymentreceived',
																										        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name),
																												)
																											);

																										$sccval .= esc_html__('Thanks for your payment. Now please wait until our admin approve your payment and activate your item.','pointfindert2d');
																											
																											/*Start : Creating Recurring Payment*/
																											$timestamp_forprofile = strtotime('+ '.$pointfinder_order_listingtime.' days');
																									
																											$recurringpay_params = array(
																												'TOKEN' => $_GET['Authority'],
																												'PAYERID' => $_GET['PAYERID'],
																												'PROFILESTARTDATE' => date("Y-m-d\TH:i:s\Z",$timestamp_forprofile),
																												'DESC' => sprintf(
																													esc_html__('%s / %s / Recurring: %s%s per %s days / For: (%s) %s','pointfindert2d'),
																													$paymentName,
																													$apipackage_name,
																													$total_package_price,
																													$pointfinder_order_pricesign,
																													$pointfinder_order_listingtime,
																													$item_post_id,
																													get_the_title($item_post_id)
																												),
																												'BILLINGPERIOD' => 'Day',
																												'BILLINGFREQUENCY' => $pointfinder_order_listingtime,
																												'AMT' => $total_package_price,
																												'CURRENCYCODE' => $paypal_price_unit,
																												'MAXFAILEDPAYMENTS' => 1
																											);
																											
																											$item_arr_rec = array(
																											   'L_PAYMENTREQUEST_0_NAME0' => $paymentName.' : '.$apipackage_name,
																											   'L_PAYMENTREQUEST_0_AMT0' => $total_package_price,
																											   'L_PAYMENTREQUEST_0_QTY0' => '1',
																											   'L_PAYMENTREQUEST_0_ITEMCATEGORY0'	=> 'Digital',
																											);
																											
																											//$response_recurring = $paypal -> request('CreateRecurringPaymentsProfile',$recurringpay_params,$item_arr_rec);
																											unset($paypal);
																											/*Create a payment record for this process */
																											PF_CreatePaymentRecord(
																													array(
																													'user_id'	=>	$user_id,
																													'item_post_id'	=>	$item_post_id,
																													'order_post_id'	=> $order_post_id,
																													'response'	=>	$response->Status,
																													'token'	=>	$_GET['Authority'],
																													'processname'	=>	'CreateRecurringPaymentsProfile',
																													'status'	=>	$_GET['Status']
																													)

																												);


																											if($_GET['Status'] == 'OK'){
																												
																												update_post_meta($order_post_id, 'pointfinder_order_recurringid', $_GET['PAYERID'] );	

																												pointfinder_mailsystem_mailsender(
																													array(
																														'toemail' => $user_info->user_email,
																												        'predefined' => 'recprofilecreated',
																												        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name,'nextpayment' => date("Y-m-d", strtotime("+".$pointfinder_order_listingtime." days")),'profileid' => $response_recurring['PROFILEID']),
																														)
																													);

																												pointfinder_mailsystem_mailsender(
																													array(
																														'toemail' => $setup33_emailsettings_mainemail,
																												        'predefined' => 'recurringprofilecreated',
																												        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name,'nextpayment' => date("Y-m-d", strtotime("+".$pointfinder_order_listingtime." days")),'profileid' => $response_recurring['PROFILEID']),
																														)
																													);

																												$sccval .= esc_html__('Recurring payment profile created.','pointfindert2d');
																											}else{
																												
																												update_post_meta($order_post_id, 'pointfinder_order_recurring', 0 );	
																												$errorval .= esc_html__('Error: Recurring profile creation is failed. Recurring payment option cancelled.','pointfindert2d');
																											}
																											
																											/*End : Creating Recurring Payment*/
																											
																									}else{
																										
																										$errorval .= esc_html__('Sorry: The operation could not be completed. Recurring profile creation is failed and payment process could not completed.','pointfindert2d').'<br>';
																										
																									}
																									
																									/** Express Checkout **/

																								/**
																								*End : Recurring Payment Process
																								**/
																							
																							}else{
																								/**
																								*Start : Express Payment Process
																								**/
																									
																									$expresspay_params = array(
																										'TOKEN' => $_GET['Authority'],
																										'PAYERID' => $_GET['PAYERID'],
																										'PAYMENTREQUEST_0_AMT' => $total_package_price,
																										'PAYMENTREQUEST_0_CURRENCYCODE' => $paypal_price_unit,
																										'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
																									);
																									// inja
																									$response_express = $paypal -> request('DoExpressCheckoutPayment',$expresspay_params);
																									/*print_r($response_express);*/
																									unset($paypal);

																										
																										/*Create a payment record for this process */
																										if (isset($response_express['TOKEN'])) {
																											$token = $response_express['TOKEN'];
																										}else{
																											$token = '';
																										}
																										PF_CreatePaymentRecord(
																												array(
																												'user_id'	=>	$user_id,
																												'item_post_id'	=>	$item_post_id,
																												'order_post_id'	=> $order_post_id,
																												'response'	=>	$response_express,
																												'token'	=>	$token,
																												'processname'	=>	'DoExpressCheckoutPayment',
																												'status'	=>	$response_express['ACK']
																												)
																											);
																									

																										if($response_express['ACK'] == 'Success'){
																											
																											if(isset($response_express['PAYMENTINFO_0_PAYMENTSTATUS'])){
																												if ($response_express['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed') {						
																													wp_update_post(array('ID' => $item_post_id,'post_status' => $publishstatus) );
																													wp_reset_postdata();
																													wp_update_post(array('ID' => $order_post_id,'post_status' => 'completed') );
																													wp_reset_postdata();
																												}
																											}

																											pointfinder_mailsystem_mailsender(
																												array(
																													'toemail' => $user_info->user_email,
																											        'predefined' => 'paymentcompleted',
																											        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name),
																													)
																												);

																											pointfinder_mailsystem_mailsender(
																												array(
																													'toemail' => $setup33_emailsettings_mainemail,
																											        'predefined' => 'newpaymentreceived',
																											        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name),
																													)
																												);

																											$sccval .= esc_html__('Thanks for your payment. Now please wait until our system approve your payment and activate your item listing.','pointfindert2d');
																										}else{
																											$errorval .= esc_html__('Sorry: The operation could not be completed. Payment is failed.','pointfindert2d').'<br>';
																											if (isset($response_express['L_SHORTMESSAGE0'])) {
																												$errorval .= '<br>'.esc_html__('Zarinpal Message:','pointfindert2d').' '.$response_express['L_SHORTMESSAGE0'];
																											}
																											if (isset($response_express['L_LONGMESSAGE0'])) {
																												$errorval .= '<br>'.esc_html__('Zarinpal Message Details:','pointfindert2d').' '.$response_express['L_LONGMESSAGE0'];
																											}
																										}
																									
																								/**
																								*End : Express Payment Process
																								**/
																							}
																						
																							

																						
																						}else{
																							$errorval .= esc_html__('Sorry: Our payment system only accepts verified Zarinpal Users. Payment is failed.','pointfindert2d');
																						}
																						
																					}else{
																						$errorval .= esc_html__('Can not get express checkout informations. Payment is failed.','pointfindert2d');
																					}
																				}elseif($response['CHECKOUTSTATUS'] == 'PaymentActionCompleted'){
																					$sccval .= esc_html__('Payment Completed.','pointfindert2d').'';
																				}else{
																					$errorval .= esc_html__('Response could not be received. Payment is failed.','pointfindert2d').'(1)';
																				}
																			}else{
																				$errorval .= esc_html__('Response could not be received. Payment is failed.','pointfindert2d').'(2)';
																			}

																	}else{
																		$errorval .= esc_html__('Response could not be received. Payment is failed.','pointfindert2d');
																	}
																	

																}else{
																	$errorval .= esc_html__('Wrong item ID (It is not your item!). Payment process is stopped.','pointfindert2d');
																}
															}

														}else{
															$errorval .= esc_html__('Need token value.','pointfindert2d');
														}
														
														

													}else{
													    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
												  	}



												}
												
											/**
											*Response Basic Listing
											**/


											/**
											*Cancel Basic Listing
											**/
											
												if ($action_ofpage == 'pf_cancel') {
													$returned_token = esc_attr($_GET['Authority']);
													if(!empty($returned_token)){
														/*Create a payment record for this process */
														PF_CreatePaymentRecord(
																array(
																'user_id'	=>	$user_id,
																'token'	=>	$returned_token,
																'processname'	=>	'CancelPayment'
																)
															);
													}

													$errorval .= esc_html__('Sale process cancelled.','pointfindert2d');
												}
												
											/**
											*Cancel Response Basic Listing
											**/


										/**
										*Payment Process Basic Listing
										**/
									}
								}

								
								


								/**
								*Refine Listing
								**/
									if(isset($_POST) && $_POST!='' && count($_POST)>0){

										if (esc_attr($_POST['action']) == 'pf_refineitemlist') {

											$nonce = esc_attr($_POST['security']);
											if ( ! wp_verify_nonce( $nonce, 'pf_refineitemlist' ) ) {
												die( 'Security check' ); 
											}

											$vars = $_POST;
											
											$vars = PFCleanArrayAttr('PFCleanFilters',$vars);
										    
											if($user_id != 0){

												$output = new PF_Frontend_Fields(
														array(
															'formtype' => 'myitems',
															'fields' => $vars,
														)
													);
												echo $output->FieldOutput;
												echo '<script type="text/javascript">
												(function($) {
													"use strict";
													'.$output->ScriptOutput.'
												})(jQuery);</script>';
												unset($output);
												break;
												
											}else{
											    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
										  	}
										}
									}
								/**
								*Refine Listing
								**/
							/**
							*End: My Items Form Request
							**/



							/**
							*Start: My Items Page Content
							**/
								
								


								$output = new PF_Frontend_Fields(
										array(
											'formtype' => 'myitems',
											'sccval' => $sccval,
											'errorval' => $errorval
										)
									);
								echo $output->FieldOutput;
								echo '<script type="text/javascript">
								(function($) {
									"use strict";
									'.$output->ScriptOutput.'
								})(jQuery);</script>';
								unset($output);

							/**
							*End: My Items Page Content
							**/
							break;

						case 'reviews':
							/**
							*Review Page Content
							**/
								$output = new PF_Frontend_Fields(
										array(
											'formtype' => 'reviews',
											'current_user' => $user_id
										)
									);
								echo $output->FieldOutput;
							/**
							*Review Page Content
							**/
							break;

						case 'favorites':

							/**
							*Favs Page Content
							**/
								if(isset($_POST) && $_POST!='' && count($_POST)>0){

									if (esc_attr($_POST['action']) == 'pf_refinefavlist') {

										$nonce = esc_attr($_POST['security']);
										if ( ! wp_verify_nonce( $nonce, 'pf_refinefavlist' ) ) {
											die( 'Security check' ); 
										}

										$vars = $_POST;
										
										$vars = PFCleanArrayAttr('PFCleanFilters',$vars);
									    
										if($user_id != 0){

											$output = new PF_Frontend_Fields(
													array(
														'formtype' => 'favorites',
														'fields' => $vars,
														'current_user' => $user_id
													)
												);
											echo $output->FieldOutput;
											echo '<script type="text/javascript">
											(function($) {
												"use strict";
												'.$output->ScriptOutput.'
											})(jQuery);</script>';
											unset($output);
											break;
											
										}else{
										    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindert2d');
									  	}
									}
								}
							/**
							*Favs Page Content
							**/

							$output = new PF_Frontend_Fields(
										array(
											'formtype' => 'favorites',
											'current_user' => $user_id
										)
									);
								echo $output->FieldOutput;
							
							break;

					}
					
					/**
					*Start: Page End Actions / Divs etc...
					**/
						switch($setup29_dashboard_contents_my_page_layout) {
							case '3':
							echo $pf_ua_col_close.$pf_ua_sidebar_codes.$sidebar_output;
							echo $pf_ua_sidebar_close.$pf_ua_suffix_codes;	
							break;
							case '2':
							echo $pf_ua_col_close.$pf_ua_suffix_codes;
							break;						
						}


						if ($setup29_dashboard_contents_my_page_pos == 0 && $setup29_dashboard_contents_my_page != '') {
							echo $content_of_section;
						}
					/**
					*End: Page End Actions / Divs etc...
					**/

				}
				/**
				*End: Member Page Actions
				**/
		}


	}else{
		
	   PFLoginWidget();
	}
}else{
	$content = get_the_content();
	if (!empty($setup4_membersettings_dashboard)) {
		if (is_page($setup4_membersettings_dashboard)) {
			$setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
			$pfmenu_perout = PFPermalinkCheck();
			pf_redirect(''.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=profile');
		}else{
			if(function_exists('PFGetHeaderBar')){
			  PFGetHeaderBar();
			}
			
			if (!has_shortcode( $content , 'vc_row' )) {
				echo '<div class="pf-blogpage-spacing pfb-top"></div>';
	            echo '<section role="main">';
	                echo '<div class="pf-container">';
	                    echo '<div class="pf-row">';
	                        echo '<div class="col-lg-12">';
	                            the_content();
	                        echo '</div>';
	                    echo '</div>';
	                echo '</div>';
	            echo '</section>';
	            echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';
			}else{
				the_content();
			}
		    
		}
	}else{
		if(function_exists('PFGetHeaderBar')){
		  PFGetHeaderBar();
		}
		if (!has_shortcode( $content , 'vc_row' )) {
			echo '<div class="pf-blogpage-spacing pfb-top"></div>';
	        echo '<section role="main">';
	            echo '<div class="pf-container">';
	                echo '<div class="pf-row">';
	                    echo '<div class="col-lg-12">';
	                        the_content();
	                    echo '</div>';
	                echo '</div>';
	            echo '</div>';
	        echo '</section>';
	        echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';
		}else{
			the_content();
		}
	}
	
	
}
?>