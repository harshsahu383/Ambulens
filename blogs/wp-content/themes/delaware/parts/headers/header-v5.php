<?php
/**
 * Template part for displaying header v5.
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
				</div>

				<div class="site-menu col-flex-lg-10 hidden-md hidden-xs hidden-sm">
					<ul class="menu-extra menu-extra-start">
						<?php delaware_socials_menu_item(); ?>
					</ul>
					<ul class="menu-extra menu-extra-end">
						<?php delaware_cart_menu_item(); ?>
					</ul>
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

				</div>

				<div class="hidden-lg col-flex-md-6 col-flex-sm-6 col-flex-xs-2 nav-mobile">
					<?php delaware_menu_icon(); ?>
				</div>
			</div>
		</div>
	</div>

</div>