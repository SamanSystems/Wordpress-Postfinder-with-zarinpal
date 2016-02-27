<?php
/**********************************************************************************************************************************
*
* Point Finder Item Add Page Metabox.
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/

/**
*Start:Enqueue Styles
**/
function pointfinder_orders_styles_ex(){
	$screen = get_current_screen();
	$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
	if ($screen->post_type == $setup3_pointposttype_pt1) {
		wp_register_script(
			'metabox-custom-cf-scriptspf', 
			get_template_directory_uri() . '/admin/core/js/metabox-scripts.js', 
			array('jquery'),
			'1.0.0',
			true
		); 
        wp_enqueue_script('metabox-custom-cf-scriptspf'); 

        wp_register_style('pfsearch-goldenforms-css', get_template_directory_uri() . '/css/golden-forms.css', array(), '1.0', 'all');
		wp_enqueue_style('pfsearch-goldenforms-css');
	}
}
add_action('admin_enqueue_scripts','pointfinder_orders_styles_ex' );
/**
*End:Enqueue Styles
**/



/**
*Start : Add Metaboxes
**/
	function pointfinder_orders_add_meta_box_ex($post_type) {
		$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');

		if ($post_type == $setup3_pointposttype_pt1) {
			$setup3_pointposttype_pt7s = PFSAIssetControl('setup3_pointposttype_pt7s','','Listing Type');
			$setup3_pointposttype_pt6 = PFSAIssetControl('setup3_pointposttype_pt6','','Features');

			remove_meta_box( 'pointfinderltypesdiv', $setup3_pointposttype_pt1, 'side' );
			remove_meta_box( 'pointfinderfeaturesdiv', $setup3_pointposttype_pt1, 'side' );
			
			add_meta_box(
				'pointfinder_itemdetailcf_process_lt',
				$setup3_pointposttype_pt7s,
				'pointfinder_itemdetailcf_process_lt_function',
				$setup3_pointposttype_pt1,
				'normal',
				'core'
			);

			add_meta_box(
				'pointfinder_itemdetailcf_process',
				esc_html__( 'Additional Details', 'pointfindert2d' ),
				'pointfinder_itemdetailcf_process_function',
				$setup3_pointposttype_pt1,
				'normal',
				'core'
			);
			$setup3_pointposttype_pt6_check = PFSAIssetControl('setup3_pointposttype_pt6_check','','1');
			if ($setup3_pointposttype_pt6_check ) {
				add_meta_box(
					'pointfinder_itemdetailcf_process_fe',
					$setup3_pointposttype_pt6,
					'pointfinder_itemdetailcf_process_fe_function',
					$setup3_pointposttype_pt1,
					'normal',
					'core'
				);
			}
			$setup3_modulessetup_openinghours = PFSAIssetControl('setup3_modulessetup_openinghours','','0');
			$setup3_modulessetup_openinghours_ex = PFSAIssetControl('setup3_modulessetup_openinghours_ex','','1');
			if ($setup3_modulessetup_openinghours == 1) {
				add_meta_box(
					'pointfinder_itemdetailoh_process_fe',
					esc_html__( 'Opening Hours', 'pointfindert2d' ).' <small>('.esc_html__('Leave blank to show closed','pointfindert2d' ).')</small>',
					'pointfinder_itemdetailoh_process_fe_function',
					$setup3_pointposttype_pt1,
					'normal',
					'core'
				);
			}


		}

		
	}
	add_action( 'add_meta_boxes', 'pointfinder_orders_add_meta_box_ex', 10,1);
/**
*End : Add Metaboxes
**/



