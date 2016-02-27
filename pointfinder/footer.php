        
        </div>
        </div>
        <div id="pf-membersystem-dialog"></div>
        <a title="<?php esc_html__('Back to Top','pointfindert2d'); ?>" class="pf-up-but"><i class="pfadmicon-glyph-859"></i></a>
        <footer class="wpf-footer">
        <?php
        $setup_footerbar_text_copy = PFSAIssetControl('setup_footerbar_text_copy','','');
        $setup_footerbar_width = PFSAIssetControl('setup_footerbar_width','','0');

        if ($setup_footerbar_width == 0) {
          echo '<div class="pf-container"><div class="pf-row clearfix">';
        }
        ?>
        <div class="wpf-footer-text col-lg-12">
          <?php echo wp_kses_post($setup_footerbar_text_copy);?>

        </div>
        <?php 
        if (PFSAIssetControl('setup_footerbar_text_copy_align','','left') == 'right') {
           $footer_menu_text = ' pfleftside';
        }else{
            $footer_menu_text = ' pfrightside';
        }
        echo '<ul class="pf-footer-menu'.$footer_menu_text.'">';pointfinder_footer_navigation_menu();echo '</ul>';?>
        <?php 
        if ($setup_footerbar_width == 0) {
          echo '</div></div>';
        }
        ?>
        </footer>
        <?php
        /*
        $general_viewoptions = PFSAIssetControl('general_viewoptions','','1');
        if ($general_viewoptions == 0) {
            echo '</div>';
        }
        */
        ?>
        
		<?php wp_footer(); ?>
	</body>
</html>
