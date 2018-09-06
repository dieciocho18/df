<?php
/**
 * BMag Customizer.
 *
 * @package BMag
 */

// Enqueue Javascript postMessage handlers for the Customizer.
add_action( 'customize_preview_init', 'bmag_customize_preview_js' );
function bmag_customize_preview_js() {
	wp_enqueue_script( 'bmag-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20130301', true );
}

/**
 * Sanitize functions.
 */

function bmag_sanitize_text( $input ) {
	return wp_kses_post( force_balance_tags( $input ) );
}

function bmag_sanitize_checkbox( $input ) {
	if ( 1 == $input ) {
		return 1;
	} else {
		return '';
	}
}

function bmag_sanitize_social_icons_color( $input ) {
	$valid = array(
		'white'          => 'White',
		'original_color' => 'Original color',
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	} else {
		return '';
	}
}

function bmag_sanitize_sidebar_position( $input ) {
	$valid = array(
		'izquierda' => 'Left',
		'derecha'   => 'Right',
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	} else {
		return '';
	}
}

function bmag_sanitize_menu_line( $input ) {
	$valid = array(
		'bottom' => 'Bottom',
		'top'    => 'Top',
		'none'   => 'None',
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	} else {
		return '';
	}
}

function bmag_sanitize_featured_image_in_post( $input ) {
	$valid = array(
		'not_show'         => 'Not show',
		'above_post_title' => 'Above post title',
		'below_post_title' => 'Below post title',
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	} else {
		return '';
	}
}

function bmag_sanitize_fonts( $input ) {
	$valid = array(
		'Arimo'     => 'Arimo',
		'Bitter'    => 'Bitter',
		'Open Sans' => 'Open Sans',
		'Poppins'   => 'Poppins',
		'Raleway'   => 'Raleway',
		'Ubuntu'    => 'Ubuntu',
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	} else {
		return '';
	}
}

function bmag_sanitize_theme_color( $input ) {
	$valid = array(
		'#0199DB' => 'Blue',
		'#77AD0A' => 'Green',
		'#F17A07' => 'Orange',
		'#F882B3' => 'Pink',
		'#DD4040' => 'Red',
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	} else {
		return '';
	}
}

/** -------------------------------
 * BMAG CUSTOMIZER
**------------------------------*/

add_action( 'customize_register', 'bmag_theme_customizer' );

