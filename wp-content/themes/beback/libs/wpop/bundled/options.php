<?php
/**
 * Bundled options
 *
 * @category   Wordspop
 * @package    WPop
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 */
$wpop_bundled_options = array(
    // General
    'general' => array(
        array(
            'type'      => 'heading',
            'title'     => __( 'General', WPOP_THEME_SLUG ),
            'name'      => 'general',
            'icon'      => 'general'
        ),
        array(
            'type'      => 'upload',
            'title'     => __( 'Logo', WPOP_THEME_SLUG ),
            'name'      => 'logo',
            'desc'      => __( 'An image that will represent your website\'s logo. A PNG transparent image would be the great one.', WPOP_THEME_SLUG )
        ),
        array(
            'type'      => 'upload',
            'title'     => __( 'Favicon', WPOP_THEME_SLUG ),
            'name'      => 'favicon',
            'desc'      => __( '16px x 16px PNG/GIF/Ico image that will represent your website\'s icon. You can create it using online tools such as <a href="http://favikon.com/">favikon</a>.', WPOP_THEME_SLUG )
        )
    ),

    // Header
    'header' => array(
        array(
            'type'      => 'heading',
            'name'      => 'header',
            'title'     => __( 'Header', WPOP_THEME_SLUG ),
            'icon'      => 'header'
        ),
        array(
            'type'      => 'textarea',
            'name'      => 'header_extras',
            'title'     => __( 'Extras', WPOP_THEME_SLUG ),
            'desc'      => __( 'Additional custom codes to be added to the header.', WPOP_THEME_SLUG ),
            'std'       => '',
            'attrs'     => array( 'rows' => 10 )
        ),
    ),

    // Footer
    'footer' => array(
        array(
            'type'      => 'heading',
            'name'      => 'footer',
            'title'     => __( 'Footer', WPOP_THEME_SLUG ),
            'icon'      => 'footer'
        ),
        array(
            'type'      => 'textarea',
            'name'      => 'copyright',
            'title'     => __( 'Copyright', WPOP_THEME_SLUG ),
            'desc'      => __( 'Enter the Copyright notice of the website.', WPOP_THEME_SLUG ),
            'std'       => sprintf( '%s &copy; %s %s. %s.', __( 'Copyright', WPOP_THEME_SLUG ), date( 'Y' ), get_bloginfo( 'title' ), __( 'All rights reserved', WPOP_THEME_SLUG ) )
        ),
        array(
            'type'      => 'textarea',
            'name'      => 'tracking_code',
            'title'     => __( 'Tracking Code', WPOP_THEME_SLUG ),
            'desc'      => __( 'Paste the <a href="http://google.com/analytics/">Google Analytics</a> (nor others) tracking code to be added to footer.', WPOP_THEME_SLUG ),
            'attrs'     => array( 'rows' => 10 )
        ),
        array(
            'type'      => 'textarea',
            'name'      => 'footer_extras',
            'title'     => __( 'Extras', WPOP_THEME_SLUG ),
            'desc'      => __( 'Additional custom codes to be added to the footer.', WPOP_THEME_SLUG ),
            'attrs'     => array( 'rows' => 10 )
        )
    ),

    // Styling
    'styling' => array(
        array(
            'type'      => 'heading',
            'title'     => __( 'Styling', WPOP_THEME_SLUG ),
            'name'      => 'styling',
            'icon'      => 'styling'
        ),
        array(
            'type'      => 'checkbox',
            'name'      => 'styling_enable',
            'title'     => __( 'Custom Style', WPOP_THEME_SLUG ),
            'desc'      => __( 'Enable and applies custom styling to the theme.', WPOP_THEME_SLUG )
        ),
        array(
            'type'      => 'color',
            'name'      => 'background_color',
            'title'     => __( 'Background Color', WPOP_THEME_SLUG ),
            'desc'      => __( 'Custom body background color.', WPOP_THEME_SLUG )
        ),
        array(
            'type'      => 'upload',
            'name'      => 'background_image',
            'title'     => __( 'Background Image', WPOP_THEME_SLUG ),
            'desc'      => __( 'Custom body background image.', WPOP_THEME_SLUG )
        ),
        array(
            'type'      => 'select',
            'name'      => __( 'background_repeat', WPOP_THEME_SLUG ),
            'title'     => __( 'Background Image Repeat', WPOP_THEME_SLUG ),
            'options'   => array(
                'no-repeat' => __( 'No Repeat', WPOP_THEME_SLUG ),
                'repeat-x'  => __( 'Repeat Horizontally', WPOP_THEME_SLUG ),
                'repeat-y'  => __( 'Repeat Vertically', WPOP_THEME_SLUG ),
                'repeat'    => __( 'Repeat Both Horizontally and Vertically', WPOP_THEME_SLUG )
            )
        ),
        array(
            'type'      => 'select',
            'name'      => 'background_position',
            'title'     => __( 'Background Image Position', WPOP_THEME_SLUG ),
            'options'   => array(
                'top left'      => __( 'Top Left', WPOP_THEME_SLUG ),
                'top center'    => __( 'Top Center', WPOP_THEME_SLUG ),
                'top right'     => __( 'Top Right', WPOP_THEME_SLUG ),
                'center left '  => __( 'Center Left', WPOP_THEME_SLUG ),
                'center center' => __( 'Center Center', WPOP_THEME_SLUG ),
                'center right'  => __( 'Center Right', WPOP_THEME_SLUG ),
                'bottom left'   => __( 'Bottom Left', WPOP_THEME_SLUG ),
                'bottom center' => __( 'Bottom Center', WPOP_THEME_SLUG ),
                'bottom right'  => __( 'Bottom Right', WPOP_THEME_SLUG )
            )
        ),
        array(
            'type'      => 'color',
            'name'      => 'link_color',
            'title'     => __( 'Link', WPOP_THEME_SLUG ),
            'desc'      => __( 'Customize the color of the link.', WPOP_THEME_SLUG )
        ),
        array(
            'type'      => 'color',
            'name'      => 'link_hover_color',
            'title'     => __( 'Link Hover', WPOP_THEME_SLUG ),
            'desc'      => __( 'Customize the color of the link hover.', WPOP_THEME_SLUG )
        ),
        array(
            'type'      => 'textarea',
            'name'      => 'custom_css',
            'title'     => __( 'Custom CSS', WPOP_THEME_SLUG ),
            'desc'      => __( 'Additional custom css to be added to template.', WPOP_THEME_SLUG ),
            'attrs'     => array( 'rows' => 10 )
        )
    ),

    // Typography
    'typography'  => array(
        array(
            'type'      => 'heading',
            'title'     => __( 'Typography', WPOP_THEME_SLUG ),
            'name'      => 'typography',
            'icon'      => 'typography',
            'desc'      => __( 'Here you can use custom typography which also support the <a href="http://code.google.com/webfonts">Google Fonts</a>.', WPOP_THEME_SLUG )
        ),
        array(
            'type'      => 'character',
            'name'      => 'typography_body',
            'title'     => __( 'Document', WPOP_THEME_SLUG ),
            'selector'  => 'body'
        ),
        array(
            'type'      => 'character',
            'name'      => 'typography_h1',
            'title'     => __( 'Heading 1', WPOP_THEME_SLUG ),
            'selector'  => 'h1'
        ),
        array(
            'type'      => 'character',
            'name'      => 'typography_h2',
            'title'     => __( 'Heading 2', WPOP_THEME_SLUG ),
            'selector'  => 'h2'
        ),
        array(
            'type'      => 'character',
            'name'      => 'typography_h3',
            'title'     => __( 'Heading 3', WPOP_THEME_SLUG ),
            'selector'  => 'h3'
        ),
        array(
            'type'      => 'character',
            'name'      => 'typography_h4',
            'title'     => __( 'Heading 4', WPOP_THEME_SLUG ),
            'selector'  => 'h4'
        ),
        array(
            'type'      => 'character',
            'name'      => 'typography_h5',
            'title'     => __( 'Heading 5', WPOP_THEME_SLUG ),
            'selector'  => 'h5'
        ),
        array(
            'type'      => 'character',
            'name'      => 'typography_blockquote',
            'title'     => __( 'Blockquote', WPOP_THEME_SLUG ),
            'selector'  => 'blockquote'
        ),
        array(
            'type'      => 'character',
            'name'      => 'typography_ul',
            'title'     => __( 'List', WPOP_THEME_SLUG ),
            'selector'  => 'ul'
        )
    ),

    // Slider
    'slider' => array(
        array(
            'type'      => 'heading',
            'title'     => __( 'Slider', WPOP_THEME_SLUG ),
            'name'      => 'slider',
            'icon'      => 'slider'
        ),
        array(
            'type'      => 'radio',
            'attrs'     => array( 'class' => 'slider_source' ),
            'title'     => __( 'Source', WPOP_THEME_SLUG ),
            'name'      => 'slider_source',
            'desc'      => __( 'Which source would you like to use as the slider. (You should re-create the entries everytime the source changed)', WPOP_THEME_SLUG ),
            'options'   => array(
                'posts'      => 'Posts',
                'pages'      => 'Pages',
                'categories' => 'Categories',
                'tags'       => 'Tags'
            ),
            'std'       => 'posts'
        ),
        array(
            'type'      => 'slider_entries',
            'title'     => __( 'Entries', WPOP_THEME_SLUG ),
            'name'      => 'slider_entries',
            'desc'      => __( 'Add the entry which you like to show on slider, drag and drop to reorder it. (Note: for Categories and Tags, the posts will be automatically ordered by date)', WPOP_THEME_SLUG ),
            'reference' => 'slider_source'
        )
    ),

    // Scheme
    'scheme' => array(
        array(
            'type'      => 'heading',
            'name'      => 'colorscheme',
            'title'     => __( 'Scheme', WPOP_THEME_SLUG ),
            'icon'      => 'scheme'
        ),
        array(
            'type'      => 'scheme',
            'name'      => 'scheme',
            'title'     => __( 'Available Schemes', WPOP_THEME_SLUG ),
            'desc'      => __( 'Click the screenshot and press "Save Changes" to activate.', WPOP_THEME_SLUG ),
            'options'   => array( 'WPop_Theme', 'availableSchemes' ),
            'std'       => 'default'
        )
    )

);
