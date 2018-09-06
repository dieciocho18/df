<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package BMag
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="page" class="hfeed site">

	<header id="masthead" class="site-header" role="banner">

		<?php
		if ( get_theme_mod( 'bmag_display_top_bar', 1 ) == 1 ) {

			get_template_part( BMAG_TEMPLATE_PARTS . 'top-bar' ); ?>

			<div style="position:relative">
				<?php get_template_part( BMAG_TEMPLATE_PARTS . 'menu-movil' ); ?>
			</div>

		<?php } ?>

		<?php if ( get_header_image() ) { ?>

			<div class="header-image-wrapper">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php header_image(); ?>" class="header-image" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" /></a>
			</div><!-- .header-image-wrapper -->

		<?php } else { ?>
			<div class="logo-blog-info-widget-wrapper">
				<div class="logo-blog-info-wrapper">
					<?php
					if ( has_custom_logo() ) {
						the_custom_logo();
					}
					?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php esc_html( bloginfo( 'name' ) ); ?></a></h1>
					<h2 class="site-description"><?php esc_html( bloginfo( 'description' ) ); ?></h2>
				</div><!-- .logo-blog-info-wrapper -->

				<div class="widget-area-cabecera-wrapper">
					<?php
					if ( ! dynamic_sidebar( 'bmag-sidebar-header' ) ) {}
					?>
				</div>

			</div><!-- .logo-blog-info-widget-wrapper -->
		<?php
		}

		if ( get_theme_mod( 'bmag_display_top_bar', 1 ) == '' ) {
			?>
			<div class="boton-menu-movil-sin-top-bar"><?php esc_html_e( 'MENU', 'bmag' ); ?></div>
			<div style="position:relative">
				<?php
				get_template_part( BMAG_TEMPLATE_PARTS . 'menu-movil' ); ?>
			</div>
		<?php } ?>

		<?php $menu_line = get_theme_mod( 'bmag_menu_line', 'top' ); ?>

			<nav id="site-navigation" class="main-navigation <?php echo 'menu-line-' . $menu_line; ?>" role="navigation">
				<a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to content', 'bmag' ); ?>"><?php esc_html_e( 'Skip to content', 'bmag' ); ?></a>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
			</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div id="main" class="wrapper">

	<?php
	if ( is_front_page() && is_page_template( 'page-templates/template-mag-front-page.php' ) ) {
		if ( is_active_sidebar( 'bmag-sidebar-fp-mag-below-header' ) ) {
		?>
		<div id="magazine-front-page-below-header-widgets">
			<?php
			dynamic_sidebar( 'bmag-sidebar-fp-mag-below-header' );
			?>
		</div>
		<?php
		} else {
			if ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) {
				?>
				<div class="widget-area-front-page-vacio">
					<strong><?php esc_html_e( 'Mag Front Page: Below Header', 'bmag' ); ?></strong>
					<hr>
					<?php esc_html_e( 'Please go to Appearance > Customize > Widgets and add to the "Mag Front Page: Below Header" widget area the recommended widget "(BMag) 5 posts - Style 1". You can also leave this area empty.', 'bmag' ); ?>
				</div>
				<?php
			} // if (current_user_can).
		} // if (is_active_sidebar()).
		?>
	<?php
	} // if (is_front_page).
