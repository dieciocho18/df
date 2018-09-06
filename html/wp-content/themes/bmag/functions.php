<?php
/**
 * BMag constants, functions and definitions
 *
 * @package BMag
 */

$bmag_theme = wp_get_theme();
define( 'BMAG_NAME', $bmag_theme->get( 'Name' ) );
define( 'BMAG_VERSION', $bmag_theme->get( 'Version' ) );
define( 'BMAG_THEME_URI', $bmag_theme->get( 'ThemeURI' ) );
define( 'BMAG_AUTHOR_URI', $bmag_theme->get( 'AuthorURI' ) );
define( 'BMAG_TEMPLATE_PARTS', 'template-parts/' );

// Set up the content width value based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) {
	$content_width = 800;
}

/**
 * BMag setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * BMag supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links, custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since BMag 1.0
 */

add_action( 'after_setup_theme', 'bmag_setup' );
function bmag_setup() {
	/*
	 * Makes BMag available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on BMag, use a find and replace
	 * to change 'bmag' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'bmag', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary'  => __( 'Primary Menu', 'bmag' ),
		'social-1' => __( 'Social Menu (Top bar)', 'bmag' ),
	) );

	/*
	 * This theme supports custom background color and image,
	 * and here we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'ffffff',
	) );

	// Custom logo.
	add_theme_support( 'custom-logo', array(
		'height'      => 90,
		'width'       => 300,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	add_theme_support( 'title-tag' );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop.

	// Images size.
	add_image_size( 'bmag-excerpt-thumbnail', 240, 180, true );
	add_image_size( 'bmag-excerpt-thumbnail-rounded', 180, 180, true );
	add_image_size( 'bmag-recent-posts-thumbnail', 80, 60, true );
	add_image_size( 'bmag-thumbnail-4x3', 576, 432, true );
	add_image_size( 'bmag-thumbnail-3x2', 576, 384, true );
	add_image_size( 'bmag-thumbnail-5x3', 576, 346, true );

	// Indicate widget sidebars can use selective refresh in the Customizer.
	add_theme_support( 'customize-selective-refresh-widgets' );
}

/**
 * Add support for a custom header image.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Return the Google font stylesheet URL if available.
 *
 * The use of Open Sans by default is localized. For languages that use
 * characters not supported by the font, the font can be disabled.
 *
 * @since BMag 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function bmag_get_font_url() {
	$font_url = '';

	/**
	 * Translators: If there are characters in your language that are not supported
	 * by Open Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'bmag' ) ) {
		$subsets = 'latin,latin-ext';

		/**
		 * Translators: To add an additional Open Sans character subset specific to your language,
		 * translate this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language.
		 */
		$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'bmag' );

		if ( 'cyrillic' === $subset ) {
			$subsets .= ',cyrillic,cyrillic-ext';
		} elseif ( 'greek' === $subset ) {
			$subsets .= ',greek,greek-ext';
		} elseif ( 'vietnamese' === $subset ) {
			$subsets .= ',vietnamese';
		}

		$fuente = str_replace( ' ', '+', get_theme_mod( 'bmag_fonts', 'Arimo' ) );

		$query_args = array(
			'family' => $fuente . ':400italic,700italic,400,700',
			'subset' => $subsets,
		);

		$font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $font_url;
}

/**
 * Enqueue scripts and styles for front-end.
 *
 * @since BMag 1.0
 */

add_action( 'wp_enqueue_scripts', 'bmag_scripts_styles' );
function bmag_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Adds JavaScript for handling the navigation menu hide-and-show behavior.
	wp_enqueue_script( 'bmag-navigation', get_template_directory_uri() . '/js/navigation.js', array( 'jquery' ), '20140711', true );

	$font_url = bmag_get_font_url();
	if ( ! empty( $font_url ) ) {
		wp_enqueue_style( 'bmag-fonts', esc_url_raw( $font_url ), array(), null );
	}

	// Loads our main stylesheet.
	wp_enqueue_style( 'bmag-style', get_stylesheet_uri(), '', BMAG_VERSION );

	// Mag front page sytle.
	wp_enqueue_style( 'bmag-widgets-fp-styles', get_template_directory_uri() . '/css/widgets-fp-styles.css', '', BMAG_VERSION );

	// Loads the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'bmag-ie', get_template_directory_uri() . '/css/ie.css', array( 'bmag-style' ), '20121010' );
	$wp_styles->add_data( 'bmag-ie', 'conditional', 'lt IE 9' );

	// Dashicons.
	wp_enqueue_style( 'dashicons' );

	// Font Awesome.
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome-4.7.0/css/font-awesome.min.css' );

	// BMag scripts.
	wp_enqueue_script( 'bmag-scripts-functions', get_template_directory_uri() . '/js/bmag-scripts-functions.js', array( 'jquery' ), BMAG_VERSION, true );

}