function bmag_theme_customizer( $wp_customize ) {

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	class Bmag_Customize_Heading_Control extends WP_Customize_Control {

		public $type  = 'heading_1';
		public $color = 'blue';

		public function render_content() {

			if ( ! empty( $this->label ) ) {
				if ( $this->type == 'heading_1' ) {

					echo '<h3 class="bmag-heading-1-' . esc_attr( $this->color ) . '">' . esc_html( $this->label ) . '<h3>';

				} elseif ( $this->type == 'heading_2' ) { ?>

					<h3 class="bmag-heading-2">
						<?php echo esc_html( $this->label ); ?>
					</h3>
				<?php
				}
			}

			if ( ! empty( $this->description ) ) {
				?>
				<p class="customize-control-description"><?php echo wp_kses_post( $this->description ); ?></p>
				<?php
			}

		} // render_content.

	} // Class Bmag_Customize_Heading_Control.

	class Bmag_Text_Control extends WP_Customize_Control {

		public $control_text = '';

		public function render_content() {

			if ( ! empty( $this->label ) ) {
				?>
				<span class="customize-control-title">
					<?php echo esc_html( $this->label ); ?>
				</span>
				<?php
			}

			if ( ! empty( $this->description ) ) {
				?>
				<span class="customize-control-description">
					<?php echo wp_kses_post( $this->description ); ?>
				</span>
				<?php
			}

			if ( ! empty( $this->control_text ) ) {
				?>
				<span class="bmag-text-control-content">
					<?php echo wp_kses_post( $this->control_text ); ?>
				</span>
				<?php
			}

		}

	}

	/**
	 * GENERAL SETTINGS PANEL
	 * Sections: Site indentity, Colors, Fonts, Background image.
	 */

	$wp_customize->add_panel( 'bmag_panel_general_settings',
		array(
			'title'    => __( 'General Settings', 'bmag' ),
			'priority' => 9,
		)
	);

	/**
	 * Static Front Page
	 */
	$wp_customize->get_section( 'static_front_page' )->panel    = 'bmag_panel_general_settings';
	$wp_customize->get_section( 'static_front_page' )->priority = 1;

	/**
	 * Site Logo/Icon/Title/Tagline
	 */

	$wp_customize->get_section( 'title_tagline' )->panel    = 'bmag_panel_general_settings';
	$wp_customize->get_section( 'title_tagline' )->title    = __( 'Site Logo/Icon/Title/Tagline', 'bmag' );
	$wp_customize->get_section( 'title_tagline' )->priority = 10;

	/**
	 * Colors
	 */

	$wp_customize->get_section( 'colors' )->panel    = 'bmag_panel_general_settings';
	$wp_customize->get_section( 'colors' )->priority = 11;

	// Theme color.
	$wp_customize->add_setting( 'bmag_color_tema', array(
		'default'           => '#DD4040',
		'sanitize_callback' => 'bmag_sanitize_theme_color',
	) );
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'bmag_color_tema',
			array(
				'label'    => __( 'Theme Primary Color', 'bmag' ),
				'section'  => 'colors',
				'settings' => 'bmag_color_tema',
				'type'     => 'select',
				'priority' => 1,
				'choices'  => array(
					'#0199DB' => __( 'Blue', 'bmag' ),
					'#77AD0A' => __( 'Green', 'bmag' ),
					'#F17A07' => __( 'Orange', 'bmag' ),
					'#F882B3' => __( 'Pink', 'bmag' ),
					'#DD4040' => __( 'Red', 'bmag' ),
				),
			)
		)
	);

	// Color excerpt title.
	$wp_customize->add_setting( 'bmag_color_excerpt_title', array(
		'default'           => '',
		'sanitize_callback' => 'bmag_sanitize_checkbox',
	) );
	$wp_customize->add_control('bmag_color_excerpt_title', array(
		'type'     => 'checkbox',
		'label'    => __( 'Apply to entry title in excerpts', 'bmag' ),
		'section'  => 'colors',
		'priority' => 2,
	));

	// Excerpt border.
	$wp_customize->add_setting( 'bmag_color_excerpt_border', array(
		'default'           => 1,
		'sanitize_callback' => 'bmag_sanitize_checkbox',
	));
	$wp_customize->add_control('bmag_color_excerpt_border', array(
		'type'     => 'checkbox',
		'label'    => __( 'Apply to the left border of extracts', 'bmag' ),
		'section'  => 'colors',
		'priority' => 3,
	));

	// Widgets title color.
	$wp_customize->add_setting( 'bmag_color_widget_title', array(
		'default'           => '',
		'sanitize_callback' => 'bmag_sanitize_checkbox',
	));
	$wp_customize->add_control('bmag_color_widget_title', array(
		'type'        => 'checkbox',
		'label'       => __( 'Apply to widget title', 'bmag' ),
		'section'     => 'colors',
		'description' => __( '( Uncheck: Light gray )', 'bmag' ),
		'priority'    => 3,
	));

	/**
	 * Fonts
	 */

	$wp_customize->add_section('bmag_fonts', array(
		'panel'    => 'bmag_panel_general_settings',
		'title'    => __( 'Fonts', 'bmag' ),
		'priority' => 12,
	));
	$wp_customize->add_setting( 'bmag_fonts', array(
		'default'           => 'Arimo',
		'sanitize_callback' => 'bmag_sanitize_fonts',
	));
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'bmag_fonts',
			array(
				'label'    => __( 'Select font', 'bmag' ),
				'section'  => 'bmag_fonts',
				'settings' => 'bmag_fonts',
				'type'     => 'select',
				'choices'  => array(
					'Arimo'     => 'Arimo',
					'Bitter'    => 'Bitter',
					'Open Sans' => 'Open Sans',
					'Poppins'   => 'Poppins',
					'Raleway'   => 'Raleway',
					'Ubuntu'    => 'Ubuntu',
				),
			)
		)
	);

	/**
	 * Header image
	 */

	$wp_customize->get_section( 'header_image' )->panel    = 'bmag_panel_general_settings';
	$wp_customize->get_section( 'header_image' )->priority = 13;

	$header_image_description = $wp_customize->get_section( 'header_image' )->description;
	$add_description          = ' <strong>' . __( 'if you set Header Image, it will replace logo set in Site Logo option and Header widget area.', 'bmag' ) . '</strong>';
	$wp_customize->get_section( 'header_image' )->description = $header_image_description . $add_description;

	/**
	 * Background image
	 */

	$wp_customize->get_section( 'background_image' )->panel    = 'bmag_panel_general_settings';
	$wp_customize->get_section( 'background_image' )->priority = 14;

	/**
	 * PANEL: FOOTER, CONTENT AND FOOTER
	 * Sections: Top bar, Main Menu, Content, Sidebar, Footer texts
	 */

	$wp_customize->add_panel( 'bmag_panel_header_content_footer', array(
		'title'    => __( 'Header, Content and Footer', 'bmag' ),
		'priority' => 10,
	));

	/**
	 * Top bar
	 */

	$wp_customize->add_section('bmag_top_bar', array(
		'panel'    => 'bmag_panel_header_content_footer',
		'title'    => __( 'Top bar', 'bmag' ),
		'priority' => 10,
	));

	// Display top bar.
	$wp_customize->add_setting('bmag_display_top_bar', array(
		'default'           => 1,
		'sanitize_callback' => 'bmag_sanitize_checkbox',
	));
	$wp_customize->add_control('bmag_display_top_bar', array(
		'type'    => 'checkbox',
		'label'   => __( 'Display top bar', 'bmag' ),
		'section' => 'bmag_top_bar',
	));

	// Top bar color.
	$wp_customize->add_setting( 'bmag_top_bar_black', array(
		'default'           => '',
		'sanitize_callback' => 'bmag_sanitize_checkbox',
	));
	$wp_customize->add_control( 'bmag_top_bar_black', array(
		'type'    => 'checkbox',
		'label'   => __( 'Top bar black (Uncheck: Theme color)', 'bmag' ),
		'section' => 'bmag_top_bar',
	));


	// Word MENU.
	$wp_customize->add_setting( 'bmag_mostrar_menu_junto_icono', array(
		'default'           => '',
		'sanitize_callback' => 'bmag_sanitize_checkbox',
	));
	$wp_customize->add_control('bmag_mostrar_menu_junto_icono', array(
		'type'    => 'checkbox',
		'label'   => __( 'Show the word menu next to the icon menu on mobile devices.', 'bmag' ),
		'section' => 'bmag_top_bar',
	));

	// Custom text.
	$wp_customize->add_setting( 'bmag_top_bar_custom_text', array(
		'default'           => '',
		'sanitize_callback' => 'bmag_sanitize_text',
	));
	$wp_customize->add_control('bmag_top_bar_custom_text', array(
		'type'    => 'textarea',
		'label'   => __( 'Custom text (HTML allowed)', 'bmag' ),
		'section' => 'bmag_top_bar',
	));

	$wp_customize->add_setting('bmag_social_icons_color', array(
		'default'           => 'white',
		'sanitize_callback' => 'bmag_sanitize_social_icons_color',
	));
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'bmag_social_icons_color',
			array(
				'label'    => __( 'Social icons color', 'bmag' ),
				'section'  => 'bmag_top_bar',
				'settings' => 'bmag_social_icons_color',
				'type'     => 'select',
				'choices'  => array(
					'white'          => _x( 'White', 'Social icons color', 'bmag' ),
					'original_color' => __( 'Original color', 'bmag' ),
				),
			)
		)
	);

	/**
	 * Main menu
	 */
	$wp_customize->add_section('bmag_main_menu', array(
		'panel' => 'bmag_panel_header_content_footer',
		'title' => __( 'Main Menu', 'bmag' ),
	));

	// Black menu.
	$wp_customize->add_setting('bmag_fondo_menu_negro', array(
		'default'           => 1,
		'sanitize_callback' => 'bmag_sanitize_checkbox',
	));
	$wp_customize->add_control('bmag_fondo_menu_negro', array(
		'type'        => 'checkbox',
		'label'       => __( 'Black menu', 'bmag' ),
		'section'     => 'bmag_main_menu',
		'description' => __( '(Uncheck: Light gray)', 'bmag' ),
	));

	// Centered menu.
	$wp_customize->add_setting('bmag_menu_center', array(
		'default'           => '',
		'sanitize_callback' => 'bmag_sanitize_checkbox',
	));
	$wp_customize->add_control('bmag_menu_center', array(
		'type'    => 'checkbox',
		'label'   => __( 'Center menu', 'bmag' ),
		'section' => 'bmag_main_menu',
	));

	// Menu line.
	$wp_customize->add_setting('bmag_menu_line', array(
		'default'           => 'top',
		'sanitize_callback' => 'bmag_sanitize_menu_line',
	));
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'bmag_menu_line',
			array(
				'label'    => __( 'Menu line', 'bmag' ),
				'section'  => 'bmag_main_menu',
				'settings' => 'bmag_menu_line',
				'type'     => 'select',
				'choices'  => array(
					'bottom' => __( 'Bottom', 'bmag' ),
					'top'    => __( 'Top', 'bmag' ),
					'none'   => __( 'None', 'bmag' ),
				),
			)
		)
	);

	/**
	 * Posts
	 */

	$wp_customize->add_section('bmag_entradas', array(
		'panel' => 'bmag_panel_header_content_footer',
		'title' => __( 'Posts and Pages', 'bmag' ),
	));

	$wp_customize->add_setting('bmag_contenido_completo_entradas_pp', array(
		'default'           => '',
		'sanitize_callback' => 'bmag_sanitize_checkbox',
	));
	$wp_customize->add_control('bmag_contenido_completo_entradas_pp', array(
		'type'    => 'checkbox',
		'label'   => __( 'Show full content of the entries in the main page (If the main page is not set as a Magazine.)', 'bmag' ),
		'section' => 'bmag_entradas',
	));

	// Featured image in post.
	$wp_customize->add_setting('bmag_featured_image_in_post', array(
		'default'           => 'not_show',
		'sanitize_callback' => 'bmag_sanitize_featured_image_in_post',
	));
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'bmag_featured_image_in_post',
			array(
				'label'    => __( 'Show featured image in posts', 'bmag' ),
				'section'  => 'bmag_entradas',
				'settings' => 'bmag_featured_image_in_post',
				'type'     => 'select',
				'choices'  => array(
					'not_show'         => __( 'Not show', 'bmag' ),
					'above_post_title' => __( 'Above post title', 'bmag' ),
					'below_post_title' => __( 'Below post title', 'bmag' ),
				),
			)
		)
	);

	$wp_customize->add_setting('bmag_thumbnail_rounded', array(
		'default'           => '',
		'sanitize_callback' => 'bmag_sanitize_checkbox',
	));
	$wp_customize->add_control('bmag_thumbnail_rounded', array(
		'type'    => 'checkbox',
		'label'   => __( "Excerpt's thumbnail image rounded", 'bmag' ),
		'section' => 'bmag_entradas',
	));

	$wp_customize->add_setting( 'bmag_show_meta_in_excerpts', array(
		'default'           => '',
		'sanitize_callback' => 'bmag_sanitize_checkbox',
	));
	$wp_customize->add_control('bmag_show_meta_in_excerpts', array(
		'type'    => 'checkbox',
		'label'   => __( 'Show metadata in excerpts (Author, date and number of comments)', 'bmag' ),
		'section' => 'bmag_entradas',
	));

	$wp_customize->add_setting('bmag_text_justify', array(
		'default'           => '',
		'sanitize_callback' => 'bmag_sanitize_checkbox',
	));
	$wp_customize->add_control('bmag_text_justify', array(
		'type'    => 'checkbox',
		'label'   => __( 'Entry text justified', 'bmag' ),
		'section' => 'bmag_entradas',
	));

	$wp_customize->add_setting( 'bmag_related_posts', array(
		'default'           => '',
		'sanitize_callback' => 'bmag_sanitize_checkbox',
	));
	$wp_customize->add_control('bmag_related_posts', array(
		'type'    => 'checkbox',
		'label'   => __( 'Display related posts at the end of entries (based on tags)', 'bmag' ),
		'section' => 'bmag_entradas',
	));

	$wp_customize->add_setting( 'bmag_related_posts_title', array(
		'default'           => __( 'Related posts...', 'bmag' ),
		'sanitize_callback' => 'bmag_sanitize_text',
	));
	$wp_customize->add_control('bmag_related_posts_title', array(
		'label'   => __( 'Related posts title', 'bmag' ),
		'section' => 'bmag_entradas',
		'type'    => 'text',
	));

	$wp_customize->add_setting('bmag_sticky_post_label', array(
		'default'           => __( 'Featured', 'bmag' ),
		'sanitize_callback' => 'bmag_sanitize_text',
	));
	$wp_customize->add_control('bmag_sticky_post_label', array(
		'label'   => __( 'Label for Sticky Posts', 'bmag' ),
		'section' => 'bmag_entradas',
		'type'    => 'text',
	));

	$wp_customize->add_setting('bmag_show_nav_single', array(
		'default'           => 1,
		'sanitize_callback' => 'bmag_sanitize_checkbox',
	));
	$wp_customize->add_control('bmag_show_nav_single', array(
		'type'    => 'checkbox',
		'label'   => __( 'Show navigation at the end of posts (links to previous and next posts)', 'bmag' ),
		'section' => 'bmag_entradas',
	));

	$wp_customize->add_setting('bmag_back_to_top_button', array(
		'default'           => 1,
		'sanitize_callback' => 'bmag_sanitize_checkbox',
	));
	$wp_customize->add_control('bmag_back_to_top_button', array(
		'type'    => 'checkbox',
		'label'   => __( "Display 'Back to top' button", 'bmag' ),
		'section' => 'bmag_entradas',
	));

	/**
	 * Sidebar
	 */

	$wp_customize->add_section('bmag_sidebar', array(
		'panel' => 'bmag_panel_header_content_footer',
		'title' => __( 'Sidebar', 'bmag' ),
	));

	// Sidebar.
	$wp_customize->add_setting('bmag_sidebar_position', array(
		'default'           => 'derecha',
		'sanitize_callback' => 'bmag_sanitize_sidebar_position',
	));
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'bmag_sidebar_position',
			array(
				'label'    => __( 'Select sidebar position', 'bmag' ),
				'section'  => 'bmag_sidebar',
				'settings' => 'bmag_sidebar_position',
				'type'     => 'radio',
				'choices'  => array(
					'izquierda' => __( 'Left', 'bmag' ),
					'derecha'   => __( 'Right', 'bmag' ),
				),
			)
		)
	);

	/**
	 * Footer texts
	 */
	$wp_customize->add_section('bmag_footer_texts', array(
		'panel' => 'bmag_panel_header_content_footer',
		'title' => __( 'Footer texts', 'bmag' ),
	));

	$wp_customize->add_setting('bmag_footer_text_left', array(
		'default'           => '',
		'sanitize_callback' => 'bmag_sanitize_text',
	));
	$wp_customize->add_control('bmag_footer_text_left', array(
		'label'   => __( 'Footer left text', 'bmag' ),
		'section' => 'bmag_footer_texts',
		'type'    => 'textarea',
	));

	$wp_customize->add_setting('bmag_footer_text_center', array(
		'default'           => '',
		'sanitize_callback' => 'bmag_sanitize_text',
	));
	$wp_customize->add_control('bmag_footer_text_center', array(
		'label'   => __( 'Footer center text', 'bmag' ),
		'section' => 'bmag_footer_texts',
		'type'    => 'textarea',
	));

	$wp_customize->add_setting('bmag_footer_text_right', array(
		'default'           => '',
		'sanitize_callback' => 'bmag_sanitize_text',
	));
	$wp_customize->add_control('bmag_footer_text_right', array(
		'label'   => __( 'Footer right text', 'bmag' ),
		'section' => 'bmag_footer_texts',
		'type'    => 'textarea',
	));

	$wp_customize->add_setting('bmag_hide_credits', array(
		'default'           => '',
		'sanitize_callback' => 'bmag_sanitize_checkbox',
	));
	$wp_customize->add_control('bmag_hide_credits', array(
		'type'    => 'checkbox',
		'label'   => __( 'Hide credits', 'bmag' ),
		'section' => 'bmag_footer_texts',
	));

	/*
	* Firts Steps and links
	*/

	$wp_customize->add_section( 'bmag_first_steps_links', array(
		'title'    => __( 'First Steps and Links', 'bmag' ),
		'priority' => 1,
	));

	/* Links */
	$wp_customize->add_setting( 'bmag_heading_first_step_links', array(
		'default'           => '',
		'sanitize_callback' => 'bmag_sanitize_text',
	));
	$wp_customize->add_control( new Bmag_Customize_Heading_Control(
		$wp_customize,
		'bmag_heading_first_step_links',
		array(
			'type'     => 'heading_1',
			'settings' => 'bmag_heading_first_step_links',
			'section'  => 'bmag_first_steps_links',
			'label'    => __( 'Links', 'bmag' ),
		)
	));

	// Rate/Review.
	$wp_customize->add_setting( 'bmag_rate_button', array( 'sanitize_callback' => 'bmag_sanitize_text' ) );
	$wp_customize->add_control( new Bmag_Text_Control(
		$wp_customize,
		'bmag_rate_button',
		array(
			'settings'     => 'bmag_rate_button',
			'section'      => 'bmag_first_steps_links',
			'control_text' => __( 'Please, if you are happy with the theme, say it on wordpress.org and give BMag a nice review. Thank you', 'bmag' ) . '<a class="gt-customizer-link-button" href="https://wordpress.org/support/theme/bmag/reviews/#new-post" target="_blank">' . __( 'Rate/Review', 'bmag' ) . '</a>',
		)
	));

	// Live demo.
	$wp_customize->add_setting( 'bmag_link_buttons', array( 'sanitize_callback' => 'bmag_sanitize_text' ) );
	$wp_customize->add_control( new Bmag_Text_Control(
		$wp_customize,
		'bmag_link_buttons',
		array(
			'settings'     => 'bmag_link_buttons',
			'section'      => 'bmag_first_steps_links',
			'control_text' => '<a class="gt-customizer-link-button" href="http://demos.galussothemes.com/bmag/" target="_blank">' . __( 'Live Demo', 'bmag' ) . '</a>
			<a class="gt-customizer-link-button" href="https://galussothemes.com/wordpress-themes/bmag-pro/" target="_blank">' . __( 'Pro Version', 'bmag' ) . '</a>',
		)
	));

	/* First steps */
	$wp_customize->add_setting('bmag_heading_first_step', array(
		'default'           => '',
		'sanitize_callback' => 'bmag_sanitize_text',
	));
	$wp_customize->add_control( new Bmag_Customize_Heading_Control(
		$wp_customize,
		'bmag_heading_first_step',
		array(
			'type'     => 'heading_1',
			'settings' => 'bmag_heading_first_step',
			'section'  => 'bmag_first_steps_links',
			'label'    => __( 'First Steps', 'bmag' ),
		)
	));

	$wp_customize->add_setting( 'bmag_first_steps', array( 'sanitize_callback' => 'bmag_sanitize_text' ) );
	$wp_customize->add_control( new Bmag_Text_Control(
		$wp_customize,
		'bmag_first_steps',
		array(
			'settings'     => 'bmag_first_steps',
			'section'      => 'bmag_first_steps_links',
			'label'        => '&#9679; ' . __( 'Setting up Magazine Home Page', 'bmag' ),
			'control_text' => __( '1. Create a new page (with any title, it does not matter)<br/>
2. In right column go to Page Attributes > Template and select (BMag) Magazine Front Page<br/>
3. Click on Publish<br/>
4. Go to Appearance > Customize > General settings > Static Front Page<br/>
5. Select A static page<br/>
6. In Front Page, select the page that you created in the step 1<br/>
7. Save changes', 'bmag' ),
		)
	));

	$wp_customize->add_setting( 'bmag_first_steps_2', array( 'sanitize_callback' => 'bmag_sanitize_text' ) );
	$wp_customize->add_control( new Bmag_Text_Control(
		$wp_customize,
		'bmag_first_steps_2',
		array(
			'settings'     => 'bmag_first_steps_2',
			'section'      => 'bmag_first_steps_links',
			'label'        => '&#9679; ' . __( 'Widgets do not display images correctly', 'bmag' ),
			'control_text' => __( '<p><strong>In order for images to be displayed correctly in widgets, featured images must have a minimum size of 576x432 pixels</strong>.</p><p>If the image thumbnails are not displayed correctly (because BMag is not the first theme used) you will need to regenerate the thumbnails with a free plugin as', 'bmag' ) . ' <a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">Regenerate Thumbnails</a></p>.',
		)
	));
}
