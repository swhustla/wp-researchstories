<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Spun
 * @since Spun 1.0
 */
?>

	</div><!-- #main .site-main -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php do_action( 'spun_credits' ); ?>
			<a href="http://www.researchstories.co.uk/" title="<?php esc_attr_e( 'We tell stories', 'spun' ); ?>" rel="generator"><?php printf( __( 'Copyright 2014 by Research Stories Ltd %s', 'spun' ), '' ); ?></a>
			<span class="sep"> | </span>
		</div><!-- .site-info -->
	</footer><!-- #colophon .site-footer -->
</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>

</body>
</html>