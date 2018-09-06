<?php
/*This file is part of daily-news, daily-magazine child theme.

All functions of this file will be loaded before of parent theme functions.
Learn more at https://codex.wordpress.org/Child_Themes.

Note: this function loads the parent stylesheet before, then child theme stylesheet
(leave it in place unless you know what you are doing.)
*/

function daily_news_enqueue_child_styles() {
    $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
    $parent_style = 'daily-magazine-style';

    $fonts_url = 'https://fonts.googleapis.com/css?family=Andada';
    wp_enqueue_style('daily-magazine-google-fonts', $fonts_url, array(), null);
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap' . $min . '.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style(
        'daily-news',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'bootstrap', $parent_style ),
        wp_get_theme()->get('Version') );


}
add_action( 'wp_enqueue_scripts', 'daily_news_enqueue_child_styles' );



/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function daily_magazine_customize_register($wp_customize) {

    $wp_customize->get_setting( 'top_header_background_color')->default = '#f54336';
    $wp_customize->get_control( 'archive_layout')->choices = array(
        'archive-layout-full' => esc_html__( 'Full', 'daily-magazine' ),
        'archive-layout-grid' => esc_html__( 'Grid', 'daily-magazine' ),
        'archive-layout-list' => esc_html__( 'List', 'daily-magazine' ),

    );

}
add_action('customize_register', 'daily_magazine_customize_register', 9999);

/**
 *
 * @since Daily Magazine 1.0.0
 *
 * @param null
 * @return null
 *
 */
function elegant_magazine_page_layout_blocks( $archive_layout='full' ) {

    $archive_layout = elegant_magazine_get_option('archive_layout');

    switch ($archive_layout) {
        case "archive-layout-grid":
            elegant_magazine_get_block('grid');
            break;
        case "archive-layout-list":
            elegant_magazine_get_block('lists');
            break;
        case "archive-layout-full":
            elegant_magazine_get_block('full');;
            break;
        default:
            elegant_magazine_get_block('full');;
    }
}