/**
 * Add preconnect for Google Fonts.
 *
 * @since BMag 1.0.0
 *
 * @param  array  $urls URLs to print for resource hints.
 * @param  string $relation_type The relation type the URLs are printed.
 * @return array URLs to print for resource hints.
 */
function bmag_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'bmag-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '>=' ) ) {
			$urls[] = array(
				'href' => 'https://fonts.gstatic.com',
				'crossorigin',
			);
		} else {
			$urls[] = 'https://fonts.gstatic.com';
		}
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'bmag_resource_hints', 10, 2 );

/**
 * Filter TinyMCE CSS path to include Google Fonts.
 *
 * Adds additional stylesheets to the TinyMCE editor if needed.
 *
 * @uses bmag_get_font_url() To get the Google Font stylesheet URL.
 *
 * @since BMag 1.0
 *
 * @param string $mce_css CSS path to load in TinyMCE.
 * @return string Filtered CSS path.
 */

add_filter( 'mce_css', 'bmag_mce_css' );
function bmag_mce_css( $mce_css ) {
	$font_url = bmag_get_font_url();

	if ( empty( $font_url ) ) {
		return $mce_css;
	}

	if ( ! empty( $mce_css ) ) {
		$mce_css .= ',';
	}

	$mce_css .= esc_url_raw( str_replace( ',', '%2C', $font_url ) );

	return $mce_css;
}

/**
 * Filter the page menu arguments.
 *
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since BMag 1.0
 */
add_filter( 'wp_page_menu_args', 'bmag_page_menu_args' );
function bmag_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) ) {
		$args['show_home'] = true;
	}

	return $args;
}

/**
 * Register sidebars.
 *
 * Registers our main widget area and the front page widget areas.
 *
 * @since BMag 1.0
 */

