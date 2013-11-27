<?php
/**
 * BeBack extra functions.
 *
 * @category   Wordspop
 * @package    BeBack
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 */

/**
 * WP hook: init
 *
 * @return void
 */
function beback_init() {
    if ( !is_admin() ) {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery_progressbar', WPOP_THEME_URL . '/js/jquery.progressbar.min.js', array( 'jquery' ) );
        wp_enqueue_script( 'jquery_countdown', WPOP_THEME_URL . '/js/jquery.countdown.js', array( 'jquery' ) );
        wp_enqueue_script( 'beback', WPOP_THEME_URL . '/js/init.js', array( 'jquery',  'jquery_progressbar', 'jquery_countdown' ) );
    }
}
