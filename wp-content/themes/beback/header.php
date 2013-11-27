<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?php  echo esc_html( wpop_get_option( 'site_title' ) ); ?></title>
<?php wp_head(); ?>
</head>
<script type="text/javascript">
var BeBack_Settings = {
  launchDate_year:  <?php echo date('Y', strtotime( wpop_get_option( 'launch_date' )  ) ); ?>,
  launchDate_month: <?php echo date('n', strtotime( wpop_get_option( 'launch_date' )  ) ); ?>,
  launchDate_day:   <?php echo date('j', strtotime( wpop_get_option( 'launch_date' )  ) ); ?>,
  progressBoxImage: '<?php bloginfo( 'template_directory' ); ?>/images/progressbar-frame.gif' ,
  progressBarImage: '<?php bloginfo( 'template_directory' ); ?>/images/progressbar-yellow.gif'
};
</script>
<body>
<div class="container">
  <div class="logo">
    <h1><a href="<?php bloginfo('url'); ?>" title="<?php  echo esc_html( wpop_get_option( 'site_title' ) ); ?>"><img src="<?php wpop_get_option( 'logo', true ); ?>" alt="logo" /></a></h1>
  </div>