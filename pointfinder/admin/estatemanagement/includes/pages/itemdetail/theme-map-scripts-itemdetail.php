<?php
function add_pointfindermappagedetails_code () {
	global $pointfindertheme_option;
	$setup42_itempagedetails_configuration = (isset($pointfindertheme_option['setup42_itempagedetails_configuration']))? $pointfindertheme_option['setup42_itempagedetails_configuration'] : array();
	
	$street_view_height = (isset($setup42_itempagedetails_configuration['streetview']['mheight']))?$setup42_itempagedetails_configuration['streetview']['mheight']:340;
	$location_view_height = (isset($setup42_itempagedetails_configuration['location']['mheight']))?$setup42_itempagedetails_configuration['location']['mheight']:340;

	$pfid = get_the_id();
	$pfstview = array('heading'=>'0','pitch'=>0,'zoom'=>0);

	if (PFcheck_postmeta_exist('webbupointfinder_item_streetview',$pfid)) {
		$pfstview = get_post_meta( $pfid, 'webbupointfinder_item_streetview', true );
	}

	$pfstview = PFCleanArrayAttr('PFCleanFilters',$pfstview);

	$pfstviewcor = '0,0';

	if (PFcheck_postmeta_exist('webbupointfinder_items_location',$pfid)) {
		$pfstviewcor = esc_attr(get_post_meta( $pfid, 'webbupointfinder_items_location', true ));
	}


	/*Point settings*/
	$setup10_infowindow_height = PFSAIssetControl('setup10_infowindow_height','','136');
	$setup10_infowindow_width = PFSAIssetControl('setup10_infowindow_width','','350');
	if($setup10_infowindow_height != 136){ $heightbetweenitems = $setup10_infowindow_height - 136;}else{$heightbetweenitems = 0;}
	if($setup10_infowindow_width != 350){ $widthbetweenitems = (($setup10_infowindow_width - 350)/2);}else{$widthbetweenitems = 0;}

?>
	<script type="text/javascript">
	(function($) {
		"use strict";

		// ITEM DETAIL PAGE MAP FUNCTION STARTED --------------------------------------------------------------------------------------------
		$.pfitemdetailpagemap = function(){
			
			$(function(){
				$('#pf-itempage-header-map').css('height','<?php echo esc_js($location_view_height); ?>px');
				$('#pf-itempage-header-map').gmap3({
				  defaults:{ 
		            classes:{
		              Marker:RichMarker
		            }
		          },
				  map:{
					  options:{
						zoom: 15, 
						mapTypeId: google.maps.MapTypeId.ROADMAP,
						mapTypeControl: true,
						zoomControl: true,
						panControl: true,
						scaleControl: true,
						navigationControl: true,
						draggable:true,
						scrollwheel: false,
						streetViewControl: false,
						streetViewControlOptions: {position: google.maps.ControlPosition.LEFT_BOTTOM},
						styles: [{
							featureType: 'poi',
							elementType: 'labels',
							stylers: [{ visibility: 'off' }]
						}]
					  },

					  callback: function(){
						$.pfloadmarker_itempage("<?php echo get_the_id();?>");
					  }
				  }
				});
			});
		};

		// ITEM DETAIL PAGE MAP FUNCTION FINISHED --------------------------------------------------------------------------------------------


		// ITEM PAGE HEADER MAP FUNCTION STARTED --------------------------------------------------------------------------------------------
		$.pfitemdetailpagetopmap = function(){
			
			$(function(){
				$('#item-map-page').gmap3({
				  defaults:{ 
		            classes:{
		              Marker:RichMarker
		            }
		          },
				  map:{
					  options:{
					  	center:[<?php echo $pfstviewcor;?>],
						zoom: 16, 
						mapTypeId: google.maps.MapTypeId.ROADMAP,
						mapTypeControl: true,
						zoomControl: true,
						panControl: false,
						scaleControl: true,
						navigationControl: false,
						draggable:true,
						scrollwheel: false,
						streetViewControl: $.pf_mobile_check(),
						streetViewControlOptions: {position: google.maps.ControlPosition.LEFT_BOTTOM},
						styles: [{
							featureType: 'poi',
							elementType: 'labels',
							stylers: [{ visibility: 'off' }]
						}]
					  },

					  callback: function(){
					  	
						$.pfloadmarker_itempage_top("<?php echo get_the_id();?>",(!$.pf_mobile_check())? "-89" :"<?php echo esc_js($widthbetweenitems);?>","<?php echo esc_js($heightbetweenitems);?>","<?php echo PF_current_language();?>");
						
					  }
				  }
				});
			});
		};
		// ITEM PAGE HEADER MAP FUNCTION FINISHED --------------------------------------------------------------------------------------------


		// ITEM DETAIL PAGE STREETVIEW FUNCTION STARTED --------------------------------------------------------------------------------------------
		$.pfitemdetailpagestview = function(){
			$('#pf-itempage-header-streetview').css('height','<?php echo esc_js($street_view_height); ?>px');
			$(function(){
				function pf_init_stvmap() {
				  var pfpanoramaOptions = {
				    position: new google.maps.LatLng(<?php echo $pfstviewcor;?>),
				    pov: {
				      heading: <?php echo $pfstview['heading'];?>,
				      pitch: <?php echo $pfstview['pitch'];?>
				    },
				    zoom: <?php echo $pfstview['zoom'];?>
				  };
				  var pfstpano = new google.maps.StreetViewPanorama(
				      document.getElementById('pf-itempage-header-streetview'),
				      pfpanoramaOptions);
				  pfstpano.setVisible(true);
				}

				pf_init_stvmap();
			});
		};

		// ITEM DETAIL PAGE STREETVIEW FUNCTION FINISHED --------------------------------------------------------------------------------------------



		// LOAD MAP STARTED --------------------------------------------------------------------------------------------
		$(function(){
			$.pfitemdetailpagemap();
			if($('#item-map-page').length > 0){
			$.pfitemdetailpagetopmap();
			};
			<?php if($setup42_itempagedetails_configuration['streetview']['status'] == 1){ ?>
			if($('#pf-itempage-header-streetview').length > 0){
			$.pfitemdetailpagestview();
			}
			<?php }?>
		});

		$('#pfidplocation').one().live('click',function(){
			$('#pf-itempage-header-map').css('height','<?php echo esc_js($location_view_height); ?>px');
			setTimeout(function(){
			$('#pf-itempage-header-map').gmap3({trigger:"resize"});
				var map = $('#pf-itempage-header-map').gmap3('get');
				var marker = $("#pf-itempage-header-map").gmap3({get:"marker"});
				$.pfmap_recenter(map,marker.getPosition(),0,0);
			}, 400);
		});

		$('#pfidpstreetview').one().live('click',function(){
			$('#pf-itempage-header-streetview').css('height','<?php echo esc_js($street_view_height); ?>px');
			setTimeout(function(){
				$.pfitemdetailpagestview();
			}, 400);
		});
		
		// LOAD MAP FINISHED --------------------------------------------------------------------------------------------

	})(jQuery);
	</script>
	<?php
}

add_action('wp_footer', 'add_pointfindermappagedetails_code',220);

?>