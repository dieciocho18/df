<?php
/**
 * This file adds CSS (Customizer options).
 *
 * @package BMag
 */

add_action( 'wp_enqueue_scripts', 'bmag_customization', 11 );
/**
 * Output CSS (Customizer options).
 *
 * @since 1.0.0
 */
function bmag_customization() {

	$color          = esc_html( get_theme_mod( 'bmag_color_tema', '#DD4040' ) );
	$color_contrast = bmag_color_contrast( $color );
	$fuente_aux     = esc_html( get_theme_mod( 'bmag_fonts', 'Arimo' ) );

	$fuente = "body.custom-font-enabled {font-family: '$fuente_aux', Arial, Verdana;}";

	$top_bar_black = ( get_theme_mod( 'bmag_top_bar_black', '' ) == 1 ) ?
	'.top-bar {background-color: #222222; color:#f2f2f2;}
	.top-bar a{color: #f2f2f2;}' : ".top-bar {background-color: $color; color:#f2f2f2;}
	.top-bar a{color: #f2f2f2;}";

	$color_widget_title = ( get_theme_mod( 'bmag_color_widget_title', '' ) == 1 ) ?
	"h3.widget-title{background-color:$color;}
	.widget-title-tab{color:$color_contrast;}
	.widget-title-tab a.rsswidget{color:$color_contrast !important;}" : '';

	$excerpt_title_color = ( get_theme_mod( 'bmag_color_excerpt_title', '' ) == 1 ) ?
	".entry-title:not(.overlay-title) a, entry-title a:visited {color:$color;}" : '';

	$thumbnail_rounded = ( get_theme_mod( 'bmag_thumbnail_rounded', '' ) == 1 ) ?
	'.wrapper-excerpt-thumbnail img {border-radius:50%;}' : '';

	$text_justify = ( get_theme_mod( 'bmag_text_justify', '' ) == 1 ) ?
	'.entry-content {text-align:justify;}' : '';

	$sidebar_position = ( get_theme_mod( 'bmag_sidebar_position', 'derecha' ) === 'derecha' ) ?
	'@media screen and (min-width: 768px) {
		#primary {float:left;}
		#secondary {float:right;}
	}' : '';

	$excerpt_border_color = ( get_theme_mod( 'bmag_color_excerpt_border', 1 ) == 1 ) ?
	".excerpt-wrapper{border-left:2px solid $color;}" : '';

	$menu_negro = ( get_theme_mod( 'bmag_fondo_menu_negro', 1 ) == 1 ) ?
	".main-navigation{background-color: #222222;}
	.main-navigation ul.nav-menu,
	.main-navigation div.nav-menu > ul {
		background-color:#222222;
	}
	.main-navigation li a {
		color:#f2f2f2;
	}
	.main-navigation li ul li a {
		background-color:#222222;
		color:#f2f2f2;
	}
	.main-navigation li ul li a:hover {
		background-color:#222222;
		color:$color;
	}" : '';

	$menu_center = ( get_theme_mod( 'bmag_menu_center', '' ) === 1 ) ?
	'#site-navigation ul{text-align:center;}
	#site-navigation ul li ul{text-align:left;}' : '';

	$css = "$fuente
	$top_bar_black $color_widget_title $excerpt_title_color $thumbnail_rounded $text_justify $sidebar_position $excerpt_border_color $menu_negro $menu_center
	a {color: $color;}
	a:hover, .site-header h1 a:hover {color: $color;}
	.social-icon-wrapper a:hover {color: $color;}
	.sub-title a:hover {color:$color;}
	.entry-content a:visited,.comment-content a:visited {color:$color;}
	button, input[type='submit'], input[type='button'], input[type='reset'] {background-color:$color !important;}
	.bypostauthor cite span {background-color:$color;}
	.entry-header .entry-title a:hover,
	.entry-title:not(.overlay-title) a:hover{color:$color}
	.archive-header {border-left-color:$color;}
	.main-navigation .current-menu-item > a,
	.main-navigation .current-menu-ancestor > a,
	.main-navigation .current_page_item > a,
	.main-navigation .current_page_ancestor > a {background-color: $color; color:#ffffff;}
	.main-navigation li a:hover {background-color: $color !important;color:#ffffff !important;}
	.nav-menu a.selected-menu-item{background-color: $color !important; color:#ffffff !important;}
	.mag-widget-title-tab,
	.wrapper-widget-area-footer .widget-title:after, .ir-arriba:hover{
		background-color: $color;
	}
	.widget-area .widget a:hover {
		color: $color !important;
	}
	footer[role='contentinfo'] a:hover {
		color: $color;
	}
	.entry-meta a:hover {
	color: $color;
	}
	.format-status .entry-header header a:hover {
		color: $color;
	}
	.comments-area article header a:hover {
		color: $color;
	}
	a.comment-reply-link:hover,
	a.comment-edit-link:hover {
		color: $color;
	}
	.currenttext, .paginacion a:hover {background-color:$color;}
	.aside{border-left-color:$color !important;}
	blockquote{border-left-color:$color;}
	.logo-header-wrapper{background-color:$color;}
	h3.cabeceras-fp {border-bottom-color:$color;}
	.encabezados-front-page {background-color:$color;}
	.icono-caja-destacados {color: $color;}
	.enlace-caja-destacados:hover {background-color: $color;}
	.sticky-post-label{background-color: $color;}
	.menu-line-top, .menu-line-bottom{border-color:$color;}
	#wp-calendar a{font-weight:bold; color: $color;}
	.widget-title-tab a.rsswidget{color:#fff;}";

	wp_add_inline_style( 'bmag-widgets-fp-styles', $css );

}
