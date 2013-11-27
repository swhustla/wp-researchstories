<?php
/**
 * Wordspop functions to render page.
 *
 * @category   Wordspop
 * @package    Functions
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 */

/**
 * Display theme options page
 *
 * @category   Wordspop
 * @package    WPop
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 */
function wpop_theme_settings( WPop_Theme $theme ) {
    // Works out on the options to collect the headings
    $headings = array();
    $options = $theme->options();
    foreach( $options as $option ) {
        if ( $option['type'] == 'heading' ) {
            $headings[ $option['name'] ] = array(
                'title' => $option['title'],
                'icon'  => $option['icon']
            );
        }
    }

    // Load the WPop_Form
    require_once 'wpop_form.php';
?>
<div id="wpop_container" class="wrap" style="display: none;">
  <form id="wpop_theme_form" enctype="multipart/form-data" method="post" action="options.php">
    <?php settings_fields( 'wpop_theme_options' ) ?> 
    <div id="wpop_body">
      <!-- sidebar -->
      <div id="wpop_sidebar">
        <div id="wpop_logo">
          <h2>Wordspop</h2>
          <p>Framework <?php echo WPOP_VERSION; ?></p>
        </div>
        <div id="wpop_nav">
          <ul>
            <?php foreach( $headings as $name => $info ): ?>
            <li id="wpop_nav_<?php echo $name; ?>" class="<?php echo $info['icon']; ?>"><a href="#wpop_options_<?php echo $name; ?>"><span><?php echo esc_html($info['title']); ?></span></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <!-- end sidebar -->
      <!-- main layout -->
      <div id="wpop_main">
        <div id="wpop_main_inner">
          <?php if ( $theme->notification() ): ?><div id="wpop_notification"><?php echo $theme->notification(); ?></div><?php endif; ?>
          <div id="wpop_message" class="wpop_message" style="display: none;">&nbsp;</div>
          <!-- header -->
          <div id="wpop_header">
            <div id="wpop_theme_info">
              <h2><?php echo $theme->name(); ?></h2>
              <p><?php printf( __( 'Version %s', WPOP_THEME_SLUG ), $theme->version() ); ?></p>
              <div class="clear"></div>
            </div>
           <div id="wpop_menu">
              <div id="wpop_support">
                <ul>
                  <li><a href="http://docs.wordspop.com/theme/<?php echo $theme->slug(); ?>#changelog-<?php echo $theme->version(); ?>" target="_blank" title="<?php _e( 'View theme changelog', WPOP_THEME_SLUG ); ?>"><?php _e('Changelog', WPOP_THEME_SLUG); ?></a></li>
                  <li><a href="http://docs.wordspop.com/theme/<?php echo $theme->slug(); ?>" target="_blank" title="<?php _e( 'View theme documentation', WPOP_THEME_SLUG ); ?>"><?php _e( 'Documentation', WPOP_THEME_SLUG ); ?></a></li>
                  <li><a href="http://forum.wordspop.com/" target="_blank" title="<?php _e( 'Visit the Wordspop support forum', WPOP_THEME_SLUG ); ?>"><?php _e( 'Forum', WPOP_THEME_SLUG ); ?></a></li>
                </ul>
              </div>
              <div id="wpop_submit">
                <input type="submit" class="button-primary" value="<?php _e( 'Save Changes', WPOP_THEME_SLUG ); ?>">
              </div>
              <div class="clear"></div>
            </div>
          </div>
          <!-- end header -->
          <!-- content -->
          <div id="wpop_content">

            <?php $heading = 0; ?>
            <?php foreach( $options as $i => $option ): ?>
              <?php if ( $option['type'] == 'heading' ): // hits the heading ?>

                <?php if ( $heading > 0 ): // close if heading greater than zero ?>
            </div>
            <!-- end group -->
                <?php endif; ?>

            <!-- start group -->
            <div id="wpop_options_<?php echo $option['name'] ?>" class="group">
            <?php if ( !empty( $option['desc'] ) ): ?><p class="group_desc"><?php echo $option['desc']; ?></p><?php endif; ?>
                <?php $heading++; ?>

              <?php else: // hits the input option ?>
              <div class="section section_<?php echo $option['type']; ?>">
                <h3><?php echo esc_html( $option['title'] ); ?></h3>
                <div class="option">
                  <div class="input"><?php echo WPop_Form::input( "wpop_theme_{$option['name']}", $option ); ?></div>
                  <?php if ( $option['type'] != 'checkbox' && isset( $option['desc'] ) ): // skip this for checkbox ?>
                  <div class="desc"><?php echo $option['desc']; ?></div>
                  <?php endif; ?>
                  <div class="clear"></div>
                </div>
              </div>
              <?php endif; ?>

              <?php if ( $i == count( $options ) - 1 ): // close the heading on the last option ?>
            </div>
            <!-- end group -->
              <?php endif; ?>

            <?php endforeach; ?>

          </div>
          <!-- end content -->
        </div>
        <!-- end main inner -->
      </div>
      <!-- end main layout -->
      <div class="clear"></div>
    </div>
    <!-- end body -->
    <!-- bottom -->
    <div id="wpop_bottom">
      <div id="wpop_bottom_left">&nbsp;</div>
      <div id="wpop_bottom_right">
        <p><input type="submit" class="button-primary" value="<?php _e( 'Save Changes', WPOP_THEME_SLUG  ); ?>"></p>
        <?php if ( $theme->note() ): ?><p id="wpop_theme_note"><?php echo $theme->note(); ?></p><?php endif; ?>
      </div>
      <div id="wpop_footer">
        <p id="wpop_copy"><?php if ( $theme->copyright() ): ?><?php echo esc_html( $theme->name() ); ?> <?php echo esc_html( $theme->copyright() ); ?>. <?php endif; ?>
        Wordspop Framework &copy; 2011 Wordspop.<br />
        All rights reserved.</p>
      </div>
      <div class="clear"></div>
    </div>
    <!-- end bottom -->
  </form>
  <div id="slider_entries_dummy_entry" class="widget-top" style="display: none;">
    <div class="widget-title-action">
      <a class="widget-action slider_entries_remove" href="#" title="<?php _e( 'Remove', WPOP_THEME_SLUG ); ?>"><?php _e( 'Remove', WPOP_THEME_SLUG ); ?></a>
      <span class="slider_entry_value" style="display: none;">0</span>
    </div>
    <div class="widget-title"><h4><?php _e( 'Title', WPOP_THEME_SLUG ); ?></h4></div>
  </div>
</div>
<?php
}