/**
*Start : Listing Type
**/
function pointfinder_itemdetailcf_process_lt_function( $post ) {
	
	$setup4_submitpage_listingtypes_title = PFSAIssetControl('setup4_submitpage_listingtypes_title','','Listing Type');
    $setup4_submitpage_listingtypes_group = PFSAIssetControl('setup4_submitpage_listingtypes_group','','0');
    $setup4_submitpage_listingtypes_group_ex = 1;
    $setup4_submitpage_listingtypes_verror = PFSAIssetControl('setup4_submitpage_listingtypes_verror','','Please select a listing type.');
    $setup4_submitpage_listingtypes_gridview = PFSAIssetControl('setup4_submitpage_listingtypes_gridview','','0');

    $item_defaultvalue = (isset($post)) ? wp_get_post_terms($post->ID, 'pointfinderltypes', array("fields" => "ids")) : '' ;

    echo '<div class="form-field">';
    echo '<section>';  
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
    echo PFGetList_forAdmin($fields_output_arr);
    echo '</section>';


    echo '
    <script>
    (function($) {
  	"use strict";
    $(function(){
        $("#pfupload_listingtypes").select2({
            placeholder: "'.esc_html__("Please select","pointfindert2d").'", 
            formatNoMatches:"'.esc_html__("Nothing found.","pointfindert2d").'",
            allowClear: true, ';
			if($setup4_submitpage_listingtypes_gridview == 1){
				echo 'dropdownCssClass: "pointfinder-upload-page",';
			}
			echo '
            minimumResultsForSearch: 10
        });
    });
    ';

    $setup3_modulessetup_openinghours = PFSAIssetControl('setup3_modulessetup_openinghours','','0');
    
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

        $cbox_term_arr = "["; $cbox_term_arr2 = "["; $cbox_term_arr3 = "[";$ohours_term_arr = "[";

        global $pfadvancedcontrol_options;

		if($pfstart){
			foreach ($pf_get_term_details as &$pf_get_term_detail) {


				if (PFADVIssetControl('setupadvancedconfig_'.$pf_get_term_detail->term_id.'_advanced_status','','0') == 1) {
					
					if ($setup3_modulessetup_openinghours == 1) {
					
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
					
		 			$setup42_itempagedetails_configuration = (isset($pfadvancedcontrol_options['setupadvancedconfig_'.$pf_get_term_detail->term_id.'_configuration']))? $pfadvancedcontrol_options['setupadvancedconfig_'.$pf_get_term_detail->term_id.'_configuration'] : array();

		 			if (isset($setup42_itempagedetails_configuration['customtab1']['status'])) {
		 				if ($setup42_itempagedetails_configuration['customtab1']['status'] != 0) {

							$cbox_term_arr .= '"'.$pf_get_term_detail->term_id.'"';
							$cbox_term_arr .= ",";

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
					        		$cbox_term_arr .= '"'.$pf_get_term_detail2->term_id.'"';
									$cbox_term_arr .= ",";
					        	}
					        }

						}
		 			}

		 			if (isset($setup42_itempagedetails_configuration['customtab2']['status'])) {
		 				if ($setup42_itempagedetails_configuration['customtab2']['status'] != 0) {

							$cbox_term_arr2 .= '"'.$pf_get_term_detail->term_id.'"';
							$cbox_term_arr2 .= ",";

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
					        		$cbox_term_arr2 .= '"'.$pf_get_term_detail2->term_id.'"';
									$cbox_term_arr2 .= ",";
					        	}
					        }

						}
		 			}

		 			if (isset($setup42_itempagedetails_configuration['customtab3']['status'])) {
		 				if ($setup42_itempagedetails_configuration['customtab3']['status'] != 0) {

							$cbox_term_arr3 .= '"'.$pf_get_term_detail->term_id.'"';
							$cbox_term_arr3 .= ",";

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
					        		$cbox_term_arr3 .= '"'.$pf_get_term_detail2->term_id.'"';
									$cbox_term_arr3 .= ",";
					        	}
					        }

						}
		 			}

				}
			}
		}

		$ohours_term_arr .= "]";$cbox_term_arr .= "]";$cbox_term_arr2 .= "]";$cbox_term_arr3 .= "]";

		echo "
		var openingharr = ".$ohours_term_arr.";
		var cboxarr1 = ".$cbox_term_arr.";
		var cboxarr2 = ".$cbox_term_arr2.";
		var cboxarr3 = ".$cbox_term_arr3.";
		$(function(){
			if ($( '#pfupload_listingtypes' ).val() != '') {

				if ($.inArray( $('#pfupload_listingtypes').val(), cboxarr1 ) == -1) {
					if (cboxarr1.length > 0) {
						$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox1').hide();
					}
				}else{
					$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox1').show();
				}

				if ($.inArray( $('#pfupload_listingtypes').val(), cboxarr2 ) == -1) {
					if (cboxarr2.length > 0) {
						$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox2').hide();
					}
				}else{
					$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox2').show();
				}

				if ($.inArray( $('#pfupload_listingtypes').val(), cboxarr3 ) == -1) {
					if (cboxarr3.length > 0) {
						$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox3').hide();
					}
				}else{
					$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox3').show();
				}

				";

				if ($setup3_modulessetup_openinghours == 1) {
				echo "
				if ($.inArray( $('#pfupload_listingtypes').val(), openingharr ) != -1) {
					$('#pointfinder_openinghours').hide();$('#pointfinder_itemdetailoh_process_fe').hide();
				}else{
					$('#pointfinder_openinghours').show();$('#pointfinder_itemdetailoh_process_fe').show();
				}
				";
				}

				echo "
			}
			
		});

		$( '#pfupload_listingtypes' ).change(function(){	

			if ($.inArray( $('#pfupload_listingtypes').val(), cboxarr1 ) == -1) {
				$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox1').hide();
			}else{
				$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox1').show();
			}

			if ($.inArray( $('#pfupload_listingtypes').val(), cboxarr2 ) == -1) {
				$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox2').hide();
			}else{
				$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox2').show();
			}

			if ($.inArray( $('#pfupload_listingtypes').val(), cboxarr3 ) == -1) {
				$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox3').hide();
			}else{
				$('#redux-pointfinderthemefmb_options-metabox-pf_item_custombox3').show();
			}

			";
			if ($setup3_modulessetup_openinghours == 1) {
			echo "		
			if ($.inArray( $('#pfupload_listingtypes').val(), openingharr ) != -1) {
				$('#pointfinder_openinghours').hide();$('#pointfinder_itemdetailoh_process_fe').hide();
			}else{
				$('#pointfinder_openinghours').show();$('#pointfinder_itemdetailoh_process_fe').show();
			}
			";
			}
			echo "
		});
		";

		echo "})(jQuery);</script></div>";
	
}
/**
*End : Listing Type
**/



