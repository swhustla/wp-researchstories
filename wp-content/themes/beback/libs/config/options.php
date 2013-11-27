<?php 
/**
 * BeBack options.
 *
 * @category   Wordspop
 * @package    BeBack
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 */

$wpop_options = array(

    // General
    array(
        'type'      => 'general',
        'overrides' => array(
            'logo'  => array(
                'std' => WPOP_THEME_URL . '/images/logo.png'
            )
        )
    ),
    array(
        'type'      => 'text',
        'title'     => __( 'Site Title', WPOP_THEME_SLUG ),
        'name'      => 'site_title',
        'desc'      => __( 'Set your site title while using this theme. eg. "We\'ll be Back" or "Under Construction".', WPOP_THEME_SLUG ),
        'std'       => __( 'Under Construction', WPOP_THEME_SLUG )
    ),
    array(
        'type'      => 'date',
        'title'     => __( 'Launch Date', WPOP_THEME_SLUG ),
        'name'      => 'launch_date'
    ),
    array(
        'type'      => 'textarea',
        'title'     => __( 'Short Description', WPOP_THEME_SLUG ),
        'name'      => 'intro',
        'desc'      => __( 'What\'s going on your site. It\'s OK to add &lt;a&gt;.', WPOP_THEME_SLUG ),
        'std'       => __( 'Hi there, thanks for visited! Unfortunately this site is under construction, meanwhile feel free to follow us on <a href="http://twitter.com/wordspop">twitter</a> to get latest update.', WPOP_THEME_SLUG ),
        'attrs'     => array('rows' => 5)
    ),
    array(
        'type'      => 'text',
        'title'     => __( 'Current Progress (%)', WPOP_THEME_SLUG ),
        'name'      => 'progress'
    ),
    
    // Footer
    array(
        'type'      => 'footer'
    ),
    array(
        'type'      => 'radio',
        'name'      => 'show_wordspop',
        'title'     => __( 'Show Wordspop Logo', WPOP_THEME_SLUG ),
        'options'   => array(
            'yes' => __( 'Yes (recommended)', WPOP_THEME_SLUG ),
            'no' => __( 'No', WPOP_THEME_SLUG )
        ),
        'std'       => 'yes'
    )

);
