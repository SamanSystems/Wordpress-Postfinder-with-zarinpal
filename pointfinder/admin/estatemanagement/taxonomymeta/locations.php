<?php

/********************* BEGIN DEFINITION OF META SECTIONS FOR LOCATION ***********************/

global $pf_extra_taxonomyfields;
$pf_extra_taxonomyfields = array();

/*For locations*/
$setup3_pointposttype_pt5_check = PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
if ($setup3_pointposttype_pt5_check == 1) {
	$pf_extra_taxonomyfields[] = array(
		'title' => esc_html__('Coordinates for This Location','pointfindert2d'),			
		'taxonomies' => array('pointfinderlocations'),			
		'id' => 'pointfinderlocations_vars',					
		
		'fields' => array(							
			array(
				'name' => esc_html__('Lat Coordinate','pointfindert2d'),
				'desc' => sprintf(esc_html__('This coordinate for lat point. %sPlease click here for find your coordinates','pointfindert2d'),'<a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">','</a>'),
				'id' => 'pf_lat_of_location',
				'type' => 'text'						
			),
			
			
			array(
				'name' => esc_html__('Lng Coordinate','pointfindert2d'),
				'desc' => sprintf(esc_html__('This coordinate for lat point. %sPlease click here for find your coordinates','pointfindert2d'),'<a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">','</a>'),
				'id' => 'pf_lng_of_location',
				'type' => 'text'						
			),
			
		)
	);
}


/*For listing Types*/
$pf_extra_taxonomyfields[] = array(
	'title' => esc_html__('Directory List Specifications','pointfindert2d'),			
	'taxonomies' => array('pointfinderltypes'),			
	'id' => 'pointfinderltypes_vars',					
	
	'fields' => array(	
		array(
			'name' => esc_html__('Icon Image','pointfindert2d'),
			'id'   => 'pf_icon_of_listing',
			'type' => 'image',
		),
		array(
			'name' => esc_html__('Icon Width','pointfindert2d'),
			'desc' => esc_html__('Please write only number.','pointfindert2d'),
			'id'   => 'pf_iconwidth_of_listing',
			'type' => 'text',
			'std'  => 20,
		),
		array(
			'name' => esc_html__('Category Background Color','pointfindert2d'),
			'id'   => 'pf_catbg_of_listing',
			'type' => 'color',
		),
		array(
			'name' => esc_html__('Category Text Color','pointfindert2d'),
			'id'   => 'pf_cattext_of_listing',
			'type' => 'color',
		),
		array(
			'name' => esc_html__('Category Text Hover Color','pointfindert2d'),
			'id'   => 'pf_cattext2_of_listing',
			'type' => 'color',
		)
	)
);




/**
 * Register meta boxes
 *
 * @return void
 */
function pointfinder2_TAX_register_taxonomy_meta_boxes()
{
	// Make sure there's no errors when the plugin is deactivated or during upgrade
	if ( !class_exists( 'RW_Taxonomy_Meta' ) )
		return;

	global $pf_extra_taxonomyfields;
	foreach ( $pf_extra_taxonomyfields as $pf_extra_taxonomyfield )
	{
		new RW_Taxonomy_Meta( $pf_extra_taxonomyfield );
	}
}

// Hook to 'admin_init' to make sure the class is loaded before
// (in case using the class in another plugin)
add_action( 'admin_init', 'pointfinder2_TAX_register_taxonomy_meta_boxes' );


/********************* END DEFINITION OF META SECTIONS FOR LOCATION ***********************/

?>
