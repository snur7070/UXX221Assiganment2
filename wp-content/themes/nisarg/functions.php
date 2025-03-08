<?php
/**
 * Nisarg functions and definitions
 *
 * @package Nisarg
 */

if ( ! function_exists( 'nisarg_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	/**
	 * Nisarg only works in WordPress 4.9.7 or later.
	 */
	if ( version_compare( $GLOBALS['wp_version'], '5.0', '<' ) ) {
		require get_template_directory() . '/inc/back-compat.php';
	}

	function nisarg_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Nisarg, use a find and replace
		 * to change 'nisarg' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'nisarg', get_template_directory() . '/languages' );

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
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 604, 270 );
		add_image_size( 'nisarg-full-width', 1038, 576, true );

		/*
		 * Enable support for Responsive embedded content.
		 *
		 */
		add_theme_support( 'responsive-embeds' );

		function register_nisarg_menus() {
			// This theme uses wp_nav_menu() in one location.
			register_nav_menus( array(
				'primary' => esc_html__( 'Top Primary Menu', 'nisarg' ),
			) );
		}
		add_action( 'init', 'register_nisarg_menus' );

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
		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'nisarg_custom_background_args', array(
			'default-color' => 'eceff1',
			'default-image' => '',
		) ) );
	}
endif; // nisarg_setup
add_action( 'after_setup_theme', 'nisarg_setup' );

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 */
function nisarg_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'nisarg_content_width', 640 );
}
add_action( 'after_setup_theme', 'nisarg_content_width', 0 );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function nisarg_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'nisarg' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'nisarg_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function nisarg_scripts() {
	//Enqueue Styles
	wp_enqueue_style( 'bootstrap', get_template_directory_uri().'/css/bootstrap.css' );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/font-awesome/css/font-awesome.min.css' );
	wp_enqueue_style( 'nisarg-style', get_stylesheet_uri() );
	//Enqueue Scripts
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'nisarg-navigation', get_template_directory_uri() . '/js/navigation.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'nisarg-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'nisarg-js', get_template_directory_uri() . '/js/nisarg.js', array( 'jquery' ), '',true );
	wp_enqueue_script( 'html5shiv', get_template_directory_uri(). '/js/html5shiv.js', array(),'3.7.3' ,false );
	wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );
	wp_localize_script( 'nisarg-js', 'screenReaderText', array(
		'expand'   => __( 'expand child menu', 'nisarg' ),
		'collapse' => __( 'collapse child menu', 'nisarg' ),
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'nisarg_scripts' );


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
 * Customizer Upgrade to Pro Section.
 */
require get_template_directory() . '/inc/upsell/class-customize.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load sanitize functions for customizer control values.
 */
require get_template_directory() . '/inc/sanitize-functions.php';

/**
 * Load google fonts.
 */
function nisarg_google_fonts() {

	$font_host = get_theme_mod( 'nisarg_google_font_load_setting', 'cdn' );
	
	//Get fonts list which includes system defaults and google fonts
	$fontArr = nisarg_get_fonts_list();

	//Remove system default element from the font list array 
	//and keeps only google fonts in fontArr
	array_splice($fontArr, 0, 1); 
	
	$fonts_url = '';

	// Load google fonts based on Customizer settings
	$body_font_family = get_theme_mod( 'nisarg_body_font_family_setting', 'Source Sans Pro' );
	
	$heading_font_family = get_theme_mod( 'nisarg_heading_font_family_setting', 'Lato' );
	
	if( array_key_exists( $heading_font_family, $fontArr) || array_key_exists( $body_font_family, $fontArr) ) {
		//get body font weight
		$body_font_weight = get_theme_mod( 'nisarg_body_font_weight_setting', '400' );
		$body_font_weight = str_replace( 'italic', 'i', $body_font_weight );
		
		//get heading font weight
		$heading_font_weight = get_theme_mod( 'nisarg_heading_font_weight_setting', '400' );
		$heading_font_weight = str_replace( 'italic', 'i', $heading_font_weight );
		
		//get menu font weight
		$menu_item_font_family = get_theme_mod( 'nisarg_menu_font_family_setting', 'body' );
		$menu_item_font_weight = get_theme_mod( 'nisarg_menu_font_weight_setting', '400' );
		$menu_item_font_weight = str_replace( 'italic', 'i', $menu_item_font_weight );

		$fonts_url = '';

		if ( '' !== $body_font_family ) {
			$body_font_family = esc_html( $body_font_family );
		}
		if ( '' !== $heading_font_family ) {
			$heading_font_family = esc_html( $heading_font_family );
		}

		// Construct url query based on chosen fonts
		
		$font_families = array();
		if ( array_key_exists( $body_font_family, $fontArr) ) {
			if( 'body' === $menu_item_font_family ) {
				if( $body_font_weight != $menu_item_font_weight ) {
					$body_font_weight .= ','.$menu_item_font_weight.',700';
				} else {
					$body_font_weight .= ',700';
				}
			}
			$font_families[] = $body_font_family.':'.$body_font_weight;
		}
		if ( array_key_exists( $heading_font_family, $fontArr) ) {
			if( 'heading' === $menu_item_font_family ) {
				if ( $heading_font_weight != $menu_item_font_weight ) {
					$heading_font_weight .= ','.$menu_item_font_weight.',700';
				} else {
					$heading_font_weight .= ',700';
				}
			}
			$font_families[] = $heading_font_family.':'.$heading_font_weight;
		}

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'display' => 'swap',
		);
		$fonts_url = esc_url(add_query_arg( $query_args, 'https://fonts.googleapis.com/css' ));

		if( 'cdn' === $font_host ) {
			wp_register_style( 'nisarg-google-fonts', $fonts_url, array(), null );
			wp_enqueue_style( 'nisarg-google-fonts' );
		} elseif('local-host' === $font_host ) {
			
			// Include the file.
			require_once get_template_directory().'/inc/self-host-fonts/class-nisarg-wptt-webfont-loader.php';
			
			$local_google_fonts_url = esc_url(wptt_get_webfont_url( $fonts_url, 'woff' ));
			
			// Load the webfont.
			wp_enqueue_style(
				'nisarg-google-fonts',
				$local_google_fonts_url,
				array(),
				'1.5'
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'nisarg_google_fonts' );


/**
 * Change Read More link.
 */
function nisarg_new_excerpt_more( $more ) {
	return '...<p class="read-more"><a class="btn btn-default" href="'. esc_url( get_permalink( get_the_ID() ) ) . '">' . __( ' Read More', 'nisarg' ) . '<span class="screen-reader-text"> '. __( ' Read More', 'nisarg' ).'</span></a></p>';
}
add_filter( 'excerpt_more', 'nisarg_new_excerpt_more' );

/**
 * Change excerpt length to 80 characters.
 */
function custom_excerpt_length( $length ) {
	return 80;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

/**
 * Return the post URL.
 *
 * @uses get_url_in_content() to get the URL in the post meta (if it exists) or
 * the first link found in the post content.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 *  * @return string The Link format URL.
 */
function nisarg_get_link_url() {
	$nisarg_content = get_the_content();
	$nisarg_has_url = get_url_in_content( $nisarg_content );

	return ( $nisarg_has_url ) ? $nisarg_has_url : apply_filters( 'the_permalink', get_permalink() );
}

/**
 * Return the list of fonts and its corresponding weights.
 *
 *
 *  @since 1.5
 */
function nisarg_get_fonts_list() {
	
	return ( array (
		'System Default' => array(
			'family' => '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif',
		    'variants' => 
			array(
			  0 => '400',
		      1 => '400italic',
		      2 => '700',
		      3 => '700italic',
			),
		),
		'Lato' => 
		  array (
		    'family' => 'Lato',
		    'variants' => 
		    array (
		      0 => '400',
		      1 => '400italic',
		      2 => '700',
		      3 => '700italic',
		    ),
		),
		'Source Sans Pro' => 
		  array (
		    'family' => 'Source Sans Pro',
		    'variants' => 
		    array (
		      0 => '400',
		      1 => '400italic',
		      2 => '700',
		      3 => '700italic',
		    ),
		),
		'Open Sans' => 
		  array (
		    'family' => 'Open Sans',
		    'variants' => 
		    array (
		      0 => '400',
		      1 => '400italic',
		      2 => '700',
		      3 => '700italic',
		    ),
		),
		'PT Sans' => 
		  array (
		    'family' => 'PT Sans',
		    'variants' => 
		    array (
		      0 => '400',
		      1 => '400italic',
		      2 => '700',
		      3 => '700italic',
		    ),
		),
		'Work Sans' => 
		  array (
		    'family' => 'Work Sans',
		    'variants' => 
		    array (
		      0 => '400',
		      1 => '400italic',
		      2 => '700',
		      3 => '700italic',
		    ),
		 ),
		'Playfair Display' => 
		  array (
		    'family' => 'Playfair Display',
		    'variants' => 
		    array (
		      0 => '400',
		      1 => '400italic',
		      2 => '700',
		      3 => '700italic',
		    ),
		),
		'Merriweather' => 
		  array (
		    'family' => 'Merriweather',
		    'variants' => 
		    array (
		      0 => '400',
		      1 => '400italic',
		      2 => '700',
		      3 => '700italic',
		    ),
		),
		'PT Serif' => 
		  array (
		    'family' => 'PT Serif',
		    'variants' => 
		    array (
		      0 => '400',
		      1 => '400italic',
		      2 => '700',
		      3 => '700italic',
		    ),
		),
		'Lora' => 
		  array (
		    'family' => 'Lora',
		    'variants' => 
		    array (
		      0 => '400',
		      1 => '400italic',
		      2 => '700',
		      3 => '700italic',
		    ),
		),
	) );
}