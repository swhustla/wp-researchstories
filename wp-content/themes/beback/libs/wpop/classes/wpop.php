<?php
/**
 * Wordspop Framework
 *
 * @category   Wordspop
 * @package    WPop
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 */

/**
 * @see WPop_Theme
 */
require_once 'wpop_theme.php';

/**
 * @see WPop_Migration
 */
require_once 'wpop_migration.php';

/**
 * @see WPop_Widget
 */
require_once 'wpop_widget.php';

/**
 * @see WPop_Mobile
 */
require_once 'wpop_mobile.php';

/**
 * @see WPop_Utils
 */
require_once 'wpop_utils.php';

/**
 * @see WPop_API
 */
require_once 'wpop_api.php';

/**
 * @category   Wordspop
 * @package    WPop
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @author     Firman Wandayandi <firmanw@wordspop.com>
 */
class WPop
{
    /**
     * Initialization
     *
     * @access  public
     * @static
     */
    function init()
    {
        $theme = WPop_Theme::instance();
        
        if ( isset( $_GET[ 'full' ] ) ) {
            $scheme = 'http';
            if( isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] == 'on' ) {
                $scheme = 'https';
            }

            $url = parse_url( $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] );

            setcookie( 'full', 1, 0, '/' );
            header( 'Location: ' . $scheme . '://' .  $url[ 'path' ] );
            exit;
        }

        $mobile = WPop_Mobile::instance(); // Detect whether user is mobile or not
        if ( $mobile->isMobile() ) {
            if ( !is_admin() && is_dir( WPOP_THEME_MOBILE ) ) {
                if ( !isset( $_COOKIE[ 'full' ] ) && !isset( $_GET['full'] ) ) {
                    add_action('template_redirect', array('WPop', 'loadMobileTemplate'));
                }
            }
        } else {
            define( 'WPOP_TEMPLATEPATH', TEMPLATEPATH );
        }

        if ( is_admin() ) {
            add_action( 'init', array( 'WPop', 'setup' ) );
        }

        add_action( 'admin_init', array( 'WPop', 'loadAssets' ) );
        add_action( 'admin_menu', array( 'WPop', 'createMenu') );

        // Create a admin bar menu (WordPress 3.1 or later)
        if ( version_compare( get_bloginfo( 'version' ), '3.1', '>=' ) ) {
            // Put it after the updates menu (80)
            add_action( 'admin_bar_menu', array( 'WPop', 'createAdminBarMenu'), 90 );
        }

        $migration = new WPop_Migration;
        if ( $migration->isRequired() ) {
            $migrated = self::getOption( 'migrated' );
            if ( !$migrated || version_compare( $migrated, $theme->version(), 'lt' ) == -1 ) {
                add_action( 'admin_init', array( $migration, 'migrate' ));
            }
        }

        // Check for updates
        $api = WPop_API::instance();
        add_filter( 'pre_set_site_transient_update_themes', array( $api, 'getThemeUpdates' ) );
        add_filter( 'pre_set_transient_update_themes', array( $api, 'getThemeUpdates' ) );
        add_filter( 'site_transient_update_themes', array( $api, 'getThemeUpdates' ) );
        add_filter( 'transient_update_themes', array( $api, 'getThemeUpdates' ) );

        // Show notification if updates available
        $wpop_updates = get_site_transient( 'wpop_updates' );
        if ( is_object( $wpop_updates ) && isset( $wpop_updates->response ) ) {
            $slug = basename( TEMPLATEPATH );
            if ( isset( $wpop_updates->response[$slug] ) ) {
                $info = $wpop_updates->response[$slug];
                if ( version_compare( $theme->version(), $info['new_version'], 'lt' ) ) {
                    $theme_link = sprintf( '<a href="http://wordspop.com/themes/%s">%s</a> %s', $theme->slug(), $theme->name(), $info['new_version'] );
                    if ( $info['license'] == 'Free' ) {
                        $message = $theme_link .' ' . __( 'is available!', WPOP_THEME_SLUG ) . ' ' .
                                   sprintf( '<a href="%supdate-core.php">' . __( 'Please update now' ) . '</a>.<br />', get_admin_url() );
                    } else {
                        $message = $theme_link .' ' . __( 'is available!', WPOP_THEME_SLUG ) . '<br />' .
                                   __( 'Please contact sales@wordspop.com to get the download link and don\'t forget to tell us your order number.', WPOP_THEME_SLUG );
                    }
                    $theme->notification( $message );
                }
            }
        }
    }

    /**
     * Get option
     *
     * @param   string  $name                 Option name
     * @param   mixed   $default  (optional)  Default value
     * @return  mixed
     * @access  public
     * @static
     */
    function getOption( $name, $default = false )
    {
        return get_option( "wpop_{$name}", $default );
    }

    /**
     * Save the option
     *
     * @access  public
     * @param   mixed   $name   Option name
     * @param   mixed   $value  Value
     * @return  bool
     * @static
     */
    function saveOption( $name, $value )
    {
        return update_option( "wpop_{$name}", stripslashes( $value ) );
    }

    /**
     * Setup routine
     *
     * @access  public
     * @static
     */
    function setup()
    {
        // Register new post type
        register_post_type( 'wpopframework', array(
            'labels'            => array(
                'name' => 'Wordspop Internal'
            ),
            'public'            => true,
            'show_ui'           => false,
            'rewrite'           => false,
            'supports'          => array( 'title', 'editor' ),
            'query_var'         => false,
            'show_in_nav_menus' => false
        ));
    }

    /**
     * Get dummy post.
     *
     * Dummy post needed for attachement upload. A dummy post will be create if nothing found.
     *
     * @access  public
     * @return  integer
     * @static
     */
    function getDummyPost( $token = 'dummy' )
    {
        global $wpdb;

        $token = WPop_Utils::slugify( $token, '-' );

        $params = array(
            'post_type'       => 'wpopframework',
            'post_name'       => 'wpop-wf-' . $token,
        );

        $query = "SELECT ID FROM {$wpdb->posts} WHERE post_parent = 0";
        foreach ( $params as $column => $value ) {
            $query .= " AND {$column} = '{$value}'";
        }
        $query .= ' LIMIT 1';

        $post = $wpdb->get_row( $query) ;
        if ( count( $post ) ) {
            return $post->ID;
        }

        $params['post_status'] = 'draft';
        $params['comment_status'] = 'closed';
        $params['ping_status'] = 'closed';
        $params['post_title'] = ucwords( preg_replace( '/[-_]+/', ' ', $token ) );
        return wp_insert_post( $params );
    }

    /**
     * Call a callback
     *
     * Safely call a callback which is automatically load the script if needed and call function if exists.
     *
     * @param   string  $callback A string of function name or a callback
     * @return  mixed
     * @access  public
     * @static
     */
    function call( $callback )
    {
        // only accept callback and string value
        if ( !is_callable( $callback ) && !is_string( $callback ) ) {
            wp_die(' Invalid paramater for WPop::callback()', 'WPop' );
        }

        // Get the arguments except the callback
        $args = func_get_args();
        array_shift( $args );

        if ( is_callable( $callback ) ) {
            return call_user_func_array( $callback, $args );
        } else if ( is_string( $callback ) && !function_exists( $callback ) ) {
            if ( !file_exists( WPOP_THEME_FUNCTIONS . DS . $callback . '.php' ) ) {
                if ( !file_exists( WPOP_FUNCTIONS . DS . $callback . '.php' ) ) {
                    // Gave up, no such callback!
                    wp_die( 'Callback "' . $callback . '" not found' );
                } else {
                    // Found in theme functions directory
                    require_once WPOP_FUNCTIONS. DS . $callback . '.php';
                }
            } else {
                // Found in framework function directory
                require_once WPOP_THEME_FUNCTIONS . DS . $callback . '.php';
            }

            return call_user_func_array( $callback, $args );
        }
    }

    /**
     * Hook: admin_init
     *
     * Load the assets needed for admin interface setup.
     *
     * @access  public
     * @static
     */
    function loadAssets()
    {
        if ( !is_admin() ) {
            return false;
        }

        // Thickbox
        wp_enqueue_style( 'thickbox' );

        // Wordspop admin
        wp_enqueue_style( 'widgets' );

        // Wordspop admin
        wp_enqueue_style( 'wpop', WPOP_ASSETS . '/wpop.css' );

        // Media uploader
        wp_enqueue_script( 'media-upload' );

        // Thickbox
        wp_enqueue_script( 'thickbox' );

        // Colorpicker
        wp_enqueue_script( 'jquery_colorpicker', WPOP_ASSETS . '/js/colorpicker.min.js', array( 'jquery' ) );

        // jQuery Draggable
        wp_enqueue_script( 'jquery-ui-draggable' );

        // jQuery Droppable
        wp_enqueue_script( 'jquery-ui-droppable' );

        // jQuery Droppable
        wp_enqueue_script( 'jquery-ui-sortable' );

        // Wordspop
        $wpop_js = 'wpop.min.js';
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $wpop_js = 'wpop.js';
        }
        wp_enqueue_script( 'wpop', WPOP_ASSETS . '/' . $wpop_js, array( 'jquery', 'jquery_colorpicker' ) );
    }

    /**
     * Hook: admin_menu
     *
     * Creates the admin menu.
     *
     * @access public
     * @static
     */
    function createMenu()
    {
        global $menu;

        // Get theme object instance
        $theme = WPop_Theme::instance();

        // Create a new separator
        if ( version_compare( get_bloginfo( 'version' ), '2.9', '>=' ) ) {
            $menu['58.995'] = array( '', 'manage_options', 'separator-wpop', '', 'wp-menu-separator' );
        }

        // Theme top level menu
        add_menu_page( 'Wordspop', $theme->name(), 'manage_options', $theme->slug(), array( $theme, 'displaySettings' ), WPOP_ASSETS . '/images/wpop-icon-edit.png', '58.996' );

        // Theme settings submenu
        add_submenu_page( $theme->slug(), $theme->name() . ' ' . __( 'Settings', WPOP_THEME_SLUG ), __( 'Settings', WPOP_THEME_SLUG ), 'manage_options', $theme->slug() );

        // Mobile settings
        if ( is_dir( WPOP_THEME_MOBILE ) ) {
            add_submenu_page( $theme->slug(), __( 'Mobile', WPOP_THEME_SLUG ), __( 'Mobile', WPOP_THEME_SLUG ), 'manage_options', $theme->slug() . '_mobile', array( $theme, 'displaySettings' ) );
        }

        // Theme update
        #add_submenu_page($theme->slug(), $theme->name() . ' Update' . 'Import/Export', 'Update', 'manage_options', $theme->slug() . '_update', array($theme, 'displaySettings'));

        // Wordspop top level menu
        add_menu_page ( 'Wordspop', 'Wordspop', 'read', 'wpop', array( 'WPop', 'availableThemes' ), WPOP_ASSETS . '/images/wpop-icon.png', '58.999');

        // Wordspop themes
        add_submenu_page( 'wpop', 'Wordspop ' .  __( 'Themes', WPOP_THEME_SLUG ), __( 'Themes', WPOP_THEME_SLUG ), 'read', 'wpop' );
    }
    
    /**
     * Hook: admin_bar_menu
     *
     * Creates the admin bar menu.
     *
     * @access public
     * @static
     * @since   Version: 1.0.0-beta3
     */
    function createAdminBarMenu()
    {
        global $wp_admin_bar;
  
        // Get theme object instance
        $theme = WPop_Theme::instance();

        // Theme related menu
        if ( current_user_can('edit_theme_options') ) {
            $wp_admin_bar->add_menu( array(
                'id'  => $theme->slug(),
                'title' => $theme->name(),
                'href'  => get_admin_url( null, 'admin.php?page=' . $theme->slug() )
            ));

            $wp_admin_bar->add_menu( array(
                'parent' => $theme->slug(),
                'id'  => $theme->slug() . '-settings',
                'title' => __( 'Settings', WPOP_THEME_SLUG ),
                'href'  => get_admin_url( null, 'admin.php?page=' . $theme->slug() )
            ));

            // Mobile settings
            if ( is_dir( WPOP_THEME_MOBILE ) ) {
                $wp_admin_bar->add_menu( array(
                    'parent' => $theme->slug(),
                    'id'  => $theme->slug() . '-mobile',
                    'title' => __( 'Mobile', WPOP_THEME_SLUG ),
                    'href'  => get_admin_url( null, 'admin.php?page=' . $theme->slug() . '_mobile' )
                ));
            }

            $wp_admin_bar->add_menu( array(
                'parent' => $theme->slug(),
                'id'  => $theme->slug() . '-changelog',
                'title' => __( 'Changelog', WPOP_THEME_SLUG ),
                'href'  => 'http://docs.wordspop.com/theme/' . $theme->slug() .'#changelog-' . $theme->version()
            ));
            $wp_admin_bar->add_menu( array(
                'parent' => $theme->slug(),
                'id'  => $theme->slug() . '-docs',
                'title' => __( 'Documentations', WPOP_THEME_SLUG ),
                'href'  => 'http://docs.wordspop.com/theme/' . $theme->slug()
            ));
        }

        // Wordspop related menu
        $wp_admin_bar->add_menu( array(
            'id'  => 'wordspop-menu',
            'title' => 'Wordspop',
            'href'  => get_admin_url( null, 'admin.php?page=wpop' )
        ));
        $wp_admin_bar->add_menu( array(
            'parent' => 'wordspop-menu',
            'id'  => 'wordspop-forum',
            'title' => __( 'Forum', WPOP_THEME_SLUG ),
            'href'  => 'http://forum.wordspop.com/'
        ));
        $wp_admin_bar->add_menu( array(
            'parent' => 'wordspop-menu',
            'id'  => 'wordspop-themes',
            'title' => __( 'Themes', WPOP_THEME_SLUG ),
            'href'  => get_admin_url( null, 'admin.php?page=wpop' )
        ));
        $wp_admin_bar->add_menu( array(
            'parent' => 'wordspop-menu',
            'id'  => 'wordspop-website',
            'title' => __( 'Website', WPOP_THEME_SLUG ),
            'href'  => 'http://wordspop.com/'
        ));
    }

    /**
     * Render the available themes lis.
     *
     * @access public
     */
    function availableThemes()
    {
        self::call( 'wpop_themes' );
    }

    /**
     * Automatically searching and load the right template for mobile version themes if available.
     *
     * @access public
     */
    function loadMobileTemplate()
    {
        if (!defined('WP_USE_THEMES') || !WP_USE_THEMES) {
            return;
        }

        // Intercepts template-loader
        $template = false;
        if     ( is_robots() || is_feed() || is_trackback()                       ) : return;
        elseif ( is_404()            && $template = get_404_template()            ) :
        elseif ( is_search()         && $template = get_search_template()         ) :
        elseif ( is_tax()            && $template = get_taxonomy_template()       ) :
        elseif ( is_front_page()     && $template = get_front_page_template()     ) :
        elseif ( is_home()           && $template = get_home_template()           ) :
        elseif ( is_attachment()     && $template = get_attachment_template()     ) :
                remove_filter('the_content', 'prepend_attachment');
        elseif ( is_single()         && $template = get_single_template()         ) :
        elseif ( is_page()           && $template = get_page_template()           ) :
        elseif ( is_category()       && $template = get_category_template()       ) :
        elseif ( is_tag()            && $template = get_tag_template()            ) :
        elseif ( is_author()         && $template = get_author_template()         ) :
        elseif ( is_date()           && $template = get_date_template()           ) :
        elseif ( is_archive()        && $template = get_archive_template()        ) :
        elseif ( is_comments_popup() && $template = get_comments_popup_template() ) :
        elseif ( is_paged()          && $template = get_paged_template()          ) :
        else :
            $template = get_index_template();
        endif;

        $mobile = WPop_Mobile::instance();
        $agents = $mobile->agents();
        if ( !in_array( 'mobile', $agents ) ) {
            $agents[] = 'default';
        }

        // Creating possible template according to user agent
        $templates = array();
        foreach ( $agents as $agent ) {
            $templates[] = dirname($template) . DS . 'mobile' . DS . $agent . DS . basename($template);
            $templates[] = dirname($template) . DS . 'mobile' . DS . $agent . DS . 'index.php';
        }

        foreach ( $templates as $file ) {
            if ( file_exists( $file)  ) {
                if ( !defined( 'WPOP_TEMPLATEPATH' ) ) {
                  define( 'WPOP_TEMPLATEPATH', dirname( $file ) );
                }

                $template = $file;
                self::debug("{$file} currently used");
                break;
            } else {
                self::debug("{$file} not found");
            }
        }

        if ( !defined( 'WPOP_TEMPLATEPATH' ) ) {
            define( 'WPOP_TEMPLATEPATH', false );
        }

        if ( $template = apply_filters( 'template_include', $template ) ) {
            include $template;
        }

        exit;
    }

    /**
     * Show debug message.
     *
     * @param string $log Message.
     * @access public
     */
    function debug( $log )
    {
        if ( defined( 'WPOP_DEBUG' ) && WPOP_DEBUG ) {
            echo "{$log}<br />\n";
        }
    }
}
