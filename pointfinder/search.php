<?php 
get_header();
	
	
	if (isset($_GET['action']) && $_GET['action'] == 'pfs') {
		
		$nonce = $_GET['_wpnonce'];
		if ( ! wp_verify_nonce( $nonce, 'search-widget' ) ) {
			 die(PFPageNotFound());
		}
		/**
		*Start: Get search data & apply to query arguments.
		**/
			$pfgetdata = $_GET;
			$hidden_output = $search_output = '';
			$searchkeys = array('pfsearch-filter','pfsearch-filter-order','pfsearch-filter-number','pfsearch-filter-col');
			if(is_array($pfgetdata)){

				$pfformvars = array();
				
					foreach($pfgetdata as $key=>$value){
						
						//Get Values & clean
						if($value != ''){
							
							if(isset($pfformvars[$key])){
								$pfformvars[$key] = $pfformvars[$key]. ',' .$value;
							}else{
								$pfformvars[$key] = $value;
							}
							if(!in_array($key, $searchkeys)){
								$hidden_output .= '<input type="hidden" name="'.$key.'" value="'.$value.'"/>';
							}

						}
					
					}
					$hidden_output .= '<input type="hidden" name="s" value=""/>';

					/*Data clean*/
					$pfgetdata = PFCleanArrayAttr('PFCleanFilters',$pfgetdata);
					
					$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
					$args = array( 'post_type' => $setup3_pointposttype_pt1, 'post_status' => 'publish');
					

					if(isset($_GET['pfsearch-filter']) && $_GET['pfsearch-filter']!=''){$pfg_orderbyx = esc_attr($_GET['pfsearch-filter']);}else{$pfg_orderbyx = '';}
					if(isset($_POST['pfg_order']) && $_POST['pfg_order']!=''){$pfg_orderx = esc_attr($_POST['pfg_order']);}else{$pfg_orderx = '';}
					if(isset($_POST['pfg_number']) && $_POST['pfg_number']!=''){$pfg_numberx = esc_attr($_POST['pfg_number']);}else{$pfg_numberx = '';}

					$setup22_searchresults_defaultppptype = PFSAIssetControl('setup22_searchresults_defaultppptype','','10');
					$setup22_searchresults_defaultsortbytype = PFSAIssetControl('setup22_searchresults_defaultsortbytype','','ID');
					$setup22_searchresults_defaultsorttype = PFSAIssetControl('setup22_searchresults_defaultsorttype','','ASC');

					if($pfg_orderbyx == ''){
						$args['meta_key'] = 'webbupointfinder_item_featuredmarker';
						$args['orderby'] = array('meta_value_num' => 'DESC' , $setup22_searchresults_defaultsortbytype => $setup22_searchresults_defaultsorttype);
						$args['posts_per_page'] = $setup22_searchresults_defaultppptype;
					}else{
						$args['meta_key'] = 'webbupointfinder_item_featuredmarker';
						$args['orderby'] = array('meta_value_num' => 'DESC');
						$args['posts_per_page'] = $pfg_numberx;
					}


					foreach($pfformvars as $pfformvar => $pfvalue){
						
						if(!in_array($pfformvar, $searchkeys)){
							$thiskeyftype = '';
							$thiskeyftype = PFFindKeysInSearchFieldA_ld($pfformvar);
							
							//Get target field & condition
							$target = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_target','','');
							$target_condition = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_target_according','','');

							switch($thiskeyftype){
								case '1'://select
									//is_Multiple
									$multiple = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_multiple','','0');
									//if($multiple == 1){ $multiplevar = 'multiple';}else{$multiplevar = '';};
									
									//Find Select box type
									//Check element: is it a taxonomy?
									$rvalues_check = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_rvalues_check','','0');
									
									if($rvalues_check == 0){
										
										$pfvalue_arr = PFGetArrayValues_ld($pfvalue);
										
										$fieldtaxname = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_posttax','','');
										if(isset($args['tax_query']) == false || isset($args['tax_query']) == NULL){
											$args['tax_query'] = array();
										}
										if(count($args['tax_query']) > 0){
											$args['tax_query'][(count($args['tax_query'])-1)]=
											array(
													'taxonomy' => $fieldtaxname,
													'field' => 'id',
													'terms' => $pfvalue_arr,
													'operator' => 'IN'
											);
										}else{
											$args['tax_query']=
											array(
												'relation' => 'AND',
												array(
													'taxonomy' => $fieldtaxname,
													'field' => 'id',
													'terms' => $pfvalue_arr,
													'operator' => 'IN'
												)
											);
										}
						
									}else{
										
										$target_r = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_rvalues_target','','');
										if (empty($target_r)) {
											$target_r = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_rvalues_target_target','','');
										}
										$target_condition_r = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_rvalues_target_according','','');
										//custom field search value in target. (check multiple.)
										if(isset($args['meta_query']) == false || isset($args['meta_query']) == NULL){
											$args['meta_query'] = array();
										}
										
										if(is_numeric($pfvalue)){
											$pfcomptype = 'NUMERIC';
										}else{
											$pfcomptype = 'CHAR';
										}
										
										if(count($args['meta_query']) > 0){
											$args['meta_query'][(count($args['meta_query'])-1)] = 
												array(
												'key' => 'webbupointfinder_item_'.$target_r,
												'value' => $pfvalue,
												'compare' => $target_condition_r,
												'type' => $pfcomptype
												
											);
										}else{
											$args['meta_query'] = array(
												'relation' => 'AND',
												array(
												'key' => 'webbupointfinder_item_'.$target_r,
												'value' => $pfvalue,
												'compare' => $target_condition_r,
												'type' => $pfcomptype
												),
											);
										}
									}
									
									break;
									
								case '2'://slider
									//Find Slider Type from slug
									$slidertype = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_type','','');
									$pfcomptype = 'NUMERIC';
									
									if($slidertype == 'range'){ 
									$pfvalue = trim($pfvalue,"\0");
										$pfvalue_exp = explode(',',$pfvalue);
										if(isset($args['meta_query']) == false || isset($args['meta_query']) == NULL){
											$args['meta_query'] = array();
										}
																		
										if(count($args['meta_query']) > 0){
											$args['meta_query'][(count($args['meta_query'])-1)] = 
												array(
												'key' => 'webbupointfinder_item_'.$target,
												'value' => $pfvalue_exp[0],
												'compare' => '>=',
												'type' => $pfcomptype
												);
											$args['meta_query'][(count($args['meta_query']))] = 
												array(
												'key' => 'webbupointfinder_item_'.$target,
												'value' => $pfvalue_exp[1],
												'compare' => '<=',
												'type' => $pfcomptype
												);
											
										}else{
												$args['meta_query'] = array(
													'relation' => 'AND',
													array(
													'key' => 'webbupointfinder_item_'.$target,
													'value' => $pfvalue_exp[0],
													'compare' => '>=',
													'type' => $pfcomptype
												),
												array(
													'key' => 'webbupointfinder_item_'.$target,
													'value' => $pfvalue_exp[1],
													'compare' => '<=',
													'type' => $pfcomptype
												)
											);

										}
									}else{

										if(isset($args['meta_query']) == false || isset($args['meta_query']) == NULL){
											$args['meta_query'] = array();
										}	
										if(count($args['meta_query']) > 0){
											$args['meta_query'][(count($args['meta_query'])-1)] = 
											array(
												'key' => 'webbupointfinder_item_'.$target,
												'value' => $pfvalue,
												'compare' => $target_condition,
												'type' => $pfcomptype
												
											);
										}else{
											$args['meta_query'] = array(
												'relation' => 'AND',
												array(
												'key' => 'webbupointfinder_item_'.$target,
												'value' => $pfvalue,
												'compare' => $target_condition,
												'type' => $pfcomptype
												),
											);
										}
									}
									
									
									break;
									
								case '4'://text field

							  		$target = PFSFIssetControl('setupsearchfields_'.$pfformvar.'_target_target','','');
									
									switch ($target) {
										case 'title':
												$args['search_prod_title'] = $pfvalue;
												function title_filter( $where, &$wp_query )
												{
													global $wpdb;
													if ( $search_term = $wp_query->get( 'search_prod_title' ) ) {
														if($search_term != ''){
															$search_term = $wpdb->esc_like( $search_term );
															$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql(  $search_term ) . '%\'';
														}
													}
													return $where;
												}

										  		add_filter( 'posts_where', 'title_filter', 10, 2 );

											break;

										case 'google':
												//echo 'google';
											break;

										case 'address':

												
												$pfcomptype = 'CHAR';

												if(isset($args['meta_query']) == false || isset($args['meta_query']) == NULL){
													$args['meta_query'] = array();
												}
																				
												if(count($args['meta_query']) > 0){
													$args['meta_query'][(count($args['meta_query'])-1)] = 
														array(
														'key' => 'webbupointfinder_items_address',
														'value' => $pfvalue,
														'compare' => 'LIKE',
														'type' => $pfcomptype
														);
													
												}else{
														$args['meta_query'] = array(
															'relation' => 'AND',
															array(
															'key' => 'webbupointfinder_items_address',
															'value' => $pfvalue,
															'compare' => 'LIKE',
															'type' => $pfcomptype
														)
													);

												}
											
											break;
										
										default:

												$pfcomptype = 'CHAR';

												if(isset($args['meta_query']) == false || isset($args['meta_query']) == NULL){
													$args['meta_query'] = array();
												}
																				
												if(count($args['meta_query']) > 0){
													$args['meta_query'][(count($args['meta_query'])-1)] = 
														array(
														'key' => 'webbupointfinder_item_'.$target,
														'value' => $pfvalue,
														'compare' => 'LIKE',
														'type' => $pfcomptype
														);
													
												}else{
														$args['meta_query'] = array(
															'relation' => 'AND',
															array(
															'key' => 'webbupointfinder_item_'.$target,
															'value' => $pfvalue,
															'compare' => 'LIKE',
															'type' => $pfcomptype
														)
													);

												}
											break;
									}


									break;
							}
						}
						
					}
			}		
		/**
		*End: Get search data & apply to query arguments.
		**/

		$manualargs = base64_encode(maybe_serialize($args));
		$hidden_output = base64_encode(maybe_serialize($hidden_output));
				
        $setup_item_searchresults_sidebarpos = PFSAIssetControl('setup_item_searchresults_sidebarpos','','2');

		$setup42_searchpagemap_headeritem = PFSAIssetControl('setup42_searchpagemap_headeritem','','1');
		if ($setup42_searchpagemap_headeritem != 1) {
			if(function_exists('PFGetDefaultPageHeader')){PFGetDefaultPageHeader();}
		}else{

			/* Get Variables and apply */
			$setup42_searchpagemap_height = PFSAIssetControl('setup42_searchpagemap_height','height','550');
			$setup42_searchpagemap_lat = PFSAIssetControl('setup42_searchpagemap_lat','','');
			$setup42_searchpagemap_lng = PFSAIssetControl('setup42_searchpagemap_lng','','');
			$setup42_searchpagemap_zoom = PFSAIssetControl('setup42_searchpagemap_zoom','','12');
			$setup42_searchpagemap_mobile = PFSAIssetControl('setup42_searchpagemap_mobile','','10');
			$setup42_searchpagemap_autofitsearch = PFSAIssetControl('setup42_searchpagemap_autofitsearch','','1');
			$setup42_searchpagemap_type = PFSAIssetControl('setup42_searchpagemap_type','','ROADMAP');
			$setup42_searchpagemap_business = PFSAIssetControl('setup42_searchpagemap_business','','0');
			$setup42_searchpagemap_streetViewControl = PFSAIssetControl('setup42_searchpagemap_streetViewControl','','1');
			$setup42_searchpagemap_style = PFSAIssetControl('setup42_searchpagemap_style','','');
			if (substr($setup42_searchpagemap_style, 0, 1) == '[' && substr($setup42_searchpagemap_style, -1, 1) == ']') {
				$setup42_searchpagemap_style = substr_replace($setup42_searchpagemap_style,"",0,1);
				$setup42_searchpagemap_style = substr_replace($setup42_searchpagemap_style,"",-1,1);
			}
			$setup42_searchpagemap_style = rawurlencode( base64_encode( strip_tags( $setup42_searchpagemap_style )));
			$setup42_searchpagemap_ajax = PFSAIssetControl('setup42_searchpagemap_ajax','','0');
			$setup42_searchpagemap_ajax_drag = PFSAIssetControl('setup42_searchpagemap_ajax_drag','','0');
			$setup42_searchpagemap_ajax_zoom = PFSAIssetControl('setup42_searchpagemap_ajax_zoom','','0');
			$setup42_searchpagemap_height = str_replace('px', '', $setup42_searchpagemap_height);
			
			
			echo do_shortcode('[pf_directory_map setup5_mapsettings_height="'.$setup42_searchpagemap_height.'" setup5_mapsettings_zoom="'.$setup42_searchpagemap_zoom.'" setup5_mapsettings_zoom_mobile="'.$setup42_searchpagemap_mobile.'" setup8_pointsettings_ajax="'.$setup42_searchpagemap_ajax.'" setup8_pointsettings_ajax_drag="'.$setup42_searchpagemap_ajax_drag.'" setup8_pointsettings_ajax_zoom="'.$setup42_searchpagemap_ajax_zoom.'" setup5_mapsettings_autofit="0" setup5_mapsettings_autofitsearch="'.$setup42_searchpagemap_autofitsearch.'" setup5_mapsettings_type="'.$setup42_searchpagemap_type.'" setup5_mapsettings_business="'.$setup42_searchpagemap_business.'" setup5_mapsettings_streetViewControl="'.$setup42_searchpagemap_streetViewControl.'" mapsearch_status="0" mapnot_status="0" setup5_mapsettings_lat="'.$setup42_searchpagemap_lat.'" setup5_mapsettings_lng="'.$setup42_searchpagemap_lng.'" setup5_mapsettings_style="'.$setup42_searchpagemap_style.'"]');
		}

        
		$setup22_searchresults_background2 = PFSAIssetControl('setup22_searchresults_background2','','#ffffff');
		$setup42_authorpagedetails_grid_layout_mode = PFSAIssetControl('setup22_searchresults_grid_layout_mode','','1');
		$setup42_authorpagedetails_defaultppptype = PFSAIssetControl('setup22_searchresults_defaultppptype','','10');
		$setup42_authorpagedetails_grid_layout_mode = ($setup42_authorpagedetails_grid_layout_mode == 1) ? 'fitRows' : 'masonry' ;

			echo '<section role="main">';
		        echo '<div class="pf-page-spacing"></div>';
		        echo '<div class="pf-container"><div class="pf-row clearfix">';
		        	if ($setup_item_searchresults_sidebarpos == 3) {
		        		echo '<div class="col-lg-12"><div class="pf-page-container">';

							echo do_shortcode('[pf_itemgrid2 filters="true" manualargs="'.$manualargs.'" orderby="" sortby="" items="'.$setup42_authorpagedetails_defaultppptype.'" cols="3" itemboxbg="'.$setup22_searchresults_background2.'" grid_layout_mode="'.$setup42_authorpagedetails_grid_layout_mode.'" ]' );


						echo '</div></div>';
		        	}else{
		        		if($setup_item_searchresults_sidebarpos == 1){
			                echo '<div class="col-lg-3 col-md-4">';
			                    get_sidebar('itemsearchres' ); 
			                echo '</div>';
			            }
			              
			            echo '<div class="col-lg-9 col-md-8"><div class="pf-page-container">'; 
			            echo do_shortcode('[pf_itemgrid2 filters="true" hidden_output="'.$hidden_output.'" manualargs="'.$manualargs.'" orderby="" sortby="" items="'.$setup42_authorpagedetails_defaultppptype.'" cols="3" itemboxbg="'.$setup22_searchresults_background2.'" grid_layout_mode="'.$setup42_authorpagedetails_grid_layout_mode.'" ]' );


			            echo '</div></div>';
			            if($setup_item_searchresults_sidebarpos == 2){
			                echo '<div class="col-lg-3 col-md-4">';
			                    get_sidebar('itemsearchres' );
			                echo '</div>';
			            }
		        	}
		            
		        echo '</div></div>';
		        echo '<div class="pf-page-spacing"></div>';
		    echo '</section>';
		

		
	}else{
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
	}


get_footer();
?>