<?php
/**
 * Nisarg Theme Customizer
 *
 * @package Nisarg
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function nisarg_customize_register( $wp_customize ) {

	//get the current color value for accent color
	$color_scheme = nisarg_get_color_scheme();
	//get the default color for current color scheme
	$current_color_scheme = nisarg_current_color_scheme_default_color();

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	$wp_customize->get_control('background_color')->active_callback = 'nisarg_dark_skin_not_enabled';


	//Add section for theme skin
	$wp_customize->add_section(
		'nisarg_theme_skin',
		array(
			'title'     => __('Theme Skin','nisarg'),
			'priority'  => 0,
			'panel'     => 'nisarg_colors_panel'
		)
	);
	$skin_atts = array(
	   'light'     => __('Light (default)','nisarg'),
	   'dark'      => __('Dark','nisarg'),
	);
	$wp_customize->add_setting( 'nisarg_skin_select', array(
	   'default'           =>  'light',
	   'transport'         =>  'refresh',
	   'sanitize_callback' =>  'nisarg_sanitize_select'
	) );
	$wp_customize->add_control( 'nisarg_skin_select', array(
	   'description'     => '',
	   'section'         => 'nisarg_theme_skin',
	   'settings'        => 'nisarg_skin_select',
	   'type'            => 'select',
	   'choices'         => $skin_atts,
	) );

	//  ==========================================================
    //  =Colors Panel 
    //  ==========================================================
    $wp_customize->add_panel( 'nisarg_colors_panel', array(
        'priority'          =>  40,
        'capability'        =>  'edit_theme_options',
        'theme_supports'    =>  '',
        'title'             =>  esc_html__('Colors', 'nisarg'),
    ) );
    
    $wp_customize->get_section( 'colors' )->title = esc_html__('General Colors', 'nisarg');
    $wp_customize->get_section( 'colors' )->panel = 'nisarg_colors_panel';
    $wp_customize->get_section( 'colors' )->priority = '1'; 

	//Header Background Color setting
	$wp_customize->add_setting( 'header_bg_color', array(
		'default'           => '#b0bec5',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_bg_color', array(
		'label'       => __( 'Header Background Color', 'nisarg' ),
		'description' => __( 'Applied to header background.', 'nisarg' ),
		'section'     => 'colors',
		'settings'    => 'header_bg_color',
	) ) );

	// Add color scheme setting and control.
	$wp_customize->add_setting( 'color_scheme', array(
		'default'           => 'default',
		'sanitize_callback' => 'nisarg_sanitize_color_scheme',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'color_scheme', array(
		'label'    => __( 'Accent Color Name', 'nisarg' ),
		'section'     => 'colors',
		'type'     => 'select',
		'choices'  => nisarg_get_color_scheme_choices(),
		'priority' => 10,
	) );

	// Add custom accent color.

	$wp_customize->add_setting( 'accent_color', array(
		'default'           => $current_color_scheme[0],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'accent_color', array(
		'label'       => __( 'Accent Color', 'nisarg' ),
		'description' => __( 'Applied to highlight elements.', 'nisarg' ),
		'section'     => 'colors',
		'settings'    => 'accent_color',
		'priority' => 11,
	) ) );

	//Add section for post option
	$wp_customize->add_section(
	    'post_options',
	    array(
	        'title'     => __('Post Options','nisarg'),
	        'priority'  => 52,
	    )
	);

	$wp_customize->add_setting('post_display_option', array(
        'default'        => 'post-excerpt',
        'sanitize_callback' => 'nisarg_sanitize_post_display_option',
		'transport'         => 'refresh'
    ));
 
    $wp_customize->add_control('post_display_types', array(
        'label'      => __('How would you like to dipaly a post on post listing page?', 'nisarg'),
        'section'    => 'post_options',
        'settings'   => 'post_display_option',
        'type'       => 'radio',
        'choices'    => array(
            'post-excerpt' => __('Post excerpt','nisarg'),
            'full-post' => __('Full post','nisarg'),            
        ),
    ));
	$atts = array(
		'old-new-posts' 		=> esc_html__( 'Older and Newer Posts(Default)', 'nisarg' ),
		'page-number-round' 	=> esc_html__( 'Page Numbers', 'nisarg' ),
	);
	$wp_customize->add_setting( 'nisarg_posts_nav', array(
		'default'	 		=> 	'page-number-round',
		'transport'	 		=> 	'refresh',
		'sanitize_callback' => 	'nisarg_sanitize_select'
	) );
	$wp_customize->add_control( 'nisarg_posts_nav', array(
		'label'				=> 	__('Posts Navigation Style on Posts Index Page','nisarg'), 
		'description'		=>	'',
		'section' 			=> 'post_options',
		'settings' 			=> 'nisarg_posts_nav',
		'type'				=> 	'select',
		'choices' 			=> 	$atts,
	) );

	//  ==========================================================
    //  =Typography Panel 
    //  ==========================================================
    $wp_customize->add_section( 'nisarg_typography_section', 
		array(
			'title'		=>	esc_html__( 'Typography', 'nisarg' ),
			'priority'	=> 	41,
		)
	);

	//Get available list of fonts
	$fontArr = nisarg_get_fonts_list();
	$font_family = array();
	foreach( $fontArr as $key => $val ) {
        $font_family[$key] = $key;
    }

    $font_wghtOpt = array( 
			'400'   	=> __( 'Normal 400', 'nisarg' ),
			'400italic' => __( 'Normal 400 Italic', 'nisarg' ),
			'700'       => __( 'Bold 700', 'nisarg' ),
			'700italic' => __( 'Bold 700 Italic', 'nisarg' ),
		);

    //  ==========================================================
    //  = Heading font      =
    //  ==========================================================
	
	//Heading Fonts
	$wp_customize->add_setting( 'nisarg_heading_font_family_setting', array(
		'default'	 		=> 	'Lato',
		'transport'	 		=> 	'refresh',
		'sanitize_callback' => 	'nisarg_sanitize_select'
	) );
	$wp_customize->add_control( 'nisarg_heading_font_family_setting', array(
		'label'				=> 	__('Heading Font Family','nisarg'), 
		'description'		=>	'',
		'section' 			=> 'nisarg_typography_section',
		'settings' 			=> 'nisarg_heading_font_family_setting',
		'type'				=> 	'select',
		'choices' 			=> 	$font_family,
	) );

	//Font weight settings for body text
	$wp_customize->add_setting( 'nisarg_heading_font_weight_setting', array(
		'default'           =>	'400',
		'sanitize_callback'	=> 	'nisarg_sanitize_select',
		'transport'	 		=> 	'refresh',
	));
	$wp_customize->add_control( 'nisarg_heading_font_weight_setting', array(
		'label'				=>	__( 'Heading Font Weight', 'nisarg' ),
		'section'			=>	'nisarg_typography_section',
		'settings'    		=>	'nisarg_heading_font_weight_setting',
		'type'     			=>	'select',
		'choices'			=>	$font_wghtOpt,
	) );

    //  ==========================================================
    //  = Body font      =
    //  ==========================================================

	$wp_customize->add_setting( 'nisarg_body_font_family_setting', array(
		'default'	 		=> 	'Source Sans Pro',
		'transport'	 		=> 	'refresh',
		'sanitize_callback' => 	'nisarg_sanitize_select'
	) );
	$wp_customize->add_control( 'nisarg_body_font_family_setting', array(
		'label'				=> 	__('Body Font Family','nisarg'), 
		'description'		=>	'',
		'section' 			=> 'nisarg_typography_section',
		'settings' 			=> 'nisarg_body_font_family_setting',
		'type'				=> 	'select',
		'choices' 			=> 	$font_family,
	) );


	//Font weight settings for body text
	$wp_customize->add_setting( 'nisarg_body_font_weight_setting', array(
		'default'           =>	'400',
		'sanitize_callback'	=> 	'nisarg_sanitize_select',
		'transport'	 		=> 	'refresh',
	));
	$wp_customize->add_control( 'nisarg_body_font_weight_setting', array(
		'label'				=>	__( 'Body Font Weight', 'nisarg' ),
		'section'			=>	'nisarg_typography_section',
		'settings'    		=>	'nisarg_body_font_weight_setting',
		'type'     			=>	'select',
		'choices'			=>	$font_wghtOpt,
	) );

	

	//  ==========================================================
    //  = Menu font      =
    //  ==========================================================

	//Font weight settings for menu item text
	$wp_customize->add_setting( 'nisarg_menuitem_font_family_setting', 
		array(
			'default'			=> 'body',
			'sanitize_callback'	=> 'nisarg_sanitize_select',
			'transport'	 		=> 	'refresh',
	));
	$wp_customize->add_control( 'nisarg_menuitem_font_family_setting', 
		array(
			'label'             => esc_html__( 'Top Primary Menu Font Family', 'nisarg' ),
			'section'           => 'nisarg_typography_section',
			'settings'          => 'nisarg_menuitem_font_family_setting',
			'type'     			=> 'select',
			'choices'   		=>  array( 
									'body' 		=>	__( 'Same as body font', 'nisarg' ),
								   	'heading' 	=>	__( 'Same as heading font', 'nisarg' ),
								),
	) );

	//Font weight settings for menu text
	$wp_customize->add_setting( 'nisarg_menu_font_weight_setting', array(
		'default'           =>	'400',
		'sanitize_callback'	=> 	'nisarg_sanitize_select',
		'transport'	 		=> 	'refresh',
	));
	$wp_customize->add_control( 'nisarg_menu_font_weight_setting', array(
		'label'				=>	__( 'Menu Font Weight', 'nisarg' ),
		'section'			=>	'nisarg_typography_section',
		'settings'    		=>	'nisarg_menu_font_weight_setting',
		'type'     			=>	'select',
		'choices'			=>	$font_wghtOpt,
	) );

	//  ==========================================================
    //  = Google font Loading Options  =
    //  ==========================================================

	$wp_customize->add_setting( 'nisarg_google_font_load_setting', 
		array(
		    'default'        	=> 'cdn',
		    'sanitize_callback' => 'nisarg_sanitize_radio',
			'transport'         => 'refresh',
	));
	$wp_customize->add_control( 'nisarg_google_font_load_setting', 
		array(
		    'label'      		=>	__( 'Load Google Fonts From', 'nisarg' ),
		    'description'		=> __( 'The Google fonts delivery service option uses CDNs to load the fonts assets. If Host locally option is selected, Google fonts will be hosted locally on your webserver. The Host Google fonts locally option handles privacy/tracking concerns.', 'nisarg' ),
		    'section'    		=> 'nisarg_typography_section',
		    'settings'  		=> 'nisarg_google_font_load_setting',
		    'type'       		=> 'radio',
		    'choices' 			=> array(
			    	'cdn' 			=> __( 'Load Google fonts from Google fonts delivery service( Default )', 'nisarg' ),
			    	'local-host' 	=> __( 'Host Google fonts locally on your server', 'nisarg' ),
			),	
	));

	//  ==========================================================
    //  = Site Title and Tagline in Navbar Section  =
    //  ==========================================================
	//Hide Site Title in navbar on Desktop
	$wp_customize->add_setting( 'nisarg_hide_site_title_in_top_navbar_desktop', 
		array(
		    'default'        	=> false,
		    'sanitize_callback' => 'nisarg_sanitize_checkbox',
			'transport'         => 'refresh',
	));
	$wp_customize->add_control( 'nisarg_hide_site_title_in_top_navbar_desktop', 
		array(
		    'label'      =>	__( 'Hide Site Title in the Top Navbar on Desktop', 'nisarg' ),
		    'section'    => 'title_tagline',
		    'settings'   => 'nisarg_hide_site_title_in_top_navbar_desktop',
		    'type'       => 'checkbox',
		    'priority'   => 50,
	));
	//Hide Site Title in navbar on Mobile
	$wp_customize->add_setting( 'nisarg_hide_site_title_in_top_navbar_mobile', 
		array(
		    'default'        	=> false,
		    'sanitize_callback' => 'nisarg_sanitize_checkbox',
			'transport'         => 'refresh',
	));
	$wp_customize->add_control( 'nisarg_hide_site_title_in_top_navbar_mobile', 
		array(
		    'label'      =>	__( 'Hide Site Title in the Top Navbar on Small Devices', 'nisarg' ),
		    'section'    => 'title_tagline',
		    'settings'   => 'nisarg_hide_site_title_in_top_navbar_mobile',
		    'type'       => 'checkbox',
		    'priority'   => 51,
	));

	//  ==========================================================
    //  = Site Header Section  =
    //  ==========================================================

	$wp_customize->add_panel( 'nisarg_header_section_panel', array(
        'priority'          =>  41,
        'capability'        =>  'edit_theme_options',
        'theme_supports'    =>  '',
        'title'             =>  esc_html__( 'Header Section', 'nisarg'),
        'description'       =>  '',
    ) );

    //Move Header Image section from main customizer pannel to Header Section Panel
    $wp_customize->get_section( 'header_image' )->panel = 'nisarg_header_section_panel';

    //add option to set type of header
    $wp_customize->add_section( 'nisarg_header_section', array(
      'title'       =>  esc_html__( 'Header Options', 'nisarg' ),
      'panel'       =>  'nisarg_header_section_panel', 
      'priority'    =>  61,
    ) );

    //types of header
    $header_types = array(
        'none'  =>  esc_html__( 'None', 'nisarg'),
        'h-title-tagline' =>  esc_html__( 'Header Image with Site title and Tagline', 'nisarg'),
    );
    $wp_customize->add_setting( 'nisarg_header_type', array(
	   'default'           =>  'h-title-tagline',
	   'transport'         =>  'refresh',
	   'sanitize_callback' =>  'nisarg_sanitize_select'
	) );
	$wp_customize->add_control( 'nisarg_header_type', array(
		'label'			  => 'Header Type',
		'description'     => '',
		'section'         => 'nisarg_header_section',
		'settings'        => 'nisarg_header_type',
		'type'            => 'select',
		'choices'         => $header_types,
	) );

}
add_action( 'customize_register', 'nisarg_customize_register' );

/**
 * Register color schemes for Nisarg.
 *
 * @return array An associative array of color scheme options.
 */
