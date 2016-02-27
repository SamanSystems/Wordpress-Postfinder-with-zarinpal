<?php 
get_header();

	$post_type = get_post_type();
	$post_id = get_the_id();

	$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
	$setup3_pointposttype_pt8 = PFSAIssetControl('setup3_pointposttype_pt8','','agents');

    switch ($post_type) {
		case $setup3_pointposttype_pt1: /*Items*/
			if ( have_posts() ){
			the_post();

				$item_term = pf_get_item_term_id($post_id);

				get_template_part('admin/estatemanagement/includes/pages/itemdetail/itempage','content');
					
				$setup4_membersettings_dashboard = PFSAIssetControl('setup4_membersettings_dashboard','','');
				$setup3_modulessetup_headersection = PFSAIssetControl('setup3_modulessetup_headersection','',1);

				if (!empty($item_term)) {
					if (PFADVIssetControl('setupadvancedconfig_'.$item_term.'_advanced_status','','0') == 1) {
						$setup3_modulessetup_headersection = PFADVIssetControl('setupadvancedconfig_'.$item_term.'_headersection','','2');
					}
				}

				if ($setup3_modulessetup_headersection == 1) {
					echo '<section role="itempagemapheader" class="pf-itempage-mapheader">';
					echo '<div class="pfheaderbarshadow2"></div>';
					echo '<div id="item-map-page"></div>';
					echo '</section>';
					$setup3_modulessetup_breadcrumbs = PFSAIssetControl('setup3_modulessetup_breadcrumbs','','1');
					if ($setup3_modulessetup_breadcrumbs == 1) {
						echo '<div class="pf-fullwidth pf-itempage-br-xm"><div class="pf-container"><div class="pf-row"><div class="col-lg-12">';
						$br_output = pf_the_breadcrumb();
						echo '<div class="pf-breadcrumbs pf-breadcrumbs-special">'.$br_output.'</div></div></div></div></div>';
	                }
					
				} elseif ($setup3_modulessetup_headersection == 0) {
					if(function_exists('PFGetDefaultPageHeader')){
						PFGetDefaultPageHeader(array('itemname' => get_the_title(), 'itemaddress' => esc_html(get_post_meta( get_the_id(), 'webbupointfinder_items_address', true ))));
					}
				}else{
					$setup3_modulessetup_breadcrumbs = PFSAIssetControl('setup3_modulessetup_breadcrumbs','','1');
					if ($setup3_modulessetup_breadcrumbs == 1) {
						echo '<div class="pf-fullwidth pf-itempage-br-xm pf-itempage-br-xm-nh"><div class="pf-container"><div class="pf-row"><div class="col-lg-12">';
						$br_output = pf_the_breadcrumb();
						echo '<div class="pf-breadcrumbs pf-breadcrumbs-special">'.$br_output.'</div></div></div></div></div>';
	                }
				}

				get_template_part('admin/estatemanagement/includes/pages/itemdetail/theme-map-scripts','itemdetail');

				$setup42_itempagedetails_sidebarpos = PFSAIssetControl('setup42_itempagedetails_sidebarpos','','2');


				
				
				if (!empty($item_term)) {
					$pointfinder_customsidebar = PFADVIssetControl('setupadvancedconfig_'.$item_term.'_sidebar','','');
				}else{
					$pointfinder_customsidebar = '';
				}
				

				echo '<section role="main" class="pf-itempage-maindiv" itemscope itemtype="http://schema.org/Product">';
					echo '<div class="pf-container clearfix">';
					echo '<div class="pf-row clearfix">';
					if ($setup42_itempagedetails_sidebarpos == 2) {
						if(function_exists('PFGetItemPageCol1')){PFGetItemPageCol1();}
	              		if(function_exists('PFGetItemPageCol2')){PFGetItemPageCol2($pointfinder_customsidebar);}
					} elseif ($setup42_itempagedetails_sidebarpos == 1) {
						if(function_exists('PFGetItemPageCol2')){PFGetItemPageCol2($pointfinder_customsidebar);}
	              		if(function_exists('PFGetItemPageCol1')){PFGetItemPageCol1();}
					}else{
						if(function_exists('PFGetItemPageCol1')){PFGetItemPageCol1();}
					}
                	echo '</div>';
                	echo '</div>';
                echo '</section>';
            };
			break;

		case $setup3_pointposttype_pt8: /*Agents*/
			if ( have_posts() ){
				the_post();
				get_template_part('admin/estatemanagement/includes/functions/agentpage','functions');
				if(function_exists('PFGetDefaultPageHeader')){
					PFGetDefaultPageHeader(array('agent_id' => $post_id));
				}

				$setup42_itempagedetails_sidebarpos_auth = PFSAIssetControl('setup42_itempagedetails_sidebarpos_auth','','2');
				echo '<section role="main" class="pf-itempage-maindiv">';
					echo '<div class="pf-container clearfix">';
					echo '<div class="pf-row clearfix">';
		    		if ($setup42_itempagedetails_sidebarpos_auth == 2) {
						if(function_exists('PFGetAgentPageCol1')){PFGetAgentPageCol1($post_id);}
		          		if(function_exists('PFGetAgentPageCol2')){PFGetAgentPageCol2();}
					} elseif ($setup42_itempagedetails_sidebarpos_auth == 1) {
						if(function_exists('PFGetAgentPageCol2')){PFGetAgentPageCol2();}
		          		if(function_exists('PFGetAgentPageCol1')){PFGetAgentPageCol1($post_id);}
					}else{
						if(function_exists('PFGetAgentPageCol1')){PFGetAgentPageCol1($post_id);}
					}
		    		echo '</div>';
		        	echo '</div>';
		        echo '</section>';
			};	                
			break;

		case 'post':/*Blog Posts*/
			if(function_exists('PFGetHeaderBar')){
				PFGetDefaultPageHeader();
			}
	        $setup_item_blogpage_sidebarpos = PFSAIssetControl('setup_item_blogpage_sidebarpos','','2');
	        get_template_part( 'admin/core/post', 'functions' );
	        if ( have_posts() ){
	        	the_post();
				echo '<section role="main">';
			        echo '<div class="pf-blogpage-spacing pfb-top"></div>';
			        echo '<div class="pf-container"><div class="pf-row">';
			        	if ($setup_item_blogpage_sidebarpos == 3) {
			        		echo '<div class="col-lg-12">';

								get_template_part('sloop');

							echo '</div>';
			        	}else{
			        	
				            if($setup_item_blogpage_sidebarpos == 1){
				                echo '<div class="col-lg-3 col-md-4">';
				                    if (is_active_sidebar( 'pointfinder-blogpages-area' )) {

				                    	get_sidebar('singleblog' );
				                    } else {
				                    	get_sidebar();
				                    }
				                    
				                echo '</div>';
				            }
				              
				            echo '<div class="col-lg-9 col-md-8">'; 
				            
				            get_template_part('sloop');

				            echo '</div>';
				            if($setup_item_blogpage_sidebarpos == 2){
				                echo '<div class="col-lg-3 col-md-4">';
				                    if (is_active_sidebar( 'pointfinder-blogpages-area' )) {
				                    	get_sidebar('singleblog' );
				                    } else {
				                    	get_sidebar();
				                    }
				                echo '</div>';
				            }

			            }
			        echo '</div></div>';
			        echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';
			    echo '</section>';
			};
			break;
	}
    

get_footer();
?>