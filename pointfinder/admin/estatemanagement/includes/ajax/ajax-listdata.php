<?php

/**********************************************************************************************************************************
*
* Ajax list data
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/


	add_action( 'PF_AJAX_HANDLER_pfget_listitems', 'pf_ajax_list_items' );
	add_action( 'PF_AJAX_HANDLER_nopriv_pfget_listitems', 'pf_ajax_list_items' );
	
	
function pf_ajax_list_items(){
	
	//Security	
	check_ajax_referer( 'pfget_listitems', 'security' );

	header('Content-Type: text/html; charset=UTF-8;');

		//Current Language
		if(isset($_POST['cl']) && $_POST['cl']!=''){
			$pflang = esc_attr($_POST['cl']);
			global $sitepress;
			$sitepress->switch_lang($pflang);
		}else{
			$pflang = '';
		}

		function PFIF_SortFields_ld($searchvars,$orderarg_value = NULL){

			$pfstart = PFCheckStatusofVar('setup1_slides');
			
			if($pfstart == true){
				$if_sorttext = '';
				$available_fields = array(1,2,3,4,5,7,8,14);
				$setup1_slides = PFSAIssetControl('setup1_slides','','');	
				
				
				//Prepare detailtext
				foreach ($setup1_slides as &$value) {
					$stext = '';
					if(!empty($orderarg_value)){
						if(strcmp($orderarg_value,$value['url']) == 0){
							$stext = 'selected';
						}else{
							$stext = '';
						}
					}
					$Parentcheckresult = PFIF_CheckItemsParent_ld($value['url']);
					if(is_array($searchvars)){$res = PFIF_CheckFormVarsforExist_ld($searchvars,$Parentcheckresult);}else{$res = false;}
					$customfield_sortcheck = PFCFIssetControl('setupcustomfields_'.$value['url'].'_sortoption','','0');
					
					if($Parentcheckresult == 'none'){
						if(in_array($value['select'], $available_fields) && $customfield_sortcheck != 0){
							$if_sorttext .= '<option value="'.$value['url'].'" '.$stext.'>'.$value['title'].'</option>';
						}
					}else{
						if($res == true){
							$sortnamecheck = PFCFIssetControl('setupcustomfields_'.$value['url'].'_sortname','','');
							if($sortnamecheck == ''){$sortnamecheck = $value['title'];}
							if(in_array($value['select'], $available_fields) && $customfield_sortcheck != 0){
								$if_sorttext .= '<option value="'.$value['url'].'" '.$stext.'>'.$sortnamecheck.'</option>';
							}
						}
					
					}
					
				}
				
			}
			return $if_sorttext;
		}


		/* Write data */
		$wpflistdata = '';
		
		
		/* Get admin values */
		$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
		
		
		if(isset($_POST['ne']) && $_POST['ne']!=''){
			$ne = esc_attr($_POST['ne']);
		}else{
			$ne = 360;
		}
		
		if(isset($_POST['ne2']) && $_POST['ne2']!=''){
			$ne2 = esc_attr($_POST['ne2']);
		}else{
			$ne2 = 360;
		}
		
		if(isset($_POST['sw']) && $_POST['sw']!=''){
			$sw = esc_attr($_POST['sw']);
		}else{
			$sw = -360;
		}
		
		if(isset($_POST['sw2']) && $_POST['sw2']!=''){
			$sw2 = esc_attr($_POST['sw2']);
		}else{
			$sw2 = -360;
		}
		
		
		//Defaults
		$pfaction = '';
		$pfgrid = '';
		$pfheaderfilters = '';
		$pfitemboxbg = '';
		$setup22_searchresults_grid_layout_mode = PFSAIssetControl('setup22_searchresults_grid_layout_mode','','1');
		$grid_layout_mode = ($setup22_searchresults_grid_layout_mode == 1) ? 'fitRows' : 'masonry' ;

		if(isset($_POST['gdt']) && $_POST['gdt']!=''){
			$variables_gdt = $_POST['gdt'];
			$pfaction = 'grid';
		}

		
		//Search form check
		if(isset($_POST['act']) && $_POST['act']!=''){
			$pfaction = esc_attr($_POST['act']);
		}

		//Container & show check
		if(isset($_POST['pfcontainerdiv']) && $_POST['pfcontainerdiv']!=''){
			$pfcontainerdiv = str_replace('.', '', esc_attr($_POST['pfcontainerdiv']));
		}else{
			$pfcontainerdiv = '';
		}
		if(isset($_POST['pfcontainershow']) && $_POST['pfcontainershow']!=''){
			$pfcontainershow = str_replace('.', '', esc_attr($_POST['pfcontainershow']));
			if (isset($_POST['pfex']) && !empty($_POST['pfex'])) {
				$pfcontainershow .= ' pfajaxgridview';	
			}
		}else{
			$pfcontainershow = '';
		}
		

		

		//Get if sort/order/number values exist
		if(isset($_POST['pfg_orderby']) && $_POST['pfg_orderby']!=''){$pfg_orderby = esc_attr($_POST['pfg_orderby']);}else{$pfg_orderby = '';}
		if(isset($_POST['pfg_order']) && $_POST['pfg_order']!=''){$pfg_order = esc_attr($_POST['pfg_order']);}else{$pfg_order = '';}
		if(isset($_POST['pfg_number']) && $_POST['pfg_number']!=''){$pfg_number = esc_attr($_POST['pfg_number']);}else{$pfg_number = '';}
		if(isset($_POST['page']) && $_POST['page']!=''){$pfg_paged = esc_attr($_POST['page']);}else{$pfg_paged = '';}
		
		
		$args = array( 'post_type' => $setup3_pointposttype_pt1, 'post_status' => 'publish');
		
		
		
		
		$setup22_searchresults_defaultppptype = PFSAIssetControl('setup22_searchresults_defaultppptype','','10');
		$setup22_searchresults_defaultsortbytype = PFSAIssetControl('setup22_searchresults_defaultsortbytype','','ID');
		$setup22_searchresults_defaultsorttype = PFSAIssetControl('setup22_searchresults_defaultsorttype','','ASC');
		
		if($pfg_orderby != ''){
			if($pfg_orderby == 'date' || $pfg_orderby == 'title'){
				$args['meta_key'] = 'webbupointfinder_item_featuredmarker';
				$args['orderby'] = array('meta_value_num' => 'DESC' , $pfg_orderby => $pfg_order);
			}else{
				$args['meta_key']='webbupointfinder_item_'.$pfg_orderby;
				if(PFIF_CheckFieldisNumeric_ld($pfg_orderby) == false){
					$args['orderby']= array('meta_value' => $pfg_order);
				}else{
					$args['orderby']= array('meta_value_num' => $pfg_order);
				}
				
			}
		}else{
			$args['meta_key'] = 'webbupointfinder_item_featuredmarker';
			$args['orderby'] = array('meta_value_num' => 'DESC' , $setup22_searchresults_defaultsortbytype => $setup22_searchresults_defaultsorttype);
		}
		


		if($pfg_number != ''){$args['posts_per_page'] = $pfg_number;}else{$args['posts_per_page'] = $setup22_searchresults_defaultppptype;}
		
		if($pfg_paged != ''){$args['paged'] = $pfg_paged;}
		
		if($pfaction == 'search'){
			if(isset($_POST['dt']) && $_POST['dt']!=''){$pfgetdata = $_POST['dt'];}else{$pfgetdata = '';}

				if(is_array($pfgetdata)){
					
					$pfformvars = array();
					
						foreach($pfgetdata as $singledata){
							
							//Get Values & clean
							if(esc_attr($singledata['value']) != ''){
								
								if(isset($pfformvars[esc_attr($singledata['name'])])){
									$pfformvars[esc_attr($singledata['name'])] = $pfformvars[esc_attr($singledata['name'])]. ',' .$singledata['value'];
								}else{
									$pfformvars[esc_attr($singledata['name'])] = $singledata['value'];
								}
		
							}
						
						}
						$pfsearchvars = $pfformvars;
						foreach($pfformvars as $pfformvar => $pfvalue){
							
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
		}else if( $pfaction == 'grid'){
			$setup3_pointposttype_pt4_check = PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
			$setup3_pointposttype_pt5_check = PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
			$setup3_pointposttype_pt6_check = PFSAIssetControl('setup3_pointposttype_pt6_check','','1');

			$pfgetdata = $variables_gdt;
			$grid_layout_mode = $pfgetdata['grid_layout_mode'];

				//GDT form check
				if(is_array($pfgetdata)){

					if($pfgetdata['posts_in']!=''){
						$args['post__in'] = pfstring2BasicArray($pfgetdata['posts_in']);

					}
		
					$args['tax_query'] = array();

					// listing type
					if($pfgetdata['listingtype'] != ''){
						$pfvalue_arr_lt = PFGetArrayValues_ld($pfgetdata['listingtype']);

						$fieldtaxname_lt = 'pointfinderltypes';

						if(count($args['tax_query']) > 0){
							$args['tax_query'][(count($args['tax_query'])-1)]=
							array(
									'taxonomy' => $fieldtaxname_lt,
									'field' => 'id',
									'terms' => $pfvalue_arr_lt,
									'operator' => 'IN'
							);
						}else{
							$args['tax_query']=
							array(
								'relation' => 'AND',
								array(
									'taxonomy' => $fieldtaxname_lt,
									'field' => 'id',
									'terms' => $pfvalue_arr_lt,
									'operator' => 'IN'
								)
							);
						}
					}

					if($setup3_pointposttype_pt5_check == 1){
						// location type
						if($pfgetdata['locationtype'] != ''){
							$pfvalue_arr_loc = PFGetArrayValues_ld($pfgetdata['locationtype']);

							$fieldtaxname_loc = 'pointfinderlocations';

							if(count($args['tax_query']) > 0){
								$args['tax_query'][(count($args['tax_query'])-1)]=
								array(
										'taxonomy' => $fieldtaxname_loc,
										'field' => 'id',
										'terms' => $pfvalue_arr_loc,
										'operator' => 'IN'
								);
							}else{
								$args['tax_query']=
								array(
									'relation' => 'AND',
									array(
										'taxonomy' => $fieldtaxname_loc,
										'field' => 'id',
										'terms' => $pfvalue_arr_loc,
										'operator' => 'IN'
									)
								);
							}
						}
					}

					if($setup3_pointposttype_pt4_check == 1){
						// item type
						if($pfgetdata['itemtype'] != ''){
						$pfvalue_arr_it = PFGetArrayValues_ld($pfgetdata['itemtype']);

						$fieldtaxname_it = 'pointfinderitypes';

						if(count($args['tax_query']) > 0){
							$args['tax_query'][(count($args['tax_query'])-1)]=
							array(
									'taxonomy' => $fieldtaxname_it,
									'field' => 'id',
									'terms' => $pfvalue_arr_it,
									'operator' => 'IN'
							);
						}else{
							$args['tax_query']=
							array(
								'relation' => 'AND',
								array(
									'taxonomy' => $fieldtaxname_it,
									'field' => 'id',
									'terms' => $pfvalue_arr_it,
									'operator' => 'IN'
								)
							);
						}
						}
					}

					if($setup3_pointposttype_pt6_check == 1){
						// features type
						if($pfgetdata['features'] != ''){
						$pfvalue_arr_fe = PFGetArrayValues_ld($pfgetdata['features']);

						$fieldtaxname_fe = 'pointfinderfeatures';

						if(count($args['tax_query']) > 0){
							$args['tax_query'][(count($args['tax_query'])-1)]=
							array(
									'taxonomy' => $fieldtaxname_fe,
									'field' => 'id',
									'terms' => $pfvalue_arr_fe,
									'operator' => 'IN'
							);
						}else{
							$args['tax_query']=
							array(
								'relation' => 'AND',
								array(
									'taxonomy' => $fieldtaxname_fe,
									'field' => 'id',
									'terms' => $pfvalue_arr_fe,
									'operator' => 'IN'
								)
							);
						}
						}
					}


					$pfitemboxbg = ' style="background-color:'.$pfgetdata['itemboxbg'].';"';
					$pfheaderfilters = ($pfgetdata['filters']=='true') ? '' : 'false' ;

					if($pfgetdata['cols'] != ''){$pfgrid = 'grid'.$pfgetdata['cols'];}

					//Changed values by user
					if($pfg_orderby != ''){
						if($pfg_orderby == 'date' || $pfg_orderby == 'title'){
							$args['orderby']=$pfg_orderby;
						}else{
							$args['meta_key']='webbupointfinder_item_'.$pfg_orderby;
							if(PFIF_CheckFieldisNumeric_ld($pfg_orderby) == false){
								$args['orderby']='meta_value';
							}else{
								$args['orderby']='meta_value_num';
							}
							
						}
						if($pfg_orderby == 'date' || $pfg_orderby == 'title'){
							if ($pfgetdata['featureditemshide'] != 'yes') {
								$args['meta_key'] = 'webbupointfinder_item_featuredmarker';
								$args['orderby'] = array('meta_value_num' => 'DESC' , $pfg_orderby => $pfg_order);
							}else{
								unset($args['meta_key']);
								$args['orderby'] = array($pfg_orderby => $pfg_order);
							}
							
						}else{
							$args['meta_key']='webbupointfinder_item_'.$pfg_orderby;
							if(PFIF_CheckFieldisNumeric_ld($pfg_orderby) == false){
								$args['orderby']=array('meta_value' => $pfg_order);
							}else{
								$args['orderby']= array('meta_value_num' => $pfg_order);
							}
							
						}
					}else{
						if($pfgetdata['orderby'] != ''){
							if ($pfgetdata['featureditemshide'] != 'yes') {
								$args['meta_key'] = 'webbupointfinder_item_featuredmarker';
								$order_user_dt = (isset($pfgetdata['sortby'])) ? $pfgetdata['sortby'] : 'ASC' ;
								$args['orderby'] = array('meta_value_num' => 'DESC',$pfgetdata['orderby'] => $order_user_dt);
							}else{
								unset($args['meta_key']);
								$order_user_dt = (isset($pfgetdata['sortby'])) ? $pfgetdata['sortby'] : 'ASC' ;
								$args['orderby'] = array($pfgetdata['orderby'] => $order_user_dt);
							}
						}else{
							if ($pfgetdata['featureditemshide'] != 'yes') {
								$args['meta_key'] = 'webbupointfinder_item_featuredmarker';
								$args['orderby'] = array('meta_value_num' => 'DESC' , $setup22_searchresults_defaultsortbytype => $setup22_searchresults_defaultsorttype);
							}else{
								unset($args['meta_key']);
								$args['orderby'] = array($setup22_searchresults_defaultsortbytype => $setup22_searchresults_defaultsorttype);
							}
						}
					}
					
					
					if($pfg_number != ''){
						$args['posts_per_page'] = $pfg_number;
					}else{
						if($pfgetdata['items'] != ''){
							$args['posts_per_page'] = $pfgetdata['items'];
						}else{
							$args['posts_per_page'] = $setup22_searchresults_defaultppptype;
						}
					}
					
					if($pfg_paged != ''){$args['paged'] = $pfg_paged;}	

					//Featured items filter
					if($pfgetdata['featureditems'] == 'yes' && $pfgetdata['featureditemshide'] != 'yes'){

						$args['meta_query'] = array();

						if(count($args['meta_query']) > 0){
							$args['meta_query'][(count($args['meta_query'])-1)] = array(
								'key' => 'webbupointfinder_item_featuredmarker',
								'value' => 1,
								'compare' => '=',
								'type' => 'NUMERIC'
								);
							
						}else{
								$args['meta_query'] = array(
									'relation' => 'AND',
									array(
									'key' => 'webbupointfinder_item_featuredmarker',
									'value' => 1,
									'compare' => '=',
									'type' => 'NUMERIC'
								)
							);

						}
					}
					//Hide Featured items filter
					if ($pfgetdata['featureditemshide'] == 'yes') {
						if (!isset($args['meta_query'])) {
							$args['meta_query'] = array();
						}

						if(count($args['meta_query']) > 0){
							$args['meta_query'][(count($args['meta_query'])-1)] = array(
								'key' => 'webbupointfinder_item_featuredmarker',
								'value' => 1,
								'compare' => '!=',
								'type' => 'NUMERIC'
								);
							
						}else{
							$args['meta_query'] = array(
								'relation' => 'AND',
								array(
								'key' => 'webbupointfinder_item_featuredmarker',
								'value' => 0,
								'compare' => '=',
								'type' => 'NUMERIC'
								)
							);

						}
					}			
					
				}
		}else{
			$pfsearchvars = array();
			if(isset($_POST['dtx']) && $_POST['dtx']!=''){

				$pfgetdatax = $_POST['dtx'];
				$pfgetdatax = PFCleanArrayAttr('PFCleanFilters',$pfgetdatax);


				if (is_array($pfgetdatax)) {
					foreach ($pfgetdatax as $key => $value) {

						if(isset($value['value'])){
							if (!empty($value['value'])) {
							
								if(isset($args['tax_query']) == false || isset($args['tax_query']) == NULL){
									$args['tax_query'] = array();
								}
								if(count($args['tax_query']) > 0){
									$args['tax_query'][(count($args['tax_query'])-1)]=
									array(
											'taxonomy' => $value['name'],
											'field' => 'id',
											'terms' => pfstring2BasicArray($value['value']),
											'operator' => 'IN'
									);
								}else{
									$args['tax_query']=
									array(
										'relation' => 'AND',
										array(
											'taxonomy' => $value['name'],
											'field' => 'id',
											'terms' => pfstring2BasicArray($value['value']),
											'operator' => 'IN'
										)
									);
								}
							}
						}
					}
				}

			}
			
		}

		
		
		//Grid
		if(isset($_POST['grid']) && $_POST['grid']!=''){
			$pfgrid = esc_attr($_POST['grid']);
		}
		

		$setup22_searchresults_defaultlistingtype = PFSAIssetControl('setup22_searchresults_defaultlistingtype','','4');
		if($pfgrid == ''){
			switch($setup22_searchresults_defaultlistingtype){
				case '2':
				case '3':
				case '4':
					$pfgrid = 'grid'.$setup22_searchresults_defaultlistingtype;	
				break;
				case '2h':
					$pfgrid = 'grid1';
				break;
			
			}
		}
		
		
		$general_retinasupport = PFSAIssetControl('general_retinasupport','','0');
		if($general_retinasupport == 1){$pf_retnumber = 2;}else{$pf_retnumber = 1;}
		/*Array ( [width] => 440px [height] => 330px [units] => px )*/
		$setupsizelimitconf_general_gridsize1_width = PFSizeSIssetControl('setupsizelimitconf_general_gridsize1','width',440);
		$setupsizelimitconf_general_gridsize1_height = PFSizeSIssetControl('setupsizelimitconf_general_gridsize1','height',330);

		$featured_image_width = $setupsizelimitconf_general_gridsize1_width*$pf_retnumber;
		$featured_image_height = $setupsizelimitconf_general_gridsize1_height*$pf_retnumber;

		switch($pfgrid){
			case 'grid1':
				$pfgrid_output = 'pf1col';
				$pfgridcol_output = 'col-lg-6 col-md-12 col-sm-12 col-xs-12';
				break;
			case 'grid2':
				$pfgrid_output = 'pf2col';
				$pfgridcol_output = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
				break;
			case 'grid3':
				$pfgrid_output = 'pf3col';
				$pfgridcol_output = 'col-lg-4 col-md-6 col-sm-6 col-xs-12';
				break;
			case 'grid4':
				$pfgrid_output = 'pf4col';
				$pfgridcol_output = 'col-lg-3 col-md-4 col-sm-4 col-xs-12';
				break;
			default:
				$pfgrid_output = 'pf4col';
				$pfgridcol_output = 'col-lg-3 col-md-4 col-sm-4 col-xs-12';
				break;
		}
		
		if ($pfcontainerdiv === 'pfsearchresults') {
			$setup22_searchresults_status_sortby = PFSAIssetControl('setup22_searchresults_status_sortby','','0');
			$setup22_searchresults_status_ascdesc = PFSAIssetControl('setup22_searchresults_status_ascdesc','','0');
			$setup22_searchresults_status_number = PFSAIssetControl('setup22_searchresults_status_number','','0');
			$setup22_searchresults_status_2col = PFSAIssetControl('setup22_searchresults_status_2col','','0');
			$setup22_searchresults_status_3col = PFSAIssetControl('setup22_searchresults_status_3col','','0');
			$setup22_searchresults_status_4col = PFSAIssetControl('setup22_searchresults_status_4col','','0');
			$setup22_searchresults_status_2colh = PFSAIssetControl('setup22_searchresults_status_2colh','','0');
		}else{
			$setup22_searchresults_status_sortby = $setup22_searchresults_status_ascdesc = $setup22_searchresults_status_number = $setup22_searchresults_status_2col = $setup22_searchresults_status_3col = $setup22_searchresults_status_4col = $setup22_searchresults_status_2colh = 0;
		}
		
		
		
		//Create html codes
		$wpflistdata .= '
            <div class="pfsearchresults '.$pfcontainershow.' pflistgridview">';
            if($pfheaderfilters == ''){
            	$wpflistdata .= '
                <div class="'.$pfcontainerdiv.'-header pflistcommonview-header">
                ';
                if ($pfcontainerdiv === 'pfsearchresults') {
                $wpflistdata .= '
                <div class="pf-container">
                        <div class="pf-row">
                            <div class="col-lg-12">';
                        	} 
                            $wpflistdata .= '
                                <ul class="'.$pfcontainerdiv.'-filters-left '.$pfcontainerdiv.'-filters searchformcontainer-filters searchformcontainer-filters-left golden-forms clearfix col-lg6 col-md-6 col-sm-6 col-xs-12">
								';
								   if($setup22_searchresults_status_sortby == 0){
								   $wpflistdata .= '
                                    <li>
                                        <label for="pfsearch-filter" class="lbl-ui select pfsortby">
                                    	<select class="pfsearch-filter" name="pfsearch-filter" id="pfsearch-filter">';
											if($args['orderby'] == 'ID' && $args['orderby'] != 'meta_value_num' && $args['orderby'] != 'meta_value'){
													$wpflistdata .= '<option value="" selected>'.esc_html__('SORT BY','pointfindert2d').'</option>';
											}else{
												$wpflistdata .= '<option value="">'.esc_html__('SORT BY','pointfindert2d').'</option>';
											}
											$pfgform_values3 = array('title','date');
											$pfgform_values3_texts = array('title'=>esc_html__('Title','pointfindert2d'),'date'=>esc_html__('Date','pointfindert2d'));
											
											foreach($pfgform_values3 as $pfgform_value3){
											   if(isset($pfg_orderby)){

												   if(strcmp($pfgform_value3, $pfg_orderby) == 0){
													   $wpflistdata .= '<option value="'.$pfgform_value3.'" selected>'.$pfgform_values3_texts[$pfgform_value3].'</option>';
												   }else{
													   $wpflistdata .= '<option value="'.$pfgform_value3.'">'.$pfgform_values3_texts[$pfgform_value3].'</option>';
												   }

												}else{

												   if(strcmp($pfgform_value3, $setup22_searchresults_defaultsortbytype)){
													   $wpflistdata .= '<option value="'.$pfgform_value3.'" selected>'.$pfgform_values3_texts[$pfgform_value3].'</option>';
												   }else{
													   $wpflistdata .= '<option value="'.$pfgform_value3.'">'.$pfgform_values3_texts[$pfgform_value3].'</option>';
												   }

												}
											}
											if(!isset($pfsearchvars)){$pfsearchvars = array();}

												if(!isset($pfg_orderby)){
													$wpflistdata .= PFIF_SortFields_ld($pfsearchvars);
												}else{
													$wpflistdata .= PFIF_SortFields_ld($pfsearchvars,$pfg_orderby);
												}
											
											$wpflistdata .='
                                        </select>
                                        </label>
                                    </li>';}
									if($setup22_searchresults_status_ascdesc == 0){
									$wpflistdata .= '
                                    <li>
                                        <label for="pfsearch-filter-order" class="lbl-ui select pforderby">
                                    	<select class="pfsearch-filter-order" name="pfsearch-filter-order" id="pfsearch-filter-order" >';
										$pfgform_values2 = array('ASC','DESC');
										
										$pfgform_values2_texts = array('ASC'=>esc_html__('ASC','pointfindert2d'),'DESC'=>esc_html__('DESC','pointfindert2d'));
										foreach($pfgform_values2 as $pfgform_value2){
										   if(isset($pfg_order)){
	                                           if(strcmp($pfgform_value2,$pfg_order) == 0){
											  	   $wpflistdata .= '<option value="'.$pfgform_value2.'" selected>'.$pfgform_values2_texts[$pfgform_value2].'</option>';
											   }else{
												   $wpflistdata .= '<option value="'.$pfgform_value2.'">'.$pfgform_values2_texts[$pfgform_value2].'</option>';
											   }
											}else{
												if(strcmp($pfgform_value2,$setup22_searchresults_defaultsorttype) == 0){
											  	   $wpflistdata .= '<option value="'.$pfgform_value2.'" selected>'.$pfgform_values2_texts[$pfgform_value2].'</option>';
											   }else{
												   $wpflistdata .= '<option value="'.$pfgform_value2.'">'.$pfgform_values2_texts[$pfgform_value2].'</option>';
											   }
											}
										}
										$wpflistdata .= '</select>
                                        </label>
                                    </li>
									';}
									if($setup22_searchresults_status_number == 0){
									$wpflistdata .= '
                                    <li>
                                        <label for="pfsearch-filter-number" class="lbl-ui select pfnumberby">
                                    	<select class="pfsearch-filter-number" name="pfsearch-filter-number" id="pfsearch-filter-number" >';
										$pfgform_values = PFIFPageNumbers();
										if($args['posts_per_page'] != ''){
											$pagevalforn = $args['posts_per_page'];
										}else{
											$pagevalforn = $setup22_searchresults_defaultppptype;
										}
										foreach($pfgform_values as $pfgform_value){
                                           if(strcmp($pfgform_value,$pagevalforn) == 0){
										  	   $wpflistdata .= '<option value="'.$pfgform_value.'" selected>'.$pfgform_value.'</option>';
										   }else{
											   $wpflistdata .= '<option value="'.$pfgform_value.'">'.$pfgform_value.'</option>';
										   }
										}
										$wpflistdata .= '</select>
                                        </label>
                                    </li>
									';}
									if (!isset($_POST['pfex']) && empty($_POST['pfex'])) {
									$wpflistdata .= '<li class="pfgridlist6"></li>';
									}
									$wpflistdata .= '
                                </ul>
                                <ul class="'.$pfcontainerdiv.'-filters-right '.$pfcontainerdiv.'-filters searchformcontainer-filters searchformcontainer-filters-right clearfix col-lg6 col-md-6 col-sm-6 col-xs-12">';
								
                                    if($setup22_searchresults_status_2col == 0){$wpflistdata .= '<li class="pfgridlist2 pfgridlistit" data-pf-grid="grid2" ></li>';}
                                    if($setup22_searchresults_status_3col == 0){$wpflistdata .= '<li class="pfgridlist3 pfgridlistit" data-pf-grid="grid3" ></li>';}
                                    if($setup22_searchresults_status_4col == 0){$wpflistdata .= '<li class="pfgridlist4 pfgridlistit" data-pf-grid="grid4" ></li>';}
                                    if($setup22_searchresults_status_2colh == 0){$wpflistdata .= '<li class="pfgridlist5 pfgridlistit" data-pf-grid="grid1" ></li>';}
                                    
									$wpflistdata .= '<li class="pfgridlist6"></li>';
                                
								$wpflistdata .= '</ul>
                            </div>';
                            if ($pfcontainerdiv === 'pfsearchresults') {
                            $wpflistdata .='
                       </div>
                   </div>
                </div>';
            	}
            }//Search results header finished
                $wpflistdata .=
                '<div class="'.$pfcontainerdiv.'-content pflistcommonview-content" data-layout-mode="'.$grid_layout_mode.'">';
                
                if ($pfcontainerdiv === 'pfsearchresults') {
                	$wpflistdata.='
                    <div class="pf-container">
                    <div class="pf-row clearfix">
                    <div class="col-lg-12">';
                	}
                    $wpflistdata .='
                        <ul class="pfitemlists-content-elements '.$pfgrid_output.'" data-layout-mode="'.$grid_layout_mode.'">';
		
		
		$wpflistdata_output = '';	
		
		$setup22_searchresults_animation_image  = PFSAIssetControl('setup22_searchresults_animation_image','','WhiteSquare');
		$setup22_searchresults_hover_image  = PFSAIssetControl('setup22_searchresults_hover_image','','0');
		$setup22_searchresults_hover_video  = PFSAIssetControl('setup22_searchresults_hover_video','','0');
		$setup22_searchresults_hide_address  = PFSAIssetControl('setup22_searchresults_hide_address','','0');
		
		$pfbuttonstyletext = 'pfHoverButtonStyle ';
		
		switch($setup22_searchresults_animation_image){
			case 'WhiteRounded':
				$pfbuttonstyletext .= 'pfHoverButtonWhite pfHoverButtonRounded';
				break;
			case 'BlackRounded':
				$pfbuttonstyletext .= 'pfHoverButtonBlack pfHoverButtonRounded';
				break;
			case 'WhiteSquare':
				$pfbuttonstyletext .= 'pfHoverButtonWhite pfHoverButtonSquare';
				break;
			case 'BlackSquare':
				$pfbuttonstyletext .= 'pfHoverButtonBlack pfHoverButtonSquare';
				break;
			
		}

		
		$pfboptx1 = PFSAIssetControl('setup22_searchresults_hide_excerpt','1','0');
		$pfboptx2 = PFSAIssetControl('setup22_searchresults_hide_excerpt','2','0');
		$pfboptx3 = PFSAIssetControl('setup22_searchresults_hide_excerpt','3','0');
		$pfboptx4 = PFSAIssetControl('setup22_searchresults_hide_excerpt','4','0');
		
		if($pfboptx1 != 1){$pfboptx1_text = 'style="display:none"';}else{$pfboptx1_text = '';}
		if($pfboptx2 != 1){$pfboptx2_text = 'style="display:none"';}else{$pfboptx2_text = '';}
		if($pfboptx3 != 1){$pfboptx3_text = 'style="display:none"';}else{$pfboptx3_text = '';}
		if($pfboptx4 != 1){$pfboptx4_text = 'style="display:none"';}else{$pfboptx4_text = '';}
		
		switch($pfgrid_output){case 'pf1col':$pfboptx_text = $pfboptx1_text;break;case 'pf2col':$pfboptx_text = $pfboptx2_text;break;case 'pf3col':$pfboptx_text = $pfboptx3_text;break;case 'pf4col':$pfboptx_text = $pfboptx4_text;break;}
		
		
		
		
		if (is_user_logged_in()) {
			$user_favorites_arr = get_user_meta( get_current_user_id(), 'user_favorites', true );
			if (!empty($user_favorites_arr)) {
				$user_favorites_arr = json_decode($user_favorites_arr,true);
			}else{
				$user_favorites_arr = array();
			}
		}			
						

		$setup16_featureditemribbon_hide = PFSAIssetControl('setup16_featureditemribbon_hide','','1');
		$setup4_membersettings_favorites = PFSAIssetControl('setup4_membersettings_favorites','','1');
		$setup22_searchresults_hide_re = PFREVSIssetControl('setup22_searchresults_hide_re','','1');
		$setup22_searchresults_hide_excerpt_rl = PFSAIssetControl('setup22_searchresults_hide_excerpt_rl','','2');
		$setup16_reviewstars_nrtext = PFREVSIssetControl('setup16_reviewstars_nrtext','','0');

		//If coordinatefilter on
		if ($sw != -360 && (!empty($sw) && !empty($sw2) && !empty($ne) && !empty($ne2))) {
			$loop_ex_posts = array();
			$args2 = $args;
			$args2['posts_per_page'] = -1;
			$loop_ex = new WP_Query( $args2 );
			if($loop_ex->post_count > 0){
				while ( $loop_ex->have_posts() ) : $loop_ex->the_post();
				//If grid disable coordinate check.
				if($pfaction != 'grid'){
					$coordinates = explode( ',', rwmb_meta('webbupointfinder_items_location') );
				}else{
					$coordinates = '0,0';
				}
				if($coordinates[0] > $sw && $coordinates[0] < $ne && $coordinates[1] > $sw2 && $coordinates[1] < $ne2 && $coordinates[0] != '' && $coordinates[1] != ''){
					$loop_ex_posts[] = get_the_id();
				}
				endwhile;
			}
			$args['post__in'] = $loop_ex_posts;
			wp_reset_postdata();
		}


		$loop = new WP_Query( $args );
			/*
			print_r($loop->query).PHP_EOL;
			echo $loop->request.PHP_EOL;
			echo $loop->post_count.PHP_EOL;
			*/
			
			if($loop->post_count > 0){
		
				while ( $loop->have_posts() ) : $loop->the_post();
				
				
				$post_id = get_the_id();
				
				/* Print out icon visibility --------------------------------------------------------------------*/
				
					$pfitemvisibilityGet = redux_post_meta("pointfinderthemefmb_options", $post_id, "webbupointfinder_item_point_visibility");
					$pfitemvisibilityGet = (empty($pfitemvisibilityGet))? 1: $pfitemvisibilityGet;
					if($pfitemvisibilityGet == 0){$pfitemvisibility = 'false';}else{$pfitemvisibility = 'true';}
				
				/* Print out icon visibility --------------------------------------------------------------------*/

					//if($coordinates[0] > $sw && $coordinates[0] < $ne && $coordinates[1] > $sw2 && $coordinates[1] < $ne2 && $coordinates[0] != '' && $coordinates[1] != ''){				
						$ItemDetailArr = array();
						
						if ($pflang) {// If WPML working..
							$pfitemid = PFLangCategoryID_ld($post_id,$pflang);
						}else{
							$pfitemid = $post_id;
						}

						
						$featured_image = '';
						$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $pfitemid ), 'full' );
						$ItemDetailArr['featured_image_org'] = $featured_image[0];
						if($featured_image[0] != '' && $featured_image[0] != NULL){
							$ItemDetailArr['featured_image'] = aq_resize($featured_image[0],$featured_image_width,$featured_image_height,true);

							if($ItemDetailArr['featured_image'] === false) {
								if($general_retinasupport == 1){
									$ItemDetailArr['featured_image'] = aq_resize($featured_image[0],$featured_image_width/2,$featured_image_height/2,true);
									if($ItemDetailArr['featured_image'] === false) {
										$ItemDetailArr['featured_image'] = aq_resize($featured_image[0],$featured_image_width/4,$featured_image_height/4,true);
										if ($ItemDetailArr['featured_image'] === false) {
											$ItemDetailArr['featured_image'] = $ItemDetailArr['featured_image_org'];
											if($ItemDetailArr['featured_image'] == '') {
												$ItemDetailArr['featured_image'] = get_template_directory_uri().'/images/noimg.png';
											}
										}
									}
								}else{
									$ItemDetailArr['featured_image'] = aq_resize($featured_image[0],$featured_image_width/2,$featured_image_height/2,true);
									if ($ItemDetailArr['featured_image'] === false) {
										$ItemDetailArr['featured_image'] = $ItemDetailArr['featured_image_org'];
										if($ItemDetailArr['featured_image'] == '') {
											$ItemDetailArr['featured_image'] = get_template_directory_uri().'/images/noimg.png';
										}
									}
								}
								
							}
				
						}else{
							$ItemDetailArr['featured_image'] = get_template_directory_uri().'/images/noimg.png';
						}
						//Title
						$ItemDetailArr['if_title'] = get_the_title($pfitemid);
						//Title
						$ItemDetailArr['if_excerpt'] = get_the_excerpt();
						//Permalink
						$ItemDetailArr['if_link'] = get_permalink($pfitemid);;
						//Address
						$ItemDetailArr['if_address'] = esc_html(get_post_meta( $pfitemid, 'webbupointfinder_items_address', true ));
						//Featured Video
						$ItemDetailArr['featured_video'] =  get_post_meta( $pfitemid, 'webbupointfinder_item_video', true );
						
						$output_data = PFIF_DetailText_ld($pfitemid);
						if (is_array($output_data)) {
							if (!empty($output_data['ltypes'])) {
								$output_data_ltypes = $output_data['ltypes'];
							} else {
								$output_data_ltypes = '';
							}
							if (!empty($output_data['content'])) {
								$output_data_content = $output_data['content'];
							} else {
								$output_data_content = '';
							}
							if (!empty($output_data['priceval'])) {
								$output_data_priceval = $output_data['priceval'];
							} else {
								$output_data_priceval = '';
							}
						} else {
							$output_data_priceval = '';
							$output_data_content = '';
							$output_data_ltypes = '';
						}
						
						
						$wpflistdata_output .= '
							<li class="'.$pfgridcol_output.' wpfitemlistdata isotope-item">
								<div class="pflist-item"'.$pfitemboxbg.'>
								<div class="pflist-item-inner">
									<div class="pflist-imagecontainer pflist-subitem">
									';
									
									if($setup22_searchresults_hover_image == 1){
										$wpflistdata_output .= "<a href='".$ItemDetailArr['if_link']."'><img src='".$ItemDetailArr['featured_image'] ."' alt='' /></a>";
										if($setup4_membersettings_favorites == 1){
											
											$fav_check = 'false';
											$favtitle_text = esc_html__('Add to Favorites','pointfindert2d');

											if (is_user_logged_in() && count($user_favorites_arr)>0) {
												if (in_array($pfitemid, $user_favorites_arr)) {
													$fav_check = 'true';
													$favtitle_text = esc_html__('Remove from Favorites','pointfindert2d');
												}
											}

											$wpflistdata_output .= '<div class="RibbonCTR">
				                                <span class="Sign"><a class="pf-favorites-link" data-pf-num="'.$pfitemid.'" data-pf-active="'.$fav_check.'" data-pf-item="false" title="'.$favtitle_text.'"><i class="pfadmicon-glyph-629"></i></a>
				                                </span>
				                                <span class="Triangle"></span>
				                            </div>';
				                        }
									}else{
										$wpflistdata_output .= "<a href='".$ItemDetailArr['if_link']."'><img src='".$ItemDetailArr['featured_image'] ."' alt='' /></a>";
										
										if($setup4_membersettings_favorites == 1){
											
											$fav_check = 'false';
											$favtitle_text = esc_html__('Add to Favorites','pointfindert2d');

											if (is_user_logged_in() && count($user_favorites_arr)>0) {
												if (in_array($pfitemid, $user_favorites_arr)) {
													$fav_check = 'true';
													$favtitle_text = esc_html__('Remove from Favorites','pointfindert2d');
												}
											}

											$wpflistdata_output .= '<div class="RibbonCTR">
				                                <span class="Sign"><a class="pf-favorites-link" data-pf-num="'.$pfitemid.'" data-pf-active="'.$fav_check.'" data-pf-item="false" title="'.$favtitle_text.'"><i class="pfadmicon-glyph-629"></i></a>
				                                </span>
				                                <span class="Triangle"></span>
				                            </div>';
				                        }


				                        
										$wpflistdata_output .= '
										<div class="pfImageOverlayH hidden-xs"></div>
										';
										if($setup22_searchresults_hover_video != 1 && !empty($itemvars['featured_video'])){	
										$wpflistdata_output .= '
										<div class="pfButtons pfStyleV pfStyleVAni hidden-xs">';
										}else{
										$wpflistdata_output .= '
										<div class="pfButtons pfStyleV2 pfStyleVAni hidden-xs">';
										}
											$wpflistdata_output .= '
											<span class="'.$pfbuttonstyletext.' clearfix">
												<a class="pficon-imageclick" data-pf-link="'.$ItemDetailArr['featured_image_org'].'" style="cursor:pointer">
													<i class="pfadmicon-glyph-684"></i>
												</a>
											</span>';
											if($setup22_searchresults_hover_video != 1 && !empty($itemvars['featured_video'])){	
											$wpflistdata_output .= '
											<span class="'.$pfbuttonstyletext.'">
												<a class="pficon-videoclick" data-pf-link="'.$ItemDetailArr['featured_video'].'" style="cursor:pointer">
													<i class="pfadmicon-glyph-573"></i>
												</a>
											</span>';
											}
											$wpflistdata_output .= '
											<span class="'.$pfbuttonstyletext.'">
												<a href="'.$ItemDetailArr['if_link'].'">
													<i class="pfadmicon-glyph-794"></i>
												</a>
											</span>
										</div>';
									}
									

									if ($setup16_featureditemribbon_hide != 0) {
			                        	if (PFcheck_postmeta_exist('webbupointfinder_item_featuredmarker',$pfitemid)) {
			                        		if (esc_attr(get_post_meta( $pfitemid, 'webbupointfinder_item_featuredmarker', true )) == 1) {
			                        			$wpflistdata_output .= '<div class="pfribbon-wrapper-featured"><div class="pfribbon-featured">'.esc_html__('FEATURED','pointfindert2d').'</div></div>';
			                        		}
			                        	}

			                        }

			                        if (PFREVSIssetControl('setup11_reviewsystem_check','','0') == 1) {
			                        	if ($setup22_searchresults_hide_re == 0) {
			                        		$reviews = pfcalculate_total_review($pfitemid);
			                        		if (!empty($reviews['totalresult'])) {
			                        			$rev_total_res = round($reviews['totalresult']);
			                        			$wpflistdata_output .= '<div class="pfrevstars-wrapper-review">';
			                        			$wpflistdata_output .= ' <div class="pfrevstars-review"> <span class="hidden-xs hidden-sm"></i> '.PFREVSIssetControl('setup16_reviewstars_revtextbefore','','').'</span>';
			                        				for ($ri=0; $ri < $rev_total_res; $ri++) { 
			                        					$wpflistdata_output .= '<i class="pfadmicon-glyph-377"></i>';
			                        				}
			                        				for ($ki=0; $ki < (5-$rev_total_res); $ki++) { 
			                        					$wpflistdata_output .= '<i class="pfadmicon-glyph-378"></i>';
			                        				}

			                        			$wpflistdata_output .= '</div></div>';
			                        		}else{
			                        			if($setup16_reviewstars_nrtext == 0){
				                        			$wpflistdata_output .= '<div class="pfrevstars-wrapper-review">';
				                        			$wpflistdata_output .= ' <div class="pfrevstars-review">  '.esc_html__('Not rated yet.','pointfindert2d').'';
				                        			$wpflistdata_output .= '</div></div>';
			                        			}
			                        		}
			                        	}

			                        }




									if ($output_data_priceval != '' || $output_data_ltypes != '') {
										$wpflistdata_output .= '<div class="pflisting-itemband">';
									
										$wpflistdata_output .= '<div class="pflist-pricecontainer">';
										if ($output_data_ltypes != '') {
											$wpflistdata_output .= $output_data_ltypes;
											
										}
										if ($output_data_priceval != '') {
											$wpflistdata_output .= $output_data_priceval;
										}else{
											$wpflistdata_output .= '<div class="pflistingitem-subelement pf-price" style="visibility: hidden;"><i class="pfadmicon-glyph-553"></i></div>';
										}
										
										$wpflistdata_output .= '</div>';
								
										$wpflistdata_output .= '</div>';
									}



									if($pfgrid_output == 'pf1col'){
										$wpflistdata_output .= '</div><div class="pfrightcontent">';
									}else{
										$wpflistdata_output .='
										
									</div>
									';
									}

									

									switch($pfgrid){
										
										case 'grid2':
											$limit_chr = PFSizeSIssetControl('setupsizelimitwordconf_general_grid2address','',96);
											$limit_chr_title = PFSizeSIssetControl('setupsizelimitwordconf_general_grid2title','',96);
											break;
										case 'grid3':
											$limit_chr = PFSizeSIssetControl('setupsizelimitwordconf_general_grid3address','',32);
											$limit_chr_title = PFSizeSIssetControl('setupsizelimitwordconf_general_grid3title','',32);
											break;
										case 'grid4':
											$limit_chr = PFSizeSIssetControl('setupsizelimitwordconf_general_grid4address','',32);
											$limit_chr_title = PFSizeSIssetControl('setupsizelimitwordconf_general_grid4title','',32);
											break;
										default:
											$limit_chr = PFSizeSIssetControl('setupsizelimitwordconf_general_grid4address','',32);
											$limit_chr_title = PFSizeSIssetControl('setupsizelimitwordconf_general_grid4title','',32);
											break;
									}
									
									$titlecount = strlen($ItemDetailArr['if_title']);
									$titlecount = (strlen($ItemDetailArr['if_title'])<=$limit_chr_title ) ? '' : '...' ;
									$title_text = mb_substr($ItemDetailArr['if_title'], 0, $limit_chr_title ,'UTF-8').$titlecount;

									$addresscount = strlen($ItemDetailArr['if_address']);
									$addresscount = (strlen($ItemDetailArr['if_address'])<=$limit_chr ) ? '' : '...' ;
									$address_text = mb_substr($ItemDetailArr['if_address'], 0, $limit_chr ,'UTF-8').$addresscount;

									$excerpt_text = mb_substr($ItemDetailArr['if_excerpt'], 0, ($limit_chr*$setup22_searchresults_hide_excerpt_rl),'UTF-8').$addresscount;
									


									$wpflistdata_output .= '
									<div class="pflist-detailcontainer pflist-subitem">
										<ul class="pflist-itemdetails">
											<li class="pflist-itemtitle"><a href="'.$ItemDetailArr['if_link'].'">'.$title_text.'</a></li>
											';
											if($setup22_searchresults_hide_address == 0){
											$wpflistdata_output .= '
											<li class="pflist-address">'.$address_text.'</li>
											';
											}
											$wpflistdata_output .= '
										</ul>
									</div>
									';
								
									if($pfboptx_text != 'style="display:none"'){
									$wpflistdata_output .= '
										<div class="pflist-excerpt pflist-subitem" '.$pfboptx_text.'>'.$excerpt_text.'</div>
									';
									}
									if (!empty($output_data_content)) {
										$wpflistdata_output .= '<div class="pflist-subdetailcontainer pflist-subitem"><div class="pflist-customfields">'.$output_data_content.'</div></div>';
									}
									
									
									if ($pfcontainerdiv === 'pfsearchresults' && PFSAIssetControl('setup22_searchresults_showmapfeature','','1') == 1) {
										$wpflistdata_output .= '<div class="pflist-subdetailcontainer pflist-subitem"><a data-pfitemid="'.$pfitemid.'" class="pfshowmaplink"><i class="pfadmicon-glyph-372"></i> '.esc_html__('SHOW ON MAP','pointfindert2d').'</a></</div>';
									}
									
									$wpflistdata_output .= '
									</div>
								</div>
								
							</li>
						';
					//}
					
					
				endwhile;
				wp_reset_postdata();
				$wpflistdata .= $wpflistdata_output;               
	            $wpflistdata .= '</ul>';
			}else{
				$wpflistdata .= $wpflistdata_output;               
	            $wpflistdata .= '</ul>';
	            $wpflistdata .= '<div class="golden-forms">';
	            $wpflistdata .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p>';
				$wpflistdata .= '<strong>'.esc_html__('No record found!','pointfindert2d').'</strong></p>';
				$wpflistdata .= '</div></div>';
			}
            
			$wpflistdata .= '<div class="pfajax_paginate" >';
			$big = 999999999;
			$wpflistdata .= paginate_links(array(
				'base' => '%_%',
				'format' => '',
				'current' => max(1, $pfg_paged),
				'total' => $loop->max_num_pages,
				'type' => 'list',
			));
			$wpflistdata .= '</div>';
			if ($pfcontainerdiv === 'pfsearchresults') {
				$wpflistdata .= '</div></div></div>';
			}
			$wpflistdata .= '</div></div>';
		
	   echo $wpflistdata;

		
	die();
}

?>