function nisarg_get_color_schemes() {
	return apply_filters( 'nisarg_color_schemes', array(
		'default' => array(
			'label'  => __( 'Default', 'nisarg' ),
			'colors' => array(
				'#009688',			
			),
		),
		'pink'    => array(
			'label'  => __( 'Pink', 'nisarg' ),
			'colors' => array(
				'#FF4081',				
			),
		),
		'orange'  => array(
			'label'  => __( 'Orange', 'nisarg' ),
			'colors' => array(
				'#FF5722',
			),
		),
		'green'    => array(
			'label'  => __( 'Green', 'nisarg' ),
			'colors' => array(
				'#8BC34A',
			),
		),
		'red'    => array(
			'label'  => __( 'Red', 'nisarg' ),
			'colors' => array(
				'#FF5252',
			),
		),
		'yellow'    => array(
			'label'  => __( 'yellow', 'nisarg' ),
			'colors' => array(
				'#FFC107',
			),
		),
		'blue'   => array(
			'label'  => __( 'Blue', 'nisarg' ),
			'colors' => array(
				'#03A9F4',
			),
		),
	) );
}

if(!function_exists('nisarg_current_color_scheme_default_color')):
/**
 * Get the default hex color value for current color scheme
 *
 *
 * @return array An associative array of current color scheme hex values.
 */
