<?php
function wpop_get_header( $name = null ) {
    do_action( 'get_header', $name );

    $prefix = str_replace( TEMPLATEPATH . '/', '', WPOP_TEMPLATEPATH );

    $templates = array();
    if ( isset($name) ) {
        $templates[] = "{$prefix}/header-{$name}.php";
    }

    $templates[] = "{$prefix}/header.php";
    
    locate_template($templates, true);
}

function wpop_get_footer( $name = null ) {
    do_action( 'get_footer', $name );

    $prefix = str_replace( TEMPLATEPATH . '/', '', WPOP_TEMPLATEPATH );

    $templates = array();
    if ( isset($name) ) {
        $templates[] = "{$prefix}/footer-{$name}.php";
    }

    $templates[] = "{$prefix}/footer.php";
    
    locate_template($templates, true);
}

/**
 * Get theme option
 *
 * @category   Wordspop
 * @package    WPop
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 */
function wpop_get_option( $name, $echo = false ) {
    if ( !class_exists( 'WPop_Theme' ) ) {
        return false;
    }

    if ( $echo ) {
        echo WPop_Theme::getOption( $name );
    } else {
        return WPop_Theme::getOption( $name );
    }
}

/**
 * Day options list
 *
 * @category   Wordspop
 * @package    WPop
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 */
function wpop_days_options() {
    $options = array();
    for ( $i = 1; $i <= 31; $i++ ) {
      $options[$i] = $i;
    }
    return $options;
}

/**
 * Month options list
 *
 * @category   Wordspop
 * @package    WPop
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 */
function wpop_months_options() {
    $options = array();
    for ( $i = 1; $i <= 12; $i++ ) {
        $options[$i] = strftime( '%B', mktime( 0, 0, 0, $i, 1, 1970 ) );
    }

    return $options;
}

/**
 * Posts options list
 *
 * @category   Wordspop
 * @package    WPop
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 */
function wpop_posts_options() {
    $posts = get_posts( 'numberposts=-1' );
    $options = array();
    foreach( $posts as $post ){
        $options[ $post->ID ] = $post->post_title;
    }
    return $options;
}

/**
 * Pages options list
 *
 * @category   Wordspop
 * @package    WPop
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 */
function wpop_pages_options() {
    $posts = get_posts( 'post_type=page' );
    $options = array();
    foreach( $posts as $post ){
        $options[ $post->ID ] = $post->post_title;
    }
    return $options;
}

/**
 * Categories options list
 *
 * @category   Wordspop
 * @package    WPop
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 */
function wpop_categories_options() {
    $cats = get_categories( 'hide_empty=0' );
    $options = array();
    foreach( $cats as $cat ){
        $options[ $cat->cat_ID ] = $cat->name;
    }
    return $options;
}

/**
 * Tags options list
 *
 * @category   Wordspop
 * @package    WPop
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 */
function wpop_tags_options() {
    $tags = get_tags( 'hide_empty=0' );
    $options = array();
    foreach( $tags as $tag ){
        $options[ $tag->term_id ] = $tag->name;
    }
    return $options;
}
