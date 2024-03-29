<?php
/**
 * Limeasyblog Theme Customizer
 *
 * @package limeasyblog
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function limeasyblog_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'limeasyblog_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'limeasyblog_customize_partial_blogdescription',
			)
		);
	}

	// theme settings section
	$wp_customize->add_section(
		'limeasyblog_theme_setings_section',
		array(
			'title' => __( 'Theme settings', 'limeasyblog' ),
			'priority' => 30,
		)
	);

	$wp_customize->add_setting(
		'limeasyblog_theme_setting_design',
		array(
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'limeasyblog_sanitize_select',
			'default' => 'grand-retro',
		)
	);

	$wp_customize->add_control(
		'limeasyblog_theme_setting_design',
		array(
			'type' => 'select',
			'section' => 'limeasyblog_theme_setings_section',
			'label' => __( 'Design', 'limeasyblog' ),
			'description' => __( 'Select theme design', 'limeasyblog' ),
			'choices' => array(
				'grand-retro' => 'Grand Retro',
				'blu-retro' => 'BluRetro',
			),
		)
	);

	// footer settings section
	$wp_customize->add_section(
		'limeasyblog_footer_setings_section',
		array(
			'title' => __( 'Footer settings', 'limeasyblog' ),
			'priority' => 30,
		)
	);

	// columns number
	$wp_customize->add_setting(
		'limeasyblog_theme_footer_columns',
		array(
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'limeasyblog_sanitize_select',
			'default' => 4,
		)
	);

	$wp_customize->add_control(
		'limeasyblog_theme_footer_columns',
		array(
			'type' => 'select',
			'section' => 'limeasyblog_footer_setings_section',
			'label' => __( 'Columns', 'limeasyblog' ),
			'description' => __( 'Select the footer columns number', 'limeasyblog' ),
			'choices' => array(
				1 => 1,
				2 => 2,
				3 => 3,
				4 => 4,
				6 => 6,
				12 => 12
			),
		)
	);

	// columns width
	$wp_customize->add_setting(
		'limeasyblog_theme_footer_columns_width',
		array(
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'limeasyblog_sanitize_select',
			'default' => 'auto',
		)
	);

	$wp_customize->add_control(
		'limeasyblog_theme_footer_columns_width',
		array(
			'type' => 'select',
			'section' => 'limeasyblog_footer_setings_section',
			'label' => __( 'Columns', 'limeasyblog' ),
			'description' => __( 'Select the footer columns number', 'limeasyblog' ),
			'choices' => array(
				'auto' => 'Auto',
				1 => 1,
				2 => 2,
				3 => 3,
				4 => 4,
				6 => 6,
				12 => 12
			),
		)
	);
}
add_action( 'customize_register', 'limeasyblog_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function limeasyblog_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function limeasyblog_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Sanitize select.
 *
 * @return mixed
 */
function limeasyblog_sanitize_select( $input, $setting ) {

	// Ensure input is a slug.
	$input = sanitize_key( $input );

	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;

	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize checkbox.
 *
 * @return bool
 */
function limeasyblog_sanitize_checkbox( $input ){

	return isset( $input ) && $input == '1' ? true : false;
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function limeasyblog_customize_preview_js() {
	wp_enqueue_script( 'limeasyblog-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), LIMEASYBLOG_VERSION, true );
}
add_action( 'customize_preview_init', 'limeasyblog_customize_preview_js' );
