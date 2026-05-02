<?php
/**
 * Template part for displaying header v4.
 *
 * @package Delaware
 */

?>

<div class="header-main">
	<div class="site-contact">
		<div class="container">
			<div class="row-flex">
				<div class="col-flex-lg-2 col-flex-md-6 col-flex-sm-6 col-flex-xs-10">
					<div class="site-logo">
						<?php get_template_part( 'parts/logo' ); ?>
					</div>
					<!-- .site-branding -->
				</div>
				<div class="col-flex-lg-10 hidden-md hidden-xs hidden-sm">
					<div class="site-extra-text">
						<?php if ( is_active_sidebar( 'header-contact' ) ) : ?>
							<?php
							ob_start();
							dynamic_sidebar( 'header-contact' );
							$output = ob_get_clean();

							echo apply_filters( 'dl_header_contact', $output );
							?>
						<?php endif; ?>
					</div>
				</div>

				<div class="hidden-lg col-flex-md-6 col-flex-sm-6 col-flex-xs-2 nav-mobile">
					<?php delaware_menu_icon(); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="site-menu hidden-md hidden-xs hidden-sm">
		<div class="container">
			<div class="main-menu">
				<nav id="site-navigation" class="main-nav primary-nav nav">
					<?php
					if ( has_nav_menu( 'primary' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'primary',
								'container'      => false,
								'menu_class'     => 'menu',
							)
						);
					}
					?>
				</nav>
				<ul class="menu-extra">
					<?php delaware_socials_menu_item(); ?>
					<?php delaware_search_menu_item(); ?>
				</ul>
			</div>
		</div>
	</div>
</div>