<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Delaware
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php do_action('delaware_before_site'); ?>
<div id="page" class="hfeed site">

	<?php do_action( 'delaware_before_header' ); ?>

	<header id="masthead" class="site-header">
		<?php do_action( 'delaware_header' ); ?>
	</header>

	<?php do_action( 'delaware_after_header' ); ?>

	<?php
	$container = 'container';

	if ( is_page_template( 'template-homepage.php' ) ||
		is_page_template('template-fullwidth.php' ) ) {
		$container = 'container-fluid';
	}

	?>

	<div id="content" class="site-content">
		<div class="<?php echo esc_attr( $container ) ?>">
			<div class="row">
