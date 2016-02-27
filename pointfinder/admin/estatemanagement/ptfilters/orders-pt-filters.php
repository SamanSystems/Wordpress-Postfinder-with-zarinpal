<?php
/**********************************************************************************************************************************
*
* Orders Post Type Custom Filters
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/

	/**
	*Start: Reviews Item Filter
	**/
	    add_action( 'restrict_manage_posts', 'pf_orders_item_filter' );
	    add_filter('parse_query','pf_orders_item_filter_query');
	    function pf_orders_item_filter() {

	        // only display these taxonomy filters on desired custom post_type listings
	        global $typenow;
	        if ($typenow == 'pointfinderorders' ) {
	            echo '<input type="text" name="itemnumber" value="" placeholder="'.esc_html__('Item Number','pointfindert2d').'" />';
	        }
	    }

	    function pf_orders_item_filter_query($query) {
	        global $pagenow;
	        global $typenow;
	        if ($pagenow=='edit.php' && $typenow == 'pointfinderorders' && isset($_GET['itemnumber'])) {
	            $query->query_vars['meta_key'] = 'pointfinder_order_itemid';
	            $query->query_vars['meta_value'] = $_GET['itemnumber'];
	        }
	        return $query;
	    }

	    
	/**
	*End: Reviews Item Filter
	**/



	function pf_orders_remove_unused_links($actions, $page_object)
	{   
	    global $post;
	    if($page_object->post_type == 'pointfinderorders'){
	    	
	        unset($actions['edit']);
	        unset($actions['inline hide-if-no-js']);
	        unset($actions['edit_as_new_draft']);
	     
	    }
	    return $actions;
	}
	add_filter('page_row_actions', 'pf_orders_remove_unused_links', 10, 2);

	
	add_action('admin_head', 'pointfinder_extrafunction_01');
	function pointfinder_extrafunction_01(){
	    $screen = get_current_screen();
	    if($screen->post_type == 'pointfinderorders'){
	    	 
	    	global $submenu;
    		unset($submenu['edit.php?post_type=pointfinderorders'][10]); 
	        echo '<style type="text/css">#titlediv{margin-bottom: 10px;}#favorite-actions {display:none;}.add-new-h2{display:none;}/*.tablenav{display:none;}*/</style>';
	    }
	};

	
	add_filter( 'manage_edit-pointfinderorders_columns', 'edit_orders_columns' );
	function edit_orders_columns( $columns ) {
	    
	        $columns = array(
	            'cb' => '<input type="checkbox" />',
	            'title' => esc_html__( 'Title','pointfindert2d' ),
	            'istatus' => esc_html__( 'Status','pointfindert2d' ),
	            'itemname' => esc_html__( 'Item','pointfindert2d' ),
	            'price' => esc_html__( 'Total','pointfindert2d' ),
	            'itime' => esc_html__( 'Time','pointfindert2d' ),
	            'itype' => esc_html__( 'Type','pointfindert2d' ),
	            'date' => esc_html__( 'Create Date','pointfindert2d' ),
	            'idate' => esc_html__( 'Expire Date','pointfindert2d' ),
	        );
	    

	    return $columns;
	}

	
	add_filter( 'manage_edit-pointfinderorders_sortable_columns', 'orders_sortable_columns' );

	function orders_sortable_columns( $columns ) {

	    $columns['istatus'] = 'istatus';

	    return $columns;
	}


	
	add_action( 'manage_pointfinderorders_posts_custom_column', 'manage_orders_columns', 10, 2 );

	function manage_orders_columns( $column, $post_id ) {
	    global $post;

	    switch( $column ) {
	        

	        case 'istatus' :

	            $value2 = '';
	            
	            $value2 = get_post_status( $post_id );
	            
	            if($value2 == 'publish'){ 
	                $value2 = '<span style="color:green">'.esc_html__( 'Published', 'pointfindert2d' ).'</span>';
	            }elseif($value2 == 'pendingapproval'){ 
	                $value2 = '<span style="color:red">'.esc_html__( 'Pending Approval', 'pointfindert2d' ).'</span>';
	            }elseif ($value2 == 'pendingpayment') {
	                $value2 = '<span style="color:red">'.esc_html__( 'Pending Payment', 'pointfindert2d' ).'</span>';
	            }elseif ($value2 == 'pfsuspended') {
	                $value2 = '<span style="color:red">'.esc_html__( 'Suspended', 'pointfindert2d' ).'</span>';
	            }elseif ($value2 == 'completed') {
	                $value2 = '<span style="color:green">'.esc_html__( 'Completed', 'pointfindert2d' ).'</span>';
	            }elseif ($value2 == 'pfcancelled') {
	                $value2 = '<span style="color:red">'.esc_html__( 'Cancelled', 'pointfindert2d' ).'</span>';
	            }
	            echo $value2;
	            break;

	        case 'itemname':

	            $prderinfo_itemid = esc_attr(get_post_meta( $post_id , 'pointfinder_order_itemid', true ));
	            
	            if(!empty($prderinfo_itemid)){
	                //echo '<a href="'.get_permalink($item_id).'" target="_blank">'.get_the_title($item_id).'('.$item_id.')</a>';
	                echo '<a href="'.get_edit_post_link($prderinfo_itemid).'" target="_blank"><strong>'.get_the_title($prderinfo_itemid).'('.$prderinfo_itemid.')</strong></a>';
	            }
	            break;

	        case 'price':
	            echo esc_attr(get_post_meta( $post_id, 'pointfinder_order_price', true ));
	            echo esc_attr(get_post_meta( $post_id, 'pointfinder_order_pricesign', true ));
	            break;


	        case 'itime':
	            echo esc_attr(get_post_meta( $post_id, 'pointfinder_order_listingtime', true )).' '.esc_html__('Days','pointfindert2d');
	            break;


	        case 'itype':
	        	$nameofb = esc_attr(get_post_meta( $post_id, 'pointfinder_order_listingpname', true ));

	            if(esc_attr(get_post_meta( $post_id, 'pointfinder_order_recurring', true )) == 1){
	            	echo esc_html__('Recurring','pointfindert2d').' : '.$nameofb;
	            }else{
	            	echo esc_html__('Direct','pointfindert2d').' : '.$nameofb;
	            }
	            break;


	        case 'idate':
	            echo esc_attr(get_post_meta( $post_id, 'pointfinder_order_expiredate', true ));
	            break;

	    }
	}
	/**/
?>