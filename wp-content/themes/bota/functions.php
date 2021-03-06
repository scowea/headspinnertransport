<?php
/**
 * Bota functions and definitions
 *
 * @package Bota
 * @since Bota 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Bota 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 654; /* pixels */

if ( ! function_exists( 'bota_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Bota 1.0
 */
function bota_setup() {

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	require( get_template_directory() . '/inc/tweaks.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Bota, use a find and replace
	 * to change 'bota' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'bota', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'bota' ),
	) );
	
	/**
	 * Add support for post thumbnails
	 */
	add_theme_support('post-thumbnails');
	add_image_size(100, 300, true);
	add_image_size( 'featured', 670, 300, true );

	/**
	 * Add support for the Aside Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', ) );
}
endif; // bota_setup
add_action( 'after_setup_theme', 'bota_setup' );

/**
 * Setup the WordPress core custom background feature.
 *
 * Use add_theme_support to register support for WordPress 3.4+
 * as well as provide backward compatibility for previous versions.
 * Use feature detection of wp_get_theme() which was introduced
 * in WordPress 3.4.
 *
 * Hooks into the after_setup_theme action.
 *
 * @since Bota 1.0
 */
function bota_register_custom_background() {
	$args = array(
		'default-color' => 'EEE',
	);

	$args = apply_filters( 'bota_custom_background_args', $args );

	if ( function_exists( 'wp_get_theme' ) ) {
		add_theme_support( 'custom-background', $args );
	} else {
		define( 'BACKGROUND_COLOR', $args['default-color'] );
		define( 'BACKGROUND_IMAGE', $args['default-image'] );
		add_theme_support( 'custom-background', $args );
	}
}
add_action( 'after_setup_theme', 'bota_register_custom_background' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Bota 1.0
 */
function bota_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Primary Sidebar', 'bota' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Secondary Sidebar', 'bota' ),
		'id' => 'sidebar-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
	
	register_sidebar(array(
			'name' => 'Left Footer Column',
			'id'   => 'left_column',
			'description'   => 'Widget area for footer left column',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>'
		));
		register_sidebar(array(
			'name' => 'Center  Footer Column',
			'id'   => 'center_column',
			'description'   => 'Widget area for footer center column',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>'
		));
		register_sidebar(array(
			'name' => 'Right Footer Column',
			'id'   => 'right_column',
			'description'   => 'Widget area for footer right column',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>'
		));

}
add_action( 'widgets_init', 'bota_widgets_init' );


/**
	 * Customizer additions
	 */
	require( get_template_directory() . '/inc/customizer.php' );



/**
 * Enqueue scripts and styles
 */
function bota_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );
	
	if (!is_admin()) {
	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
	
	if (!is_admin()) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'bota_scripts' );

/**
 * Implement the Custom Header feature
 */
require( get_template_directory() . '/inc/custom-header.php' );


/**
 * Implement home slider
 */

function bota_add_scripts() {
	if (!is_admin()) {
    wp_enqueue_script('flexslider', get_stylesheet_directory_uri('stylesheet_directory').'/js/jquery.flexslider-min.js', array('jquery'));
    wp_enqueue_script('flexslider-init', get_stylesheet_directory_uri('stylesheet_directory').'/js/flexslider-init.js', array('jquery', 'flexslider'));
	}
}
add_action('wp_enqueue_scripts', 'bota_add_scripts');

function bota_add_styles() {
    wp_enqueue_style('flexslider', get_stylesheet_directory_uri('stylesheet_directory').'/js/flexslider.css');
}
add_action('wp_enqueue_scripts', 'bota_add_styles');

add_theme_support('post-thumbnails');
add_image_size(100, 300, true);

/**
 * Implement the Custom Logo feature
 */
function bota_theme_customizer( $wp_customize ) {
   
   $wp_customize->add_section( 'bota_logo_section' , array(
    'title'       => __( 'Logo', 'bota' ),
    'priority'    => 30,
    'description' => 'Upload a logo to replace the default site name and description in the header',
) );

   $wp_customize->add_setting( 'bota_logo' );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bota_logo', array(
    'label'    => __( 'Logo', 'bota' ),
    'section'  => 'bota_logo_section',
    'settings' => 'bota_logo',
) ) );


}
add_action('customize_register', 'bota_theme_customizer');


/**
 * Adds the individual section for featured text box top
 */
function bota_customizer( $wp_customize ) {
    $wp_customize->add_section(
        'featured_section_top',
        array(
            'title' => 'Featured Text Area',
            'description' => 'This is a settings section to change the homepage featured text area.',
            'priority' => 150,
        )
    );
	
	$wp_customize->add_setting(
    'featured_textbox',
    array(
        'default' => 'Default featured text',
		'sanitize_callback' => 'bota_sanitize_text',
    )
);

$wp_customize->add_control(
    'featured_textbox',
    array(
        'label' => 'Featured Header text',
        'section' => 'featured_section_top',
        'type' => 'text',
    )
);
$wp_customize->add_setting(
		'featured_textbox_text_head',
		array(
			'default' => 'Default featured text',
			'sanitize_callback' => 'bota_sanitize_text',
		)
	);
	
	$wp_customize->add_control(
		'featured_textbox_text_head',
		array(
			'label' => 'Featured text',
			'section' => 'featured_section_top',
			'type' => 'text',
		)
	);
}
add_action( 'customize_register', 'bota_customizer' );


