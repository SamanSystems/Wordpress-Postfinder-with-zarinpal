<?php
/**********************************************************************************************************************************
*
* Hooks & Sidebars & Menu
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/


//Main Menu Walker Class --------------------------------------------------------------------------
class pointfinder_walker_nav_menu extends Walker_Nav_Menu {
  	private $megamenu_status = "";
	function start_lvl( &$output, $depth = 0, $args = array() ) {
	    $indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); 
	    $display_depth = ( $depth + 1); 
	    $classes = array(
	        'pfnavsub-menu sub-menu',
	        ( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
	        ( $display_depth >=2 ? 'pfnavsub-menu sub-menu' : '' ),
	        'menu-depth-' . $display_depth
	        );
	    $class_names = implode( ' ', $classes );

	    $output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
	}
	  

	function start_el(  &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
	    $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); 

	    $depth_classes = array(
	        ( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
	        ( $depth >=2 ? 'sub-sub-menu-item' : '' ),
	        ( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
	        'menu-item-depth-' . $depth
	    );

	    $depth_class_names = esc_attr( implode( ' ', $depth_classes ) );
	  
	    $classes = empty( $item->classes ) ? array() : (array) $item->classes;

	    

	    $class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );
	  	
	  	

	    $output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';
	  
	    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
	    $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
	    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
	    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
	    $attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';
	  	
	  	$args_before = (isset($args->before))? $args->before: '';
	  	$args_link_before = (isset($args->link_before))? $args->link_before: '';
	  	$args_link_after = (isset($args->link_after))? $args->link_after: '';
	  	$args_after = (isset($args->after))? $args->after: '';

	    $item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
	        $args_link_before,
	        $attributes,
	        $args_link_before,
	        apply_filters( 'the_title', $item->title, $item->ID ),
	        $args_link_after,
	        $args_after
	    );
	  
	    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

function pointfinder_navigation_menu(){
	$defaults = array(
	    'theme_location'  => 'pointfinder-main-menu',
	    'menu'            => '',
	    'container'       => '',
	    'container_class' => '',
	    'container_id'    => '',
	    'menu_class'      => '',
	    'menu_id'         => '',
	    'echo'            => true,
	    'fallback_cb'     => 'wp_page_menu',
	    'before'          => '',
	    'after'           => '',
	    'link_before'     => '',
	    'link_after'      => '',
	    'items_wrap'      => '%3$s',
	    'depth'           => 0,
	    'walker'          => new pointfinder_walker_nav_menu()
	);
	if (has_nav_menu( 'pointfinder-main-menu' )) {
		wp_nav_menu( $defaults ); 
	}
}


function pointfinder_footer_navigation_menu(){
	$defaults = array(
	    'theme_location'  => 'pointfinder-footer-menu',
	    'menu'            => '',
	    'container'       => 'div',
	    'container_class' => 'pf-footer-menu',
	    'container_id'    => '',
	    'menu_class'      => '',
	    'menu_id'         => '',
	    'echo'            => true,
	    'fallback_cb'     => 'wp_page_menu',
	    'before'          => '',
	    'after'           => '',
	    'link_before'     => '',
	    'link_after'      => '',
	    'items_wrap'      => '%3$s',
	    'depth'           => 0,
	    'walker'          => ''
	);
	if (has_nav_menu( 'pointfinder-footer-menu' )) {
		wp_nav_menu( $defaults ); 
	}
}




function pointfinder_widgets_init() {
	global $pointfindertheme_option;
	// If Dynamic Sidebar Exists
	if (function_exists('register_sidebar'))
	{
		
	    register_sidebar(array(
	        'name' => esc_html__('PF Default Widget Area', 'pointfindert2d'),
	        'description' => esc_html__('PF  Default Widget Area', 'pointfindert2d'),
	        'id' => 'pointfinder-widget-area',
	        'before_widget' => '<div id="%1$s" class="%2$s">',
	        'after_widget' => '</div></div>',
	        'before_title' => '',
	        'after_title' => ''
	    ));

	    register_sidebar(array(
	        'name' => esc_html__('PF Item Page Widget', 'pointfindert2d'),
	        'description' => esc_html__('Widget area for item detail page.', 'pointfindert2d'),
	        'id' => 'pointfinder-itempage-area',
	        'before_widget' => '<div id="%1$s" class="%2$s">',
	        'after_widget' => '</div></div>',
	        'before_title' => '',
	        'after_title' => ''
	    ));

	    register_sidebar(array(
	        'name' => esc_html__('PF Author Page Widget', 'pointfindert2d'),
	        'description' => esc_html__('Widget area for author detail page.', 'pointfindert2d'),
	        'id' => 'pointfinder-authorpage-area',
	        'before_widget' => '<div id="%1$s" class="%2$s">',
	        'after_widget' => '</div></div>',
	        'before_title' => '',
	        'after_title' => ''
	    ));

	    if (function_exists('is_bbpress')) {
	    	register_sidebar(array(
		        'name' => esc_html__('PF bbPress Sidebar', 'pointfindert2d'),
		        'description' => esc_html__('Widget area for inner bbPress pages.', 'pointfindert2d'),
		        'id' => 'pointfinder-bbpress-area',
		        'before_widget' => '<div id="%1$s" class="%2$s">',
		        'after_widget' => '</div></div>',
		        'before_title' => '',
		        'after_title' => ''
		    ));
	    }

	    if (function_exists('is_woocommerce')) {
	    	register_sidebar(array(
		        'name' => esc_html__('PF WooCommerce Sidebar', 'pointfindert2d'),
		        'description' => esc_html__('Widget area for inner WooCommerce pages.', 'pointfindert2d'),
		        'id' => 'pointfinder-woocom-area',
		        'before_widget' => '<div id="%1$s" class="%2$s">',
		        'after_widget' => '</div></div>',
		        'before_title' => '',
		        'after_title' => ''
		    ));
	    }
	    
	    if (function_exists('dsidxpress_InitWidgets')) {
	    	register_sidebar(array(
		        'name' => esc_html__('PF dsIdxpress Sidebar', 'pointfindert2d'),
		        'description' => esc_html__('Widget area for inner dsIdxpress pages.', 'pointfindert2d'),
		        'id' => 'pointfinder-dsidxpress-area',
		        'before_widget' => '<div id="%1$s" class="%2$s">',
		        'after_widget' => '</div></div>',
		        'before_title' => '',
		        'after_title' => ''
		    ));
	    }
	    register_sidebar(array(
	        'name' => esc_html__('PF Category Sidebar', 'pointfindert2d'),
	        'description' => esc_html__('Widget area for Item Category Page.', 'pointfindert2d'),
	        'id' => 'pointfinder-itemcatpage-area',
	        'before_widget' => '<div id="%1$s" class="%2$s">',
	        'after_widget' => '</div></div>',
	        'before_title' => '',
	        'after_title' => ''
	    ));

	    register_sidebar(array(
	        'name' => esc_html__('PF Search Results Sidebar', 'pointfindert2d'),
	        'description' => esc_html__('Widget area for Item Search Results Page.', 'pointfindert2d'),
	        'id' => 'pointfinder-itemsearchres-area',
	        'before_widget' => '<div id="%1$s" class="%2$s">',
	        'after_widget' => '</div></div>',
	        'before_title' => '',
	        'after_title' => ''
	    ));


	    register_sidebar(array(
	        'name' => esc_html__('PF Blog Sidebar', 'pointfindert2d'),
	        'description' => esc_html__('Widget area for single blog page.', 'pointfindert2d'),
	        'id' => 'pointfinder-blogpages-area',
	        'before_widget' => '<div id="%1$s" class="%2$s">',
	        'after_widget' => '</div></div>',
	        'before_title' => '',
			'after_title' => ''
	    ));

	    
	}

	/*------------------------------------
		Unlimited Sidebar
	------------------------------------*/
	global $pfsidebargenerator_options;
	$setup25_sidebargenerator_sidebars = (isset($pfsidebargenerator_options['setup25_sidebargenerator_sidebars']))?$pfsidebargenerator_options['setup25_sidebargenerator_sidebars']:'';

	if(PFControlEmptyArr($setup25_sidebargenerator_sidebars)){
		if(count($setup25_sidebargenerator_sidebars) > 0){
			foreach($setup25_sidebargenerator_sidebars as $itemvalue){
				if (function_exists('register_sidebar') && !empty($itemvalue['title']))
				{
					// Define Sidebar Widget Area 2
					register_sidebar(array(
						'name' => $itemvalue['title'],
						'id' => $itemvalue['url'],
						'before_widget' => '<div id="%1$s" class="%2$s">',
				        'after_widget' => '</div></div>',
				        'before_title' => '',
				        'after_title' => ''
					));
				
				}
			}
		}
	}
}