function nisarg_current_color_scheme_default_color(){
	$color_scheme_option = get_theme_mod( 'color_scheme', 'default' );
	
	$color_schemes       = nisarg_get_color_schemes();

	if ( array_key_exists( $color_scheme_option, $color_schemes ) ) {
		return $color_schemes[ $color_scheme_option ]['colors'];
	}

	return $color_schemes['default']['colors'];
}
endif; //nisarg_current_color_scheme_default_color

if ( ! function_exists( 'nisarg_get_color_scheme' ) ) :
/**
 * Get the current Nisarg color scheme.
 *
 *
 * @return array An associative array of currently set color hex values.
 */
function nisarg_get_color_scheme() {
	$color_scheme_option = get_theme_mod( 'color_scheme', 'default' );
	$accent_color = get_theme_mod('accent_color','#009688');
	$color_schemes       = nisarg_get_color_schemes();

	if ( array_key_exists( $color_scheme_option, $color_schemes ) ) {
		$color_schemes[ $color_scheme_option ]['colors'] = array($accent_color);
		return $color_schemes[ $color_scheme_option ]['colors'];
	}

	return $color_schemes['default']['colors'];
}
endif; // nisarg_get_color_scheme

if ( ! function_exists( 'nisarg_get_color_scheme_choices' ) ) :
/**
 * Returns an array of color scheme choices registered for Nisarg.
 *
 *
 * @return array Array of color schemes.
 */
