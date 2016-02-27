<?php 
get_header();
	global $wp_query;
	$pf_category = 0;

	if(isset($wp_query->query_vars['taxonomy'])){
		$taxonomy_name = $wp_query->query_vars['taxonomy'];
		if (in_array($taxonomy_name, array('pointfinderltypes','pointfinderitypes','pointfinderlocations','pointfinderfeatures'))) {
			
			$term_slug = $wp_query->query_vars['term'];
			$pf_category = 1;
			$term_name = get_term_by('slug', $term_slug, $taxonomy_name,'ARRAY_A');
			
			$get_termname = $term_name['name'];
			$get_term_nameforlink = '<a href="'.get_term_link( $term_name['term_id'], $taxonomy_name ).'" title="' . esc_attr( sprintf( esc_html__( "View all posts in %s","pointfindert2d" ), $term_name['name']) ) . '">'.$term_name['name'].'</a>';

			if (!empty($term_name['parent'])) {
				$term_parent_name = get_term_by('id', $term_name['parent'], $taxonomy_name,'ARRAY_A');
				$get_termname = $term_parent_name['name'].' / '.$term_name['name'];
				$get_term_nameforlink = '<a href="'.get_term_link( $term_name['parent'], $taxonomy_name ).'" title="' . esc_attr( sprintf( esc_html__( "View all posts in %s","pointfindert2d" ), $term_parent_name['name']) ) . '">'.$term_parent_name['name'].'</a> / '.'<a href="'.get_term_link( $term_name['term_id'], $taxonomy_name ).'" title="' . esc_attr( sprintf( esc_html__( "View all posts in %s","pointfindert2d" ), $term_name['name']) ) . '">'.$term_name['name'].'</a>';
			}

			$filter_text = '';

			switch ($taxonomy_name) {
				case 'pointfinderltypes':
					$filter_text .= 'listingtype = "'.$term_name['term_id'].'"';
					break;
				
				case 'pointfinderitypes':
					$filter_text .= 'itemtype = "'.$term_name['term_id'].'"';
					break;

				case 'pointfinderlocations':
					$filter_text .= 'locationtype = "'.$term_name['term_id'].'"';
					break;
			}

		}
	}
	
	
	
	
	
	if ($pf_category == 0) {
		if(function_exists('PFGetDefaultPageHeader')){PFGetDefaultPageHeader();}
		echo '<div class="pf-blogpage-spacing pfb-top"></div>';
		echo '<section role="main">';
			echo '<div class="pf-container">';
				echo '<div class="pf-row">';
					echo '<div class="col-lg-12">';

						get_template_part('loop');

					echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</section>';
		echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';

	}else{

        $setup_item_catpage_sidebarpos = PFSAIssetControl('setup_item_catpage_sidebarpos','','2');
        
		if(function_exists('PFGetDefaultPageHeader')){PFGetDefaultPageHeader(array('taxname' => $get_termname,'taxnamebr' => $get_term_nameforlink,'taxinfo'=>$term_name['description']));}
		$setup42_authorpagedetails_grid_layout_mode = PFSAIssetControl('setup22_searchresults_grid_layout_mode','','1');
		$setup42_authorpagedetails_defaultppptype = PFSAIssetControl('setup22_searchresults_defaultppptype','','10');

		$setup22_searchresults_defaultsortbytype = PFSAIssetControl('setup22_searchresults_defaultsortbytype','','Date');
		$setup22_searchresults_defaultsorttype = PFSAIssetControl('setup22_searchresults_defaultsorttype','','DESC');
		
		$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
		$setup42_authorpagedetails_grid_layout_mode = ($setup42_authorpagedetails_grid_layout_mode == 1) ? 'fitRows' : 'masonry' ;
		$setup22_searchresults_background2 = PFSAIssetControl('setup22_searchresults_background2','','#ffffff');
		$setup22_searchresults_status_catfilters = PFSAIssetControl('setup22_searchresults_status_catfilters','','1');
		
		if ($setup22_searchresults_status_catfilters == 1) {
			$filters_text = 'true';
		}else{
			$filters_text = 'false';
		}
		
		echo '<section role="main">';
	        echo '<div class="pf-page-spacing"></div>';
	        echo '<div class="pf-container"><div class="pf-row clearfix">';
	        	if ($setup_item_catpage_sidebarpos == 3) {
	        		echo '<div class="col-lg-12"><div class="pf-page-container">';
						echo do_shortcode('[pf_itemgrid2  orderby="'.$setup22_searchresults_defaultsortbytype.'" sortby="'.$setup22_searchresults_defaultsorttype.'" items="'.$setup42_authorpagedetails_defaultppptype.'" cols="3" grid_layout_mode="'.$setup42_authorpagedetails_grid_layout_mode.'" filters="'.$filters_text.'" itemboxbg="'.$setup22_searchresults_background2.'" '.$filter_text.']' );
					echo '</div></div>';
	        	}else{
	        		if($setup_item_catpage_sidebarpos == 1){
		                echo '<div class="col-lg-3 col-md-4">';
		                    get_sidebar('itemcats' ); 
		                echo '</div>';
		            }
		              
		            echo '<div class="col-lg-9 col-md-8"><div class="pf-page-container">'; 
		            
		            echo do_shortcode('[pf_itemgrid2 orderby="'.$setup22_searchresults_defaultsortbytype.'" sortby="'.$setup22_searchresults_defaultsorttype.'" items="'.$setup42_authorpagedetails_defaultppptype.'" cols="3" grid_layout_mode="'.$setup42_authorpagedetails_grid_layout_mode.'" filters="'.$filters_text.'" itemboxbg="'.$setup22_searchresults_background2.'" '.$filter_text.']' );

		            echo '</div></div>';
		            if($setup_item_catpage_sidebarpos == 2){
		                echo '<div class="col-lg-3 col-md-4">';
		                    get_sidebar('itemcats' );
		                echo '</div>';
		            }
	        	}
	            
	        echo '</div></div>';
	        echo '<div class="pf-page-spacing"></div>';
	    echo '</section>';

	}


get_footer();
?>