function pfedit_my_widget_title($title = '', $instance = array(), $id_base = '') {

	if (!empty($id_base)) {
		if (empty($instance['title'])) {
			if ($id_base != 'search') {
				return '<div class="pfwidgettitle"><div class="widgetheader">'.$title.'</div></div><div class="pfwidgetinner">';
			} else {
				return '<div class="pfwidgettitle pfemptytitle"><div class="widgetheader"></div></div><div class="pfwidgetinner pfemptytitle">';
			}
			
		}else{
			return '<div class="pfwidgettitle"><div class="widgetheader">'.$title.'</div></div><div class="pfwidgetinner">';
		}
	}else{
		if (!empty($title)) {
			return '<div class="pfwidgettitle"><div class="widgetheader">'.$title.'</div></div><div class="pfwidgetinner">';
		}else{
			return '<div class="pfwidgettitle pfemptytitle"><div class="widgetheader"></div></div><div class="pfwidgetinner pfemptytitle">';
		}
		
	}
}
 
add_filter ( 'widget_title' , 'pfedit_my_widget_title', 10, 3);


/*------------------------------------*\
  FEATURED MARKER FIX HOOK
\*------------------------------------*/
function PF_SAVE_FEATURED_MARKER_DATA( $post_id ) {

    $setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');

    if ( $setup3_pointposttype_pt1 == get_post_type($post_id)) {
	   
	    $featured_marker = (isset($_REQUEST['webbupointfinder_item_featuredmarker'])) ? $_REQUEST['webbupointfinder_item_featuredmarker'] : '0' ;
	    
		if (isset($_POST['pfget_uploaditem'])) {
	    	if(isset($_POST['featureditembox'])){
	    		if ($_POST['featureditembox'] == "on") {
					update_post_meta($post_id, 'webbupointfinder_item_featuredmarker', 1);						
	    		}else{
					update_post_meta($post_id, 'webbupointfinder_item_featuredmarker', 0);	
	    		}
	    	}else{
				update_post_meta ($post_id, 'webbupointfinder_item_featuredmarker', 0);
	    	}
	    }else{
	    	if (isset($_POST['pointfinderthemefmb_options']['webbupointfinder_item_featuredmarker'])) {
	    		if ($_POST['pointfinderthemefmb_options']['webbupointfinder_item_featuredmarker'] != 1) {
					update_post_meta($post_id, 'webbupointfinder_item_featuredmarker', 0);	
		    	}
	    	}else{
				update_post_meta ($post_id, 'webbupointfinder_item_featuredmarker', 0);
	    	}
	    	
	    }

    }

}
add_action( 'wp_insert_post', 'PF_SAVE_FEATURED_MARKER_DATA',0);





