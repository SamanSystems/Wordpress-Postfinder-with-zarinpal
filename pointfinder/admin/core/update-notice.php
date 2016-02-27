<?php
/**********************************************************************************************************************************
*
* Update notice
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/
add_action('admin_notices', 'pointfinder_afterinstall_admin_notice');

function pointfinder_afterinstall_admin_notice() {
	global $current_user ;
    $user_id = $current_user->ID;

	if ( ! get_user_meta($user_id, 'pointfinder_afterinstall_admin_notice') ) {
        echo '<div class="updated"><p>'; 
        echo '<h3>Point Finder Help Doc Information</h3>';

        echo '<ul>';
            echo '<li><strong>Register Support Forum : </strong>';
            echo '<a href="http://support.webbudesign.com/wp-login.php?action=register" target="_blank">Click here for create account.</a>';
            echo '</li>';

        	echo '<li><strong>Tutorial Videos : </strong>';
			echo '<a href="http://support.webbudesign.com/forums/forum/point-finder-versatile-directory-theme/tutorial-videos/" target="_blank">View</a>';
            echo '</li>';

            echo '<li><strong>Knowledgebase : </strong>';
            echo '<a href="http://support.webbudesign.com/forums/forum/point-finder-versatile-directory-theme/knowledgebase/" target="_blank">View</a>';
            echo '</li>';

            echo '<li><strong>Help Docs : </strong>';
            echo '<a href="http://help.pointfindertheme.com" target="_blank">View</a>';
            echo '</li>';

            echo '<li><strong>Ideal Hosting Settings : </strong>';
            echo '<a href="http://support.webbudesign.com/forums/topic/hosting-settings/" target="_blank">View</a>';
            echo '</li>';

            echo '<li><strong>Common Installation Errors & Solutions : </strong>';
            echo '<a href="http://support.webbudesign.com/forums/topic/read-me-installation-errors-solutions/" target="_blank">View</a>';
            echo '</li>';

            echo '<li><strong>Support Forum : </strong>';
            echo '<a href="http://support.webbudesign.com" target="_blank">http://support.webbudesign.com</a></li>';

            echo '<li><strong>Changelog : </strong>';
            printf('<a href="%1$s" target="_blank">View</a>','http://support.webbudesign.com/forums/topic/changelog/');
            echo '</li>';

        echo '</ul>';

        printf(__('<a href="%1$s">Dismiss</a>'), '?pointfinderafterinstall_nag_ignore=0');
        echo "</p></div>";
	}
}

add_action('admin_init', 'pointfinderafterinstall_nag_ignore');

function pointfinderafterinstall_nag_ignore() {
	    global $current_user;
        $user_id = $current_user->ID;
        if ( isset($_GET['pointfinderafterinstall_nag_ignore']) && '0' == $_GET['pointfinderafterinstall_nag_ignore'] ) {
             update_user_meta($user_id, 'pointfinder_afterinstall_admin_notice', 'true', true);
	    }
}

add_action('admin_init', 'pointfinderafterinstall_nag_enable');

function pointfinderafterinstall_nag_enable() {
        global $current_user;
        $user_id = $current_user->ID;
        if ( isset($_GET['pointfinderafterinstall_nag_enable']) && '0' == $_GET['pointfinderafterinstall_nag_enable'] ) {
             delete_user_meta($user_id, 'pointfinder_afterinstall_admin_notice');
        }
}