add_action( 'widgets_init', 'bmag_widgets_init' );
function bmag_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'bmag' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Appears on posts and pages except Full-width Page Template', 'bmag' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title"><span class="widget-title-tab">',
		'after_title'   => '</span></h3>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Site Header', 'bmag' ),
		'id'            => 'bmag-sidebar-header',
		'description'   => esc_html__( 'Appears inside the site header, at right. You can use it to show Google AdSense ad.', 'bmag' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<p>',
		'after_title'   => '</p>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Mag Front Page: Below Header', 'bmag' ),
		'id'            => 'bmag-sidebar-fp-mag-below-header',
		'description'   => esc_html__( 'Appears on (BMag) Magazine Front Page template only. Use the "(BMag 5 Posts - Style 1" widget in this widget area.', 'bmag' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title"><span class="mag-widget-title-tab">',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Mag Front Page: Main Content', 'bmag' ),
		'id'            => 'bmag-sidebar-fp-mag-content',
		'description'   => esc_html__( 'Appears on (BMag) Magazine Front Page template only. Use the (BMag) widgets to customize the content of your site front page.', 'bmag' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title"><span class="mag-widget-title-tab">',
		'after_title'   => '</span></h2>',
	) );

	register_sidebar(array(
		'name'          => esc_html__( 'Below Post Title', 'bmag' ),
		'description'   => esc_html__( 'Appears below posts title', 'bmag' ),
		'id'            => 'bmag-sidebar-below-title',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<p class="widget-title">',
		'after_title'   => '</p>',
	));

	register_sidebar(array(
		'name'          => esc_html__( 'Below Post Content', 'bmag' ),
		'description'   => esc_html__( 'Appears at the end of posts content', 'bmag' ),
		'id'            => 'bmag-sidebar-below-content',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<p class="widget-title">',
		'after_title'   => '</p>',
	));

	register_sidebar(array(
		'name'          => esc_html__( 'Footer 1', 'bmag' ),
		'description'   => esc_html__( 'Appears in footer', 'bmag' ),
		'id'            => 'bmag-sidebar-footer-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));

	register_sidebar(array(
		'name'          => esc_html__( 'Footer 2', 'bmag' ),
		'description'   => esc_html__( 'Appears in footer', 'bmag' ),
		'id'            => 'bmag-sidebar-footer-2',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));

	register_sidebar(array(
		'name'          => esc_html__( 'Footer 3', 'bmag' ),
		'description'   => esc_html__( 'Appears in footer', 'bmag' ),
		'id'            => 'bmag-sidebar-footer-3',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));

	register_sidebar(array(
		'name'          => esc_html__( 'Footer 4', 'bmag' ),
		'description'   => esc_html__( 'Appears in footer', 'bmag' ),
		'id'            => 'bmag-sidebar-footer-4',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
}

if ( ! function_exists( 'bmag_the_posts_pagination' ) ) {
	function bmag_the_posts_pagination() {

		echo '<div class="posts-pagination-wrapper">';
		the_posts_pagination(array(
			'mid_size'  => 2,
			'prev_text' => __( '&laquo; Previous', 'bmag' ),
			'next_text' => __( 'Next &raquo;', 'bmag' ),
		));
		echo '</div>';

	}
}

if ( ! function_exists( 'bmag_comment' ) ) :
	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own bmag_comment(), and that function will be used instead.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since BMag 1.0
	 */
	function bmag_comment( $comment, $args, $depth ) {
		// $GLOBALS[ 'comment' ] = $comment.
		switch ( $comment->comment_type ) :
			case 'pingback':
			case 'trackback':
				// Display trackbacks differently than normal comments.
		?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
			<p><?php esc_attr_e( 'Pingback:', 'bmag' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'bmag' ), '<span class="edit-link">', '</span>' ); ?></p>
		<?php
				break;
			default:
				// Proceed with normal comments.
				global $post;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment">
				<header class="comment-meta comment-author vcard">
					<?php
						echo get_avatar( $comment, 44 );
						printf( '<cite><b class="fn">%1$s</b> %2$s</cite>',
							get_comment_author_link(),
							// If current post author is also comment author, make it known visually.
							( $comment->user_id === $post->post_author ) ? '<span>' . esc_html( __( 'Post author', 'bmag' ) ) . '</span>' : ''
						);
						printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
							esc_url( get_comment_link( $comment->comment_ID ) ),
							get_comment_time( 'c' ),
							/* translators: 1: date, 2: time */
							sprintf( __( '%1$s at %2$s', 'bmag' ), get_comment_date(), get_comment_time() )
						);
					?>
				</header><!-- .comment-meta -->

				<?php if ( '0' === $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php esc_attr_e( 'Your comment is awaiting moderation.', 'bmag' ); ?></p>
				<?php endif; ?>

				<section class="comment-content comment">
					<?php comment_text(); ?>
					<?php edit_comment_link( __( 'Edit', 'bmag' ), '<p class="edit-link">', '</p>' ); ?>
				</section><!-- .comment-content -->

				<div class="reply">
					<?php
					comment_reply_link( array_merge( $args,
						array(
							'reply_text' => __( 'Reply', 'bmag' ),
							'after'      => ' <span>&darr;</span>',
							'depth'      => $depth,
							'max_depth'  => $args['max_depth'],
						)
					));
					?>
				</div><!-- .reply -->
			</article><!-- #comment-## -->
		<?php
				break;
		endswitch; // end comment_type check.
	}
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Extends the default WordPress body class to denote:
 * 1. Using a full-width layout, when no active widgets in the sidebar
 *    or full-width template.
 * 2. Front Page template: thumbnail in use and number of sidebars for
 *    widget areas.
 * 3. White or empty background color to change the layout and spacing.
 * 4. Custom fonts enabled.
 * 5. Single or multiple authors.
 *
 * @since BMag 1.0
 *
 * @param array $classes Existing class values.
 * @return array Filtered class values.
 */
function bmag_body_class( $classes ) {
	$background_color = get_background_color();
	$background_image = get_background_image();

	if ( ! is_active_sidebar( 'sidebar-1' ) || is_page_template( 'page-templates/full-width.php' ) ) {
		$classes[] = 'full-width';
	}

	if ( empty( $background_image ) ) {
		if ( empty( $background_color ) ) {
			$classes[] = 'custom-background-empty';
		} elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) ) {
			$classes[] = 'custom-background-white';
		}
	}

	// Enable custom font class only if the font CSS is queued to load.
	if ( wp_style_is( 'bmag-fonts', 'queue' ) ) {
		$classes[] = 'custom-font-enabled';
	}

	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	if ( get_header_image() ) {
		$classes[] = 'has-header-image';
	}

	return $classes;
}
add_filter( 'body_class', 'bmag_body_class' );

/**
 * Adjust content width in certain contexts.
 *
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since BMag 1.0
 */
