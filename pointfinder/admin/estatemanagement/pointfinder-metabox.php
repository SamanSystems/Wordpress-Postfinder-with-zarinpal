<?php
/**********************************************************************************************************************************
*
* Meta Boxes
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/
global $pointfindertheme_option;


$pointfinder_center_lat = PFSAIssetControl('setup5_mapsettings_lat','','33.87212589943945');
$pointfinder_center_lng = PFSAIssetControl('setup5_mapsettings_lng','','-118.19297790527344');
$pointfinder_google_map_zoom = PFSAIssetControl('setup5_mapsettings_zoom','','6');
$pointfinder_google_map_default = PFSAIssetControl('pointfinder_google_map_default','','ROADMAP');

$setup3_pointposttype_pt7 = PFSAIssetControl('setup3_pointposttype_pt7','','Listing Types');
$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
$setup3_pointposttype_pt8 = PFSAIssetControl('setup3_pointposttype_pt8','','agents');

$setup3_modulessetup_openinghours = PFSAIssetControl('setup3_modulessetup_openinghours','','0');
$setup3_modulessetup_openinghours_ex = PFSAIssetControl('setup3_modulessetup_openinghours_ex','','1');

$setup3_pointposttype_pt6_status = PFSAIssetControl('setup3_pointposttype_pt6_status','','1');

$setup1_slides = PFSAIssetControl('setup1_slides','','');

$prefix = 'webbupointfinder';

global $meta_boxes;

$meta_boxes = array();


$meta_boxes[] = array(
	'id' => 'pointfinder_map',
	'title' => esc_html__('Please Select Location','pointfindert2d'),
	'pages' => array( $setup3_pointposttype_pt1 ),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'id'            => "{$prefix}_items_address",
			'name'          => esc_html__('Address','pointfindert2d'),
			'type'          => 'text',
			'std'           => '',
			),
		
		array(
			'id'            => "{$prefix}_items_location",
			'name'          => esc_html__('Location','pointfindert2d'),
			'type'          => 'map',
			'std'           => ''.$pointfinder_center_lat.', '.$pointfinder_center_lng.','.$pointfinder_google_map_zoom.'',     
			'style'         => 'width: 100%; height: 500px',
			'address_field' => "{$prefix}_items_address",                     
		),
	),
	
);



$setup42_itempagedetails_configuration = (isset($pointfindertheme_option['setup42_itempagedetails_configuration']))? $pointfindertheme_option['setup42_itempagedetails_configuration'] : array();
$pf_gallery_status = (isset($setup42_itempagedetails_configuration['gallery']['status']))? $setup42_itempagedetails_configuration['gallery']['status'] : 0;							
if( $pf_gallery_status == 1){
	
	$meta_boxes[] = array(
		'title' => esc_html__('Gallery','pointfindert2d'),
		'pages' => array( $setup3_pointposttype_pt1 ),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
				array(
					'name'             => esc_html__('Images','pointfindert2d'),
					'id'               => "{$prefix}_item_images",
					'max_file_uploads' => 50,
					'type'             => 'image_advanced',
				),
		)
	);
}


function pointfinder_pfitems_remove_meta_box($post_type) {

	$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
	if ($post_type == $setup3_pointposttype_pt1) {
		
		remove_meta_box( 'mymetabox_revslider_0', $setup3_pointposttype_pt1, 'normal' );
	}

}
add_action( 'add_meta_boxes', 'pointfinder_pfitems_remove_meta_box', 10,1);

function webbu_pointfinder_register_meta_boxes()
{
	if ( !class_exists( 'RW_Meta_Box' ) )
		return;

	global $meta_boxes;
	foreach ( $meta_boxes as $meta_box )
	{
		new RW_Meta_Box( $meta_box );
	}
}
add_action( 'admin_init', 'webbu_pointfinder_register_meta_boxes' );