/**
 * Adds the individual section for featured text box 1
 */
function featured_text_one_customizer( $wp_customize ) {
    $wp_customize->add_section(
        'featured_section_one',
        array(
            'title' => 'Featured Text Box 1',
            'description' => 'This is a settings section to change the homepage featured text area.',
            'priority' => 155,
        )
    );
	
	$wp_customize->add_setting(
    'featured_textbox_header_one',
    array(
        'default' => 'Default featured text Header',
		'sanitize_callback' => 'bota_sanitize_text',
    )
	);
	
	$wp_customize->add_control(
		'featured_textbox_header_one',
		array(
			'label' => 'Featured Header text',
			'section' => 'featured_section_one',
			'type' => 'text',
		)
	);
	
	$wp_customize->add_setting(
		'featured_textbox_text_one',
		array(
			'default' => 'Default featured text',
			'sanitize_callback' => 'bota_sanitize_text',
		)
	);
	
	$wp_customize->add_control(
		'featured_textbox_text_one',
		array(
			'label' => 'Featured text',
			'section' => 'featured_section_one',
			'type' => 'text',
		)
	);

}
add_action( 'customize_register', 'featured_text_one_customizer' );

/**
 * Adds the individual section for featured text box 2
 */
function featured_text_two_customizer( $wp_customize ) {
    $wp_customize->add_section(
        'featured_section_two',
        array(
            'title' => 'Featured Text Box 2',
            'description' => 'This is a settings section to change the homepage featured text area.',
            'priority' => 160,
        )
    );
	
	$wp_customize->add_setting(
    'featured_textbox_header_two',
    array(
        'default' => 'Default featured text Header',
		'sanitize_callback' => 'bota_sanitize_text',
    )
	);
	
	$wp_customize->add_control(
		'featured_textbox_header_two',
		array(
			'label' => 'Featured Header text',
			'section' => 'featured_section_two',
			'type' => 'text',
		)
	);
	
	$wp_customize->add_setting(
		'featured_textbox_text_two',
		array(
			'default' => 'Default featured text',
			'sanitize_callback' => 'bota_sanitize_text',
		)
	);
	
	$wp_customize->add_control(
		'featured_textbox_text_two',
		array(
			'label' => 'Featured text',
			'section' => 'featured_section_two',
			'type' => 'text',
		)
	);
}
add_action( 'customize_register', 'featured_text_two_customizer' );

/**
 * Adds the individual section for featured text box 3
 */
function featured_text_three_customizer( $wp_customize ) {
    $wp_customize->add_section(
        'featured_section_three',
        array(
            'title' => 'Featured Text Box 3',
            'description' => 'This is a settings section to change the homepage featured text area.',
            'priority' => 165,
        )
    );
	
	$wp_customize->add_setting(
    'featured_textbox_header_three',
    array(
        'default' => 'Default featured text Header',
		'sanitize_callback' => 'bota_sanitize_text',
    )
	);
	
	$wp_customize->add_control(
		'featured_textbox_header_three',
		array(
			'label' => 'Featured Header text',
			'section' => 'featured_section_three',
			'type' => 'text',
		)
	);
	
	$wp_customize->add_setting(
		'featured_textbox_text_three',
		array(
			'default' => 'Default featured text',
			'sanitize_callback' => 'bota_sanitize_text',
		)
	);
	
	$wp_customize->add_control(
		'featured_textbox_text_three',
		array(
			'label' => 'Featured text',
			'section' => 'featured_section_three',
			'type' => 'text',
		)
	);
}
add_action( 'customize_register', 'featured_text_three_customizer' );

/**
 * Implement excerpt for homepage thumbnails
 */
function get_excerpt(){
$excerpt = get_the_content();
$excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
$excerpt = strip_shortcodes($excerpt);
$excerpt = strip_tags($excerpt);
$excerpt = substr($excerpt, 0, 60);
$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
$excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
return $excerpt;
}

/**
 * sanitize customizer text input
 */
 function bota_sanitize_text( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}

add_filter( 'wp_title', 'bota_wp_title' );
/**
 * Filters the page title appropriately depending on the current page
 *
 * This function is attached to the 'wp_title' fiilter hook.
 *
 * @uses	get_bloginfo()
 * @uses	is_home()
 * @uses	is_front_page()
 */
function bota_wp_title( $title ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	$site_description = get_bloginfo( 'description' );

	$filtered_title = $title . get_bloginfo( 'name' );
	$filtered_title .= ( ! empty( $site_description ) && ( is_home() || is_front_page() ) ) ? ' | ' . $site_description: '';
	$filtered_title .= ( 2 <= $paged || 2 <= $page ) ? ' | ' . sprintf( __( 'Page %s', 'bota' ), max( $paged, $page ) ) : '';

	return $filtered_title;
}
