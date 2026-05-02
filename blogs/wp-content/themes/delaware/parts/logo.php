<?php
/**
 * Hooks for template logo
 *
 * @package Solo
 */

$header_layout = delaware_get_option( 'header_layout' );
$logo_dark  = delaware_get_option( 'logo_dark' );
$logo_light = delaware_get_option( 'logo_light' );

if ( ! $logo_dark ) {
	$logo_dark = get_template_directory_uri() . '/img/logo.png';
}

if ( ! $logo_light ) {
	$logo_light = get_template_directory_uri() . '/img/logo-light.png';

	if ( $header_layout == 'v6' ) {
		$logo_light = get_template_directory_uri() . '/img/logo-light-2.png';
	}
}

if (
	( is_page_template( 'template-homepage.php' ) &&  delaware_get_option( 'header_transparent' ) == true && $header_layout == 'v5' )
	|| $header_layout == 'v6'
	|| $header_layout == 'v4'
	)
{
	$logo = $logo_light;

} else {
	$logo = $logo_dark;
}

?>
	<a href="<?php echo esc_url( home_url() ) ?>" class="logo">
		<img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo get_bloginfo( 'name' ); ?>" class="logo">
	</a>
<?php

printf(
	'<%1$s class="site-title"><a href="%2$s" rel="home">%3$s</a></%1$s>',
	is_home() || is_front_page() ? 'h1' : 'p',
	esc_url( home_url( '' ) ),
	get_bloginfo( 'name' )
);
?>
<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
