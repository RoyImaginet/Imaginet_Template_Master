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



	
<body <?php body_class(); ?>>
	<div class="off-canvas-wrapper">
		<div class="off-canvas-wrapper-inner" data-off-canvas-wrapper>
			<div class="off-canvas-content" data-off-canvas-content>
				<header class="header clear" role="banner" id="header">
					<div class="flex_container">
						<div class="logo">
							<?php if (has_custom_logo()) : ?>
								<?php
									$custom_logo_id = get_theme_mod('custom_logo');
									$image = wp_get_attachment_image_src($custom_logo_id, 'full');
									?>
								<a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo get_bloginfo('name') ?>" class="site_logo" rel="home" itemprop="url">
									<img src="<?php echo $image[0]; ?>" alt="<?php echo get_bloginfo('name') ?>" itemprop="logo">
								</a>
							<?php else : ?>
								<a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo get_bloginfo('name'); ?>" class="site_logo" rel="home" itemprop="url">
									<?php echo get_bloginfo('name'); ?>
								</a>
							<?php endif; ?>
						</div>
						<nav class="nav" role="navigation">
							<div class="mobile_menu_button">
								<button type="button" class="button triggerMobileMenu" data-toggle="offCanvas" data-fontsize="18" aria-expanded="false" aria-controls="offCanvas">
									<span></span><span></span><span></span>
								</button>
							</div>
							<?php
							wp_nav_menu(array(
								'theme_location' => 'main-menu',
								'menu_id' => 'main-menu',
								'menu_class' => 'menu',
								'container_class' => 'wrap_main_menu'
							));
							?>
						</nav>
					</div>
				</header>
