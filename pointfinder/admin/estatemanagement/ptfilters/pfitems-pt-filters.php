<?php
/**********************************************************************************************************************************
*
* PF Items Post Type Custom Filters
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/
	
	$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
	

	/**
	*Start: PF Items Item Filter
	**/
	    add_action( 'restrict_manage_posts', 'pf_items_item_filter' );
	    add_filter('parse_query','pf_items_item_filter_query');
	    function pf_items_item_filter() {
	        global $typenow;
	        $setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
	        if ($typenow == $setup3_pointposttype_pt1 ) {

	           
	            echo '<input type="text" name="itemnumber" value="" placeholder="'.esc_html__('Item Number','pointfindert2d').'" />';
	             
	            
	        }
	    }

	    function pf_items_item_filter_query($query) {
	        global $pagenow;
	        global $typenow;
	        $setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
	        if ($pagenow=='edit.php' && $typenow == $setup3_pointposttype_pt1 && isset($_GET['itemnumber'])) {
	            $query->query_vars['p'] = $_GET['itemnumber'];

	        }
	        return $query;
	    }
	/**
	*End: PF Items Item Filter
	**/

	/* Get Featured Image */
	function WEBBU_get_featured_image($post_ID) {  
		$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post_ID) );
		if( !empty($feat_image) ){ $output = aq_resize($feat_image,171,114,true);}else{ $output = get_template_directory_uri().'/images/noimg.png';};
		return $output;    
	} 

	add_filter( 'manage_edit-'.$setup3_pointposttype_pt1.'_columns', 'edit_realestates_columns' ) ;

	function edit_realestates_columns( $columns ) {
			$columns = array(
				'cb' => '<input type="checkbox" />',
				'title1' => esc_html__( 'Title','pointfindert2d'),
				'istatus' => esc_html__( 'Status','pointfindert2d'),
				'ltype' => esc_html__( 'List Type','pointfindert2d'),
				'author' => esc_html__( 'Author','pointfindert2d'),
				'date' => esc_html__( 'Date','pointfindert2d'),
				'estatephoto' => esc_html__( 'Photo','pointfindert2d'),
			);
			return $columns;
	}

	
	add_filter( 'manage_edit-'.$setup3_pointposttype_pt1.'_sortable_columns', 'realestates_sortable_columns' );

	function realestates_sortable_columns( $columns ) {
		$columns['title1'] = 'title1';
		$columns['author'] = 'author';
		$columns['istatus'] = 'istatus';
		$columns['ltype'] = 'ltype';

		return $columns;
	}


	
	add_action( 'manage_'.$setup3_pointposttype_pt1.'_posts_custom_column', 'manage_realestates_columns', 10, 2 );

	function manage_realestates_columns( $column, $post_id ) {
		global $post;

		switch( $column ) {
			
			case 'title1' :

					echo '<a href="post.php?post='.$post_id.'&action=edit" style="font-weight:bold">'.get_the_title( $post_id ).'</a>';

				break;
			
			
			case 'estatephoto' :

				$post_featured_image = WEBBU_get_featured_image($post_id);  
				if ($post_featured_image) {  
					echo '<img src="' . $post_featured_image . '" width="101" height="67" alt="" />';  
				}  
				else {  
					echo '<img src="' . get_template_directory_uri() . '/images/default.jpg" />';  
				}  

				break;

			case 'istatus' :

				$value2 = '';

				$value2 = get_post_status( $post_id );
				
				if($value2 == 'publish'){ 
					$value2 = '<span style="color:green">'.esc_html__( 'Published', 'pointfindert2d' ).'</span>';
				}elseif($value2 == 'pendingapproval'){ 
					$value2 = '<span style="color:red">'.esc_html__( 'Pending Approval', 'pointfindert2d' ).'</span>';
				}elseif ($value2 == 'pendingpayment') {
					$value2 = '<span style="color:red">'.esc_html__( 'Pending Payment', 'pointfindert2d' ).'</span>';
				}elseif ($value2 == 'rejected') {
					$value2 = '<span style="color:red">'.esc_html__( 'Rejected', 'pointfindert2d' ).'</span>';
				}
				echo $value2;
				break;

			case 'ltype':

				echo GetPFTermInfo($post_id, 'pointfinderltypes');
				break;		
				
		}
	}

	function PF_post_type_filters(){

		$screen = get_current_screen();
		$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
		
		if($screen->post_type == $setup3_pointposttype_pt1 && $screen->id == 'edit-'.$setup3_pointposttype_pt1){
			$setup3_pointposttype_pt4_check = PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
			$setup3_pointposttype_pt5_check = PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
			$pftaxarray = array('pointfinderltypes');
			if($setup3_pointposttype_pt4_check == 1){$pftaxarray[] = 'pointfinderitypes';}	
			if($setup3_pointposttype_pt5_check == 1){$pftaxarray[] = 'pointfinderlocations';}
			require_once( get_template_directory().'/admin/estatemanagement/taxonomy-filter-class.php');
			new Tax_CTP_Filter(array($setup3_pointposttype_pt1 => $pftaxarray));
		}
	}
	add_action('admin_head','PF_post_type_filters');
?>