<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package BMag
 * @since BMag 1.0
 */

?>
	</div><!-- #main .wrapper -->

	<footer id="colophon" role="contentinfo">

		<?php
		if ( is_active_sidebar( 'bmag-sidebar-footer-1' ) || is_active_sidebar( 'bmag-sidebar-footer-2' ) || is_active_sidebar( 'bmag-sidebar-footer-3' ) || is_active_sidebar( 'bmag-sidebar-footer-4' ) ) { ?>

				<div class="wrapper-widget-area-footer">
					<div class="widget-area-footer-1">
						<?php if ( ! dynamic_sidebar( 'bmag-sidebar-footer-1' ) ) {} ?>
					</div>

					<div class="widget-area-footer-2">
						<?php if ( ! dynamic_sidebar( 'bmag-sidebar-footer-2' ) ) {} ?>
					</div>

					<div class="widget-area-footer-3">
						<?php if ( ! dynamic_sidebar( 'bmag-sidebar-footer-3' ) ) {} ?>
					</div>

					<div class="widget-area-footer-4">
						<?php if ( ! dynamic_sidebar( 'bmag-sidebar-footer-4' ) ) {} ?>
					</div>

				</div><!-- .wrapper-widget-area-footer -->

		<?php } // if is active widget areas ; ?>

		<div class="site-info">
			<div class="footer-text-left"><?php echo wp_kses_post( get_theme_mod( 'bmag_footer_text_left', '' ) ); ?></div>
			<div class="footer-text-center"><?php echo wp_kses_post( get_theme_mod( 'bmag_footer_text_center', '' ) ); ?></div>
			<div class="footer-text-right"><?php echo wp_kses_post( get_theme_mod( 'bmag_footer_text_right', '' ) ); ?></div>
		</div><!-- .site-info -->

		<?php if ( '' == get_theme_mod( 'bmag_hide_credits', '' ) ) { ?>
			<div class="bmag-theme-credits">
					Theme <a href="<?php echo BMAG_THEME_URI; ?>"><?php echo BMAG_NAME; ?></a> <?php esc_html_e( 'by', 'bmag' ); ?> GalussoThemes |
					<?php esc_html_e( 'Powered by', 'bmag' ); ?> <a href="<?php echo esc_url( __( 'http://wordpress.org/', 'bmag' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'bmag' ); ?>">WordPress</a>
			</div><!-- .credits-blog-wp -->
		<?php } ?>
	</footer><!-- #colophon -->

	<?php
	if ( get_theme_mod( 'bmag_back_to_top_button', 1 ) == 1 ) { ?>
		<div class="ir-arriba"><i class="fa fa-arrow-up"></i></div>
	<?php } ?>

	</div><!-- #page -->

	<?php wp_footer(); ?>

</body>
</html>