function bmag_content_width() {
	if ( is_page_template( 'page-templates/full-width.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) ) {
		global $content_width;
		$content_width = 960;
	}
}
add_action( 'template_redirect', 'bmag_content_width' );

/**
 * Modifies tag cloud widget arguments to display all tags in the same font size
 * and use list format for better accessibility.
 *
 * @since BMag 1.1.7
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array The filtered arguments for tag cloud widget.
 */
if ( ! function_exists( 'bmag_widget_tag_cloud_args' ) ) :
	function bmag_widget_tag_cloud_args( $args ) {
		$args['largest']  = 1;
		$args['smallest'] = 1;
		$args['unit']     = 'em';
		$args['format']   = 'flat';

		return $args;
	}
endif;
add_filter( 'widget_tag_cloud_args', 'bmag_widget_tag_cloud_args' );

/**
 * Excerpt
 */

// Excerpt length.
add_filter( 'excerpt_length', 'bmag_excerpt_length', 999 );
function bmag_excerpt_length( $length ) {
	return 30;
}

// Read more....
add_filter( 'excerpt_more', 'bmag_excerpt_more' );
function bmag_excerpt_more( $more ) {
	global $post;
	return '... <a class="read-more-link" href="' . esc_url( get_permalink( $post->ID ) ) . '">' . __( 'Read more', 'bmag' ) . ' &raquo;</a>';
}

/**
 * hAtom.
 */

// hAtom author.
function bmag_entry_author( $display = 1 ) {

	$author     = get_the_author();
	$author_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );

	$author_string = "<span class='author vcard'><a class='fn' rel='author' href='$author_url'>$author</a></span>";

	if ( 1 !== $display ) {
		return $author_string;
	}

	echo wp_kses_post( $author_string );

}

// hAtom date.
function bmag_entry_date( $display = 1 ) {

	$published_long = esc_attr( get_the_date( 'c' ) );
	$published      = get_the_date();
	$updated_long   = esc_attr( get_the_modified_date( 'c' ) );
	$updated        = get_the_modified_date();

	$time_string = "<time class='entry-date published' datetime='$published_long'>$published</time> <time class='updated' style='display:none;' datetime='$updated_long'>$updated</time>";

	if ( 1 !== $display ) {
		return $time_string;
	}

	echo $time_string;

}

function bmag_entry_info_fp() {

	$sep1          = '&nbsp;';
	$sep2          = '&nbsp;&nbsp;&nbsp;';
	$post_author   = '<i class="fa fa-user"></i>' . $sep1 . bmag_entry_author( 0 );
	$post_date     = $sep2 . '<i class="fa fa-calendar-o"></i>' . $sep1 . bmag_entry_date( 0 );
	$comments_icon = '<i class="fa fa-comment"></i>';

	echo $post_author . $post_date;

	if ( comments_open() ) {
		comments_popup_link( '', $sep2 . $comments_icon . ' 1', $sep2 . $comments_icon . ' %' );
	}
}

if ( is_admin() ) {
	add_filter( 'admin_post_thumbnail_html', 'bmag_featured_image_notice', 10, 2 );
}
function bmag_featured_image_notice( $content, $post_id ) {

	$notice = __( '<strong>BMag Notice:</strong> In order for images to be displayed correctly in widgets, featured images must have a minimum size of 576x432 pixels.', 'bmag' );

	return $content .= $notice;
}

add_action( 'customize_controls_enqueue_scripts', 'bmag_customizer_styles' );
function bmag_customizer_styles() {
	wp_enqueue_style( 'bmag-customizer-styles', get_stylesheet_directory_uri() . '/css/customizer-styles.css' );
}

/**
 * Calculates if a light or dark color would contrast more with the provided color.
 *
 * @since 1.0.0
 *
 * @param string $color A color in hex format.
 * @param string $light_color Light color that will be return.
 * @param string $dark_color Dark color that will be return.
 * @return string The hex code for the most contrasting color: dark or light.
 */
function bmag_color_contrast( $color, $light_color = '#ffffff', $dark_color = '#333333' ) {

	$hexcolor = str_replace( '#', '', $color );
	$red      = hexdec( substr( $hexcolor, 0, 2 ) );
	$green    = hexdec( substr( $hexcolor, 2, 2 ) );
	$blue     = hexdec( substr( $hexcolor, 4, 2 ) );

	$luminosity = ( ( $red * 0.2126 ) + ( $green * 0.7152 ) + ( $blue * 0.0722 ) );

	return ( $luminosity > 162 ) ? $dark_color : $light_color;
}

// Includes.
require_once get_template_directory() . '/inc/customization.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/widget-5-posts-st1.php';
require_once get_template_directory() . '/inc/widget-5-posts-st2.php';
require_once get_template_directory() . '/inc/widget-3-posts.php';
require_once get_template_directory() . '/inc/widget-cols-cats.php';
