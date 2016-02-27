<?php
/**********************************************************************************************************************************
*
* Admin Filters
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/

/**
*Start: Testimonials Filters
**/
    function pf_testi_remove_unused_links($actions, $page_object)
    {   
        global $post;
        $setup3_pointposttype_pt11 = PFSAIssetControl('setup3_pointposttype_pt11','','testimonials');
        if($page_object->post_type == $setup3_pointposttype_pt11){
            unset($actions['view']);
            
        }
        return $actions;
    }
    add_filter('page_row_actions', 'pf_testi_remove_unused_links', 10, 2);

    add_action('admin_head', 'pointfinder_extrafunction_02');
    function pointfinder_extrafunction_02(){
            $setup3_pointposttype_pt11 = PFSAIssetControl('setup3_pointposttype_pt11','','testimonials');
            $screen = get_current_screen();
            if($screen->post_type == $setup3_pointposttype_pt11){
                echo '<style type="text/css">#edit-slug-box{display: none;}</style>';
            }
    };
/**
*End: Testimonials Filters
**/


?>