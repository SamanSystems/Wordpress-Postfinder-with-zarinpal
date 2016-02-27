<?php
/**********************************************************************************************************************************
*
* PointFinder v1.0.0
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/


load_theme_textdomain( 'pointfindert2d',get_template_directory() . '/languages');

define('ULTIMATE_NO_EDIT_PAGE_NOTICE', true);
define('ULTIMATE_NO_PLUGIN_PAGE_NOTICE', true);
update_option('ultimate_theme_support','enable');

/*------------------------------------
	Theme Support
------------------------------------*/
if ( ! function_exists( 'pointfinder_setup' ) ){
	function pointfinder_setup() {
		if (!isset($content_width)){$content_width = 1170;}
		
		
		add_theme_support( 'automatic-feed-links' );
		add_theme_support('menus');
		add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );
	    add_theme_support('post-thumbnails');

	    add_image_size('large', 700, '', true); 
	    add_image_size('medium', 250, '', true);
	    add_image_size('small', 120, '', true);
	    add_image_size('ItemSize2x', 880, 660, true);
	    add_image_size('ItemSizex', 440, 330, true);

	    add_theme_support( 'bbpress' );

		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list',
		) );

		register_nav_menus(array( 
			'pointfinder-main-menu' => esc_html__('Point Finder Main Menu', 'pointfindert2d'),
			'pointfinder-footer-menu' => esc_html__('Point Finder Footer Menu', 'pointfindert2d')
	    ));


	}
};
add_action('after_setup_theme', 'pointfinder_setup');


/*------------------------------------
  Options Panel & Common functions
------------------------------------*/ 
require_once( get_template_directory(). '/admin/core/admin-welcomepf.php' );
require_once( get_template_directory(). '/admin/core/update-notice.php' );


//Quick Install
require_once( get_template_directory(). '/admin/quick_setup/init.php' );

require_once( get_template_directory(). '/admin/estatemanagement/includes/functions/common-functions.php' );



include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( is_plugin_active('redux-framework/redux-framework.php') ) {
	
	function pointfinder_removeDemoModeLink() { 
	    if ( class_exists('ReduxFrameworkPlugin') ) {
	        remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2 );
	    }
	    if ( class_exists('ReduxFrameworkPlugin') ) {
	        remove_action('admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );    
	    }
	}
	add_action('init', 'pointfinder_removeDemoModeLink');

	

	require_once( get_template_directory(). '/admin/options/PointfinderOptions.config.php' ); 
	require_once( get_template_directory(). '/admin/options/Metabox.config.php' );
	require_once( get_template_directory(). '/admin/options/PointfinderOptionsFMB.config.php' );
	require_once( get_template_directory(). '/admin/options/PFPBControl.config.php' );
	require_once( get_template_directory(). '/admin/options/CustomFields.config.php' );
	require_once( get_template_directory(). '/admin/options/SearchFields.config.php' );
	function PF_CustomPointsFilter(){
		require_once( get_template_directory(). '/admin/options/CustomPoints.config.php' );
		require_once( get_template_directory(). '/admin/options/PFAdvancedControl.config.php' );
	}
	add_action('init', 'PF_CustomPointsFilter',10);
	
	require_once( get_template_directory(). '/admin/options/MailSystem.config.php' );
	require_once( get_template_directory(). '/admin/options/SidebarGenerator.config.php' );
	require_once( get_template_directory(). '/admin/options/TwitterWidget.config.php' );
	require_once( get_template_directory(). '/admin/options/rCaptcha.config.php' );
	require_once( get_template_directory(). '/admin/options/ReviewSystem.config.php' );
	require_once( get_template_directory(). '/admin/options/PFSizeControl.config.php' );
	
	
	

	require_once( get_template_directory(). '/admin/core/pf-wpml.php' );
	/*WPML Custom Strings*/
	if(function_exists('icl_object_id')) {
		define('ICL_DONT_LOAD_NAVIGATION_CSS', true);
		define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
		define('ICL_DONT_LOAD_LANGUAGES_JS', true);
		require_once( get_template_directory(). '/admin/core/pf-wpml.php' );
	}
}
/*------------------------------------
	External Modules/Files
------------------------------------*/
//Auto Update
$username = PFSAIssetControl('setup_autoupdate_username','','');
$apikey   = PFSAIssetControl('setup_autoupdate_apikey','','');

if (!empty($username) && !empty($apikey)) {
	load_template( trailingslashit( get_template_directory() ) . '/admin/core/updates/envato-wp-theme-updater.php' );
	Envato_WP_Theme_Updater::init( $username, $apikey, 'Webbu (@webbudesign)');
}

//Taxonomies & CPT Metaboxes
require_once( get_template_directory().'/admin/estatemanagement/taxonomymeta/taxonomy-meta.php');
require_once( get_template_directory().'/admin/estatemanagement/metabox/meta-box.php');
require_once( get_template_directory().'/admin/estatemanagement/pointfinder-metabox.php');
require_once( get_template_directory().'/admin/estatemanagement/pointfinder-metabox-ex.php');
require_once( get_template_directory().'/admin/estatemanagement/taxonomymeta/locations.php');

$setup3_pointposttype_pt6_check = PFSAIssetControl('setup3_pointposttype_pt6_check','','1');
if($setup3_pointposttype_pt6_check == 1){
	require_once( get_template_directory().'/admin/core/new-features-pt.php');
}


if (is_admin() && isset($_GET['activated'] )) {
	if ($_GET['activated'] == 'true' && $pagenow == "themes.php" ) {
    	header( 'Location: '.admin_url().'admin.php?page=pointfinder_demo_installer' ) ;
	}
}