/*------------------------------------*\
  CONTACT FORM 7
\*------------------------------------*/	
if ( is_plugin_active('contact-form-7/wp-contact-form-7.php') ) {
	add_filter( 'wpcf7_form_class_attr', 'pointfinder_form_class_attr' );

	function pointfinder_form_class_attr( $class ) {
		$class .= ' golden-forms';
		return $class;
	}

	add_filter( 'wpcf7_form_elements', 'pointfinder_wpcf7_form_elements' );
	function pointfinder_wpcf7_form_elements( $content ) {
		// global $wpcf7_contact_form;
		
		$rl_pfind = '/<p>/';
		$rl_preplace = '<p class="wpcf7-form-text">';
		$content = preg_replace( $rl_pfind, $rl_preplace, $content, 20 );
	 	
		return $content;	
	}
}



/*------------------------------------*\
  ITEM PAGE COMMENTS
\*------------------------------------*/
$setup3_modulessetup_allow_comments = PFSAIssetControl('setup3_modulessetup_allow_comments','','0');
if($setup3_modulessetup_allow_comments == 1){
	$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
	add_post_type_support( $setup3_pointposttype_pt1, 'comments' );
	add_post_type_support( $setup3_pointposttype_pt1, 'author' );

	function pf_default_comments_on( $data ) {
		$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
	    if( $data['post_type'] == $setup3_pointposttype_pt1 ) {
	        $data['comment_status'] = "open";     
	    }

	    return $data;
	}
	add_filter( 'wp_insert_post_data', 'pf_default_comments_on' );
}


/*------------------------------------*\
	HIDE ADMIN BAR
\*------------------------------------*/
$setup4_membersettings_hideadminbar = PFSAIssetControl('setup4_membersettings_hideadminbar','','1');
$general_hideadminbar = PFSAIssetControl('general_hideadminbar','','0');

if (  current_user_can( 'manage_options' ) && $general_hideadminbar == 0) {//This is for admin
    show_admin_bar( false );
}

if (  !current_user_can( 'manage_options' ) && $setup4_membersettings_hideadminbar == 0) {//This is for users
    show_admin_bar( false );
}



/*------------------------------------*\
	Google Analytic
\*------------------------------------*/

// Add Analytic code
function add_analytic_code () {
	global $pointfindertheme_option;
	$googleanalytics_code = isset($pointfindertheme_option['googleanalytics_code'])? $pointfindertheme_option['googleanalytics_code']:'';
	if( $googleanalytics_code != "" ){
		echo '<script>';
		echo $googleanalytics_code;
		echo '</script>';
	}
}
add_action('wp_footer', 'add_analytic_code',80);

?>