function nisarg_get_color_scheme_choices() {
	$color_schemes                = nisarg_get_color_schemes();
	$color_scheme_control_options = array();

	foreach ( $color_schemes as $color_scheme => $value ) {
		$color_scheme_control_options[ $color_scheme ] = $value['label'];
	}

	return $color_scheme_control_options;
}
endif; // nisarg_get_color_scheme_choices

if ( ! function_exists( 'nisarg_sanitize_color_scheme' ) ) :
/**
 * Sanitization callback for color schemes.
 *
 *
 * @param string $value Color scheme name value.
 * @return string Color scheme name.
 */
function nisarg_sanitize_color_scheme( $value ) {
	$color_schemes = nisarg_get_color_scheme_choices();

	if ( ! array_key_exists( $value, $color_schemes ) ) {
		$value = 'default';
	}

	return $value;
}
endif; // nisarg_sanitize_color_scheme

if ( ! function_exists( 'nisarg_sanitize_post_display_option' ) ) :
/**
 * Sanitization callback for post display option.
 *
 *
 * @param string $value post display style.
 * @return string post display style.
 */

function nisarg_sanitize_post_display_option( $value ) {
    if ( ! in_array( $value, array( 'post-excerpt', 'full-post' ) ) )
        $value = 'post-excerpt';
 	
    return $value;
}
endif; // nisarg_sanitize_post_display_option
/**
 * Enqueues front-end CSS for color scheme.
 *
 *
 * @see wp_add_inline_style()
 */