//Estate management files
require_once( get_template_directory().'/admin/estatemanagement/post-types.php');
require_once( get_template_directory().'/admin/core/aq_resizer.php');
require_once( get_template_directory().'/admin/tgm/plugins.php');

if ( is_active_widget( false, '', 'pf_twitter_w', true ) ) {
	require_once( get_template_directory().'/admin/core/ajax-grabtweets.php');
}

//Scripts
require_once( get_template_directory().'/admin/core/scripts.php');


//Hooks
require_once( get_template_directory().'/admin/core/hooks.php');


//Filters
require_once( get_template_directory().'/admin/core/filters.php');
get_template_part('admin/estatemanagement/ptfilters/admin','filters');


//Dynamic Css Styles
require_once( get_template_directory().'/admin/core/dynamic_css.php');


//reCaptcha
if (PFRECIssetControl('setupreCaptcha_general_status','','0') == 1) {
	require_once( get_template_directory().'/admin/core/recaptchalib.php');
}


//Admin Dashboard Widget
require_once( get_template_directory().'/admin/core/admin-dash.php');

//VC includes
if(function_exists('vc_set_as_theme')){
	
	$setup3_pointposttype_pt6_status = PFSAIssetControl('setup3_pointposttype_pt6_status','','1');
	if ($setup3_pointposttype_pt6_status == 1) {
		require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf-list-agents.php');
	}
	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf_text_separator.php');
	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf_pfitem_carousel.php');
	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf-grid-shortcodes.php');
	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf-grid-shortcodes-static.php');
	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf-map-shortcodes.php');
	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf-cmap-shortcodes.php');
	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf-cform-shortcodes.php');
	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf-directorylist.php');
	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/pf-itemslider-shortcodes.php');
	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/vc_client_carousel.php');
	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/vc_testimonials.php');
	require_once( get_template_directory().'/admin/includes/vcextend/customshortcodes/vc_pfinfobox.php');
	require_once( get_template_directory().'/admin/includes/vcextend/pfvisualcomposeraddons.php');
	require_once( get_template_directory().'/admin/includes/vcextend/pfvisualcomposertemplates.php');
	require_once( get_template_directory().'/admin/includes/vcextend/vc_customfields.php');
	require_once( get_template_directory().'/admin/includes/vcextend/vc_extend.php');
}


//Field creation classes
require_once( get_template_directory().'/admin/includes/pfgetcustomfields.php');
require_once( get_template_directory().'/admin/includes/pfgetsearchfields.php');
require_once( get_template_directory().'/admin/includes/pfgetsubsearchfields.php');
require_once( get_template_directory().'/admin/includes/pfcustomwidgets.php');


//Ajax include files
require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-infowindow.php');
require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-taxpoint.php');
require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-poidata.php');
require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-listdata.php');
require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-modalsystem.php');
require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-modalsystemhandler.php');
require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-reportsystem.php');
require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-searchitems.php');
require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-claimsystem.php');
require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-autocomplete.php');
require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-ownerworks.php');
require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-imageupload.php');
require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-imagesystem.php');
require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-featuresystem.php');
require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-fieldsystem.php');

if (PFREVSIssetControl('setup11_reviewsystem_check','','0') == 1) {
	require_once( get_template_directory().'/admin/estatemanagement/review-metabox.php');
	require_once( get_template_directory().'/admin/estatemanagement/includes/functions/modified-review-functions.php');
	require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-revflagsystem.php');
}

require_once( get_template_directory().'/admin/estatemanagement/includes/functions/additional-cpt-statusses.php');
require_once( get_template_directory().'/admin/estatemanagement/includes/functions/mailsender-functions.php');

if(PFSAIssetControl('setup4_membersettings_loginregister','','1') == 1){
	session_start();
	require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-usersystem.php');
	require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-paymentsystem.php');
	require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-usersystemhandler.php');
	require_once( get_template_directory().'/admin/estatemanagement/includes/ajax/ajax-favoritesystem.php');
	require_once( get_template_directory().'/admin/estatemanagement/includes/user-profilemods.php');
	require_once( get_template_directory().'/admin/estatemanagement/includes/modules/social-logins.php');

	if(PFSAIssetControl('setup4_membersettings_frontend','','1') == 1){
		require_once( get_template_directory().'/admin/estatemanagement/includes/functions/common-user-functions.php' );
		require_once( get_template_directory().'/admin/estatemanagement/includes/modules/paypall-class.php');
		require_once( get_template_directory().'/admin/estatemanagement/includes/functions/modified-functions.php');
		require_once( get_template_directory().'/admin/estatemanagement/includes/functions/statuschange-functions.php');
		require_once( get_template_directory().'/admin/estatemanagement/includes/schedule-config.php');
		require_once( get_template_directory().'/admin/estatemanagement/payment-metabox.php');
	}    


}



// Add Actions
add_action('wp_enqueue_scripts', 'pf_styleandscripts'); 
add_action('get_header', 'enable_threaded_comments'); 
add_action('widgets_init', 'pointfinder_remove_recent_comments_style'); 
add_action('widgets_init', 'pointfinder_widgets_init',0 );
add_action('admin_enqueue_scripts','pf_admin_styleandscripts',10);


// Add Filters
add_filter('avatar_defaults', 'pointfindert2dgravatar'); 
add_filter('body_class', 'add_slug_to_body_class');
add_filter('widget_text', 'do_shortcode'); 
add_filter('widget_text', 'shortcode_unautop');
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args');
add_filter('the_category', 'remove_category_rel_from_category_list'); 
add_filter('the_excerpt', 'shortcode_unautop'); 
add_filter('the_excerpt', 'do_shortcode'); 
add_filter('style_loader_tag', 'pointfinderh_style_remove');
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); 
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10);
?>