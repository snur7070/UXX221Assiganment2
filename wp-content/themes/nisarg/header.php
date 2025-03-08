<?php
/**
 * The header for our theme.
 *
 * Displays all of the head section.
 *
 * @package Nisarg
 */
?>
<!DOCTYPE html>

<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
</head>
<?php 
	$theme_skin = get_theme_mod( 'nisarg_skin_select', 'light' ); 
	$skin_words = explode('-', $theme_skin );
	$header_type = get_theme_mod( 'nisarg_header_type', 'h-title-tagline' );
	$add_class = '';
	//If an option is selected to hide site header then add space after top navbar
	if( 'none' === $header_type ) {
		$add_class =  'class='.'add-margin-bottom';
	}
?>
<body <?php body_class( $skin_words ); ?>>
<?php wp_body_open(); ?>
<div id="page" class="hfeed site">
<header id="masthead"  <?php echo esc_attr( $add_class ); ?> role="banner">
	<nav id="site-navigation" class="main-navigation navbar-fixed-top navbar-left" role="navigation">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="container" id="navigation_menu">
			<div class="navbar-header">
				<?php if ( has_nav_menu( 'primary' ) ) { ?>
					<button type="button" class="menu-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span> 
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				<?php } ?>
					<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' )?></a>
			</div><!-- .navbar-header -->
			<?php if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location'    => 'primary',
					'container'         => 'div',
					'container_class'   => 'collapse navbar-collapse navbar-ex1-collapse',
					'menu_class'        => 'primary-menu',
				) ); } ?>
		</div><!--#container-->
	</nav>
	<div id="cc_spacer"></div><!-- used to clear fixed navigation by the themes js -->

	<?php if( 'h-title-tagline' === $header_type ) {?>
	<div class="site-header">
		<div class="site-branding">
			<a class="home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
				<h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</a>
		</div><!--.site-branding-->
	</div><!--.site-header-->
	<?php } ?>

</header>
<div id="content" class="site-content">
