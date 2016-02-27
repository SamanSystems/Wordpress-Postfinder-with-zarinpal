<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @package	   TGM-Plugin-Activation
 * @subpackage Example
 * @version	   2.4.2
 * @author	   Thomas Griffin <thomas@thomasgriffinmedia.com>
 * @author	   Gary Jones <gamajo@gamajo.com>
 * @copyright  Copyright (c) 2012, Thomas Griffin
 * @license	   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/thomasgriffin/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'pointfinderh_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function pointfinderh_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		
        array(
            'name'      => 'Redux Framework',
            'slug'      => 'redux-framework',
            'required'  => true,
            'force_activation'      => true, 
            'force_deactivation'    => true, 
        ),

		array(
            'name'			=> 'WPBakery Visual Composer',
            'slug'			=> 'js_composer', 
            'source'			=> get_stylesheet_directory() . '/admin/plugins/js_composer.zip', 
            'required'			=> true, 
            'version'			=> '4.5.1', 
            'force_activation'		=> true, 
            'force_deactivation'	=> true, 
            'external_url'		=> '', 
        ),

        
		array(
            'name'			=> 'Templatera',
            'slug'			=> 'templatera', 
            'source'			=> get_stylesheet_directory() . '/admin/plugins/templatera.zip', 
            'required'			=> true, 
            'version'			=> '1.1.2', 
            'force_activation'		=> true, 
            'force_deactivation'	=> true, 
            'external_url'		=> '', 
        ),


        array(
            'name'          => 'Ultimate Addons for Visual Composer',
            'slug'          => 'Ultimate_VC_Addons', 
            'source'            => get_stylesheet_directory() . '/admin/plugins/Ultimate_VC_Addons.zip', 
            'required'          => true, 
            'version'           => '3.10', 
            'force_activation'      => true, 
            'force_deactivation'    => true, 
            'external_url'      => '', 
        ),

	

        array(
            'name'                  => 'Revolution Slider',
            'slug'                  => 'revslider', 
            'source'                => get_stylesheet_directory() . '/admin/plugins/revslider.zip', 
            'required'              => true, 
            'version'               => '4.6.93', 
            'force_activation'      => false, 
            'force_deactivation'    => false, 
            'external_url'          => '', 
        ),

		
	);

	
	
	 $config = array(
        'default_path' => '',                      
        'menu'         => 'tgmpa-install-plugins', 
        'has_notices'  => true,                    
        'dismissable'  => true,                    
        'dismiss_msg'  => '',                      
        'is_automatic' => false,                   
        'message'      => '',                      
        'strings'      => array(
            'page_title'                      => esc_html__( 'Install Required Plugins', 'pointfindert2d' ),
            'menu_title'                      => esc_html__( 'Install Plugins', 'pointfindert2d' ),
            'installing'                      => esc_html__( 'Installing Plugin: %s', 'pointfindert2d' ), 
            'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'pointfindert2d' ),
            'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ),
            'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), 
            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ),
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ),
            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
            'return'                          => esc_html__( 'Return to Required Plugins Installer', 'pointfindert2d' ),
            'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'pointfindert2d' ),
            'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'pointfindert2d' ),
            'nag_type'                        => 'updated'
        )
    );

    tgmpa( $plugins, $config );

}