/**
 * Display available Wordspop themes
 *
 * @category   Wordspop
 * @package    WPop
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 */
function wpop_themes(){
    include_once ABSPATH . WPINC . DS . 'class-feed.php';
    $cache = new WP_Feed_Cache_Transient( '', md5( WPOP_FEED_THEMES_URL ), '' );
    $cache->unlink();
    $cache->load();

    $feed = fetch_feed( WPOP_FEED_THEMES_URL );
    if ( is_wp_error( $feed ) ) {
        wp_die( $feed->get_error_message() );
    }

    $items = $feed->get_items();
?>
<div class="wrap">
<div class="icon32" id="icon-wpop"><br></div>
<h2><?php _e( 'Themes by', WPOP_THEME_SLUG ); ?> Wordspop</h2>
<table cellspacing="0" cellpadding="0" id="availablethemes">
<tbody>
<?php
$rows = ceil( count( $items ) / 3 );
$k = 0;
?>
  <?php for ( $i = 0; $i < $rows; $i++ ): ?>
<tr>
  <?php for ( $j = 0; $j < 3; $j++ ): ?>
  <?php
    $pos = '';
    if ( $k % 3 == 0 ) {
      $pos = ' left';
    } else if ( $k % 3 == 2 ) {
      $pos = ' right';
    }
  ?>
  <td class="available-theme<?php echo $pos ?>">
    <?php if ( isset( $items[$k] ) ): ?><?php echo $items[$k]->get_description(); ?><?php else: ?>&nbsp;<?php endif; ?>
  </td>
  <?php $k++; ?>
  <?php endfor; ?>
</tr>
  <?php endfor; ?>
</tbody>
</table>
<br class="clear">
<br class="clear">
</div>
<?php
}
