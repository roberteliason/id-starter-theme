<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _s
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="page" class="site">
    <header id="masthead" class="site-header" role="banner">
		<div class="site-branding">
			<?php
			if ( is_front_page() && is_home() ) : ?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php endif; ?>
		</div><!-- .site-branding -->

        <div class="user-profile">
            <?php if( is_user_logged_in() ) : ?>
                <?php echo do_shortcode( '[namaste-points]' ) ?>&nbsp;<?php _e( 'Points', 'ehalsa' ) ?>
            <?php endif; ?>
        </div>

        <nav id="site-navigation" class="main-navigation" role="navigation">
            <?php if( is_user_logged_in() ) : ?>
                <a href="<?php echo wp_logout_url( home_url() ) ?>"><?php _e('Log out', 'ehalsa') ?></a>
            <?php endif; ?>
            <!--
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', '_s' ); ?></button>
			<?php wp_nav_menu( array( 'theme_location' => 'menu-1', 'menu_id' => 'primary-menu' ) ); ?>
			-->
	    </nav>
	    <!-- #site-navigation -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
