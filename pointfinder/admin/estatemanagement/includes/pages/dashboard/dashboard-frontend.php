<?php
/**********************************************************************************************************************************
*
* Custom Detail Fields Frontend Class
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/

if ( ! class_exists( 'PF_Frontend_Fields' ) ){
	class PF_Frontend_Fields
	{
		public $FieldOutput;
		public $ScriptOutput;
		public $ScriptOutputDocReady;
		public $VSORules;
		public $VSOMessages;
		public $PFHalf = 1;
		
		function __construct($params = array()){	

			$defaults = array( 
		        'fields' => '',
		        'formtype' => '',
		        'sccval' => '',
				'errorval' => '',
				'post_id' => '',
				'sheader' => '',
				'sheadermes' => '',
				'current_user' => '',
				'dontshowpage' => 0
		    );

		    $params = array_merge($defaults, $params);

		    $setup4_membersettings_dashboard = PFSAIssetControl('setup4_membersettings_dashboard','','');
			$setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
			$pfmenu_perout = PFPermalinkCheck();

			$lang_custom = '';

			if(function_exists('icl_object_id')) {
				$lang_custom = PF_current_language();
			}

		/**
		*Start: Page Header Actions / Divs / Etc...
		**/ 
			$this->FieldOutput = '<div class="golden-forms">';
			$this->FieldOutput .= '<form id="pfuaprofileform" enctype="multipart/form-data" name="pfuaprofileform" method="POST" action="">';
			$this->FieldOutput .= '<div class="pfsearchformerrors"><ul></ul><a class="button pfsearch-err-button">'.esc_html__('CLOSE','pointfindert2d').'</a></div>';
			if($params['sccval'] != ''){
				$this->FieldOutput .= '<div class="notification success" id="pfuaprofileform-notify"><div class="row"><p>'.$params['sccval'].'<br>'.$params['sheadermes'].'</p></div></div>';
				$this->ScriptOutput .= '$(document).ready(function(){$.pfmessagehide();});';
			}
			if($params['errorval'] != ''){
				$this->FieldOutput .= '<div class="notification error" id="pfuaprofileform-notify"><p>'.$params['errorval'].'</p></div>';
				$this->ScriptOutput .= '$(document).ready(function(){$.pfmessagehide();});';
			}
			$this->FieldOutput .= '<div class="">';
			$this->FieldOutput .= '<div class="">';
			$this->FieldOutput .= '<div class="row">';

		/**
		*End: Page Header Actions / Divs / Etc...
		**/

			switch ($params['formtype']) {
			
				case 'upload':
				case 'edititem':
				/**
				*Start: New Item Page Content
				**/
					global $pointfindertheme_option;
					if($params['formtype'] == 'upload'){
						$formaction = 'pfget_uploaditem';
						$buttonid = 'pf-ajax-uploaditem-button';
						$buttontext = PFSAIssetControl('setup29_dashboard_contents_submit_page_menuname','','');
						
					}else{
						$formaction = 'pfget_edititem';
						$buttonid = 'pf-ajax-uploaditem-button';
						$buttontext = PFSAIssetControl('setup29_dashboard_contents_submit_page_titlee','','');

					}
					$noncefield = wp_create_nonce($formaction);

					if ($params['dontshowpage'] != 1) {
					
					wp_enqueue_script('theme-dropzone');
					wp_enqueue_script('theme-google-api');
					wp_enqueue_script('theme-gmap3');
					wp_enqueue_style('theme-dropzone'); 

					/* Get Admin Settings for Default Fields */
					$setup4_submitpage_titletip = PFSAIssetControl('setup4_submitpage_titletip','','');
					$setup4_submitpage_descriptiontip = PFSAIssetControl('setup4_submitpage_descriptiontip','','');
					$maplanguage= PFSAIssetControl('setup5_mapsettings_maplanguage','','en');
					

					/*** DEFAULTS FOR FIRST COLUMN ***/
					$setup3_pointposttype_pt4_check = PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
					$setup4_submitpage_itemtypes_check = PFSAIssetControl('setup4_submitpage_itemtypes_check','','1');
					$setup3_pointposttype_pt5_check = PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
					$setup4_submitpage_locationtypes_check = PFSAIssetControl('setup4_submitpage_locationtypes_check','','1');
					$setup3_pointposttype_pt6_check = PFSAIssetControl('setup3_pointposttype_pt6_check','','1');
					$setup4_submitpage_featurestypes_check = PFSAIssetControl('setup4_submitpage_featurestypes_check','','1');
					$setup4_submitpage_minres = PFSAIssetControl('setup4_submitpage_minres','','10');



					/*** DEFAULTS FOR SECOND COLUMN ***/
					$setup4_submitpage_maparea = PFSAIssetControl('setup4_submitpage_maparea','','1');
					$setup4_submitpage_video = PFSAIssetControl('setup4_submitpage_video','','1');
					$setup4_submitpage_imageupload = PFSAIssetControl('setup4_submitpage_imageupload','','1');
					$setup4_submitpage_imagelimit = PFSAIssetControl('setup4_submitpage_imagelimit','','10');
					$setup4_submitpage_messagetorev = PFSAIssetControl('setup4_submitpage_messagetorev','','1');
					$setup4_membersettings_dashboard = PFSAIssetControl('setup4_membersettings_dashboard','','0');
					$setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard );
					$setup4_submitpage_featuredverror = PFSAIssetControl('setup4_submitpage_featuredverror','','');
					$setup4_submitpage_featuredverror_status = PFSAIssetControl('setup4_submitpage_featuredverror_status','',1);
					$pfmenu_perout = PFPermalinkCheck();

					/** 
					*Start : First Column (Custom Fields)
					**/
						

							/** 
							*Featured Item 
							**/
							if (PFSAIssetControl('setup31_userpayments_featuredoffer','','1') == 1) {

								
								$package_featuredcheck = 1;
								if ($params['post_id'] != '') {
									$package_featuredcheck = esc_attr(get_post_meta(PFU_GetOrderID($params['post_id'],1), 'pointfinder_order_listingpid',true));
								}


								if($package_featuredcheck == 2 || (empty($package_featuredcheck) && $params['formtype'] == 'edititem')){
									$setup31_userpayments_pricefeatured = PFSAIssetControl('setup31_userpayments_pricefeatured','','');
									$this->FieldOutput .= '<div class="pfsubmit-title">'.PFSAIssetControl('setup31_userpayments_titlefeatured','','Featured Item').'</div>';
									$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-nopadding">';
									$this->FieldOutput .= '								
										
			                            <div class="gspace pfupload-featured-item-box" style="border:0;padding: 12px;">
			                            '.esc_html__('This item is featured.','pointfindert2d').'
			                            </div>';
			                        $this->FieldOutput .= '</section>';
			                    }elseif($params['formtype'] != 'edititem' && ($package_featuredcheck == 1 || empty($package_featuredcheck))){
			                    	$this->FieldOutput .= '<div class="pfsubmit-title">'.PFSAIssetControl('setup31_userpayments_titlefeatured','','Featured Item').'</div>';
									$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-nopadding">';
									$setup31_userpayments_pricefeatured = PFSAIssetControl('setup31_userpayments_pricefeatured','','');

									$this->FieldOutput .= '								
										
			                            <div class="gspace pfupload-featured-item-box" style="border:0;padding: 12px;">
			                            	<p>
											<label class="toggle-switch blue">
												<input type="checkbox" name="featureditembox" id="featureditembox">
												<label for="featureditembox" data-on="'.esc_html__('YES','pointfindert2d').'" data-off="'.esc_html__('NO','pointfindert2d').'"></label>
											</label> 
											 <span>
											   '.PFSAIssetControl('setup31_userpayments_textfeatured','','').'
											  </span>
											</p>          
											

			                            </div>';
			                        $this->FieldOutput .= '</section>';
			                    }
		                    	
		                    	
		                    }
							/**
							*Featured Item 
							**/

							/**
							*Listing Types
							**/
								
								$setup4_submitpage_listingtypes_title = PFSAIssetControl('setup4_submitpage_listingtypes_title','','Listing Type');
								$setup4_submitpage_listingtypes_group = PFSAIssetControl('setup4_submitpage_listingtypes_group','','0');
								$setup4_submitpage_listingtypes_group_ex = PFSAIssetControl('setup4_submitpage_listingtypes_group_ex','','1');
								$setup4_submitpage_listingtypes_validation = 1;
								$setup4_submitpage_listingtypes_verror = PFSAIssetControl('setup4_submitpage_listingtypes_verror','','Please select a listing type.');
								$setup4_submitpage_listingtypes_gridview = PFSAIssetControl('setup4_submitpage_listingtypes_gridview','','0');

								$itemfieldname = 'pfupload_listingtypes';

								$this->PFValidationCheckWrite($setup4_submitpage_listingtypes_validation,$setup4_submitpage_listingtypes_verror,$itemfieldname);

								$item_defaultvalue = ($params['post_id'] != '') ? wp_get_post_terms($params['post_id'], 'pointfinderltypes', array("fields" => "ids")) : '' ;

								$this->FieldOutput .= '<div class="pfsubmit-title">'.$setup4_submitpage_listingtypes_title.'</div>';
								$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-listingtype">';
								$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
								$fields_output_arr = array(
									'listname' => 'pfupload_listingtypes',
							        'listtype' => 'listingtypes',
							        'listtitle' => '',
							        'listsubtype' => 'pointfinderltypes',
							        'listgroup' => $setup4_submitpage_listingtypes_group,
							        'listgroup_ex' => $setup4_submitpage_listingtypes_group_ex,
							        'listdefault' => $item_defaultvalue,
							        'listmultiple' => 0
								);
								$this->FieldOutput .= $this->PFGetList($fields_output_arr);
								$this->FieldOutput .= '</section>';
								$this->FieldOutput .= '</section>';
								$this->ScriptOutput .= '$("#pfupload_listingtypes").select2({
									placeholder: "'.esc_html__("Please select","pointfindert2d").'", 
									formatNoMatches:"'.esc_html__("Nothing found.","pointfindert2d").'",
									allowClear: true, ';
									if($setup4_submitpage_listingtypes_gridview == 1){
										$this->ScriptOutput .= 'dropdownCssClass: "pointfinder-upload-page",';
									}
									$this->ScriptOutput .= '
									minimumResultsForSearch: '.esc_js($setup4_submitpage_minres).'
								});';
								
							/**
							*Listing Types
							**/



							$this->FieldOutput .= '<div class="pfsubmit-title">'.esc_html__('INFORMATION','pointfindert2d').'</div>';
							$this->FieldOutput .= '<section class="pfsubmit-inner">';
							/**
							*Title
							**/
								$setup4_submitpage_titleverror = PFSAIssetControl('setup4_submitpage_titleverror','','Please type a title.');
								$item_title = ($params['post_id'] != '') ? get_the_title($params['post_id']) : '' ;
								$this->FieldOutput .= '
								<section class="pfsubmit-inner-sub">
			                        <label for="item_title" class="lbl-text">'.esc_html__('Title','pointfindert2d').':</label>
			                        <label class="lbl-ui">
			                        	<input type="text" name="item_title" id="item_title" class="input" value="'.$item_title.'"/>';
								if ($setup4_submitpage_titletip!='') {
									$this->FieldOutput .= '<b class="tooltip left-bottom"><em>'.$setup4_submitpage_titletip.'</em></b>';
								} 
			                    $this->FieldOutput .= '</label>                          
			                   </section>  
								';
								$this->PFValidationCheckWrite(1,$setup4_submitpage_titleverror,'item_title');
							/**
							*Title
							**/


							/**
							*Desc
							**/
								$setup4_submitpage_descriptionvcheck = PFSAIssetControl('setup4_submitpage_descriptionvcheck','','0');
								$setup4_submitpage_description_verror = PFSAIssetControl('setup4_submitpage_description_verror','','Please write a description');
								$item_desc = ($params['post_id'] != '') ? get_post_field('post_content',$params['post_id']) : '' ;
								$this->FieldOutput .= '
								<section class="pfsubmit-inner-sub">
			                        <label for="item_desc" class="lbl-text">'.esc_html__('Description','pointfindert2d').':</label>
			                        <label class="lbl-ui">
			                        	<textarea id="item_desc" name="item_desc" class="textarea mini">'.$item_desc.'</textarea>';
			                    if ($setup4_submitpage_descriptiontip!='') {
									$this->FieldOutput .= '<b class="tooltip left-bottom"><em>'.$setup4_submitpage_descriptiontip.'</em></b>';
								} 
			                    $this->FieldOutput .= '</label>                              
			                   </section>  
								';
								$this->PFValidationCheckWrite($setup4_submitpage_descriptionvcheck,$setup4_submitpage_description_verror,'item_desc');
							/**
							*Desc
							**/
							

							/**
							*Item Types
							**/
								if($setup3_pointposttype_pt4_check == 1 && $setup4_submitpage_itemtypes_check == 1){
									
									$setup4_submitpage_itemtypes_title = PFSAIssetControl('setup4_submitpage_itemtypes_title','','Item Type');
									$setup4_submitpage_itemtypes_multiple = PFSAIssetControl('setup4_submitpage_itemtypes_multiple','','0');
									$setup4_submitpage_itemtypes_group = PFSAIssetControl('setup4_submitpage_itemtypes_group','','0');
									$setup4_submitpage_itemtypes_group_ex = PFSAIssetControl('setup4_submitpage_itemtypes_group_ex','','1');
									$setup4_submitpage_itemtypes_validation = PFSAIssetControl('setup4_submitpage_itemtypes_validation','','1');
									$setup4_submitpage_itemtypes_verror = PFSAIssetControl('setup4_submitpage_itemtypes_verror','','Please select an item type.');

									$itemfieldname = ($setup4_submitpage_itemtypes_multiple == 1) ? 'pfupload_itemtypes[]' : 'pfupload_itemtypes' ;

									$this->PFValidationCheckWrite($setup4_submitpage_itemtypes_validation,$setup4_submitpage_itemtypes_verror,$itemfieldname);

									$item_defaultvalue = ($params['post_id'] != '') ? wp_get_post_terms($params['post_id'], 'pointfinderitypes', array("fields" => "ids")) : '' ;

									$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';	
									$fields_output_arr = array(
										'listname' => 'pfupload_itemtypes',
								        'listtype' => 'itemtypes',
								        'listtitle' => $setup4_submitpage_itemtypes_title,
								        'listsubtype' => 'pointfinderitypes',
								        'listdefault' => $item_defaultvalue,
								        'listgroup' => $setup4_submitpage_itemtypes_group,
								        'listgroup_ex' => $setup4_submitpage_itemtypes_group_ex,
								        'listmultiple' => $setup4_submitpage_itemtypes_multiple
									);
									$this->FieldOutput .= $this->PFGetList($fields_output_arr);
									$this->FieldOutput .= '</section>';
									$this->ScriptOutput .= '$("#pfupload_itemtypes").select2({
										placeholder: "'.esc_html__("Please select","pointfindert2d").'", 
										formatNoMatches:"'.esc_html__("Nothing found.","pointfindert2d").'",
										allowClear: true, 
										minimumResultsForSearch: '.esc_js($setup4_submitpage_minres).'
									});';
								}
							/**
							*Item Types
							**/

							$this->FieldOutput .= '</section>';

							/**
							*Features
							**/
								if($setup3_pointposttype_pt6_check == 1 && $setup4_submitpage_featurestypes_check == 1){
									$setup4_submitpage_featurestypes_title = PFSAIssetControl('setup4_submitpage_featurestypes_title','','Features');

									$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-features-title">'.$setup4_submitpage_featurestypes_title.'</div>';
									$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-features"></section>';

									

									$this->ScriptOutput .= "
									$.pf_getfeatures_now = function(itemid){

										$.ajax({
									    	beforeSend:function(){
									    		$('.pfsubmit-inner-features').pfLoadingOverlay({action:'show'});
									    		$('.pfsubmit-inner-listingtype').pfLoadingOverlay({action:'show'});
									    	},
											url: theme_scriptspf.ajaxurl,
											type: 'POST',
											dataType: 'html',
											data: {
												action: 'pfget_featuresystem',
												id: itemid,
												postid:'".$params['post_id']."',
												lang: '".$lang_custom."',
												security: '".wp_create_nonce('pfget_featuresystem')."'
											},
										})
										.done(function(obj) {
											if (obj.length == 0) {
												$('.pfsubmit-inner-features').hide();
												$('.pfsubmit-inner-features-title').hide();
											}else{
												$('.pfsubmit-inner-features').show();
												$('.pfsubmit-inner-features-title').show();
											}
											$('.pfsubmit-inner-features').html(obj);

											if ($('input[name=\"pointfinderfeaturecount\"]').val() == 0) {
												$('.pfsubmit-inner-features').hide();
												$('.pfsubmit-inner-features-title').hide();
											}else{
												$('.pfsubmit-inner-features').show();
												$('.pfsubmit-inner-features-title').show();
											}

	
											$('.pfsubmit-inner-features').pfLoadingOverlay({action:'hide'});
											$('.pfsubmit-inner-listingtype').pfLoadingOverlay({action:'hide'});

											$('.pfitemdetailcheckall').on('click',function(event) {
												/* Act on the event */
												$.each($('[name=\"pffeature[]\"]'), function(index, val) {
													 $(this).attr('checked', true);
												});
											});

											$('.pfitemdetailuncheckall').on('click',function(event) {
												/* Act on the event */
												$.each($('[name=\"pffeature[]\"]'), function(index, val) {
													 $(this).attr('checked', false);
												});
											});

										});
									}

									$( '#pfupload_listingtypes' ).change(function(){
										$.pf_getfeatures_now($('#pfupload_listingtypes').val());
									});

									$(function(){
										$.pf_getfeatures_now($('#pfupload_listingtypes').val());
									});
									";
								}

							/**
							*Features
							**/


							/** 
							*Start : Custom Fields
							**/

								$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-customfields-title">'.esc_html__('ADDITIONAL INFO','pointfindert2d').'</div>';
								$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-customfields"></section>';

								

								$this->ScriptOutput .= "
								$.pf_getcustomfields_now = function(itemid){

									$.ajax({
								    	beforeSend:function(){
								    		$('.pfsubmit-inner-customfields').pfLoadingOverlay({action:'show'});
								    		$('.pfsubmit-inner-listingtype').pfLoadingOverlay({action:'show'});
								    		
								    	},
										url: theme_scriptspf.ajaxurl,
										type: 'POST',
										dataType: 'html',
										data: {
											action: 'pfget_fieldsystem',
											id: itemid,
											lang: '".$lang_custom."',
											postid:'".$params['post_id']."',
											security: '".wp_create_nonce('pfget_fieldsystem')."'
										},
									})
									.done(function(obj) {
										if (obj.length == 0) {
											$('.pfsubmit-inner-customfields').hide();
											$('.pfsubmit-inner-customfields-title').hide();
										}else{
											$('.pfsubmit-inner-customfields').show();
											$('.pfsubmit-inner-customfields-title').show();
										}
										$('.pfsubmit-inner-customfields').html(obj);

										$('.pfsubmit-inner-customfields').pfLoadingOverlay({action:'hide'});
										$('.pfsubmit-inner-listingtype').pfLoadingOverlay({action:'hide'});
									});
								}

								$( '#pfupload_listingtypes' ).change(function(){
									$.pf_getcustomfields_now($('#pfupload_listingtypes').val());
								});

								$(function(){
									$.pf_getcustomfields_now($('#pfupload_listingtypes').val());
								});
								";
							/** 
							*End : Custom Fields
							**/



							/**
							*Opening Hours
							**/
								$setup3_modulessetup_openinghours = PFSAIssetControl('setup3_modulessetup_openinghours','','0');
								$setup3_modulessetup_openinghours_ex = PFSAIssetControl('setup3_modulessetup_openinghours_ex','','1');
								$setup3_modulessetup_openinghours_ex2 = PFSAIssetControl('setup3_modulessetup_openinghours_ex2','','1');

								if($setup3_modulessetup_openinghours == 1 && $setup3_modulessetup_openinghours_ex == 1){

									$this->FieldOutput .= '<div class="pfsubmit-title pf-openinghours-div">'.esc_html__('Opening Hours','pointfindert2d').' <small>('.esc_html__('Leave blank to show closed','pointfindert2d' ).')</small></div>';
									$this->FieldOutput .= '<section class="pfsubmit-inner pf-openinghours-div">';

									$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
									$this->FieldOutput .= '
					                <label class="lbl-ui">
					                <input type="text" name="o1" class="input" placeholder="Monday-Friday: 09:00 - 22:00" value="'.esc_attr(get_post_meta($params['post_id'],'webbupointfinder_items_o_o1',true)).'" />
						            </label>
						            </section>
						            ';
						            $this->FieldOutput .= '</section>';
									
								}elseif($setup3_modulessetup_openinghours == 1 && $setup3_modulessetup_openinghours_ex == 0){
									
									
									if ($setup3_modulessetup_openinghours_ex2 == 1) {
										$text_ohours1 = esc_html__('Monday','pointfindert2d');
										$text_ohours2 = esc_html__('Sunday','pointfindert2d');
									}else{
										$text_ohours1 = esc_html__('Sunday','pointfindert2d');
										$text_ohours2 = esc_html__('Monday','pointfindert2d');
									}

									$ohours_first = '<section>
										<label for="o1" class="lbl-text">'.esc_html__('Monday','pointfindert2d').':</label>
							            <label class="lbl-ui">
							            <input type="text" name="o1" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($params['post_id'],'webbupointfinder_items_o_o1',true)).'" />
							            </label>
							            </section>';

							        $ohours_last = '<section>
							            <label for="o7" class="lbl-text">'.esc_html__('Sunday','pointfindert2d').':</label>
							            <label class="lbl-ui">
							            <input type="text" name="o7" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($params['post_id'],'webbupointfinder_items_o_o7',true)).'"/>
							            </label>
							            </section>';

									if ($setup3_modulessetup_openinghours_ex2 != 1) {
										$ohours_first = $ohours_last . $ohours_first;
										$ohours_last = '';
									}

									$this->FieldOutput .= '<div class="pfsubmit-title pf-openinghours-div">'.esc_html__('Opening Hours','pointfindert2d').' <small>('.esc_html__('Leave blank to show closed','pointfindert2d' ).')</small></div>';
									$this->FieldOutput .= '<section class="pfsubmit-inner pf-openinghours-div">';
									$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
									
									$this->FieldOutput .= $ohours_first;
									$this->FieldOutput .= '
							            <section>
							            <label for="o2" class="lbl-text">'.esc_html__('Tuesday','pointfindert2d').':</label>
						                <label class="lbl-ui">
						                <input type="text" name="o2" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($params['post_id'],'webbupointfinder_items_o_o2',true)).'"/>
							            </label>
							            </section>
							            <section>
							            <label for="o3" class="lbl-text">'.esc_html__('Wednesday','pointfindert2d').':</label>
						                <label class="lbl-ui">
						                <input type="text" name="o3" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($params['post_id'],'webbupointfinder_items_o_o3',true)).'"/>
							            </label>
							            </section>
							            <section>
							            <label for="o4" class="lbl-text">'.esc_html__('Thursday','pointfindert2d').':</label>
						                <label class="lbl-ui">
						                <input type="text" name="o4" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($params['post_id'],'webbupointfinder_items_o_o4',true)).'"/>
							            </label>
							            </section>
							            <section>
							            <label for="o5" class="lbl-text">'.esc_html__('Friday','pointfindert2d').':</label>
						                <label class="lbl-ui">
						                <input type="text" name="o5" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($params['post_id'],'webbupointfinder_items_o_o5',true)).'"/>
							            </label>
							            </section>
							            <section>
							            <label for="o6" class="lbl-text">'.esc_html__('Saturday','pointfindert2d').':</label>
						                <label class="lbl-ui">
						                <input type="text" name="o6" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($params['post_id'],'webbupointfinder_items_o_o6',true)).'"/>
							            </label>
							            </section>
						            ';
						            $this->FieldOutput .= $ohours_last;
						            $this->FieldOutput .= '</section>';
						            $this->FieldOutput .= '</section>';
									
								}elseif($setup3_modulessetup_openinghours == 1 && $setup3_modulessetup_openinghours_ex == 2){
									
									wp_enqueue_script('jquery-ui-core');
									wp_enqueue_script('jquery-ui-datepicker');
									wp_enqueue_script('jquery-ui-slider');
									wp_register_script('theme-timepicker', get_template_directory_uri() . '/js/jquery-ui-timepicker-addon.js', array('jquery','jquery-ui-datepicker'), '4.0',true); 
									wp_enqueue_script('theme-timepicker');
	   								wp_enqueue_style('jquery-ui-smoothness', "http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.min.css", false, null);

									

									$this->FieldOutput .= '<div class="pfsubmit-title pf-openinghours-div">'.esc_html__('Opening Hours','pointfindert2d').' <small>('.esc_html__('Leave blank to show closed','pointfindert2d' ).')</small></div>';

									$this->FieldOutput .= '<section class="pfsubmit-inner pf-openinghours-div">';									
									$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
									
									$general_rtlsupport = PFSAIssetControl('general_rtlsupport','','0');
									if ($general_rtlsupport == 1) {
										$rtltext_oh = 'true';
									}else{
										$rtltext_oh = 'false';
									}

									for ($i=0; $i < 7; $i++) { 
										$o_value[$i] = get_post_meta($params['post_id'],'webbupointfinder_items_o_o'.($i+1),true);
										if (!empty($o_value[$i])) {
											$o_value[$i] = explode("-", $o_value[$i]);
											if (count($o_value[$i]) < 1) {
												$o_value[$i] = array("","");
											}elseif (count($o_value[$i]) < 2) {
												$o_value[$i][1] = "";
											}
										}else{
											$o_value[$i] = array("","");
										}

										
										$this->ScriptOutput .= "
										$.timepicker.timeRange(
											$('input[name=\"o".($i+1)."_1\"]'),
											$('input[name=\"o".($i+1)."_2\"]'),
											{
												minInterval: (1000*60*60),
												timeFormat: 'HH:mm',
												start: {},
												end: {},
												timeOnlyTitle: '".esc_html__('Choose Time','pointfindert2d')."',
												timeText: '".esc_html__('Time','pointfindert2d')."',
												hourText: '".esc_html__('Hour','pointfindert2d')."',
												currentText: '".esc_html__('Now','pointfindert2d')."',
												isRTL: ".$rtltext_oh."
											}
										);
										";
									}
									

									$ohours_first = '
										<section>
										<label for="o1" class="lbl-text">'.esc_html__('Monday','pointfindert2d').':</label>
						   				<div class="row">
						   					<div class="col6 first">
								                <label class="lbl-ui">
								                <input type="text" name="o1_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[0][0].'" />
									            </label>
						   					</div>
						   					<div class="col6 last">
								                <label class="lbl-ui">
								                <input type="text" name="o1_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[0][1].'" />
									            </label>
						   					</div>
						   				</div>
							            </section>
									';
									$ohours_last = '
										<section>
							            <label for="o7" class="lbl-text">'.esc_html__('Sunday','pointfindert2d').':</label>
							            <div class="row">
						                	<div class="col6 first">
								                <label class="lbl-ui">
								                <input type="text" name="o7_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[6][0].'" />
									            </label>
						   					</div>
						   					<div class="col6 last">
								                <label class="lbl-ui">
								                <input type="text" name="o7_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[6][1].'" />
									            </label>
						   					</div>
						   				</div>
							            </section>

									';

									if ($setup3_modulessetup_openinghours_ex2 != 1) {
										$ohours_first = $ohours_last . $ohours_first;
										$ohours_last = '';
									}

									
									$this->FieldOutput .= $ohours_first;
									$this->FieldOutput .= '
							            <section>
							            <label for="o2" class="lbl-text">'.esc_html__('Tuesday','pointfindert2d').':</label>
							            <div class="row">
						                	<div class="col6 first">
								                <label class="lbl-ui">
								                <input type="text" name="o2_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[1][0].'" />
									            </label>
						   					</div>
						   					<div class="col6 last">
								                <label class="lbl-ui">
								                <input type="text" name="o2_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[1][1].'" />
									            </label>
						   					</div>
						   				</div>
							            </section>
							            <section>
							            <label for="o3" class="lbl-text">'.esc_html__('Wednesday','pointfindert2d').':</label>
							            <div class="row">
						                	<div class="col6 first">
								                <label class="lbl-ui">
								                <input type="text" name="o3_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[2][0].'" />
									            </label>
						   					</div>
						   					<div class="col6 last">
								                <label class="lbl-ui">
								                <input type="text" name="o3_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[2][1].'" />
									            </label>
						   					</div>
						   				</div>
							            </section>
							            <section>
							            <label for="o4" class="lbl-text">'.esc_html__('Thursday','pointfindert2d').':</label>
							            <div class="row">
						                	<div class="col6 first">
								                <label class="lbl-ui">
								                <input type="text" name="o4_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[3][0].'" />
									            </label>
						   					</div>
						   					<div class="col6 last">
								                <label class="lbl-ui">
								                <input type="text" name="o4_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[3][1].'" />
									            </label>
						   					</div>
						   				</div>
							            </section>
							            <section>
							            <label for="o5" class="lbl-text">'.esc_html__('Friday','pointfindert2d').':</label>
							            <div class="row">
						                	<div class="col6 first">
								                <label class="lbl-ui">
								                <input type="text" name="o5_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[4][0].'" />
									            </label>
						   					</div>
						   					<div class="col6 last">
								                <label class="lbl-ui">
								                <input type="text" name="o5_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[4][1].'" />
									            </label>
						   					</div>
						   				</div>
							            </section>
							            <section>
							            <label for="o6" class="lbl-text">'.esc_html__('Saturday','pointfindert2d').':</label>
							            <div class="row">
						                	<div class="col6 first">
								                <label class="lbl-ui">
								                <input type="text" name="o6_1" class="input" placeholder="'.__('Start','pointfindert2d').'" value="'.$o_value[5][0].'" />
									            </label>
						   					</div>
						   					<div class="col6 last">
								                <label class="lbl-ui">
								                <input type="text" name="o6_2" class="input" placeholder="'.__('End','pointfindert2d').'" value="'.$o_value[5][1].'" />
									            </label>
						   					</div>
						   				</div>
							            </section>
						            ';
						            $this->FieldOutput .= $ohours_last;
						            $this->FieldOutput .= '</section>';
						            $this->FieldOutput .= '</section>';
									
								}

								if ($setup3_modulessetup_openinghours == 1) {
									/* Opening Hours show/hide by Listing Category Options*/
									$taxonomies = array( 
						                'pointfinderltypes'
						            );

						            $args = array(
						                'orderby'           => 'name', 
						                'order'             => 'ASC',
						                'hide_empty'        => false, 
						                'parent'            => 0,
						            ); 
									$pf_get_term_details = get_terms($taxonomies,$args); 
						            $pfstart = (!empty($pf_get_term_details))? true:false;

						            $ohours_term_arr = "[";

									if($pfstart){
										foreach ($pf_get_term_details as &$pf_get_term_detail) {


											if (PFADVIssetControl('setupadvancedconfig_'.$pf_get_term_detail->term_id.'_advanced_status','','0') == 1) {
												
												if (PFADVIssetControl('setupadvancedconfig_'.$pf_get_term_detail->term_id.'_ohoursmodule','',$setup3_modulessetup_openinghours) == 0) {

													$ohours_term_arr .= '"'.$pf_get_term_detail->term_id.'"';
													$ohours_term_arr .= ",";
													$args2 = array(
											            'orderby'           => 'name', 
											            'order'             => 'ASC',
											            'hide_empty'        => false, 
											            'parent'            => $pf_get_term_detail->term_id,
											        ); 
													$pf_get_term_details2 = get_terms($taxonomies,$args2); 
											        $pfstart = (!empty($pf_get_term_details2))? true:false;
											        if($pfstart){
											        	foreach ($pf_get_term_details2 as $pf_get_term_detail2) {
											        		$ohours_term_arr .= '"'.$pf_get_term_detail2->term_id.'"';
															$ohours_term_arr .= ",";
											        	}
											        }
												}
											}
										}
									}
									$ohours_term_arr .= "]";

									$this->ScriptOutput .= "
									var openingharr = ".$ohours_term_arr.";

									$(function(){
										if ($( '#pfupload_listingtypes' ).val() != '') {

											if ($.inArray( $('#pfupload_listingtypes').val(), openingharr ) != -1) {
												$('.pf-openinghours-div').hide();
											}else{
												$('.pf-openinghours-div').show();
											}

										}else{
											$('.pf-openinghours-div').hide();
										}
										
									});

									$( '#pfupload_listingtypes' ).change(function(){
										
										if ($.inArray( $('#pfupload_listingtypes').val(), openingharr ) != -1) {
											$('.pf-openinghours-div').hide();
										}else{
											$('.pf-openinghours-div').show();
										}

									});
			
									";
								}
							/**
							*Opening Hours
							**/


							/**
							*Featured Video 
							**/
								$setup4_submitpage_video = PFSAIssetControl('setup4_submitpage_video','','1');
								
								if ($setup4_submitpage_video == 1) {
									
									$pfuploadfeaturedvideo = ($params['post_id'] != '') ? get_post_meta($params['post_id'], 'webbupointfinder_item_video', true) : '' ;

									$this->FieldOutput .= '<div class="pfsubmit-title pf-videomodule-div">'.esc_html__('VIDEO','pointfindert2d').'</div>';
									$this->FieldOutput .= '<section class="pfsubmit-inner pf-videomodule-div">';
									$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
									$this->FieldOutput .= '<small style="margin-bottom:4px">'.esc_html__('Please copy & paste video sharing url. ','pointfindert2d').'</small>';
										$this->FieldOutput .= '
			                            <label for="file" class="lbl-ui" >
			                            <input class="input" name="pfuploadfeaturedvideo" placeholder="http://youtube.be/wrjbsdSYD234" value="'.$pfuploadfeaturedvideo.'">
			                            </label> 
										';
									$this->FieldOutput .= '</section>';
									$this->FieldOutput .= '</section>';

									/* Opening Hours show/hide by Listing Category Options*/
									$taxonomies = array( 
						                'pointfinderltypes'
						            );

						            $args = array(
						                'orderby'           => 'name', 
						                'order'             => 'ASC',
						                'hide_empty'        => false, 
						                'parent'            => 0,
						            ); 
									$pf_get_term_details = get_terms($taxonomies,$args); 
						            $pfstart = (!empty($pf_get_term_details))? true:false;

						            $video_term_arr = "[";

									if($pfstart){
										foreach ($pf_get_term_details as &$pf_get_term_detail) {


											if (PFADVIssetControl('setupadvancedconfig_'.$pf_get_term_detail->term_id.'_advanced_status','','0') == 1) {
												
												if (PFADVIssetControl('setupadvancedconfig_'.$pf_get_term_detail->term_id.'_videomodule','','1') == 0) {
													$video_term_arr .= '"'.$pf_get_term_detail->term_id.'"';
													$video_term_arr .= ",";
													$args2 = array(
											            'orderby'           => 'name', 
											            'order'             => 'ASC',
											            'hide_empty'        => false, 
											            'parent'            => $pf_get_term_detail->term_id,
											        ); 
													$pf_get_term_details2 = get_terms($taxonomies,$args2); 
											        $pfstart = (!empty($pf_get_term_details2))? true:false;
											        if($pfstart){
											        	foreach ($pf_get_term_details2 as $pf_get_term_detail2) {
											        		$video_term_arr .= '"'.$pf_get_term_detail2->term_id.'"';
															$video_term_arr .= ",";
											        	}
											        }
												}
											}
										}
									}
									$video_term_arr .= "]";

									$this->ScriptOutput .= "
									var videomoarr = ".$video_term_arr.";

									$(function(){
										if ($( '#pfupload_listingtypes' ).val() != '') {
											if ($.inArray( $('#pfupload_listingtypes').val(), videomoarr ) != -1) {
												$('.pf-videomodule-div').hide();
											}else{
												$('.pf-videomodule-div').show();
											}

										}else{
											$('.pf-videomodule-div').hide();
										}
										
									});

									$( '#pfupload_listingtypes' ).change(function(){
										if ($.inArray( $('#pfupload_listingtypes').val(), videomoarr ) != -1) {
											$('.pf-videomodule-div').hide();
										}else{
											$('.pf-videomodule-div').show();
										}

									});
			
									";
									

								}
							/** 
							*Featured Video 
							**/



							/** 
							*Map  & Locations
							**/
								if($setup4_submitpage_maparea == 1 || $setup3_pointposttype_pt5_check == 1){

									$this->FieldOutput .= '<div class="pfsubmit-title">'.esc_html__('ADDRESS','pointfindert2d').'</div>';
									$this->FieldOutput .= '<section class="pfsubmit-inner">';
									/**
									*Locations
									**/
										if($setup3_pointposttype_pt5_check == 1 && $setup4_submitpage_locationtypes_check == 1){
											
											$setup4_submitpage_locationtypes_title = PFSAIssetControl('setup4_submitpage_locationtypes_title','','Location');
											$setup4_submitpage_locationtypes_multiple = PFSAIssetControl('setup4_submitpage_locationtypes_multiple','','0');
											$setup4_submitpage_locationtypes_group = PFSAIssetControl('setup4_submitpage_locationtypes_group','','0');
											$setup4_submitpage_locationtypes_group_ex = PFSAIssetControl('setup4_submitpage_locationtypes_group_ex','','1');
											$setup4_submitpage_locationtypes_validation = PFSAIssetControl('setup4_submitpage_locationtypes_validation','','1');
											$setup4_submitpage_locationtypes_verror = PFSAIssetControl('setup4_submitpage_locationtypes_verror','','Please select a location.');

											$itemfieldname = ($setup4_submitpage_locationtypes_multiple == 1) ? 'pfupload_locations[]' : 'pfupload_locations' ;

											$this->PFValidationCheckWrite($setup4_submitpage_locationtypes_validation,$setup4_submitpage_locationtypes_verror,$itemfieldname);

											$item_defaultvalue = ($params['post_id'] != '') ? wp_get_post_terms($params['post_id'], 'pointfinderlocations', array("fields" => "ids")) : '' ;

											$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';	
											$fields_output_arr = array(
												'listname' => 'pfupload_locations',
										        'listtype' => 'locations',
										        'listtitle' => $setup4_submitpage_locationtypes_title,
										        'listsubtype' => 'pointfinderlocations',
										        'listdefault' => $item_defaultvalue,
										        'listgroup' => $setup4_submitpage_locationtypes_group,
										        'listgroup_ex' => $setup4_submitpage_locationtypes_group_ex,
										        'listmultiple' => $setup4_submitpage_locationtypes_multiple
											);
											$this->FieldOutput .= $this->PFGetList($fields_output_arr);
											$this->FieldOutput .= '</section>';
											$this->ScriptOutput .= '$("#pfupload_locations").select2({
												placeholder: "'.esc_html__("Please select","pointfindert2d").'", 
												formatNoMatches:"'.esc_html__("Nothing found.","pointfindert2d").'",
												allowClear: true, 
												minimumResultsForSearch: '.esc_js($setup4_submitpage_minres).'
											});';
										}
									/**
									*Locations 
									**/

									if($setup4_submitpage_maparea == 1){
										$setup4_submitpage_maparea_title = PFSAIssetControl('setup4_submitpage_maparea_title','','');
										$setup4_submitpage_maparea_tooltip = PFSAIssetControl('setup4_submitpage_maparea_tooltip','','');
										$setup4_submitpage_maparea_verror = PFSAIssetControl('setup4_submitpage_maparea_verror','','');

										$this->PFValidationCheckWrite(1,$setup4_submitpage_maparea_verror,'pfupload_lat');
										$this->PFValidationCheckWrite(1,$setup4_submitpage_maparea_verror,'pfupload_lng');
										$this->PFValidationCheckWrite(1,esc_html__('Please enter an address','pointfindert2d'),'pfupload_address');

										$description = ($setup4_submitpage_maparea_tooltip!='') ? ' <a href="javascript:;" class="info-tip" aria-describedby="helptooltip">?<span role="tooltip">'.$setup4_submitpage_maparea_tooltip.'</span></a>' : '' ;

										$pfupload_address = ($params['post_id'] != '') ? esc_html(get_post_meta($params['post_id'], 'webbupointfinder_items_address', true)) : '' ;

										
										$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
											$this->FieldOutput .= '<label for="pfupload_address" class="lbl-text">'.$setup4_submitpage_maparea_title.':'.$description.'</label>';
											$this->FieldOutput .= '<label class="lbl-ui pflabelfixsearch search">';
											$this->FieldOutput .= '<input id="pfupload_address" value="'.$pfupload_address.'" name="pfupload_address" class="controls input" type="text" placeholder="'.esc_html__('Please type an address...','pointfindert2d').'">';
											$this->FieldOutput .= '<a class="button" id="pf_search_geolocateme" title="'.esc_html__('Locate me!','pointfindert2d').'">
											<img src="'.get_template_directory_uri().'/images/geoicon.svg" width="16px" height="16px" class="pf-search-locatemebut" alt="'.esc_html__('Locate me!','pointfindert2d').'">
											<div class="pf-search-locatemebutloading"></div>
											</a>';
											$this->FieldOutput .= '</label>';
											$this->FieldOutput .= '<div id="pfupload_map" style="width: 100%;height: 300px;border:0"></div>';


										$this->FieldOutput .= '</section>';

										$setup5_mapsettings_zoom = PFSAIssetControl('setup5_mapsettings_zoom','','6');
										$setup5_mapsettings_type = PFSAIssetControl('setup5_mapsettings_type','','ROADMAP');
										$setup5_mapsettings_lat = PFSAIssetControl('setup5_mapsettings_lat','','');
										$setup5_mapsettings_lng = PFSAIssetControl('setup5_mapsettings_lng','','');

										$setup5_mapsettings_lat_text = $setup5_mapsettings_lng_text = '';

										if($params['post_id'] != ''){
											$coordinates = esc_attr(get_post_meta( $params['post_id'], 'webbupointfinder_items_location', true ));
											if(isset($coordinates)){
												$coordinates = explode(',', $coordinates);
												$setup5_mapsettings_lat = $setup5_mapsettings_lat_text = $coordinates[0];
												$setup5_mapsettings_lng = $setup5_mapsettings_lng_text = $coordinates[1];
											}
										}


										$this->ScriptOutput .= "
											
											$('#pfupload_map').gmap3({
											  map:{
												  options:{
													center:[".esc_js($setup5_mapsettings_lat).",".esc_js($setup5_mapsettings_lng)."],
													zoom: ".esc_js($setup5_mapsettings_zoom).", 
													mapTypeId: google.maps.MapTypeId.".$setup5_mapsettings_type.",
													mapTypeControl: true,
													mapTypeControlOptions: {
											          style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
											          position: google.maps.ControlPosition.RIGHT_BOTTOM
											        },
													zoomControl: true,
													zoomControlOptions: {
											          position: google.maps.ControlPosition.LEFT_BOTTOM
											        },
													panControl: false,
													scaleControl: false,
													navigationControl: false,
													draggable:true,
													scrollwheel: false,
													streetViewControl: false,
												  }
											  },
											  marker:{
											    values:[{
											    	latLng:[".esc_js($setup5_mapsettings_lat).",".esc_js($setup5_mapsettings_lng)."],
											    }],
											    options:{
											      draggable: true
											    },
											    events:{
											    	dragend: function(marker){
											    		$('#pfupload_lat_coordinate').val(marker.getPosition().lat());
											    		$('#pfupload_lng_coordinate').val(marker.getPosition().lng());
											    	},
											    },
											  },

											});
										
										";

										$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';

											$this->FieldOutput .= '<div class="row">';


											$this->FieldOutput .= '<div class="col6 first"><div id="pfupload_lat">';
												 $this->FieldOutput .= '<label for="pfupload_lat" class="lbl-text">'.esc_html__('Lat Coordinate','pointfindert2d').':</label>
				                                <label class="lbl-ui">
				                                	<input type="text" name="pfupload_lat" id="pfupload_lat_coordinate" class="input" value="'.$setup5_mapsettings_lat_text.'" />
				                                </label>';
											$this->FieldOutput .= '</div></div>';/*inner*//*col6 first*/



											$this->FieldOutput .= '<div class="col6 last colspacer-two"><div id="pfupload_lng">';
												$this->FieldOutput .= '<label for="pfupload_lng" class="lbl-text">'.esc_html__('Lng Coordinate','pointfindert2d').':</label>
				                                <label class="lbl-ui">
				                                	<input type="text" name="pfupload_lng" id="pfupload_lng_coordinate" class="input" value="'.$setup5_mapsettings_lng_text.'"/>
				                                </label>';
											$this->FieldOutput .= '</div></div>';/*inner*//*col6 last*/


											$this->FieldOutput .= '<div>';/*row*/
										$this->FieldOutput .= '</section>';
										wp_enqueue_script('theme-svginject'); 
										$this->ScriptOutput .= '
											if ($(".pf-search-locatemebut").length) {$(".pf-search-locatemebut").svgInject();};
											$("#pf_search_geolocateme").on("click",function(){
												$(".pf-search-locatemebut").hide("fast"); $(".pf-search-locatemebutloading").show("fast");
												$("#pfupload_map").gmap3({
													getgeoloc:{
														callback : function(latLng){
														  if (latLng){
															var geocoder = new google.maps.Geocoder();
															geocoder.geocode({"latLng": latLng}, function(results, status) {
															    if (status == google.maps.GeocoderStatus.OK) {
															      if (results[0]) {
															      	var map = $("#pfupload_map").gmap3("get");
															        map.setCenter(latLng);
															        map.setZoom(17);
															    	var marker =  $("#pfupload_map").gmap3({get:"marker"});
															    	marker.setPosition(latLng);

															        $("#pfupload_address").val(results[0].formatted_address);
															        $("#pfupload_lat_coordinate").val(latLng.lat());
											    					$("#pfupload_lng_coordinate").val(latLng.lng());
															      } 
															    }
															});

														  }
														  $(".pf-search-locatemebut").show("fast");
														  $(".pf-search-locatemebutloading").hide("fast");
														}
													  },
												});
												return false;
											});

											var map = $("#pfupload_map").gmap3("get");
											var input = document.getElementById("pfupload_address");
											$("#pfupload_address").bind("keypress", function(e) {
											  if (e.keyCode == 13) {               
											    e.preventDefault();
											    return false;
											  }
											});
											
											var autocomplete = new google.maps.places.Autocomplete(input);
											autocomplete.bindTo("bounds", map);
											
											google.maps.event.addListener(autocomplete, "place_changed", function() {
										 
										   
											    var place = autocomplete.getPlace();
											    if (!place.geometry) {
											      return;
											    }

										    
											    if (place.geometry.viewport) {
											      map.fitBounds(place.geometry.viewport);
											    } else {
											      map.setCenter(place.geometry.location);
											      map.setZoom(17);
											    }
										    	var marker =  $("#pfupload_map").gmap3({get:"marker"});
										    	marker.setPosition(place.geometry.location);
												$("#pfupload_lat_coordinate").val(marker.getPosition().lat());
											    $("#pfupload_lng_coordinate").val(marker.getPosition().lng());
											});
											';
									}
									$this->FieldOutput .= '</section>';
								}
							/**
							*Map & Locations
							**/



							if ($setup4_submitpage_imageupload == 1) {
								$setup4_submitpage_status_old = PFSAIssetControl('setup4_submitpage_status_old','','0');
								$this->FieldOutput .= '<div class="pfsubmit-title">'.esc_html__('IMAGE UPLOAD','pointfindert2d' ).'</div>';
								$this->FieldOutput .= '<section class="pfsubmit-inner">';
								
								/*if this is an ie9 or 8*/
								if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9') !== false || $setup4_submitpage_status_old == 1) {
									/** 
									*Featured Image Upload 
									**/
										if ($params['post_id'] != '') {
											$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $params['post_id'] ), 'full' );
											if(PFControlEmptyArr($featured_image)){
												$featured_image = aq_resize($featured_image[0],90,90,true);
													$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
													$this->FieldOutput .= '<label for="file" class="lbl-text">'.esc_html__('FEATURED IMAGE','pointfindert2d').' '.esc_html__('(Allowed: JPG,GIF,PNG)','pointfindert2d').':</label><small style="margin-bottom:4px">'.esc_html__('This is the main image for show on the listing and info window.','pointfindert2d').'<br>'.esc_html__('You have to remove it first to change.','pointfindert2d').'</small>';
													$this->FieldOutput .= '
													<div class="pf-itemimage-container">
							                            <img src="'.$featured_image.'">
							                            <div class="pf-itemimage-delete"><a id="pf-delete-featuredimg" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=edititem&i='.$params['post_id'].'&action=delfimg" title="'.esc_html__('Remove','pointfindert2d').'"><i class="pfadmicon-glyph-707"></i></a></div>
						                            </div>
													';
													$this->ScriptOutput .= '
													$("#pf-delete-featuredimg").click(function(){
													    return confirm("'.esc_html__('Are you sure want to delete this item? (This action can not be rollback.)','pointfindert2d').'");
													});
													';
												$this->FieldOutput .= '</section>';
												$showfeaturedimage = 0;
											}else{
												$showfeaturedimage = 1;
											}
										}else{$showfeaturedimage = 1;}

										if($showfeaturedimage == 1){
												$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
												$this->FieldOutput .= '<label for="file" class="lbl-text">'.esc_html__('FEATURED IMAGE','pointfindert2d').' '.esc_html__('(Allowed: JPG,GIF,PNG)','pointfindert2d').':</label><small style="margin-bottom:4px">'.esc_html__('This is the main image for show on the listing and info window.','pointfindert2d').'</small>';
												$this->FieldOutput .= '
					                            <label for="file" class="lbl-ui file-input">
					                            <span class="button">
					                            <input type="file" class="pfuploadimagesrc" name="pfuploadfeaturedimg" onChange="this.parentNode.nextSibling.value = this.value">'.esc_html__('Choose File ','pointfindert2d').'
					                            </span><input type="text" class="pfuploadimagesrctxt input" name="pfuploadimagesrctxt" placeholder="'.esc_html__('no file selected','pointfindert2d').'"  readonly>
					                            </label> 
												';
											
											$this->FieldOutput .= '</section>';
											if($setup4_submitpage_featuredverror_status == 1){
												$this->PFValidationCheckWrite(1,$setup4_submitpage_featuredverror,'pfuploadfeaturedimg');
											}
										}
										
									/** 
									*Featured Image Upload 
									**/



									/**
									*Image Upload
									**/ 
										
										$setup42_itempagedetails_configuration = (isset($pointfindertheme_option['setup42_itempagedetails_configuration']))? $pointfindertheme_option['setup42_itempagedetails_configuration'] : array();
										$images_count = 0;
										if($setup42_itempagedetails_configuration['gallery']['status'] == 1){
											
											if ($params['post_id'] != '') {
												$images_of_thispost = get_post_meta($params['post_id'],'webbupointfinder_item_images');
												if (PFControlEmptyArr($images_of_thispost)) {
													$images_count = count($images_of_thispost);
													$output_images = '';
													foreach ($images_of_thispost as $image_number) {
														$image_src = wp_get_attachment_image_src( esc_attr($image_number), 'thumbnail' );
														$output_images .= '<li>';
														$output_images .= '<div class="pf-itemimage-container">';
														$output_images .= '<img src="'.aq_resize($image_src[0],90,90,true).'">';
														$output_images .= '<div class="pf-itemimage-delete"><a id="pf-delete-standartimg'.esc_attr($image_number).'" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=edititem&i='.$params['post_id'].'&action=delimg&ii='.$image_number.'" title="'.esc_html__('Remove','pointfindert2d').'"><i class="pfadmicon-glyph-707"></i></a></div>';
														$output_images .= '</div>';
														$output_images .= '</li>';
														$this->ScriptOutput .= '
														$("#pf-delete-standartimg'.esc_attr($image_number).'").click(function(){
														    return confirm("'.esc_html__('Are you sure want to delete this item? (This action can not be rollback.)','pointfindert2d').'");
														});
														';
													}
													$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
														$this->FieldOutput .= '<label for="file" class="lbl-text">'.esc_html__('UPLOADED IMAGES','pointfindert2d').':</label>';
														$this->FieldOutput .= '<ul class="pfimages-ul">'.$output_images.'</ul>';
														
													$this->FieldOutput .= '</section>';
													
													if($setup4_submitpage_imagelimit >= $images_count){$showstandartimage = 1;}else{$showstandartimage = 0;}
													
												}else{
													$showstandartimage = 1;
												}
											}else{
												$showstandartimage = 1;
											}

											if($showstandartimage == 1){
												
												if ($setup4_submitpage_imageupload == 1) {
													$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
													$this->FieldOutput .= '<label for="file" class="lbl-text">'.esc_html__('UPLOAD NEW IMAGES','pointfindert2d').': ('.esc_html__('MAX','pointfindert2d').': '.$setup4_submitpage_imagelimit.') '.esc_html__('(Allowed: JPG,GIF,PNG)','pointfindert2d').'</label>';
													$this->FieldOutput .= '<button class="button" id="pfupload-addimage-button" style="font-size: 12px;line-height: 14px;"><i class="pfadmicon-plus" style="font-size: 14px;"></i> '.esc_html__('Add More','pointfindert2d').'</button>  ';
													$this->FieldOutput .= '<button class="button" id="pfupload-removeimage-button" style="font-size: 12px;line-height: 14px;"><i class="pfadmicon-minus" style="font-size: 14px;"></i> '.esc_html__('Remove','pointfindert2d').'</button> ';
													$this->FieldOutput .= '</section>';

													$this->FieldOutput .= '<section class="pfupload-imagesrow">';
													$this->FieldOutput .= '<div class="pfupload-imagerow pfrownum-0">';
														$this->FieldOutput .= '
							                            <label for="file" class="lbl-ui file-input">
							                            <span class="button">
							                            <input type="file" class="pfuploadimagesrc" name="pfuploadimagesrc" onChange="this.parentNode.nextSibling.value = this.value">'.esc_html__('Choose File ','pointfindert2d').'
							                            </span><input type="text" class="pfuploadimagesrctxt input" name="pfuploadimagesrctxt" placeholder="'.esc_html__('no file selected','pointfindert2d').'"  readonly>
							                            </label> 
														';
													$this->FieldOutput .= '</div>';
													$this->FieldOutput .= '</section>';

													$images_newlimit = $setup4_submitpage_imagelimit - $images_count;

													$this->ScriptOutput .= "
													$.pfrowNum = 1;
													$('#pfupload-addimage-button').click(function(e){
														e.preventDefault(e);
														if(($.pfrowNum + 1) <= ".esc_js($images_newlimit)."){
															var pfclone_div = $('.pfupload-imagerow')
															.first()
															.clone()
															.switchClass('pfrownum-0','pfrownum-'+$.pfrowNum);

															pfclone_div.find('.pfuploadimagesrc').attr('name','pfuploadimagesrc'+$.pfrowNum);
															pfclone_div.find('.pfuploadimagesrctxt').val('');
															
															pfclone_div.appendTo('.pfupload-imagesrow');
															$.pfrowNum ++;
														}else{
															alert('".esc_html__('Image upload limit reached.','pointfindert2d')."')
														}
													});
													
													$('#pfupload-removeimage-button').click(function(e){
														e.preventDefault(e);
														if($.pfrowNum >= 2){
															$('.pfupload-imagesrow').find('.pfupload-imagerow').last().remove();
															$.pfrowNum --;
														}
													});
													";
												}
											}
										}
										
									/**
									*Image Upload
									**/
								}elseif ($setup4_submitpage_status_old == 0) {
								
									/**
									*Dropzone Upload
									**/
										$setup42_itempagedetails_configuration = (isset($pointfindertheme_option['setup42_itempagedetails_configuration']))? $pointfindertheme_option['setup42_itempagedetails_configuration'] : array();
										$images_count = 0;
										if($setup42_itempagedetails_configuration['gallery']['status'] == 1 && $setup4_submitpage_imageupload == 1){
											
											$images_of_thispost = get_post_meta($params['post_id'],'webbupointfinder_item_images');
											$images_count = count($images_of_thispost) + 1;

											$this->FieldOutput .= '<div class="pfuploadedimages"></div>';

											/* Validation for upload */
											if ($params['formtype'] != 'edititem' && $setup4_submitpage_featuredverror_status == 1) {
											if($this->VSOMessages != ''){
												$this->VSOMessages .= ',pfuploadimagesrc:"'.$setup4_submitpage_featuredverror.'"';
											}else{
												$this->VSOMessages = 'pfuploadimagesrc:"'.$setup4_submitpage_featuredverror.'"';
											}

											if($this->VSORules != ''){
												$this->VSORules .= ',pfuploadimagesrc:"required"';
											}else{
												$this->VSORules = 'pfuploadimagesrc:"required"';
											}
											}
											if ($params['formtype'] != 'edititem') {
												$upload_limited = $setup4_submitpage_imagelimit;
											}else{
												$upload_limited = $setup4_submitpage_imagelimit - $images_count;
											}
											$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
											

											$setup4_submitpage_imagesizelimit = PFSAIssetControl('setup4_submitpage_imagesizelimit','','2');/*Image size limit*/

											$this->FieldOutput .= '<div id="pfdropzoneupload" class="dropzone"></div>';
											if ($params['formtype'] != 'edititem') {
											$this->FieldOutput .= '<input type="hidden" class="pfuploadimagesrc" name="pfuploadimagesrc" id="pfuploadimagesrc">';
											}
											$this->FieldOutput .= '
											<script type="text/javascript">
											(function($) {
											"use strict";
												$(function(){
													';
													if(!empty($params['post_id'])){
													$this->FieldOutput .= '$.pfitemdetail_listimages('.$params['post_id'].');';
													}
													$this->FieldOutput .= '
													console.log("'.$upload_limited.'");
													$.drzoneuploadlimit = '.$upload_limited.';
													var myDropzone = new Dropzone("div#pfdropzoneupload", {
														url: theme_scriptspf.ajaxurl,
														params: {
													      action: "pfget_imageupload",
													      security: "'.wp_create_nonce('pfget_imageupload').'",
													      ';
													      if ($params['formtype'] == 'edititem') {
													      	$this->FieldOutput .= ' id:'.$params['post_id'];
													      }
														$this->FieldOutput .= ' 
													    },
														autoProcessQueue: true,
														acceptedFiles:"image/*",
														maxFilesize: '.$setup4_submitpage_imagesizelimit.',
														maxFiles: '.$upload_limited.',
														parallelUploads:1,
														uploadMultiple: false,
														';
													      if ($params['formtype'] != 'edititem') {
													      	$this->FieldOutput .= 'addRemoveLinks:true,';
													      }
														$this->FieldOutput .= ' 
														dictDefaultMessage: "'.esc_html__( 'Drop files here to upload!','pointfindert2d').'<br/>'.esc_html__( 'You can add up to','pointfindert2d').' <div class=\'pfuploaddrzonenum\'>{0}</div> '.esc_html__( 'image(s)','pointfindert2d').' '.sprintf(esc_html__('(Max. File Size: %dMB per image)','pointfindert2d'),$setup4_submitpage_imagesizelimit).' ".format($.drzoneuploadlimit),
														dictFallbackMessage: "'.esc_html__( 'Your browser does not support drag and drop file upload', 'pointfindert2d' ).'",
														dictInvalidFileType: "'.esc_html__( 'Unsupported file type', 'pointfindert2d' ).'",
														dictFileTooBig: "'.sprintf(esc_html__( 'File size is too big. (Max file size: %dmb)', 'pointfindert2d' ),$setup4_submitpage_imagesizelimit).'",
														dictCancelUpload: "",
														dictRemoveFile: "'.esc_html__( 'Remove', 'pointfindert2d' ).'",
														dictMaxFilesExceeded: "'.esc_html__( 'Max file excited', 'pointfindert2d' ).'",
														clickable: "#pf-ajax-fileuploadformopen"
													});
													
													Dropzone.autoDiscover = false;
													
													var uploadeditems = new Array();

													myDropzone.on("success", function(file,responseText) {
														var obj = [];
														$.each(responseText, function(index, element) {
															obj[index] = element;
														});
														';
													
													    if ($params['formtype'] != 'edititem') {
													    $this->FieldOutput .= '

														if (obj.process == "up" && obj.id.length != 0) {
															file._removeLink.id = obj.id;
															uploadeditems.push(obj.id);
															$("#pfuploadimagesrc").val(uploadeditems);
														}
														';
														}else{
															$this->FieldOutput .= '
															
															$(".pfuploaddrzonenum").text($.drzoneuploadlimit -1);
															$.drzoneuploadlimit = $.drzoneuploadlimit -1
													    	$.pfitemdetail_listimages('.$params['post_id'].');
													      	';
														}
													    
													$this->FieldOutput .= ' 
														
													});

													myDropzone.on("totaluploadprogress",function(uploadProgress){
														if (uploadProgress > 0) {
															$("#pf-ajax-uploaditem-button").val("'.esc_html__( 'Please Wait for Image Upload...', 'pointfindert2d' ).'");
															$("#pf-ajax-uploaditem-button").attr("disabled", true);
														}
													});
													';
													if ($params['formtype'] != 'edititem') {
													$this->FieldOutput .= ' 	
													myDropzone.on("removedfile", function(file) {
													    if (file.upload.progress != 0) {
															if(file._removeLink.id.length != 0){
																var removeditem = file._removeLink.id;
																removeditem.replace(\'"\', "");
																$.ajax({
																    type: "POST",
																    dataType: "json",
																    url: theme_scriptspf.ajaxurl,
																    data: { 
																        action: "pfget_imageupload",
														      			security: "'.wp_create_nonce('pfget_imageupload').'",
														      			iid:removeditem
																    }
																});
																for(var i = uploadeditems.length; i--;) {
															          if(uploadeditems[i] == removeditem) {
															              uploadeditems.splice(i, 1);
															          }
															      }
																
																$("#pfuploadimagesrc").val(uploadeditems);
															}
													    }
													});
													

													myDropzone.on("queuecomplete",function(file){
														$("#pf-ajax-uploaditem-button").attr("disabled", false);
														$("#pf-ajax-uploaditem-button").val("'.PFSAIssetControl('setup29_dashboard_contents_submit_page_menuname','','').'");
													});
													';
													}else{
														$this->FieldOutput .= '
															myDropzone.on("queuecomplete",function(file){
																myDropzone.removeAllFiles();
															});
															
															myDropzone.on("queuecomplete",function(file){
																$("#pf-ajax-uploaditem-button").attr("disabled", false);
																$("#pf-ajax-uploaditem-button").val("'.PFSAIssetControl('setup29_dashboard_contents_submit_page_titlee','','').'");
															});
														';
													}
												$this->FieldOutput .= ' 	
												});
												
											})(jQuery);
											</script>
											
											<a id="pf-ajax-fileuploadformopen" class="button pfmyitempagebuttonsex" style="width:100%">'.esc_html__( 'Click to select photos', 'pointfindert2d' ).'</a>
											';
											$this->FieldOutput .= '</section>';
										}
									/**
									*Dropzone Upload
									**/
								}
								$this->FieldOutput .= '</section>';
							}



							/**
							*Message to Reviewer
							**/
								if($setup4_submitpage_messagetorev == 1){

									$this->FieldOutput .= '<div class="pfsubmit-title">'.esc_html__('Message to Reviewer','pointfindert2d').'</div>';
									$this->FieldOutput .= '<section class="pfsubmit-inner">';
									$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
									$this->FieldOutput .= '
				                        <label class="lbl-ui">
				                        	<textarea id="item_mesrev" name="item_mesrev" class="textarea mini"></textarea>';
									$this->FieldOutput .= '<b class="tooltip left-bottom"><em>'.esc_html__('OPTIONAL:','pointfindert2d').esc_html__('You can send a message to reviewer.','pointfindert2d').'</em></b>';
									 
				                    $this->FieldOutput .= '</label>';
				                    $this->FieldOutput .= '</section>';                     
				                  	$this->FieldOutput .= '</section>'; 
									
								}
								
							/**
							*Message to Reviewer
							**/



							/**
							*Terms and conditions
							**/
								if($this->VSOMessages != ''){
									$this->VSOMessages .= ',pftermsofuser:"'.esc_html__( 'You must accept terms and conditions.', 'pointfindert2d' ).'"';
								}else{
									$this->VSOMessages = 'pftermsofuser:"'.esc_html__( 'You must accept terms and conditions.', 'pointfindert2d' ).'"';
								}

								if($this->VSORules != ''){
									$this->VSORules .= ',pftermsofuser:"required"';
								}else{
									$this->VSORules = 'pftermsofuser:"required"';
								}

								global $wpdb;
								$terms_conditions_template = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s ",'_wp_page_template','terms-conditions.php'), ARRAY_A);
								if (isset($terms_conditions_template[0]['post_id'])) {
									$terms_permalink = get_permalink($terms_conditions_template[0]['post_id']);
								}else{
									$terms_permalink = '#';
								}
								
								
								if ($params['formtype'] == 'edititem') {
									$checktext1 = ' checked=""';
								}else{$checktext1 = '';}

								$this->FieldOutput .= '<section>';
								$this->FieldOutput .= '
									<span class="goption upt">
	                                    <label class="options">
	                                        <input type="checkbox" id="pftermsofuser" name="pftermsofuser" value="1"'.$checktext1.'>
	                                        <span class="checkbox"></span>
	                                    </label>
	                                    <label for="check1">'.sprintf(esc_html__( 'I have read the %s terms and conditions %s and accept them.', 'pointfindert2d' ),'<a href="'.$terms_permalink.'" target="_blank"><strong>','</strong></a>').'</label>
	                               </span>
								';
								
				                $this->FieldOutput .= '</section>';
							/**
							*Terms and conditions
							**/


					
					/** 
					*End : Second Column (Map area, Image upload etc..)
					**/


				/**
				*End: New Item Page Content
				**/
					}
					break;






				case 'profile':
				/**
				*Start: Profile Page Content
				**/
						$noncefield = wp_create_nonce('pfget_updateuserprofile');
						$formaction = 'pfget_updateuserprofile';
						$buttonid = 'pf-ajax-profileupdate-button';
						$buttontext = esc_html__('UPDATE INFO','pointfindert2d');
						$current_user = get_user_by( 'id', $params['current_user'] ); 
						$user_id = $current_user->ID;
						$usermetaarr = get_user_meta($user_id);
						
						if(!isset($usermetaarr['first_name'])){$usermetaarr['first_name'][0] = '';}
						if(!isset($usermetaarr['last_name'])){$usermetaarr['last_name'][0] = '';}
						if(!isset($usermetaarr['user_phone'])){$usermetaarr['user_phone'][0] = '';}
						if(!isset($usermetaarr['user_phone'])){$usermetaarr['user_phone'][0] = '';}
						if(!isset($usermetaarr['user_mobile'])){$usermetaarr['user_mobile'][0] = '';}
						if(!isset($usermetaarr['description'])){$usermetaarr['description'][0] = '';}
						if(!isset($usermetaarr['nickname'])){$usermetaarr['nickname'][0] = '';}
						if(!isset($usermetaarr['user_twitter'])){$usermetaarr['user_twitter'][0] = '';}
						if(!isset($usermetaarr['user_facebook'])){$usermetaarr['user_facebook'][0] = '';}
						if(!isset($usermetaarr['user_googleplus'])){$usermetaarr['user_googleplus'][0] = '';}
						if(!isset($usermetaarr['user_linkedin'])){$usermetaarr['user_linkedin'][0] = '';}

						if(!isset($usermetaarr['user_photo'])){
							$usermetaarr['user_photo'][0] = '<img src= "'.get_template_directory_uri().'/images/noimg.png">';
						}else{
							if($usermetaarr['user_photo'][0]!= ''){
								$usermetaarr['user_photo'][0] = wp_get_attachment_image( $usermetaarr['user_photo'][0] );
							}else{
								$usermetaarr['user_photo'][0] = '<img src= "'.get_template_directory_uri().'/images/noimg.png" width:"50" height="50">';
							}
						}

						$this->ScriptOutput = "
							$.pfAjaxUserSystemVars4 = {};
							$.pfAjaxUserSystemVars4.email_err = '".esc_html__('Please write an email','pointfindert2d')."';
							$.pfAjaxUserSystemVars4.email_err2 = '".esc_html__('Your email address must be in the format of name@domain.com','pointfindert2d')."';
							$.pfAjaxUserSystemVars4.nickname_err = '".esc_html__('Please write nickname','pointfindert2d')."';
							$.pfAjaxUserSystemVars4.nickname_err2 = '".esc_html__('Please enter at least 3 characters for nickname.','pointfindert2d')."';
							$.pfAjaxUserSystemVars4.passwd_err = '".esc_html__('Enter at least 7 characters','pointfindert2d')."';
							$.pfAjaxUserSystemVars4.passwd_err2 = '".esc_html__('Enter the same password as above','pointfindert2d')."';
						";

						$this->FieldOutput .= '
                           <div class="col6 first">
                           	   <section>
                                    <label for="username" class="lbl-text"><strong>'.esc_html__('User Name','pointfindert2d').'</strong>:</label>
                                    <label class="lbl-ui">
                                    	<input type="text" name="username" class="input" value="'.$current_user->user_login.'" />
                                    	<input type="hidden" name="username_old" class="input" value="'.$current_user->user_login.'" />
                                    </label>
                               </section>
                               <section>
                                    <label for="email" class="lbl-text"><strong>'.esc_html__('Email Address','pointfindert2d').'(*)</strong>:</label>
                                    <label class="lbl-ui">
                                    	<input  type="email" name="email" class="input" value="'.$current_user->user_email.'" />
                                    </label>
                                </section>
                               <section>
                                    <label for="nickname" class="lbl-text"><strong>'.esc_html__('Nickname (Display Name)','pointfindert2d').'(*)</strong>:</label>
                                    <label class="lbl-ui">
                                    	<input  type="text" name="nickname" class="input" value="'.$usermetaarr['nickname'][0].'" />
                                    </label>
                                </section>
                               <section>
                                    <label for="descr" class="lbl-text">'.esc_html__('Biographical Info','pointfindert2d').':</label>
                                    <label class="lbl-ui">
                                    	<textarea name="descr" class="textarea mini no-resize">'.$usermetaarr['description'][0].'</textarea>
                                    </label>                          
                               </section> 
                               <section>
                                    <label for="userphoto" class="lbl-text">'.esc_html__('User Photo (Recommend:200px W/H)','pointfindert2d').' (.jpg, .png, .gif):</label>
                                    <div class="col-lg-3">
                                    <div class="pfuserphoto-container">
                               		'.$usermetaarr['user_photo'][0].'
                               		</div>
                               		</div>
                                    <div class="col-lg-9">
                                    <label for="userphoto" class="lbl-ui file-input">
                                    <input type="file" name="userphoto" />
                                    <div class="clearfix" style="margin-bottom:10px"></div>     
                                    <span class="goption">
		                                <label class="options">
		                                    <input type="checkbox" name="deletephoto" value="1">
		                                    <span class="checkbox"></span>
		                                </label>
		                                <label for="check1">'.esc_html__('Remove Photo','pointfindert2d').'</label>
		                           </span>
                                    </div>
                                    </label>  
                                    <div class="clearfix"></div>             
                               </section>
                               <section>
                                    <label for="password" class="lbl-text">'.esc_html__('New Password','pointfindert2d').':</label>
                                    <label class="lbl-ui">
                                    	<input type="password" name="password" id="password" class="input" />
                                    </label>                          
                               </section> 
                               <section>
                                    <label for="password2" class="lbl-text">'.esc_html__('Repeat New Password','pointfindert2d').':</label>
                                    <label class="lbl-ui">
                                    	<input type="password" name="password2" class="input" />
                                    </label>                          
                               </section>   
                               <section><small><strong>
                               		'. esc_html__('Hint:','pointfindert2d').'</strong> '. esc_html__('The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ & ).','pointfindert2d').'</small>
                               </section>                             
                           </div>


                           <div class="col6 last">
                           		<section>
                                    <label for="firstname" class="lbl-text">'.esc_html__('First name','pointfindert2d').':</label>
                                    <label class="lbl-ui">
                                    	<input type="text" name="firstname" class="input" value="'.$usermetaarr['first_name'][0].'" />
                                    </label>
                                </section>
                           		<section>
                                    <label for="lastname" class="lbl-text">'.esc_html__('Last Name','pointfindert2d').':</label>
                                    <label class="lbl-ui">
                                    	<input type="text" name="lastname" class="input" value="'.$usermetaarr['last_name'][0].'" />
                                    </label>
                                </section>                                                           
                           		<section>
                                    <label for="webaddr" class="lbl-text">'.esc_html__('Website','pointfindert2d').':</label>
                                    <label class="lbl-ui">
                                    	<input type="text" name="webaddr" class="input" value="'.$current_user->user_url.'" />
                                    </label>
                                </section>
                                <section>
                                    <label for="phone" class="lbl-text">'.esc_html__('Telephone','pointfindert2d').':</label>
                                    <label class="lbl-ui">
                                    	<input type="tel" name="phone" class="input" placeholder="" value="'.$usermetaarr['user_phone'][0].'" />
                                    </label>                            
                                </section> 
                                <section>
                                    <label for="mobile" class="lbl-text">'.esc_html__('Mobile','pointfindert2d').':</label>
                                    <label class="lbl-ui">
                                    	<input type="tel" name="mobile" class="input" placeholder="" value="'.$usermetaarr['user_mobile'][0].'"/>
                                    </label>                            
                                </section> 
                                <section>
                                    <label for="twitter" class="lbl-text">'.esc_html__('Twitter','pointfindert2d').':</label>
                                    <label class="lbl-ui">
                                    	<input type="text" name="twitter" class="input" value="'.$usermetaarr['user_twitter'][0].'"/>
                                    </label>
                                </section>   
                                <section>
                                    <label for="facebook" class="lbl-text">'.esc_html__('Facebook','pointfindert2d').':</label>
                                    <label class="lbl-ui">
                                    	<input type="text" name="facebook" class="input" value="'.$usermetaarr['user_facebook'][0].'" />
                                    </label>
                                </section> 
                                <section>
                                    <label for="googleplus" class="lbl-text">'.esc_html__('Google+','pointfindert2d').':</label>
                                    <label class="lbl-ui">
                                    	<input type="text" name="googleplus" class="input" value="'.$usermetaarr['user_googleplus'][0].'" />
                                    </label>
                                </section> 
                                <section>
                                    <label for="linkedin" class="lbl-text">'.esc_html__('LinkedIn','pointfindert2d').':</label>
                                    <label class="lbl-ui">
                                    	<input type="text" name="linkedin" class="input" value="'.$usermetaarr['user_linkedin'][0].'"/>
                                    </label>
                                </section>                         
                           </div>
			            ';
		        /**
				*End: Profile Page Content
				**/
					break;





				case 'myitems':
				/**
				*Start: My Items Page Content
				**/
					$formaction = 'pf_refineitemlist';
					$noncefield = wp_create_nonce($formaction);
					$buttonid = 'pf-ajax-itemrefine-button';

					/**
					*Start: Content Area
					**/
						$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
						$setup3_pointposttype_pt7 = PFSAIssetControl('setup3_pointposttype_pt7','','Listing Types');

						/*User Limits*/
						$setup31_userlimits_useredit = PFSAIssetControl('setup31_userlimits_useredit','','1');
						$setup31_userlimits_userdelete = PFSAIssetControl('setup31_userlimits_userdelete','','1');
						$setup31_userlimits_useredit_pending = PFSAIssetControl('setup31_userlimits_useredit_pending','','1');
						$setup31_userlimits_userdelete_pending = PFSAIssetControl('setup31_userlimits_userdelete_pending','','1');

						$setup4_membersettings_loginregister = PFSAIssetControl('setup4_membersettings_loginregister','','1');
						$setup11_reviewsystem_check = PFREVSIssetControl('setup11_reviewsystem_check','','0');
						$setup31_userpayments_featuredoffer = PFSAIssetControl('setup31_userpayments_featuredoffer','','1');



						$this->FieldOutput .= '<div class="pfmu-itemlisting-container">';
							if ($params['fields']!= '') {
								$fieldvars = $params['fields'];
							}else{
								$fieldvars = '';
							}

							$selected_lfs = $selected_lfl = $selected_lfo2 = $selected_lfo = '';

							if (PFControlEmptyArr($fieldvars)) {
								
	                            if(isset($fieldvars['listing-filter-status'])){
	                           		if ($fieldvars['listing-filter-status'] != '') {
	                           			$selected_lfs = $fieldvars['listing-filter-status'];
	                           		}
	                            }

		                        if(isset($fieldvars['listing-filter-ltype'])){
		                       		if ($fieldvars['listing-filter-ltype'] != '') {
		                       			$selected_lfl = $fieldvars['listing-filter-ltype'];
		                       		}
		                        }

	                            if(isset($fieldvars['listing-filter-orderby'])){
	                           		if ($fieldvars['listing-filter-orderby'] != '') {
	                           			$selected_lfo = $fieldvars['listing-filter-orderby'];
	                           		}
	                            }

	                            if(isset($fieldvars['listing-filter-order'])){
	                           		if ($fieldvars['listing-filter-order'] != '') {
	                           			$selected_lfo2 = $fieldvars['listing-filter-order'];
	                           		}
	                            }

							}

							$current_user = wp_get_current_user();
							$user_id = $current_user->ID;

							$paged = ( esc_sql(get_query_var('paged')) ) ? esc_sql(get_query_var('paged')) : '';
							if (empty($paged)) {
								$paged = ( esc_sql(get_query_var('page')) ) ? esc_sql(get_query_var('page')) : 1;
							}

							$output_args = array(
									'post_type'	=> $setup3_pointposttype_pt1,
									'author' => $user_id,
									'posts_per_page' => 10,
									'paged' => $paged,
									'order'	=> 'DESC',
									'orderby' => 'ID'
								);

							if($selected_lfs != ''){$output_args['post_status'] = $selected_lfs;}
							if($selected_lfo != ''){$output_args['orderby'] = $selected_lfo;}
							if($selected_lfo2 != ''){$output_args['order'] = $selected_lfo2;}
							if($selected_lfl != ''){
								$output_args['tax_query']=
									array(
										'relation' => 'AND',
										array(
											'taxonomy' => 'pointfinderltypes',
											'field' => 'id',
											'terms' => $selected_lfl,
											'operator' => 'IN'
										)
									);
							}

							

							if($params['post_id'] != ''){
								$output_args['p'] = $params['post_id'];
							}

							$output_loop = new WP_Query( $output_args );

							/**
							*Header for search
							**/
								
								if($params['sheader'] != 'hide'){
									
									$this->FieldOutput .= '<section><div class="row"><div class="col1-5 first">';
										
										$this->FieldOutput .= '<label for="listing-filter-status" class="lbl-ui select">
			                              <select id="listing-filter-status" name="listing-filter-status">';

			                                $this->FieldOutput .= '<option value="">'.esc_html__('Status','pointfindert2d').'</option>';
			                                $this->FieldOutput  .= ($selected_lfs == 'publish') ? '<option value="publish" selected>'.esc_html__('Published','pointfindert2d').'</option>' : '<option value="publish">'.esc_html__('Published','pointfindert2d').'</option>';
			                                $this->FieldOutput  .= ($selected_lfs == 'pendingapproval') ? '<option value="pendingapproval" selected>'.esc_html__('Pending Approval','pointfindert2d').'</option>' : '<option value="pendingapproval">'.esc_html__('Pending Approval','pointfindert2d').'</option>';
			                                $this->FieldOutput  .= ($selected_lfs == 'pendingpayment') ? '<option value="pendingpayment" selected>'.esc_html__('Pending Payment','pointfindert2d').'</option>' : '<option value="pendingpayment">'.esc_html__('Pending Payment','pointfindert2d').'</option>';
			                                $this->FieldOutput  .= ($selected_lfs == 'rejected') ? '<option value="rejected" selected>'.esc_html__('Rejected','pointfindert2d').'</option>' : '<option value="rejected">'.esc_html__('Rejected','pointfindert2d').'</option>';
			                               
			                              $this->FieldOutput .= '
			                              </select>
			                            </label>';
			                        $this->FieldOutput .= '</div>';

			                        $this->FieldOutput .= '<div class="col1-5 first">';
										$this->FieldOutput .= '<label for="listing-filter-ltype" class="lbl-ui select">
			                              <select id="listing-filter-ltype" name="listing-filter-ltype">
			                                <option value="">'.$setup3_pointposttype_pt7.'</option>
			                                ';
			                                 
			                                $fieldvalues = get_terms('pointfinderltypes',array('hide_empty'=>false)); 
											foreach( $fieldvalues as $fieldvalue){
												
												$this->FieldOutput  .= ($selected_lfl == $fieldvalue->term_id) ? '<option value="'.$fieldvalue->term_id.'" selected>'.$fieldvalue->name.'</option>' : '<option value="'.$fieldvalue->term_id.'">'.$fieldvalue->name.'</option>';	
												
											}

			                                $this->FieldOutput .= '
			                              </select>
			                            </label>';
			                        $this->FieldOutput .= '</div>';


			                        $this->FieldOutput .= '<div class="col1-5">';
										$this->FieldOutput .= '<label for="listing-filter-orderby" class="lbl-ui select">
			                              <select id="listing-filter-orderby" name="listing-filter-orderby">';

			                                $this->FieldOutput .= '<option value="">'.esc_html__('Order By','pointfindert2d').'</option>';
			                                $this->FieldOutput  .= ($selected_lfo == 'title') ? '<option value="title" selected>'.esc_html__('Title','pointfindert2d').'</option>' : '<option value="title">'.esc_html__('Title','pointfindert2d').'</option>';
			                                $this->FieldOutput  .= ($selected_lfo == 'date') ? '<option value="date" selected>'.esc_html__('Date','pointfindert2d').'</option>' : '<option value="date">'.esc_html__('Date','pointfindert2d').'</option>';
			                                $this->FieldOutput  .= ($selected_lfo == 'ID') ? '<option value="ID" selected>'.esc_html__('ID','pointfindert2d').'</option>' : '<option value="ID">'.esc_html__('ID','pointfindert2d').'</option>';


			                              $this->FieldOutput .= '
			                              </select>
			                            </label>';
			                        $this->FieldOutput .= '</div>';

			                        $this->FieldOutput .= '<div class="col1-5">';
										$this->FieldOutput .= '<label for="listing-filter-order" class="lbl-ui select">
			                              <select id="listing-filter-order" name="listing-filter-order">';

			                                $this->FieldOutput .= '<option value="">'.esc_html__('Order','pointfindert2d').'</option>';
			                                $this->FieldOutput  .= ($selected_lfo2 == 'ASC') ? '<option value="ASC" selected>'.esc_html__('ASC','pointfindert2d').'</option>' : '<option value="ASC">'.esc_html__('ASC','pointfindert2d').'</option>';
			                                $this->FieldOutput  .= ($selected_lfo2 == 'DESC') ? '<option value="DESC" selected>'.esc_html__('DESC','pointfindert2d').'</option>' : '<option value="DESC">'.esc_html__('DESC','pointfindert2d').'</option>';

			                              $this->FieldOutput .= '
			                              </select>
			                            </label>';
			                        $this->FieldOutput .= '</div>';

			                        

			                        $this->FieldOutput .= '<div class="col1-5 last">';
										$this->FieldOutput .= '<button type="submit" value="" id="'.$buttonid.'" class="button blue pfmyitempagebuttons" title="'.esc_html__('Search','pointfindert2d').'"  ><i class="pfadmicon-glyph-627"></i></button>';
										$this->FieldOutput .= '<a class="button pfmyitempagebuttons" style="margin-left:4px;" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems" title="'.esc_html__('RESET','pointfindert2d').'"><i class="pfadmicon-glyph-825"></i></a>';

									$this->FieldOutput .= '</div></div></section>';
								}


							if ( $output_loop->have_posts() ) {
								/**
								*Start: Column Headers
								**/
								$this->FieldOutput .= '<section>';

								$this->FieldOutput .= '<div class="pfmu-itemlisting-inner pfhtitle pf-row clearfix hidden-xs">';
									$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle col-lg-1 col-md-1 col-sm-2 hidden-xs">';
									
									$this->FieldOutput .= '</div>';

									$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-4 col-md-4 col-sm-4 hidden-xs">';
									$this->FieldOutput .= esc_html__('Information','pointfindert2d');
									$this->FieldOutput .= '</div>';

									$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-2 col-md-2 col-sm-2 hidden-xs">';
									$this->FieldOutput .= esc_html__('Details','pointfindert2d');
									$this->FieldOutput .= '</div>';

									$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-2 col-md-2 col-sm-2 hidden-xs">';
									$this->FieldOutput .= esc_html__('Posted on','pointfindert2d');
									$this->FieldOutput .= '</div>';

									$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle col-lg-3 col-md-3 col-sm-2">';
									$this->FieldOutput .= '</div>';
								/**
								*End: Column Headers
								**/
								$this->FieldOutput .= '</div>';

								while ( $output_loop->have_posts() ) {
									$output_loop->the_post(); 

									$author_post_id = get_the_ID();
										
										
										
											/*Post Meta Info*/
											global $wpdb;
											$result_id = $wpdb->get_var( $wpdb->prepare( 
												"
													SELECT post_id
													FROM $wpdb->postmeta 
													WHERE meta_key = %s and meta_value = %s
												", 
												'pointfinder_order_itemid',
												$author_post_id
											) );
											

											$pointfinder_order_datetime = PFU_GetPostOrderDate($result_id);
											$pointfinder_order_datetime = PFU_Dateformat($pointfinder_order_datetime);

											$pointfinder_order_datetime_approval = esc_attr(get_post_meta( $result_id, 'pointfinder_order_datetime_approval', true ));
											$pointfinder_order_pricesign = esc_attr(get_post_meta( $result_id, 'pointfinder_order_pricesign', true ));
											$pointfinder_order_listingtime = esc_attr(get_post_meta( $result_id, 'pointfinder_order_listingtime', true ));
											$pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
											$pointfinder_order_recurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_recurring', true ));
											$pointfinder_order_expiredate = esc_attr(get_post_meta( $result_id, 'pointfinder_order_expiredate', true ));
											$pointfinder_order_bankcheck = esc_attr(get_post_meta( $result_id, 'pointfinder_order_bankcheck', true ));

											$featured_enabled = esc_attr(get_post_meta( $author_post_id, 'webbupointfinder_item_featuredmarker', true ));

											$pointfinder_order_listingtime = ($pointfinder_order_listingtime == '') ? 0 : $pointfinder_order_listingtime ;
											

											if($pointfinder_order_expiredate != ''){
												$item_listing_expiry = PFU_Dateformat($pointfinder_order_expiredate);
											}else{
												$item_listing_expiry = '';
											}
										
											$item_recurring_text = ($pointfinder_order_recurring == 1)? '('.esc_html__('Recurring','pointfindert2d').')' : '';


											$status_of_post = get_post_status($author_post_id);

											$status_of_order = get_post_status($result_id);

											switch ($status_of_post) {
												case 'pendingpayment':
													if ($status_of_order == 'pfsuspended') {
														$status_text = sprintf(esc_html__('Suspended (Required Paypal Activation)','pointfindert2d'));
														$status_payment = 1;
														$status_icon = 'pfadmicon-glyph-411';
														$status_lbl = 'lblpending';
													}else{
														$status_text = sprintf(esc_html__('Pending Payment (%d %s)','pointfindert2d'),$pointfinder_order_price, $pointfinder_order_pricesign);
														$status_payment = 0;
														$status_icon = 'pfadmicon-glyph-411';
														$status_lbl = 'lblpending';
													}
													
													break;
												
												case 'rejected':
													$status_text = esc_html__('Rejected','pointfindert2d');
													$status_payment = 1;
													$status_icon = 'pfadmicon-glyph-411';
													$status_lbl = 'lblcancel';
													break;

												case 'pendingapproval':
													$status_text = esc_html__('Pending Approval','pointfindert2d');
													$status_payment = 1;
													$status_icon = 'pfadmicon-glyph-411';
													$status_lbl = 'lblpending';
													break;

												case 'publish':
													$status_text = sprintf(esc_html__('Active until: %s','pointfindert2d'),$item_listing_expiry);
													$status_payment = 1;
													$status_icon = 'pfadmicon-glyph-411';
													$status_lbl = 'lblcompleted';
													break;
											}


											/*
												Reviews Store in $review_output:
											*/
												$setup11_reviewsystem_check = PFREVSIssetControl('setup11_reviewsystem_check','','0');
												if ($setup11_reviewsystem_check == 1) {
													global $pfitemreviewsystem_options;
													$setup11_reviewsystem_criterias = $pfitemreviewsystem_options['setup11_reviewsystem_criterias'];
													$review_status = PFControlEmptyArr($setup11_reviewsystem_criterias);

													if($review_status != false){
														$review_output = '';
														$setup11_reviewsystem_singlerev = PFREVSIssetControl('setup11_reviewsystem_singlerev','','0');
														$criteria_number = pf_number_of_rev_criteria();
														$return_results = pfcalculate_total_review($author_post_id);
														if ($return_results['totalresult'] > 0) {
															$review_output .= ''.esc_html__('Reviews','pointfindert2d').' : ';
															$review_output .= '<span class="pfiteminfolist-infotext pfreviews">';
																$review_output .=  $return_results['totalresult'].' (<a title="'.esc_html__('Review Total','pointfindert2d').'" style="cursor:pointer">'.pfcalculate_total_rusers($author_post_id).'</a>)';
															$review_output .= '</span>';
														}else{
															$review_output .= ''.esc_html__('Reviews','pointfindert2d').' : ';
															$review_output .= '<span class="pfiteminfolist-infotext pfreviews">';
																$review_output .=  '0 (<a title="'.esc_html__('Review Total','pointfindert2d').'" style="cursor:pointer">0</a>)';
															$review_output .= '</span>';
														}
													}
												}else{
													$review_output = '';
												}

											/*
												Favorites Store in $fav_output:
											*/
												$setup4_membersettings_favorites = PFSAIssetControl('setup4_membersettings_favorites','','1');
												if($setup4_membersettings_favorites == 1){
													$fav_number = esc_attr(get_post_meta( $author_post_id, 'webbupointfinder_items_favorites', true ));
													$fav_number = ($fav_number == false) ? '0' : $fav_number ;
													$fav_output = '';
													if ($fav_number > 0) {
														$fav_output .= '<span class="pfiteminfolist-title pfstatus-title pfreviews">'.esc_html__('Favorites','pointfindert2d').' : </span>';
														$fav_output .= '<span class="pfiteminfolist-infotext pfreviews">';
															$fav_output .=  $fav_number;
														$fav_output .= '</span>';
													}else{
														$fav_output .= '<span class="pfiteminfolist-title pfstatus-title pfreviews">'.esc_html__('Favorites','pointfindert2d').' : </span>';
														$fav_output .= '<span class="pfiteminfolist-infotext pfreviews">0</span>';
													}
												}else{
													$fav_output = '';
												}
											
											
											$setup3_pointposttype_pt7s = PFSAIssetControl('setup3_pointposttype_pt7s','','Listing Type');
											

											$setup4_membersettings_loginregister = PFSAIssetControl('setup4_membersettings_loginregister','','1');


										$this->FieldOutput .= '<div class="pfmu-itemlisting-inner pf-row clearfix">';
												
												if (get_post_status($author_post_id) == 'publish') {
													$permalink_item = get_permalink($author_post_id);
												}else{
													$permalink_item = '#';
												}

												/*Item Photo Area*/
												$this->FieldOutput .= '<div class="pfmu-itemlisting-photo col-lg-1 col-md-1 col-sm-2 hidden-xs">';
													if ( has_post_thumbnail()) {
													   $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(),'full');
													   $this->FieldOutput .= '<a href="' . $large_image_url[0] . '" title="' . the_title_attribute('echo=0') . '" rel="prettyPhoto">';
													   $this->FieldOutput .= '<img src="'.aq_resize($large_image_url[0],60,60,true).'" alt="" />';
													   $this->FieldOutput .= '</a>';
													}else{
													   $this->FieldOutput .= '<a href="#" style="border:1px solid #efefef">';
													   $this->FieldOutput .= '<img src="'.get_template_directory_uri().'/images/noimg.png'.'" alt="" />';
													   $this->FieldOutput .= '</a>';
													}
												$this->FieldOutput .= '</div>';



												/* Item Title */
												$this->FieldOutput .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pfmu-itemlisting-title-wd">';
												$this->FieldOutput .= '<div class="pfmu-itemlisting-title">';
												$this->FieldOutput .= '<a href="'.$permalink_item.'">'.get_the_title().'</a>';
												$this->FieldOutput .= '</div>';


												/*Status*/
												$this->FieldOutput .= '<div class="pfmu-itemlisting-info pffirst">';
													$this->FieldOutput .= '<ul class="pfiteminfolist">';



														/** Basic & Featured Listing Setting **/
														
														if ($featured_enabled == 1) {
															$this->FieldOutput .= '<li>';
															$this->FieldOutput .= '<span class="pfiteminfolist-title pfstatus-title">'.esc_html__('Featured Listing Status','pointfindert2d').' '.$item_recurring_text.'  : </span>';
														}else{
															$this->FieldOutput .= '<li>';
															$this->FieldOutput .= '<span class="pfiteminfolist-title pfstatus-title">'.esc_html__('Basic Listing Status','pointfindert2d').' '.$item_recurring_text.'  : </span>';
														}
													
														
														if($status_payment == 1 && $status_of_post == 'pendingapproval'){
															$this->FieldOutput .= '<span class="pfiteminfolist-infotext '.$status_lbl.'"><a href="javascript:;" class="info-tip info-tipex" aria-describedby="helptooltip"> <i class="'.$status_icon.'"></i> <span role="tooltip">'.esc_html__('This item is waiting for approval. Please be patient while this process goes on.','pointfindert2d').'</span></a>';
														}else{
															if (empty($item_listing_expiry) && $status_of_post == 'publish') {
																$this->FieldOutput .= '<span class="pfiteminfolist-infotext '.$status_lbl.'">';
															}else{
																$this->FieldOutput .= '<span class="pfiteminfolist-infotext '.$status_lbl.'"><i class="'.$status_icon.'"></i>';
															}
														}
														if (empty($item_listing_expiry) && $status_of_post == 'publish') {
															$this->FieldOutput .= '</span>';
														}else{
															$this->FieldOutput .= ' '.$status_text.'</span>';
														}
														
														$this->FieldOutput .= '</li>';

														/** Basic & Featured Listing Setting **/


														/** Reviews: show on xs **/
														$this->FieldOutput .= '<li class="visible-xs">';
														$this->FieldOutput .= $review_output;
														$this->FieldOutput .= '</li>';

														/** Favorites: show on xs **/
														$this->FieldOutput .= '<li class="visible-xs">';
														$this->FieldOutput .= $fav_output;
														$this->FieldOutput .= '</li>';
														
													$this->FieldOutput .= '</ul>';
												$this->FieldOutput .= '</div>';
												$this->FieldOutput .= '</div>';

												
												
												/*Type of item*/
												$this->FieldOutput .= '<div class="pfmu-itemlisting-info pfflast col-lg-2 col-md-2 col-sm-2 hidden-xs">';
													$this->FieldOutput .= '<ul class="pfiteminfolist">';														
														$this->FieldOutput .= '<li><strong>'.GetPFTermName($author_post_id, 'pointfinderltypes').'</strong></li>';

														/** Reviews: show on xs **/
														$this->FieldOutput .= '<li class="hidden-xs">';
														$this->FieldOutput .= $review_output;
														$this->FieldOutput .= '</li>';

														/** Favorites: show on xs **/
														$this->FieldOutput .= '<li class="hidden-xs">';
														$this->FieldOutput .= $fav_output;
														$this->FieldOutput .= '</li>';

													$this->FieldOutput .= '</ul>';
												$this->FieldOutput .= '</div>';

												/*Date Creation*/
												$this->FieldOutput .= '<div class="pfmu-itemlisting-info pfflast col-lg-2 col-md-2 col-sm-2 hidden-xs">';
													$this->FieldOutput .= '<ul class="pfiteminfolist">';
														$this->FieldOutput .= '<li>'.$pointfinder_order_datetime.'</li>';
													$this->FieldOutput .= '</ul>';
												$this->FieldOutput .= '</div>';



												


												/*Item Footer*/
												$this->FieldOutput .= '<div class="pfmu-itemlisting-footer col-lg-3 col-md-3 col-sm-2 col-xs-12">';
											    $this->FieldOutput .= '<ul class="pfmu-userbuttonlist">';

											    if ($this->PF_UserLimit_Check('delete',$status_of_post) == 1) {
													$this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-delete-item-button wpf-transition-all" id="pf-delete-item-'.$author_post_id.'" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_del&i='.$author_post_id.'" title="'.esc_html__('Delete','pointfindert2d').'"><i class="pfadmicon-glyph-644"></i></a></li>';
												}
												
												if($status_of_post == 'publish'){
													$this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-view-item-button wpf-transition-all" href="'.$permalink_item.'" title="'.esc_html__('View','pointfindert2d').'"><i class="pfadmicon-glyph-410"></i></a></li>';
												}

												if ($this->PF_UserLimit_Check('edit',$status_of_post) == 1 && $status_of_order != 'pfsuspended') {
													$this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-edit-item-button wpf-transition-all" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=edititem&i='.$author_post_id.'" title="'.esc_html__('Edit','pointfindert2d').'"><i class="pfadmicon-glyph-685"></i></a></li>';
												}

												$this->FieldOutput .= '</ul>';
												
												if ($status_payment == 0 && $pointfinder_order_price != 0) {

									            $this->FieldOutput .= '<div class="pfmu-payment-area golden-forms pf-row clearfix">';

									            	if($pointfinder_order_bankcheck == 0){

										            	$this->FieldOutput .= '<label for="paymenttype" class="lbl-text">'.esc_html__('PAY WITH:','pointfindert2d');
										            		if($pointfinder_order_recurring == 1){
										            			$this->FieldOutput .= '<a href="javascript:;" class="info-tip info-tipex" aria-describedby="helptooltip" style="background-color:#b00000"> ? <span role="tooltip">'.esc_html__('Recurring payments do not support BANK TRANSFER & CREDIT CARD PAYMENTS.','pointfindert2d').'</span></a>';
										            		}
										            		$this->FieldOutput .= '</label>';

										            
										            	$this->FieldOutput .= '<div class="col-lg-7 col-md-7 col-sm-12 col-xs-8">';
											            	
											                $this->FieldOutput .= '<label class="lbl-ui select">';
											            
													        	$this->FieldOutput .= '<select name="paymenttype">';
													        		if (PFSAIssetControl('setup20_paypalsettings_paypal_status','','1') == 1) {													
														       			$this->FieldOutput .= '<option value="zarinweb">'.esc_html__('ZarinWeb','pointfindert2d').'</option>';
														       			$this->FieldOutput .= '<option value="zaringate">'.esc_html__('ZarinGate','pointfindert2d').'</option>';
														       		}
														       		if($pointfinder_order_recurring != 1 && PFSAIssetControl('setup20_paypalsettings_bankdeposit_status','',0) == 1){
														       			$this->FieldOutput .= '<option value="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_pay2&i='.$author_post_id.'">'.esc_html__('BANK TRANS.','pointfindert2d').'</option>';
														       		}
														       		if ($pointfinder_order_recurring != 1 && PFSAIssetControl('setup20_stripesettings_status','','0') == 1) {
														       			$this->FieldOutput .= '<option value="creditcard">'.esc_html__('CREDIT CARD','pointfindert2d').'</option>';
														       		}

														        $this->FieldOutput .= '</select>';
														        
													        $this->FieldOutput .= '</label>';

												        $this->FieldOutput .= '</div>';
												       



												        $this->FieldOutput .= '<div class="col-lg-5 col-md-5 col-sm-12 col-xs-4">';
										            		$this->FieldOutput .= '<a class="button buttonpaymentb pfbuttonpaymentb" data-pfitemnum="'.$author_post_id.'" title="'.esc_html__('Click for Payment','pointfindert2d').'">'.esc_html__('PAY','pointfindert2d').'</a>';
										            	$this->FieldOutput .= '</div>';
										            }else{
										            	$this->FieldOutput .= '<div class="col-lg-12">';
										            		$this->FieldOutput .= '<div class="pfcanceltext">';
										            		$this->FieldOutput .= '<label class="lbl-text"><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_pay2c&i='.$author_post_id.'">'.esc_html__('CANCEL TRANSFER','pointfindert2d').'</a> ';
										            		$this->FieldOutput .= '<a href="javascript:;" class="info-tip info-tipex" aria-describedby="helptooltip" style="background-color:#b00000"> ? <span role="tooltip">'.esc_html__('Waiting Bank Transfer, but you can cancel this transfer and make payment with another payment method.','pointfindert2d').'</span></a>';
										            		$this->FieldOutput .= '</label>';
										            		$this->FieldOutput .= '</div>';
										            	$this->FieldOutput .= '</div>';
										            }

										            $this->FieldOutput .= '</div>';
									           
									        	}elseif ($status_payment == 0 && $pointfinder_order_price == 0) {
									        		/*If user is free user then extend it free.*/
									        		$this->FieldOutput .= '<div class="col-lg-12">';
										            		$this->FieldOutput .= '<div class="pfcanceltext">';
										            		$this->FieldOutput .= '<label class="lbl-text">
										            		<a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_extend&i='.$author_post_id.'" class="button buttonrenewpf" title="'.esc_html__('Click for renew (Extend)','pointfindert2d').'"><i class="pfadmicon-glyph-486"></i> '.esc_html__('RENEW','pointfindert2d').'</a>';
										            		$this->FieldOutput .= '</label>';
										            		$this->FieldOutput .= '</div>';
										            	$this->FieldOutput .= '</div>';
									        	}
												$this->FieldOutput .= '</div>';


											$this->FieldOutput .= '</div>';

											$this->ScriptOutput .= '
											$("#pf-delete-item-'.$author_post_id.'").click(function(){
											    return confirm("'.esc_html__('Are you sure that you want to delete this? (This action cannot rollback.)','pointfindert2d').'");
											});
											';
										
								}

								$this->FieldOutput .= '</section>';
							}else{
								$this->FieldOutput .= '<section>';
								$this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p>';
								if (PFControlEmptyArr($fieldvars)) {
									$this->FieldOutput .= '<strong>'.esc_html__('No record found!','pointfindert2d').'</strong><br>'.esc_html__('Please refine your search criteria and try to check again. Or you can press <strong>Reset</strong> button to see all items.','pointfindert2d').'</p></div>';
								}else{
									$this->FieldOutput .= '<strong>'.esc_html__('No record!','pointfindert2d').'</strong><br>'.esc_html__('If you see this error first time please upload new items for list on this page.','pointfindert2d').'</p></div>';
								}
								$this->FieldOutput .= '</section>';
							}
							$this->FieldOutput .= '<div class="pfstatic_paginate" >';
							$big = 999999999;
							$this->FieldOutput .= paginate_links(array(
								'base' => @add_query_arg('page','%#%'),
								'format' => '?page=%#%',
								'current' => max(1, $paged),
								'total' => $output_loop->max_num_pages,
								'type' => 'list',
							));
							$this->FieldOutput .= '</div>';
							wp_reset_postdata();

						$this->FieldOutput .= '</div>';

					/**
					*End: Content Area
					**/
				/**
				*End: My Items Page Content
				**/
					break;



				case 'errorview':
				/**
				*Start: Error Page Content
				**/
					
					
				/**
				*End: Error Page Content
				**/
					break;


				case 'banktransfer':
				/**
				*Start: Bank Transfer Page Content
				**/
					$this->FieldOutput .= '<div class="pf-banktransfer-window">';

						$this->FieldOutput .= '<span class="pf-orderid-text">';
						$this->FieldOutput .= esc_html__('Your Order ID:','pointfindert2d').' '.$params['post_id'];
						$this->FieldOutput .= '</span>';

						$this->FieldOutput .= '<span class="pf-order-text">';
						global $pointfindertheme_option;
						$setup20_bankdepositsettings_text = ($pointfindertheme_option['setup20_bankdepositsettings_text'])? wp_kses_post($pointfindertheme_option['setup20_bankdepositsettings_text']):'';
						$this->FieldOutput .= $setup20_bankdepositsettings_text;
						$this->FieldOutput .= '</span>';

					$this->FieldOutput .= '</div>';
					
				/**
				*End: Bank Transfer Page Content
				**/
					break;


				case 'favorites':
				$formaction = 'pf_refinefavlist';
				$noncefield = wp_create_nonce($formaction);
				$buttonid = 'pf-ajax-itemrefine-button';

				/**
				*Start: Favorites Page Content
				**/
					
					$user_favorites_arr = get_user_meta( $params['current_user'], 'user_favorites', true );

					if (!empty($user_favorites_arr)) {
						$user_favorites_arr = json_decode($user_favorites_arr,true);
					}else{
						$user_favorites_arr = array();
					}


					$output_arr = '';
					$countarr = count($user_favorites_arr);
					
					if($countarr>0){
						
						$this->FieldOutput .= '<div class="pfmu-itemlisting-container">';
							
							if ($params['fields']!= '') {
								$fieldvars = $params['fields'];
							}else{
								$fieldvars = '';
							}

							$selected_lfs = $selected_lfl = $selected_lfo2 = $selected_lfo = '';

							$paged = ( esc_sql(get_query_var('paged')) ) ? esc_sql(get_query_var('paged')) : '';
							if (empty($paged)) {
								$paged = ( esc_sql(get_query_var('page')) ) ? esc_sql(get_query_var('page')) : 1;
							}

							$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
							$setup3_pointposttype_pt7 = PFSAIssetControl('setup3_pointposttype_pt7','','Listing Types');

							if (PFControlEmptyArr($fieldvars)) {

		                        if(isset($fieldvars['listing-filter-ltype'])){
		                       		if ($fieldvars['listing-filter-ltype'] != '') {
		                       			$selected_lfl = $fieldvars['listing-filter-ltype'];
		                       		}
		                        }

	                            if(isset($fieldvars['listing-filter-orderby'])){
	                           		if ($fieldvars['listing-filter-orderby'] != '') {
	                           			$selected_lfo = $fieldvars['listing-filter-orderby'];
	                           		}
	                            }

	                            if(isset($fieldvars['listing-filter-order'])){
	                           		if ($fieldvars['listing-filter-order'] != '') {
	                           			$selected_lfo2 = $fieldvars['listing-filter-order'];
	                           		}
	                            }

							}

							$user_id = $params['current_user'];


							$output_args = array(
									'post_type'	=> $setup3_pointposttype_pt1,
									'posts_per_page' => 10,
									'paged' => $paged,
									'order'	=> 'ASC',
									'orderby' => 'Title',
									'post__in' => $user_favorites_arr
							);

							if($selected_lfs != ''){$output_args['post_status'] = $selected_lfs;}
							if($selected_lfo != ''){$output_args['orderby'] = $selected_lfo;}
							if($selected_lfo2 != ''){$output_args['order'] = $selected_lfo2;}
							if($selected_lfl != ''){
								$output_args['tax_query']=
									array(
										'relation' => 'AND',
										array(
											'taxonomy' => 'pointfinderltypes',
											'field' => 'id',
											'terms' => $selected_lfl,
											'operator' => 'IN'
										)
									);
							}

							

							if($params['post_id'] != ''){
								$output_args['p'] = $params['post_id'];
							}

							$output_loop = new WP_Query( $output_args );
							
							/**
							*START: Header for search
							**/
								
								if($params['sheader'] != 'hide'){
									
									$this->FieldOutput .= '<section><div class="row">';
										

			                        $this->FieldOutput .= '<div class="col3 first">';
										$this->FieldOutput .= '<label for="listing-filter-ltype" class="lbl-ui select">
			                              <select id="listing-filter-ltype" name="listing-filter-ltype">
			                                <option value="">'.$setup3_pointposttype_pt7.'</option>
			                                ';
			                                 
			                                $fieldvalues = get_terms('pointfinderltypes',array('hide_empty'=>false)); 
											foreach( $fieldvalues as $fieldvalue){
												
												$this->FieldOutput  .= ($selected_lfl == $fieldvalue->term_id) ? '<option value="'.$fieldvalue->term_id.'" selected>'.$fieldvalue->name.'</option>' : '<option value="'.$fieldvalue->term_id.'">'.$fieldvalue->name.'</option>';	
												
											}

			                                $this->FieldOutput .= '
			                              </select>
			                            </label>';
			                        $this->FieldOutput .= '</div>';


			                        $this->FieldOutput .= '<div class="col3">';
										$this->FieldOutput .= '<label for="listing-filter-orderby" class="lbl-ui select">
			                              <select id="listing-filter-orderby" name="listing-filter-orderby">';

			                                $this->FieldOutput .= '<option value="">'.esc_html__('Order By','pointfindert2d').'</option>';
			                                $this->FieldOutput  .= ($selected_lfo == 'title') ? '<option value="title" selected>'.esc_html__('Title','pointfindert2d').'</option>' : '<option value="title">'.esc_html__('Title','pointfindert2d').'</option>';
			                                $this->FieldOutput  .= ($selected_lfo == 'date') ? '<option value="date" selected>'.esc_html__('Date','pointfindert2d').'</option>' : '<option value="date">'.esc_html__('Date','pointfindert2d').'</option>';
			                                $this->FieldOutput  .= ($selected_lfo == 'ID') ? '<option value="ID" selected>'.esc_html__('ID','pointfindert2d').'</option>' : '<option value="ID">'.esc_html__('ID','pointfindert2d').'</option>';


			                              $this->FieldOutput .= '
			                              </select>
			                            </label>';
			                        $this->FieldOutput .= '</div>';

			                        $this->FieldOutput .= '<div class="col3">';
										$this->FieldOutput .= '<label for="listing-filter-order" class="lbl-ui select">
			                              <select id="listing-filter-order" name="listing-filter-order">';

			                                $this->FieldOutput .= '<option value="">'.esc_html__('Order','pointfindert2d').'</option>';
			                                $this->FieldOutput  .= ($selected_lfo2 == 'ASC') ? '<option value="ASC" selected>'.esc_html__('ASC','pointfindert2d').'</option>' : '<option value="ASC">'.esc_html__('ASC','pointfindert2d').'</option>';
			                                $this->FieldOutput  .= ($selected_lfo2 == 'DESC') ? '<option value="DESC" selected>'.esc_html__('DESC','pointfindert2d').'</option>' : '<option value="DESC">'.esc_html__('DESC','pointfindert2d').'</option>';

			                              $this->FieldOutput .= '
			                              </select>
			                            </label>';
			                        $this->FieldOutput .= '</div>';

			                        

			                        $this->FieldOutput .= '<div class="col3 last">';
										$this->FieldOutput .= '<button type="submit" value="" id="'.$buttonid.'" class="button blue pfmyitempagebuttons" title="'.esc_html__('Search','pointfindert2d').'"  ><i class="pfadmicon-glyph-627"></i></button>';
										$this->FieldOutput .= '<a class="button pfmyitempagebuttons" style="margin-left:4px;" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=favorites" title="'.esc_html__('RESET','pointfindert2d').'"><i class="pfadmicon-glyph-825"></i></a>';
									$this->FieldOutput .= '</div></div></section>';
								}

							/**
							*END: Header for search
							**/

							if ( $output_loop->have_posts() ) {
								
								$this->FieldOutput .= '<section>';
								$this->FieldOutput .= '<div class="pfmu-itemlisting-inner pfhtitle pf-row clearfix hidden-xs">';

								$setup3_pointposttype_pt4 = PFSAIssetControl('setup3_pointposttype_pt4s','','Item Type');
								$setup3_pointposttype_pt4_check = PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
								$setup3_pointposttype_pt5 = PFSAIssetControl('setup3_pointposttype_pt5s','','Location');
								$setup3_pointposttype_pt5_check = PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
								$setup3_pointposttype_pt7s = PFSAIssetControl('setup3_pointposttype_pt7s','','Listing Type');
								/**
								*Start: Column Headers
								**/
									$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle col-lg-1 col-md-1 col-sm-2 hidden-xs">';
									$this->FieldOutput .= '</div>';

									$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-4 col-md-4 col-sm-4 hidden-xs">';
									$this->FieldOutput .= esc_html__('Information','pointfindert2d');
									$this->FieldOutput .= '</div>';

									$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-2 col-md-2 col-sm-2 hidden-xs">';
									$this->FieldOutput .= $setup3_pointposttype_pt7s;
									$this->FieldOutput .= '</div>';

									$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-2 col-md-2 col-sm-2 hidden-xs">';
										
										if($setup3_pointposttype_pt5_check == 1){
											$this->FieldOutput .= $setup3_pointposttype_pt5;
										}else{
											if($setup3_pointposttype_pt4_check == 1){
												$this->FieldOutput .= $setup3_pointposttype_pt4;
											}
										}
									
									$this->FieldOutput .= '</div>';

									$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle col-lg-3 col-md-3 col-sm-2">';
									$this->FieldOutput .= '</div>';
								/**
								*End: Column Headers
								**/

								$this->FieldOutput .= '</div>';

								while ( $output_loop->have_posts() ) {
									$output_loop->the_post(); 

									$author_post_id = get_the_ID();
										
										$this->FieldOutput .= '<div class="pfmu-itemlisting-inner pf-row clearfix">';
												
												$permalink_item = get_permalink($author_post_id);
												

												/*Item Photo Area*/
												$this->FieldOutput .= '<div class="pfmu-itemlisting-photo col-lg-1 col-md-1 col-sm-2 hidden-xs">';
													if ( has_post_thumbnail()) {
													   $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(),'full');
													   $this->FieldOutput .= '<a href="' . $large_image_url[0] . '" title="' . the_title_attribute('echo=0') . '" rel="prettyPhoto">';
													   $this->FieldOutput .= '<img src="'.aq_resize($large_image_url[0],60,60,true).'" alt="" />';
													   $this->FieldOutput .= '</a>';
													}else{
													   $this->FieldOutput .= '<a href="#" style="border:1px solid #efefef">';
													   $this->FieldOutput .= '<img src="'.get_template_directory_uri().'/images/noimg.png'.'" alt="" />';
													   $this->FieldOutput .= '</a>';
													}
												$this->FieldOutput .= '</div>';



												/* Item Title */
												$this->FieldOutput .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pfmu-itemlisting-title-wd">';
												$this->FieldOutput .= '<div class="pfmu-itemlisting-title">';
												$this->FieldOutput .= '<a href="'.$permalink_item.'">'.get_the_title().'</a>';
												$this->FieldOutput .= '</div>';


												/*Other Infos*/
												$output_data = PFIF_DetailText_ld($author_post_id);
												$rl_pfind = '/pflistingitem-subelement pf-price/';
												$rl_pfind2 = '/pflistingitem-subelement pf-onlyitem/';
			                                    $rl_preplace = 'pf-fav-listing-price';
			                                    $rl_preplace2 = 'pf-fav-listing-item';
			                                    $mcontent = preg_replace( $rl_pfind, $rl_preplace, $output_data);
			                                    $mcontent = preg_replace( $rl_pfind2, $rl_preplace2, $mcontent );

			                                    if (isset($mcontent['content'])) {
			                                    	$this->FieldOutput .= '<div class="pfmu-itemlisting-info pffirst">';
				                                    $this->FieldOutput .= $mcontent['content'];
													$this->FieldOutput .= '</div>';
			                                    }

			                                    if (isset($mcontent['priceval'])) {
			                                    	$this->FieldOutput .= '<div class="pfmu-itemlisting-info pffirst">';
				                                    $this->FieldOutput .= $mcontent['priceval'];
													$this->FieldOutput .= '</div>';
			                                    }

			                                    $this->FieldOutput .= '</div>';
												

												
												
												/*Type of item*/
												$this->FieldOutput .= '<div class="pfmu-itemlisting-info pfflast col-lg-2 col-md-2 col-sm-2 hidden-xs">';
													$this->FieldOutput .= '<ul class="pfiteminfolist">';														
														$this->FieldOutput .= '<li>'.GetPFTermName($author_post_id, 'pointfinderltypes').'</li>';
													$this->FieldOutput .= '</ul>';
												$this->FieldOutput .= '</div>';

												/*Location*/
												$this->FieldOutput .= '<div class="pfmu-itemlisting-info pfflast col-lg-3 col-md-3 col-sm-2 hidden-xs">';
													$this->FieldOutput .= '<ul class="pfiteminfolist">';
														if($setup3_pointposttype_pt5_check == 1){
															$this->FieldOutput .= '<li>'.GetPFTermName($author_post_id, 'pointfinderlocations').'</li>';
														}else{
															if($setup3_pointposttype_pt4_check == 1){
																$this->FieldOutput .= '<li>'.GetPFTermName($author_post_id, 'pointfinderitypes').'</li>';
															}
														}
													$this->FieldOutput .= '</ul>';
												$this->FieldOutput .= '</div>';



												


												/*Item Footer*/
													
												
												$fav_check = 'true';
												$favtitle_text = esc_html__('Remove from Favorites','pointfindert2d');
												
												
												
												$this->FieldOutput .= '<div class="pfmu-itemlisting-footer col-lg-2 col-md-2 col-sm-2 col-xs-12">';
											    $this->FieldOutput .= '<ul class="pfmu-userbuttonlist">';
													$this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-delete-item-button wpf-transition-all pf-favorites-link" data-pf-num="'.$author_post_id.'" data-pf-active="'.$fav_check.'" data-pf-item="false" title="'.$favtitle_text.'"><i class="pfadmicon-glyph-644"></i></a></li>';
													$this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-view-item-button wpf-transition-all" href="'.$permalink_item.'" title="'.esc_html__('View','pointfindert2d').'"><i class="pfadmicon-glyph-410"></i></a></li>';
												$this->FieldOutput .= '</ul>';
												
												$this->FieldOutput .= '</div>';


											$this->FieldOutput .= '</div>';

										
								}

								$this->FieldOutput .= '</section>';
							}else{
								$this->FieldOutput .= '<section>';
								$this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p>';
								if (PFControlEmptyArr($fieldvars)) {
									$this->FieldOutput .= '<strong>'.esc_html__('No record found!','pointfindert2d').'</strong><br>'.esc_html__('Please refine your search criteria and try to check again. Or you can press <strong>Reset</strong> button to see all.','pointfindert2d').'</p></div>';
								}else{
									$this->FieldOutput .= '<strong>'.esc_html__('No record found!','pointfindert2d').'</strong></p></div>';
								}
								$this->FieldOutput .= '</section>';
							}
							$this->FieldOutput .= '<div class="pfstatic_paginate" >';
							$big = 999999999;
							$this->FieldOutput .= paginate_links(array(
								'base' => @add_query_arg('page','%#%'),
								'format' => '?page=%#%',
								'current' => max(1, $paged),
								'total' => $output_loop->max_num_pages,
								'type' => 'list',
							));
							$this->FieldOutput .= '</div>';
							

						$this->FieldOutput .= '</div>';
					}else{
						$this->FieldOutput .= '<section>';
						$this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p>'.esc_html__('No record found!','pointfindert2d').'</p></div>';
						$this->FieldOutput .= '</section>';
					}

				/**
				*End: Favorites Page Content
				**/
					break;


				case 'reviews':
				$formaction = 'pf_refinerevlist';
				$noncefield = wp_create_nonce($formaction);
				$buttonid = 'pf-ajax-revrefine-button';

				/**
				*Start: Reviews Page Content
				**/
					/*Post Meta Info*/
					global $wpdb;
					$results = $wpdb->get_results( $wpdb->prepare( 
						"
							SELECT ID
							FROM $wpdb->posts
							WHERE post_type = '%s' and post_author = %d
						", 
						'pointfinderreviews',
						$params['current_user']
					),'ARRAY_A' );

					function pf_arraya_2_array($aval = array()){
						$aval_output = array();
						foreach ($aval as $aval_single) {

							$aval_output[] = (isset($aval_single['ID']))? $aval_single['ID'] : '';
						}
						return $aval_output;
					}
					$results = pf_arraya_2_array($results);

					$output_arr = '';
					$countarr = count($results);

					
					if($countarr>0){
						
						$this->FieldOutput .= '<div class="pfmu-itemlisting-container">';

							$paged = ( esc_sql(get_query_var('paged')) ) ? esc_sql(get_query_var('paged')) : '';
							if (empty($paged)) {
								$paged = ( esc_sql(get_query_var('page')) ) ? esc_sql(get_query_var('page')) : 1;
							}

							
							$user_id = $params['current_user'];


							$output_args = array(
									'post_type'	=> 'pointfinderreviews',
									'posts_per_page' => 10,
									'paged' => $paged,
									'order'	=> 'DESC',
									'orderby' => 'Date',
									'post__in' => $results
							);


							$output_loop = new WP_Query( $output_args );
							/*
							print_r($output_loop->query).PHP_EOL;
							echo $output_loop->request.PHP_EOL;
							*/
							

							if ( $output_loop->have_posts() ) {
								
								$this->FieldOutput .= '<section>';
								$this->FieldOutput .= '<div class="pfmu-itemlisting-inner pfhtitle pf-row clearfix hidden-xs">';

								$setup3_pointposttype_pt4 = PFSAIssetControl('setup3_pointposttype_pt4s','','Item Type');
								$setup3_pointposttype_pt4_check = PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
								$setup3_pointposttype_pt5 = PFSAIssetControl('setup3_pointposttype_pt5s','','Location');
								$setup3_pointposttype_pt5_check = PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
								$setup3_pointposttype_pt7s = PFSAIssetControl('setup3_pointposttype_pt7s','','Listing Type');
								/**
								*Start: Column Headers
								**/
									
									$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-4 col-md-4 col-sm-4 hidden-xs">';
									$this->FieldOutput .= esc_html__('Title','pointfindert2d');
									$this->FieldOutput .= '</div>';

									$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-2 col-md-2 col-sm-2 hidden-xs">';
									$this->FieldOutput .= esc_html__('Review','pointfindert2d');
									$this->FieldOutput .= '</div>';

									
									$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-4 col-md-4 col-sm-4 hidden-xs">';
									$this->FieldOutput .= esc_html__('Date','pointfindert2d');
									$this->FieldOutput .= '</div>';

									$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle col-lg-2 col-md-2 col-sm-2">';
									$this->FieldOutput .= '</div>';
								/**
								*End: Column Headers
								**/

								$this->FieldOutput .= '</div>';

								while ( $output_loop->have_posts() ) {
									$output_loop->the_post(); 

									$author_post_id = get_the_ID();
									$item_post_id = esc_attr(get_post_meta( $author_post_id, 'webbupointfinder_review_itemid', true ));

										$this->FieldOutput .= '<div class="pfmu-itemlisting-inner pf-row clearfix">';
												

												/* Item Title */
												$this->FieldOutput .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pfmu-itemlisting-title-wd">';
													$this->FieldOutput .= '<div class="pfmu-itemlisting-title pf-review-list">';
														$this->FieldOutput .= '<a href="'.get_permalink($item_post_id).'">'.get_the_title($item_post_id).'</a>';
													$this->FieldOutput .= '</div>';
			                                    $this->FieldOutput .= '</div>';


			                                    /* Review Title */
												$this->FieldOutput .= '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 pfmu-itemlisting-title-wd">';
													$this->FieldOutput .= '<div class="pfmu-itemlisting-title pf-review-list">';

															

															
																$review_output = '';
																$return_results = pfcalculate_single_review($author_post_id);
																
																if (!empty($return_results)) {
																	$review_output .= '<span class="pfiteminfolist-infotext pfreviews">';
																		$review_output .=  $return_results;
																	$review_output .= '</span>';
																}else{
																	$review_output .= ''.esc_html__('Reviews','pointfindert2d').' : ';
																	$review_output .= '<span class="pfiteminfolist-infotext pfreviews">';
																		$review_output .=  '0 (<a title="'.esc_html__('Review Total','pointfindert2d').'" style="cursor:pointer">0</a>)';
																	$review_output .= '</span>';
																}
															
														$this->FieldOutput .= $review_output;

													$this->FieldOutput .= '</div>';
			                                    $this->FieldOutput .= '</div>';

												
												
												/*Type of item*/
												$this->FieldOutput .= '<div class="pfmu-itemlisting-info pfflast col-lg-4 col-md-4 col-sm-4 hidden-xs">';
													$this->FieldOutput .= '<ul class="pfiteminfolist">';														
														$this->FieldOutput .= '<li>'.sprintf( esc_html__('%1$s at %2$s', 'pointfindert2d'), get_the_date(),  get_the_time()).'</li>';
													$this->FieldOutput .= '</ul>';
												$this->FieldOutput .= '</div>';


												/*Item Footer*/
												$this->FieldOutput .= '<div class="pfmu-itemlisting-footer col-lg-2 col-md-2 col-sm-2 col-xs-12">';
											    $this->FieldOutput .= '<ul class="pfmu-userbuttonlist">';
													$this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-view-item-button wpf-transition-all" href="'.get_permalink($item_post_id).'" title="'.esc_html__('View','pointfindert2d').'"><i class="pfadmicon-glyph-410"></i></a></li>';
												$this->FieldOutput .= '</ul>';
												
												$this->FieldOutput .= '</div>';


											$this->FieldOutput .= '</div>';

										
								}

								$this->FieldOutput .= '</section>';
							}else{
								$this->FieldOutput .= '<section>';
								$this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p>';
								
								$this->FieldOutput .= esc_html__('No record found!','pointfindert2d').'</p></div>';
								
								$this->FieldOutput .= '</section>';
							}
							$this->FieldOutput .= '<div class="pfstatic_paginate" >';
							$big = 999999999;
							$this->FieldOutput .= paginate_links(array(
								'base' => @add_query_arg('page','%#%'),
								'format' => '?page=%#%',
								'current' => max(1, $paged),
								'total' => $output_loop->max_num_pages,
								'type' => 'list',
							));
							$this->FieldOutput .= '</div>';
							wp_reset_postdata();

						$this->FieldOutput .= '</div>';
					}else{
						$this->FieldOutput .= '<section>';
						$this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p>'.esc_html__('No record found!','pointfindert2d').'</p></div>';
						$this->FieldOutput .= '</section>';
					}

				/**
				*End: Reviews Page Content
				**/
					break;

			}

		/**
		*Start: Page Footer Actions / Divs / Etc...
		**/
			$this->FieldOutput .= '</div>';/*row*/
			$this->FieldOutput .= '</div>';/*form-section*/
			$this->FieldOutput .= '</div>';/*form-enclose*/

			
			if($params['formtype'] != 'myitems' && $params['formtype'] != 'favorites' && $params['formtype'] != 'reviews'){$xtext = '';}else{$xtext = 'style="background:transparent;background-color:transparent;display:none!important"';}

			$this->FieldOutput .= '
			<div class="pfalign-right" '.$xtext.'>';
			if($params['formtype'] != 'errorview' && $params['formtype'] != 'banktransfer'){
				if($params['formtype'] != 'myitems' && $params['formtype'] != 'favorites' && $params['formtype'] != 'reviews' && $params['dontshowpage'] != 1){
		            $this->FieldOutput .='    
		                <section '.$xtext.'> ';
		                if($params['formtype'] == 'upload'){
			                $setup31_userpayments_recurringitem_val = (PFSAIssetControl('setup31_userpayments_recurringitem','','1') == 1) ? '1' : '0' ;
			                $setup31_userpayments_recurringoption = PFSAIssetControl('setup31_userpayments_recurringoption','','1');
			                if ( $setup31_userpayments_recurringoption == 0) {
			                	$setup31_userpayments_recurringitem_val = 0;
			                }
			            	$this->FieldOutput .='
			                   <input type="hidden" name="recurringlistingitem" value="'.$setup31_userpayments_recurringitem_val.'">';
		                }
		                $this->FieldOutput .= '
		                   <input type="hidden" value="'.$formaction.'" name="action" />
		                   <input type="hidden" value="'.$noncefield.'" name="security" />
		                   <input type="submit" value="'.$buttontext.'" id="'.$buttonid.'" class="button blue pfmyitempagebuttonsex"  />
		                </section>  
		            ';
	         	}else{
	       			$this->FieldOutput .='    
		                <section  '.$xtext.'> 
		                   <input type="hidden" value="'.$formaction.'" name="action" />
		                   <input type="hidden" value="'.$noncefield.'" name="security" />
		                </section>  
		            ';
	       		}
	       	}
        
            $this->FieldOutput.='              
            </div>
			';
			
			$this->FieldOutput .= '</form>';
			$this->FieldOutput .= '</div>';/*golden-forms*/
		/**
		*End: Page Footer Actions / Divs / Etc...
		**/


		}

		/**
		*Start: Class Functions
		**/
			

			function PFGetList($params = array())
			{
			    $defaults = array( 
			        'listname' => '',
			        'listtype' => '',
			        'listtitle' => '',
			        'listsubtype' => '',
			        'listdefault' => '',
			        'listgroup' => 0,
			        'listgroup_ex' => 1,
			        'listmultiple' => 0
			    );
				
			    $params = array_merge($defaults, $params);
			    	
			    	$output_options = '';
			    	if($params['listmultiple'] == 1){ $multiplevar = ' multiple';$multipletag = '[]';}else{$multiplevar = '';$multipletag = '';};
					$fieldvalues = get_terms($params['listsubtype'],array('hide_empty'=>false)); 

					
					if($params['listgroup'] == 1){
						foreach( $fieldvalues as $parentfieldvalue){
							if($parentfieldvalue->parent == 0){
								$output_options .=  '<optgroup label="'.$parentfieldvalue->name.'">';
								
									if ($params['listgroup_ex'] == 1) {
								
										if(is_array($params['listdefault'])){
											if(in_array($parentfieldvalue->term_id, $params['listdefault'])){ $fieldtaxSelectedValuex = 1;}else{ $fieldtaxSelectedValuex = 0;}
										}else{
											if(strcmp($params['listdefault'],$parentfieldvalue->term_id) == 0){ $fieldtaxSelectedValuex = 1;}else{ $fieldtaxSelectedValuex = 0;}
										}
										if($fieldtaxSelectedValuex == 1){
											$output_options .= '<option value="'.$parentfieldvalue->term_id.'" selected>'.$parentfieldvalue->name.' ('.esc_html__('All','pointfindert2d').')</option>';
										}else{
											$output_options .= '<option value="'.$parentfieldvalue->term_id.'">'.$parentfieldvalue->name.' ('.esc_html__('All','pointfindert2d').')</option>';
										}
									}
									foreach( $fieldvalues as $fieldvalue){
										if($fieldvalue->parent == $parentfieldvalue->term_id){
											if($params['listdefault'] != ''){
												if(is_array($params['listdefault'])){
													if(in_array($fieldvalue->term_id, $params['listdefault'])){ $fieldtaxSelectedValue = 1;}else{ $fieldtaxSelectedValue = 0;}
												}else{
													if(strcmp($params['listdefault'],$fieldvalue->term_id) == 0){ $fieldtaxSelectedValue = 1;}else{ $fieldtaxSelectedValue = 0;}
												}
											}else{
												$fieldtaxSelectedValue = 0;
											}
											
											if($fieldtaxSelectedValue == 1){
												$output_options .= '	<option value="'.$fieldvalue->term_id.'" selected>'.$fieldvalue->name.'</option>';
											}else{
												$output_options .= '	<option value="'.$fieldvalue->term_id.'">'.$fieldvalue->name.'</option>';
											}
										}
									}
									
								$output_options .= '</optgroup>';
							
							}
						}
					}else{
						foreach( $fieldvalues as $fieldvalue){
							if($fieldvalue->parent != 0){$hasparentitem = ' ';}else{$hasparentitem = '';}
							if($params['listdefault'] != ''){
								if(is_array($params['listdefault'])){
									if(in_array($fieldvalue->term_id, $params['listdefault'])){ $fieldtaxSelectedValue = 1;}else{ $fieldtaxSelectedValue = 0;}
								}else{
									if(strcmp($params['listdefault'],$fieldvalue->term_id) == 0){ $fieldtaxSelectedValue = 1;}else{ $fieldtaxSelectedValue = 0;}
								}
							}else{
								$fieldtaxSelectedValue = 0;
							}
							
							if($fieldtaxSelectedValue == 1){
								$output_options .= '	<option value="'.$fieldvalue->term_id.'" selected>'.$hasparentitem.$fieldvalue->name.'</option>';
							}else{
								$output_options .= '	<option value="'.$fieldvalue->term_id.'">'.$hasparentitem.$fieldvalue->name.'</option>';
							}
									
						}
					}
					


			    	$output = '';
					$output .= '<div class="pf_fr_inner" data-pf-parent="">';
		   			
			   		switch ($params['listtype']) {
			   			/**
			   			*Listing Types,Item Types,Locations,Features
			   			**/
			   			case 'listingtypes':
			   			case 'itemtypes':
			   			case 'locations':
			   			case 'features':
			   				if (!empty($params['listtitle'])) {
				   				$output .= '<label for="'.$params['listname'].'" class="lbl-text">'.$params['listtitle'].':</label>';
			   				}
			   				$output .= '
			                <label class="lbl-ui select">
			                <select'.$multiplevar.' name="'.$params['listname'].$multipletag.'" id="'.$params['listname'].'">
			                <option></option>
			                '.$output_options.'
			                </select>
			                </label>';
			   			break;
			   		}

			   		$output .= '</div>';

	            return $output;
			}

			function PFValidationCheckWrite($field_validation_check,$field_validation_text,$itemid){
				
				$itemname = (string)trim($itemid);
				$itemname = (strpos($itemname, '[]') == false) ? $itemname : "'".$itemname."'" ;

				if($field_validation_check == 1){
					if($this->VSOMessages != ''){
						$this->VSOMessages .= ','.$itemname.':"'.$field_validation_text.'"';
					}else{
						$this->VSOMessages = $itemname.':"'.$field_validation_text.'"';
					}

					if($this->VSORules != ''){
						$this->VSORules .= ','.$itemname.':"required"';
					}else{
						$this->VSORules = $itemname.':"required"';
					}
				}
			}

			function PF_UserLimit_Check($action,$post_status){
	
				switch ($post_status) {
					case 'publish':
							
							switch ($action) {
								case 'edit':
									$output = (PFSAIssetControl('setup31_userlimits_useredit','','1') == 1) ? 1 : 0 ;
									break;
								
								case 'delete':
									$output = (PFSAIssetControl('setup31_userlimits_userdelete','','1') == 1) ? 1 : 0 ;
									break;
							}

						break;
					
					case 'pendingpayment':

							switch ($action) {
								case 'edit':
									$output = (PFSAIssetControl('setup31_userlimits_useredit_pendingpayment','','1') == 1) ? 1 : 0 ;
									break;
								
								case 'delete':
									$output = (PFSAIssetControl('setup31_userlimits_userdelete_pendingpayment','','1') == 1) ? 1 : 0 ;
									break;
							}

						break;

					case 'rejected':

							switch ($action) {
								case 'edit':
									$output = (PFSAIssetControl('setup31_userlimits_useredit_rejected','','1') == 1) ? 1 : 0 ;
									break;
								
								case 'delete':
									$output = (PFSAIssetControl('setup31_userlimits_userdelete_rejected','','1') == 1) ? 1 : 0 ;
									break;
							}

						break;

					case 'pendingapproval':

							switch ($action) {
								case 'edit':
									$output = (PFSAIssetControl('setup31_userlimits_useredit_pendingapproval','','0') == 1) ? 1 : 0 ;
									break;
								
								case 'delete':
									$output = (PFSAIssetControl('setup31_userlimits_userdelete_pendingapproval','','1') == 1) ? 1 : 0 ;
									break;
							}

						break;
				}

				return $output;
			}

			function __destruct() {
			  $this->FieldOutput = '';
			  $this->ScriptOutput = '';
		    }
	    /**
		*End: Class Functions
		**/
	   
	}
}

?>