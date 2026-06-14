<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<a class="skip-link screen-reader-text" href="#primary-content">
		<?php esc_html_e( 'Skip to content', 'imaginet' ); ?>
	</a>

	<header class="site-header" id="header">
	    <div class="flex-container">
	        
	        <div class="site-logo">
	            <?php has_custom_logo() ? the_custom_logo() : ''; ?>
	        </div>
	
	        <nav class="site-navigation" id="site-navigation" aria-label="Main Navigation">
	            
	            <button type="button" class="menu-toggle triggerMobileMenu" aria-controls="main-menu" aria-expanded="false">
	                <span class="screen-reader-text">Toggle Menu</span>
	                <span class="hamburger-bar"></span>
	                <span class="hamburger-bar"></span>
	                <span class="hamburger-bar"></span>
	            </button>
	
	            <div class="main-menu-drawer">
	                <?php
	                wp_nav_menu( array(
	                    'theme_location'  => 'main-menu',
	                    'menu_id'         => 'main-menu',
	                    'menu_class'      => 'menu-items-list',
	                    'container'       => false, // Strip out redundant wrappers
	                    'fallback_cb'     => false,
	                ) );
	                ?>
	            </div>
	
	        </nav>
	
	    </div>
	</header>