/**
*Start : Custom Fields Content
**/
function pointfinder_itemdetailcf_process_function( $post ) {
	echo "<div class='golden-forms'>";
	echo "<section class='pfsubmit-inner pfsubmit-inner-customfields'></section>";
	echo "</div>";
	$lang_custom = '';

	if(function_exists('icl_object_id')) {
		$lang_custom = PF_current_language();
	}
		
	echo "<script>";
		echo "
		(function($) {
  		'use strict';
			$.pf_getcustomfields_now = function(itemid){

				$.ajax({
			    	beforeSend:function(){
			    		$('.pfsubmit-inner-customfields').pfLoadingOverlay({action:'show'});
			    	},
					url: '".get_template_directory_uri()."/admin/core/pfajaxhandler.php',
					type: 'POST',
					dataType: 'html',
					data: {
						action: 'pfget_fieldsystem',
						id: itemid,
						place:'backend',
						lang: '".$lang_custom."',
						postid:'".get_the_id()."',
						security: '".wp_create_nonce('pfget_fieldsystem')."'
					},
				})
				.done(function(obj) {
					if (obj.length == 0) {
						$('.pfsubmit-inner-customfields').hide();
					}else{
						$('.pfsubmit-inner-customfields').show();
					}
					$('.pfsubmit-inner-customfields').html(obj);
					$('.pfsubmit-inner-customfields').pfLoadingOverlay({action:'hide'});
				});
			}

			$( '#pfupload_listingtypes' ).change(function(){
				$.pf_getcustomfields_now($('#pfupload_listingtypes').val());
			});

			$(function(){
				$.pf_getcustomfields_now($('#pfupload_listingtypes').val());
			});
		})(jQuery);
		";
	echo "</script>";
}
/**
*End : Custom Fields Content
**/


