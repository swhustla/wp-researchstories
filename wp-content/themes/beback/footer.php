  <div class="footer">
    <div class="copy"><?php echo nl2br( wpop_get_option( 'copyright' ) ); ?></div>
    <?php if ( wpop_get_option( 'show_wordspop' ) == 'yes' ): ?><a id="wordspop" href="http://wordspop.com" title="<?php _e( 'Theme by', WPOP_THEME_SLUG ); ?> Wordspop"><?php _e( 'Theme by', WPOP_THEME_SLUG ); ?> Wordspop</a><?php endif; ?>
  </div>
</div>
<?php wp_footer(); ?>
</body>
</html>