function nisarg_color_scheme_css() {
	$color_scheme_option = get_theme_mod( 'color_scheme', 'default' );
	
	$color_scheme = nisarg_get_color_scheme();

	$color = array(
	'accent_color'            => $color_scheme[0],
	);

	$color_scheme_css = nisarg_get_color_scheme_css( $color);

	wp_add_inline_style( 'nisarg-style', $color_scheme_css );
}
add_action( 'wp_enqueue_scripts', 'nisarg_color_scheme_css' );

/**
 * Returns CSS for the color schemes.
 *
 * @param array $colors Color scheme colors.
 * @return string Color scheme CSS.
 */
function nisarg_get_color_scheme_css( $colors ) {
	$colors = wp_parse_args( $colors, array(
		'accent_color'            => '',
	) );

	$css = <<<CSS
	/* Color Scheme */

	/* Accent Color */

	a:active,
	a:hover,
	a:focus {
	    color: {$colors['accent_color']};
	}

	.main-navigation .primary-menu > li > a:hover, .main-navigation .primary-menu > li > a:focus {
		color: {$colors['accent_color']};
	}
	
	.main-navigation .primary-menu .sub-menu .current_page_item > a,
	.main-navigation .primary-menu .sub-menu .current-menu-item > a {
		color: {$colors['accent_color']};
	}
	.main-navigation .primary-menu .sub-menu .current_page_item > a:hover,
	.main-navigation .primary-menu .sub-menu .current_page_item > a:focus,
	.main-navigation .primary-menu .sub-menu .current-menu-item > a:hover,
	.main-navigation .primary-menu .sub-menu .current-menu-item > a:focus {
		background-color: #fff;
		color: {$colors['accent_color']};
	}
	.dropdown-toggle:hover,
	.dropdown-toggle:focus {
		color: {$colors['accent_color']};
	}
	.pagination .current,
	.dark .pagination .current {
		background-color: {$colors['accent_color']};
		border: 1px solid {$colors['accent_color']};
	}
	blockquote {
		border-color: {$colors['accent_color']};
	}
	@media (min-width: 768px){
		.main-navigation .primary-menu > .current_page_item > a,
		.main-navigation .primary-menu > .current_page_item > a:hover,
		.main-navigation .primary-menu > .current_page_item > a:focus,
		.main-navigation .primary-menu > .current-menu-item > a,
		.main-navigation .primary-menu > .current-menu-item > a:hover,
		.main-navigation .primary-menu > .current-menu-item > a:focus,
		.main-navigation .primary-menu > .current_page_ancestor > a,
		.main-navigation .primary-menu > .current_page_ancestor > a:hover,
		.main-navigation .primary-menu > .current_page_ancestor > a:focus,
		.main-navigation .primary-menu > .current-menu-ancestor > a,
		.main-navigation .primary-menu > .current-menu-ancestor > a:hover,
		.main-navigation .primary-menu > .current-menu-ancestor > a:focus {
			border-top: 4px solid {$colors['accent_color']};
		}
		.main-navigation ul ul a:hover,
		.main-navigation ul ul a.focus {
			color: #fff;
			background-color: {$colors['accent_color']};
		}
	}

	.main-navigation .primary-menu > .open > a, .main-navigation .primary-menu > .open > a:hover, .main-navigation .primary-menu > .open > a:focus {
		color: {$colors['accent_color']};
	}

	.main-navigation .primary-menu > li > .sub-menu  li > a:hover,
	.main-navigation .primary-menu > li > .sub-menu  li > a:focus {
		color: #fff;
		background-color: {$colors['accent_color']};
	}

	@media (max-width: 767px) {
		.main-navigation .primary-menu .open .sub-menu > li > a:hover {
			color: #fff;
			background-color: {$colors['accent_color']};
		}
	}

	.sticky-post{
		color: #fff;
	    background: {$colors['accent_color']}; 
	}
	
	.entry-title a:hover,
	.entry-title a:focus{
	    color: {$colors['accent_color']};
	}

	.entry-header .entry-meta::after{
	    background: {$colors['accent_color']};
	}

	.fa {
		color: {$colors['accent_color']};
	}

	.btn-default{
		border-bottom: 1px solid {$colors['accent_color']};
	}

	.btn-default:hover, .btn-default:focus{
	    border-bottom: 1px solid {$colors['accent_color']};
	    background-color: {$colors['accent_color']};
	}

	.nav-previous:hover, .nav-next:hover{
	    border: 1px solid {$colors['accent_color']};
	    background-color: {$colors['accent_color']};
	}

	.next-post a:hover,.prev-post a:hover{
	    color: {$colors['accent_color']};
	}

	.posts-navigation .next-post a:hover .fa, .posts-navigation .prev-post a:hover .fa{
	    color: {$colors['accent_color']};
	}


	#secondary .widget-title::after{
		position: absolute;
	    width: 50px;
	    display: block;
	    height: 4px;    
	    bottom: -15px;
		background-color: {$colors['accent_color']};
	    content: "";
	}

	#secondary .widget a:hover,
	#secondary .widget a:focus,
	.dark #secondary .widget #recentcomments a:hover,
	.dark #secondary .widget #recentcomments a:focus {
		color: {$colors['accent_color']};
	}

	#secondary .widget_calendar tbody a {
		color: #fff;
		padding: 0.2em;
	    background-color: {$colors['accent_color']};
	}

	#secondary .widget_calendar tbody a:hover{
		color: #fff;
	    padding: 0.2em;
	    background-color: {$colors['accent_color']};  
	}	

	.dark .comment-respond #submit,
	.dark .main-navigation .menu-toggle:hover, 
	.dark .main-navigation .menu-toggle:focus,
	.dark html input[type="button"], 
	.dark input[type="reset"], 
	.dark input[type="submit"] {
		background: {$colors['accent_color']};
		color: #fff;
	}
	
	.dark a {
		color: {$colors['accent_color']};
	}

