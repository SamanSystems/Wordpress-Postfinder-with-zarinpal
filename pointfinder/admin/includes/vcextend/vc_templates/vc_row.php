<?php
$output = $el_class = $bg_image = $bg_color = $bg_image_repeat = $colorfortext = $padding = $margin_bottom = $css = '';
extract(shortcode_atts(array(
    'el_class'        => '',
    'bg_image'        => '',
    'bg_color'        => '',
    'bg_image_repeat' => '',
    'padding'         => '',
    'margin_bottom'   => '',
    'widthopt'		  => '',
    'fixedbg'         => '',
    'footerrow'       => '',
    'full_width' => false,
    'colorfortext' => '',
    'colorfortexth' => '',
    'css' => ''
), $atts));

wp_enqueue_style( 'js_composer_front' );
wp_enqueue_script( 'wpb_composer_front_js' );
wp_enqueue_style('js_composer_custom_css');

if($footerrow == 'yes'){
    $footer_text = ' id="pf-footer-row"';
    $footer_ex_classes = ' pointfinderexfooterclass';
    $myoutput = '<style>#pf-footer-row.pointfinderexfooterclass a{color:'.$colorfortext.'!important}#pf-footer-row.pointfinderexfooterclass a:hover{color:'.$colorfortexth.'!important}</style>';
    $output .= $myoutput;
}else{
    $footer_text = $footer_ex_classes = '';
}

$el_class = $this->getExtraClass($el_class);
$fixedbg_text = ($fixedbg != '') ? ' pf-fixed-background' : '' ;

$css_class =  apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_row '.get_row_css_class().$el_class.vc_shortcode_custom_css_class($css, ' '), $this->settings['base']);

$style = $this->buildStyle($bg_image, $bg_color, $bg_image_repeat, $colorfortext, $padding, $margin_bottom);
$output .= '<div class="'.$css_class.$fixedbg_text.'';
if ( $full_width == 'stretch_row_content_no_spaces' ){ $output .= ' vc_row-no-padding'; };
$output .= '"';
if ( ! empty( $full_width ) ) {
    $output .= ' data-vc-full-width="true"';
    if ( $full_width == 'stretch_row_content' || $full_width == 'stretch_row_content_no_spaces' ) {
        $output .=  ' data-vc-stretch-content="true"';
    }
}
$output .= $style.'>';
if($widthopt !== 'yes'){
	$output .= "\n\t\t".'<div'.$footer_text.' class="pf-container'.$footer_ex_classes.'">';
	$output .= "\n\t\t".'<div class="pf-row">';
	$output .= wpb_js_remove_wpautop($content);
	$output .= "\n\t\t".'</div>';
	$output .= "\n\t\t".'</div>';
}else{
	$output .= "\n\t\t".'<div'.$footer_text.' class="pf-fullwidth'.$footer_ex_classes.' clearfix">';
	$output .= wpb_js_remove_wpautop($content);
	$output .= "\n\t\t".'</div>';
}

$output .= '</div>'.$this->endBlockComment('row');
echo $output;