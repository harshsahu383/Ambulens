<?php
/**
 * Template part for displaying footer.
 *
 * @package Delaware
 */

$bg_color = delaware_get_option( 'footer_background_color' );
$bg_image = delaware_get_option( 'footer_background_image' );
$bg_h     = delaware_get_option( 'footer_background_horizontal' );
$bg_v     = delaware_get_option( 'footer_background_vertical' );
$bg_r     = delaware_get_option( 'footer_background_repeat' );
$bg_a     = delaware_get_option( 'footer_background_attachment' );
$bg_s     = delaware_get_option( 'footer_background_size' );

$style = array(
    ! empty( $bg_color ) ? 'background-color: ' . $bg_color . ';' : '',
    ! empty( $bg_image ) ? 'background-image: url( ' . esc_url( $bg_image ) . ' );' : '',
    ! empty( $bg_h ) ? 'background-position-x: ' . $bg_h . ';' : '',
    ! empty( $bg_v ) ? 'background-position-y: ' . $bg_v . ';' : '',
    ! empty( $bg_r ) ? 'background-repeat: ' . $bg_r . ';' : '',
    ! empty( $bg_a ) ? 'background-attachment:' . $bg_a . ';' : '',
    ! empty( $bg_s ) ? 'background-size: ' . $bg_s . ';': '',
);

?>

<nav class="footer-wrapper" style="<?php echo esc_attr( implode( '', $style ) ); ?>">
    <?php delaware_footer_widgets(); ?>
    <?php delaware_footer_copyright(); ?>
</nav>