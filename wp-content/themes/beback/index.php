<?php get_header(); ?>
  <div class="mainbox">
    <div class="progress">
      <h2><?php _e( 'Our Website Progress', WPOP_THEME_SLUG ); ?></h2>
      <div class="progress-bar">
        <span class="progressBar" id="spaceused1">
        <?php if ( (float) wpop_get_option( 'progress' ) < 0 || (float) wpop_get_option( 'progress' ) > 100 ): ?><span id="progress-invalid"><?php _e( 'Invalid progress value', WPOP_THEME_SLUG ); ?></a><?php else: ?>
        <?php printf( '%d%%', wpop_get_option( 'progress' ) ); ?></span>
        <?php endif; ?>
      </div>
      <h2><?php _e( 'Launch Date', WPOP_THEME_SLUG ); ?></h2>
      <div class="launch-date">
        <div class="launch-date-display">
          <div class="month"><?php echo date('n', strtotime( wpop_get_option( 'launch_date' ) ) ); ?></div>
          <div class="day"><?php echo date('j', strtotime( wpop_get_option( 'launch_date' ) ) ); ?></div>
          <div class="year"><?php echo date('Y', strtotime( wpop_get_option( 'launch_date' ) ) ); ?></div>
        </div>
      </div>
      <div class="countdown">&nbsp;</div>
    </div>
  </div>
  <div class="intro">
    <?php echo wp_kses( wpop_get_option( 'intro') , array( 'a' => array( 'href' => array(),'title' => array() ) ) ); ?>
  </div>
<?php get_footer(); ?>