CSS;

	return $css;
}

if(! function_exists('nisarg_header_bg_color_css' ) ):
/**
* Set the header background color 
*/
function nisarg_header_bg_color_css(){

?>

<style type="text/css">
        .site-header { background: <?php echo esc_attr( get_theme_mod( 'header_bg_color' ) ); ?>; }
</style>

<?php }

add_action( 'wp_head', 'nisarg_header_bg_color_css' );

endif;

/**
 * Binds JS listener to make Customizer color_scheme control.
 *
 * Passes color scheme data as colorScheme global.
 *
 */
function nisarg_customize_control_js() {
	wp_enqueue_script( 'color-scheme-control', get_template_directory_uri() . '/js/color-scheme-control.js', array( 'customize-controls', 'iris', 'underscore', 'wp-util' ), '20141216', true );
	wp_localize_script( 'color-scheme-control', 'colorScheme', nisarg_get_color_schemes() );
}
add_action( 'customize_controls_enqueue_scripts', 'nisarg_customize_control_js' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function nisarg_customize_preview_js() {
	wp_enqueue_script( 'nisarg_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'nisarg_customize_preview_js' );

/**
 * Output an Underscore template for generating CSS for the color scheme.
 *
 * The template generates the css dynamically for instant display in the Customizer
 * preview.
 *
 */
function nisarg_color_scheme_css_template() {
	$colors = array(
		'accent_color'            => '{{ data.accent_color }}',
	);
	?>
	<script type="text/html" id="tmpl-nisarg-color-scheme">
		<?php echo nisarg_get_color_scheme_css( $colors ); ?>
	</script>
	<?php
}
add_action( 'customize_controls_print_footer_scripts', 'nisarg_color_scheme_css_template' );

/**
 * Dark Mode Checker Calllback
 * @since 1.4
 */
function nisarg_dark_skin_not_enabled( $control ) {
    if ( $control->manager->get_setting('nisarg_skin_select')->value() !== 'dark'  ) {
        return true;
    } else {
        return false;
    }
}


if ( ! function_exists( 'nisarg_font_css' ) ) :
	/**
	* Set the header background color.
	*/
	function nisarg_font_css() {

		//Obtain Value of body fonts
		$body_font_family = get_theme_mod( 'nisarg_body_font_family_setting', 'Source Sans Pro' );

		//Obtain Value of heading fonts
		$heading_font_family = get_theme_mod( 'nisarg_heading_font_family_setting', 'Lato' );

		$heading_font_weight = get_theme_mod( 'nisarg_heading_font_weight_setting', '400' );
		$body_font_weight = get_theme_mod( 'nisarg_body_font_weight_setting', '400' );
		$menu_item_font_family = get_theme_mod( 'nisarg_menuitem_font_family_setting', 'body' );
		
		$menu_font_weight = get_theme_mod( 'nisarg_menu_font_weight_setting', '400' );
		$heading_font_style = 'normal';
		$body_font_style = 'normal';
		$menu_item_font_style = 'normal';
		
		if( strpos( $heading_font_weight, 'italic') !== false ) {
			$heading_font_style = 'italic';
			$heading_font_weight = str_replace( 'italic', ' ', $heading_font_weight );
		}
		if( strpos( $body_font_weight, 'italic') !== false ) {
			$body_font_style = 'italic';
			$body_font_weight = str_replace( 'italic', ' ', $body_font_weight );
		}
		if( strpos( $menu_font_weight, 'italic') !== false ) {
			$menu_item_font_style = 'italic';
			$menu_font_weight = str_replace( 'italic', ' ', $menu_font_weight );
		}
		if( 'System Default' === $body_font_family ) {
			$body_font_family = 'Helvetica';
		}
		if( 'System Default' === $heading_font_family ) {
			$heading_font_family = 'Helvetica';
		}
		if( 'body' === $menu_item_font_family ) {
			$menu_item_font_family = $body_font_family;	
		} else {
			$menu_item_font_family = $heading_font_family;
		}
		
	?>

	<style type="text/css">
		body,
		button,
		input,
		select,
		textarea {
		    font-family:  '<?php echo  esc_attr( $body_font_family ); ?>',-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
		    font-weight: <?php echo esc_attr( $body_font_weight ); ?>;
		    font-style: <?php echo  esc_attr( $body_font_style ); ?>; 
		}
		h1,h2,h3,h4,h5,h6 {
	    	font-family: '<?php echo  esc_attr( $heading_font_family ); ?>',-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
	    	font-weight: <?php echo esc_attr( $heading_font_weight ); ?>;
	    	font-style: <?php echo esc_attr( $heading_font_style ); ?>;
	    }
	    .navbar-brand,
	    #site-navigation.main-navigation ul {
	    	font-family: '<?php echo  esc_attr( $menu_item_font_family ); ?>',-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
	    }
	    #site-navigation.main-navigation ul {
	    	font-weight: <?php echo esc_attr( $menu_font_weight ); ?>;
	    	font-style: <?php echo  esc_attr( $menu_item_font_style ); ?>;
	    }
	</style>

	<?php }

	add_action( 'wp_head', 'nisarg_font_css' );

endif;

if ( ! function_exists( 'nisarg_hide_site_title_in_topbar' ) ) :

	/**
	 * This will output custom CSS for Hiding site title.
	 * @since 1.8
	 */
	function nisarg_hide_site_title_in_topbar() {
		$hide_title_flag_desktop = get_theme_mod( 'nisarg_hide_site_title_in_top_navbar_desktop', false );
		$hide_title_css = '';
		$hide_title_flag_mobile = get_theme_mod( 'nisarg_hide_site_title_in_top_navbar_mobile', false );
		$hide_title_mobile_css = '';
		if( $hide_title_flag_desktop ) {
			$hide_title_css .= '
			@media all and (min-width: 768px){
				.navbar-header,.main-navigation .navbar-brand {
					display: none !important; 
				}
				.main-navigation ul {
					float: left;
				}
			}';
		}
		if( $hide_title_flag_mobile ) {
			$hide_title_css .= '
			@media all and (max-width: 767px){
				.main-navigation a.navbar-brand {
					display: none; 
				}
			}';
		}

		wp_add_inline_style( 'nisarg-style', sprintf( $hide_title_css ) );
	}
	// Output custom CSS to live site
	add_action( 'wp_enqueue_scripts', 'nisarg_hide_site_title_in_topbar', 11 );
endif;