/**
*Start : Features
**/
function pointfinder_itemdetailcf_process_fe_function( $post ) {
	$setup3_pointposttype_pt6_check = PFSAIssetControl('setup3_pointposttype_pt6_check','','1');
	if ($setup3_pointposttype_pt6_check ) {

		echo "<a class='pfitemdetailcheckall'>";
		echo esc_html__('Check All','pointfindert2d');
		echo "</a>";
		echo " / ";
		echo "<a class='pfitemdetailuncheckall'>";
		echo esc_html__('Uncheck All','pointfindert2d');
		echo "</a>";
		echo "<section class='pfsubmit-inner pfsubmit-inner-features'></section>";
		
		$lang_custom = '';

		if(function_exists('icl_object_id')) {
			$lang_custom = PF_current_language();
		}

		echo "<script>";
			echo "
			(function($) {
	  		'use strict';
				$.pf_getfeatures_now = function(itemid){

					$.ajax({
				    	beforeSend:function(){
				    		$('.pfsubmit-inner-features').pfLoadingOverlay({action:'show'});
				    	},
						url: '".get_template_directory_uri()."/admin/core/pfajaxhandler.php',
						type: 'POST',
						dataType: 'html',
						data: {
							action: 'pfget_featuresystem',
							id: itemid,
							place: 'backend',
							postid:'".get_the_id()."',
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
						$('.pfsubmit-inner-features').pfLoadingOverlay({action:'hide'});
					});
				}

				$( '#pfupload_listingtypes' ).change(function(){
					$.pf_getfeatures_now($('#pfupload_listingtypes').val());
				});

				$(function(){
					$.pf_getfeatures_now($('#pfupload_listingtypes').val());
				});
										
			})(jQuery);
			";
		echo "</script>";
	}
}
/**
*End : Features
**/


/**
*Start : Opening Hours
**/
function pointfinder_itemdetailoh_process_fe_function( $post ) {
	$setup3_modulessetup_openinghours = PFSAIssetControl('setup3_modulessetup_openinghours','','0');
	$setup3_modulessetup_openinghours_ex = PFSAIssetControl('setup3_modulessetup_openinghours_ex','','1');
	$setup3_modulessetup_openinghours_ex2 = PFSAIssetControl('setup3_modulessetup_openinghours_ex2','','1');

	
	
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('jquery-ui-slider');
	wp_register_script('theme-timepicker', get_template_directory_uri() . '/js/jquery-ui-timepicker-addon.js', array('jquery','jquery-ui-datepicker'), '4.0',true); 
	wp_enqueue_script('theme-timepicker');
	wp_enqueue_style('jquery-ui-smoothnesspf', get_template_directory_uri() . "/css/jquery-ui.theme.min.css", false, null);

	
	if ($setup3_modulessetup_openinghours_ex2 == 1) {
		$text_ohours1 = esc_html__('Monday','pointfindert2d');
		$text_ohours2 = esc_html__('Sunday','pointfindert2d');
	}else{
		$text_ohours1 = esc_html__('Sunday','pointfindert2d');
		$text_ohours2 = esc_html__('Monday','pointfindert2d');
	}

	echo '<section class="pfsubmit-inner pf-openinghours-div golden-forms">';									
	
	$oh_scriptoutput = '';

	if ($setup3_modulessetup_openinghours_ex == 1) {
		echo '
		<section>
        <label class="lbl-ui">
        <input type="text" name="o1" class="input" placeholder="Monday-Friday: 09:00 - 22:00" value="'.esc_attr(get_post_meta($post->ID,'webbupointfinder_items_o_o1',true)).'" />
        </label>
        </section>
        ';
	}elseif ($setup3_modulessetup_openinghours_ex == 0) {

		$ohours_first = '<section>
			<label for="o1" class="lbl-text">'.esc_html__('Monday','pointfindert2d').':</label>
            <label class="lbl-ui">
            <input type="text" name="o1" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($post->ID,'webbupointfinder_items_o_o1',true)).'" />
            </label>
            </section>';

        $ohours_last = '<section>
            <label for="o7" class="lbl-text">'.esc_html__('Sunday','pointfindert2d').':</label>
            <label class="lbl-ui">
            <input type="text" name="o7" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($post->ID,'webbupointfinder_items_o_o7',true)).'"/>
            </label>
            </section>';

        if ($setup3_modulessetup_openinghours_ex2 != 1) {
			$ohours_first = $ohours_last . $ohours_first;
			$ohours_last = '';
		}

		echo $ohours_first;
		echo '
            <section>
            <label for="o2" class="lbl-text">'.esc_html__('Tuesday','pointfindert2d').':</label>
            <label class="lbl-ui">
            <input type="text" name="o2" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($post->ID,'webbupointfinder_items_o_o2',true)).'"/>
            </label>
            </section>
            <section>
            <label for="o3" class="lbl-text">'.esc_html__('Wednesday','pointfindert2d').':</label>
            <label class="lbl-ui">
            <input type="text" name="o3" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($post->ID,'webbupointfinder_items_o_o3',true)).'"/>
            </label>
            </section>
            <section>
            <label for="o4" class="lbl-text">'.esc_html__('Thursday','pointfindert2d').':</label>
            <label class="lbl-ui">
            <input type="text" name="o4" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($post->ID,'webbupointfinder_items_o_o4',true)).'"/>
            </label>
            </section>
            <section>
            <label for="o5" class="lbl-text">'.esc_html__('Friday','pointfindert2d').':</label>
            <label class="lbl-ui">
            <input type="text" name="o5" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($post->ID,'webbupointfinder_items_o_o5',true)).'"/>
            </label>
            </section>
            <section>
            <label for="o6" class="lbl-text">'.esc_html__('Saturday','pointfindert2d').':</label>
            <label class="lbl-ui">
            <input type="text" name="o6" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($post->ID,'webbupointfinder_items_o_o6',true)).'"/>
            </label>
            </section>
        ';
        echo $ohours_last;

	}elseif($setup3_modulessetup_openinghours_ex == 2){
		$general_rtlsupport = PFSAIssetControl('general_rtlsupport','','0');
		if ($general_rtlsupport == 1) {
			$rtltext_oh = 'true';
		}else{
			$rtltext_oh = 'false';
		}

		for ($i=0; $i < 7; $i++) { 
			$o_value[$i] = get_post_meta($post->ID,'webbupointfinder_items_o_o'.($i+1),true);
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

			
			$oh_scriptoutput .= "
			$.timepicker.timeRange(
				$('input[name=\"o".($i+1)."_1\"]'),
				$('input[name=\"o".($i+1)."_2\"]'),
				{
					minInterval: (1000*60*60),
					timeFormat: 'HH:mm',
					start: {},
					end: {},
					timeOnly:true,
					showSecond:null,
					showMillisec:null,
					showMicrosec:null,
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
	        </section>';

        $ohours_last = '<section>
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
	        </section>';

        if ($setup3_modulessetup_openinghours_ex2 != 1) {
			$ohours_first = $ohours_last . $ohours_first;
			$ohours_last = '';
		}
		
		echo $ohours_first;
		echo '
			
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
	    echo $ohours_last;
	}

    echo '</section>';

    echo '<script>
    (function($) {
  	"use strict";$(function(){';
  	echo $oh_scriptoutput;
  	echo '});})(jQuery);</script>';
	
}
/**
*End : Opening Hours
**/

/**
*Start : Save Metadata and other inputs
**/
function pointfinder_item_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	/* Make sure that it is set.*/
	if ( ! isset( $_POST['pfupload_listingtypes'] ) ) {
		return;
	}

	$pfupload_listingtypes = sanitize_text_field($_POST['pfupload_listingtypes']);

	if (function_exists('get_current_screen')) {
		$screen = get_current_screen();
	}
	
	$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
	if (isset($screen)) {
		if ($screen->post_type == $setup3_pointposttype_pt1) {
			
			/*Listing Type*/
				if(isset($pfupload_listingtypes)){
					if(PFControlEmptyArr($pfupload_listingtypes)){
						$pftax_terms = $pfupload_listingtypes;
					}else if(!PFControlEmptyArr($pfupload_listingtypes) && isset($pfupload_listingtypes)){
						$pftax_terms = array($pfupload_listingtypes);
					}
					wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderltypes');
				}


			/*Custom fields loop*/
				$pfstart = PFCheckStatusofVar('setup1_slides');
				$setup1_slides = PFSAIssetControl('setup1_slides','','');

				if($pfstart == true){

					foreach ($setup1_slides as &$value) {

			          $available_fields = array(1,2,3,4,5,7,8,9,14);
			          
			          if(in_array($value['select'], $available_fields)){

			           	if (isset($_POST[''.$value['url'].''])) {
				           	
				           	if (is_array($_POST[''.$value['url'].''])) {
				           		$post_value_url = PFCleanArrayAttr('PFCleanFilters',$_POST[''.$value['url'].'']);
				           	}else{
				           		$post_value_url = sanitize_text_field($_POST[''.$value['url'].'']);
				           	}

							if(isset($post_value_url)){
								
								if(!is_array($post_value_url)){ 
									update_post_meta($post_id, 'webbupointfinder_item_'.$value['url'], $post_value_url);	
								}else{
									if(PFcheck_postmeta_exist('webbupointfinder_item_'.$value['url'],$post_id)){
										delete_post_meta($post_id, 'webbupointfinder_item_'.$value['url']);
									};
									
									foreach ($post_value_url as $val) {
										add_post_meta ($post_id, 'webbupointfinder_item_'.$value['url'], $val);
									};

								};
							}else{
								delete_post_meta($post_id, 'webbupointfinder_item_'.$value['url']);
							};
						};

			          };
			          
			        };
				};


			/*Features*/
				$setup3_pointposttype_pt6_check = PFSAIssetControl('setup3_pointposttype_pt6_check','','1');
				if ($setup3_pointposttype_pt6_check ) {
					if (!empty($_POST['pffeature'])) {
						$feature_values = PFCleanArrayAttr('PFCleanFilters',$_POST['pffeature']);
					
						if(isset($feature_values)){				
							if(PFControlEmptyArr($feature_values)){
								$pftax_terms = $feature_values;
							}else if(!PFControlEmptyArr($feature_values) && isset($feature_values)){
								$pftax_terms = array($feature_values);
							}
							wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderfeatures');
						}
					}
				}

			/*Opening Hours*/
				$setup3_modulessetup_openinghours = PFSAIssetControl('setup3_modulessetup_openinghours','','0');
				$setup3_modulessetup_openinghours_ex = PFSAIssetControl('setup3_modulessetup_openinghours_ex','','1');
				if ($setup3_modulessetup_openinghours == 1 &&  $setup3_modulessetup_openinghours_ex == 2) {
					$i = 1;
					while ( $i <= 7) {
						if(isset($_POST['o'.$i.'_1']) && isset($_POST['o'.$i.'_2'])){
							update_post_meta($post_id, 'webbupointfinder_items_o_o'.$i, sanitize_text_field($_POST['o'.$i.'_1']).'-'.sanitize_text_field($_POST['o'.$i.'_2']));	
						}
						$i++;
					}
				}elseif ($setup3_modulessetup_openinghours == 1 &&  $setup3_modulessetup_openinghours_ex == 0) {
					$i = 1;
					while ( $i <= 7) {
						if(isset($_POST['o'.$i])){
							update_post_meta($post_id, 'webbupointfinder_items_o_o'.$i, sanitize_text_field($_POST['o'.$i]));	 
						}
						$i++;
					}
				}elseif ($setup3_modulessetup_openinghours == 1 &&  $setup3_modulessetup_openinghours_ex == 1) {
					$i = 1;
					while ( $i <= 1) {
						if(isset($_POST['o'.$i])){
							update_post_meta($post_id, 'webbupointfinder_items_o_o'.$i, sanitize_text_field($_POST['o'.$i]));	 
						}
						$i++;
					}
				}


		}
	}
}
add_action( 'save_post', 'pointfinder_item_save_meta_box_data' );
/**
*End : Save Metadata and other inputs
**/
?>