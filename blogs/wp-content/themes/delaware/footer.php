<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Delaware
 */
?>
			</div> <!-- .row -->
		</div> <!-- .container -->
	</div><!-- #content -->

	<?php do_action( 'delaware_before_footer' ); ?>

	<footer class="site-footer">
		<?php do_action( 'delaware_footer' ) ?>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
