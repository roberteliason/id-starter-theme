<?php
/**
 * _s functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package _s
 */

/**
 * If this is a dev environment, look for Kint in vendors dir and include if found
 * Else raise a flag
 *
 * d( $var ); prints a dump of $var
 * dd( $var ); same only it die(s) after
 *
 * @see http://raveren.github.io/kint/
 */
add_action( 'init', function() {
    $vendor_path = preg_replace( '/wp/', 'content/vendor', ABSPATH );

    if ( true === idkomm_is_dev() ) {
        $path_to_kint = $vendor_path . 'kint-php/kint/Kint.class.php';
        if ( true === file_exists( $path_to_kint ) ) {
            require_once $path_to_kint;
        }
        else {
            add_action( 'admin_notices', function() {
                $class = "error";
                $message = "Could not find Kint in Vendors dir, please check your Composer file";
                echo"<div class=\"$class\"> <p>$message</p></div>";
            } );
        }
    }


    // Catch any stray calls to d() if Kint is not loaded
    if ( !function_exists('d') ) {
        function d() {
            return;
        }
    }

});


/**
 * Check if site is on stage
 *
 * @return bool
 */
function idkomm_is_stage() {
    if ( strpos( site_url(), 'stage' ) !== false ) {
        return true;
    }
    return false;
}


/**
 * Check if site is on dev
 *
 * @return bool
 */
function idkomm_is_dev() {
    if ( strpos( site_url(), '.dev' ) !== false ) {
        return true;
    }
    return false;
}



if ( ! function_exists( 'idkomm_theme_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function idkomm_theme_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on _s, use a find and replace
	 * to change 'ehealth' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'ehealth', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Primary', 'ehealth' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( '_s_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;
add_action( 'after_setup_theme', 'idkomm_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function idkomm_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'idkomm_content_width', 640 );
}
add_action( 'after_setup_theme', 'idkomm_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function idkomm_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'ehealth' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'ehealth' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'idkomm_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function idkomm_scripts() {
	wp_enqueue_style( 'base-style', get_template_directory_uri() . '/resources/css/main.css' );

	wp_enqueue_script( 'main-js', get_template_directory_uri() . '/resources/js/main.js', array('jquery'), '20151215', true );

	wp_enqueue_script( '_s-skip-link-focus-fix', get_template_directory_uri() . '/resources/js/all/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'idkomm_scripts' );


/**
 * Redirect all non logged in users to login page
 */
function idkomm_redirect_to_login() {

    $current_template = idkomm_get_current_template();

    if ( $current_template != 'page_registration.php' && ! is_user_logged_in() && ! is_front_page() ) {
        wp_redirect( home_url(), 301 );
        exit;
    }
}
add_action( 'template_redirect', 'idkomm_redirect_to_login' );


/**
 * Get the filename of the current template
 *
 * @return string
 */
function idkomm_get_current_template() {
    return basename(get_page_template());
}


/**
 * Add a register link to the login form
 */
function idkomm_add_register_link_to_login_form() {
    echo( '<a href="' . site_url('registration') . '"><button>' . __( 'Register', 'ehealth' ) . '</button></a>' );
}
add_action('login_form_bottom', 'idkomm_add_register_link_to_login_form');


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
