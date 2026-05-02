<?php
/**
 * Template part for displaying header v6.
 *
 * @package Delaware
 */

?>

<div class="header-main">
	<div class="logo-wrapper">
		<div class="container">
			<div class="row-flex">
				<div class="site-logo col-flex-md-6 col-flex-sm-6 col-flex-xs-10">
					<?php get_template_part( 'parts/logo' ); ?>
				</div>

				<div class="hidden-lg col-flex-md-6 col-flex-sm-6 col-flex-xs-2 nav-mobile">
					<?php delaware_menu_icon(); ?>
				</div>
			</div>
		</div>
	</div>

	<div class="site-contact hidden-md hidden-xs hidden-sm">
		<div class="container">
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
								'menu_class'     => 'menu',
							)
						);
					}
					?>
				</nav>
				<ul class="menu-extra">
					<?php delaware_cart_menu_item(); ?>
				</ul>
			</div>
		</div>
	</div>
</div>