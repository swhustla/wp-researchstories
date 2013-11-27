<?php
/**
 * Wordspop Framework
 *
 * @category   Wordspop
 * @package    WPop_Theme
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 */

/**
 * @see WPop_Utils
 */
require_once 'wpop_utils.php';

/**
 * @category   Wordspop
 * @package    WPop_Theme
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @author     Firman Wandayandi <firmanw@wordspop.com>
 */
class WPop_Theme
{
    /**
     * Bundled options
     *
     * @var array
     * @access private
     */
    var $_bundledOptions = array();

    /**
     * Whether is mobile settings or not, also tell if user agent is mobile device.
     *
     * @var boolean
     * @access private
     */
    var $_isMobile = false;

    /**
     * Theme meta data
     *
     * @var     array
     * @access  private
     */
    var $_meta;

    /**
     * Theme ID
     *
     * @var     string
     * @access  private
     */
    var $_id;

    /**
     * Theme slug
     *
     * @var     string
     * @access  private
     */
    var $_slug;

    /**
     * Theme options
     *
     * References to $GLOBALS['wpop_options']
     *
     * @access  private
     */
    var $_options = array();

    /**
     * Notification
     *
     * @var     string
     * @access  private
     */
    var $_notification = '';

    /**
     * Initialition flag
     *
     * @var     bool
     * @access  private
     */
    var $_initialized = false;

    /**
     * Widgets list
     *
     * @var     array
     * @access  private
     */
    var $_widgets = array();

    /**
     * Hooks
     *
     * @var     array
     * @access  private
     */
    var $_hooks = array();

    /**
     * Constructor
     *
     * @access  public
     */
    function WPop_Theme()
    {
        $this->_meta = self::_getMeta();
        $this->_slug = WPop_Utils::slugify( $this->_meta['Name'], '-' );
        $this->_id = 'wpop_' . WPop_Utils::slugify( $this->_meta['Name'] );

        // Theme slug
        define( 'WPOP_THEME_SLUG', $this->_slug );

        // Load translations
        load_theme_textdomain( $this->_slug, WP_CONTENT_DIR . '/languages' ); // Load general WordPress translations
        load_theme_textdomain( $this->_slug, WPOP_THEME_LANGUAGES ); // Load theme translations

        // Load theme options
        if ( isset( $_GET['page'] ) && preg_match( '/mobile/', $_GET['page'] ) ) {
            $this->_isMobile = true;
        } else if ( !empty( $_POST ) && isset( $_POST['data'] ) ) { // Probably this is an ajax request
            parse_str( $_POST['data'], $ajax_data );
            if ( isset( $ajax_data['_wp_http_referer'] ) ) {
                $url = parse_url( $ajax_data['_wp_http_referer'] );
                if ( isset( $url['query'] ) && preg_match( '/page=[a-z0-9_-]+mobile/i', $url['query'] ) ) {
                    $this->_isMobile = true;
                }
            }
        }

        if ( $this->_isMobile ) {
            $config = WPOP_THEME_MOBILE_CONFIG . DS . 'options.php';
            if ( file_exists( $config ) ) {
                include_once $config;
                $this->_options = &$wpop_options;
            }
        } else {
            // Load bundled options
            include_once WPOP_BUNDLED . DS . 'options.php';
            $this->_bundledOptions = $wpop_bundled_options;

            // Load configured options
            $config = WPOP_THEME_CONFIG . DS . 'options.php';
            if ( file_exists( $config ) ) {
                include_once $config;
                $this->_options = &$wpop_options;
            }

            $this->_addBundledOptions();
        }

        // Load theme widgets
        $widgets = WPOP_THEME_CONFIG . DS . 'widgets.php';
        if ( file_exists( $widgets ) ) {
            include_once $widgets;
            $this->_widgets = &$wpop_widgets;
        }

        // Load theme hooks
        $hooks = WPOP_THEME_CONFIG . DS . 'hooks.php';
        if ( file_exists( $hooks ) ) {
            include_once $hooks;
            $this->_hooks = &$wpop_hooks;
        }

        // Call the initialization function
        $this->_init();
    }

    /**
     * Get theme meta data
     *
     * @access  private
     * @return  array
     */
    function _getMeta()
    {
        $headers = array(
            'Name'        => 'Theme Name',
            'URI'         => 'Theme URI',
            'Description' => 'Description',
            'Author'      => 'Author',
            'AuthorURI'   => 'Author URI',
            'Version'     => 'Version',
            'Template'    => 'Template',
            'Status'      => 'Status',
            'Tags'        => 'Tags',
            'Note'        => 'Note',
            'Copyright'   => 'Copyright'
        );

        return get_file_data( TEMPLATEPATH . '/style.css', $headers, 'theme' );
    }

    /**
     * Get this object instance
     *
     * @return  WPop_Theme
     * @access  public
     * @static
     */
    function instance()
    {
        if ( !isset( $GLOBALS['wpop_theme'] ) || !is_object( $GLOBALS['wpop_theme'] ) || !is_a( $GLOBALS['wpop_theme'], 'wpop_theme' ) ) {
            $GLOBALS['wpop_theme'] = new WPop_Theme;
        }

        return $GLOBALS['wpop_theme'];
    }

    /**
     * Get theme meta data
     *
     * @param   string  $key (optional) Meta key name
     * @return  mixed   An array if no key given, a string if key found otherwise FALSE
     * @access  public
     */
    function meta( $key = null )
    {
        if ( is_string( $key ) ) {
            if ( array_key_exists( $key, $this->_meta ) ) {
                return $this->_meta[$key];
            } else {
                return false;
            }

        }

        return $this->_meta;
    }

    /**
     * Get theme name
     *
     * Shortcuts function to WPop_Theme::meta('Name')
     *
     * @return  mixed
     * @access  public
     */
    function name()
    {
        return $this->meta( 'Name' );
    }

    /**
     * Get theme title
     *
     * Shortcuts function to WPop_Theme::meta('Title')
     *
     * @return mixed
     * @access public
     */
    function title()
    {
        return $this->meta( 'Title' );
    }

    /**
     * Get theme description
     *
     * Shortcuts function to WPop_Theme::meta('Description)
     *
     * @return  mixed
     * @access  public
     */
    function description() {
        return $this->meta( 'Description' );
    }

    /**
     * Get theme author
     *
     * Shortcuts function to WPop_Theme::meta('Author')
     *
     * @return  mixed
     * @access  public
     */
    function author()
    {
        return $this->meta( 'Author' );
    }

    /**
     * Get theme version
     *
     * Shortcuts function to WPop_Theme::meta('Version')
     *
     * @return  mixed
     * @access  public
     */
    function version()
    {
        return $this->meta( 'Version' );
    }

    /**
     * Get theme parent name (if any)
     *
     * Shortcuts function to WPop_Theme::meta('Template')
     *
     * @return  mixed
     * @access  public
     */
    function template()
    {
        return $this->meta( 'Template' );
    }

    /**
     * Get theme status
     *
     * Shortcuts function to WPop_Theme::meta('Status')
     *
     * @return  mixed
     * @access  public
     */
    function status()
    {
        return $this->meta( 'Status' );
    }

    /**
     * Get theme note
     *
     * Shortcuts to WPop_Theme::meta('Note')
     *
     * @return  mixed
     * @access  public
     */
    function note()
    {
        return $this->meta( 'Note' );
    }

    /**
     * Get theme copyright notice
     *
     * Shortcuts to WPop_Theme::meta('Copyright')
     *
     * @return  mixed
     * @access  public
     */
    function copyright()
    {
        return $this->meta( 'Copyright' );
    }

    /**
     * Get theme slug
     *
     * @return  string
     * @access  public
     */
    function slug()
    {
        return $this->_slug;
    }

    /**
     * Get theme ID
     *
     * @return  string
     * @access  public
     */
    function id()
    {
        return $this->_id;
    }

    /**
     * Get theme options
     *
     * @return  array
     * @access  public
     */
    function options()
    {
        return $this->_options;
    }

    /**
     * Get the specified option attributes
     *
     * @param string $name Option name.
     *
     * @return array|FALSE
     * @access public
     */
    function option( $name )
    {
        foreach ( $this->_options as $option ) {
            if ( $option['name'] == $name ) {
                return $option;
            }
        }

        return false;
    }

    /**
     * Get theme widget(s)
     *
     * @return  array
     * @access  public
     */
    function widgets( $id = null )
    {
        if ( is_string( $id ) && array_key_exists( $id, $this->_widgets ) ) {
            return $this->_widgets[$id];
        }

        return $this->_widgets;
    }

    /**
     * Set noticiation
     */
    function notification( $message = null )
    {
        if ( is_string( $message ) ) {
            $this->_notification = $message;
        }

        return $this->_notification;
    }

    /**
     * Theme initialization
     *
     * @access private
     */
    function _init()
    {
        if ( $this->_initialized ) {
            return;
        }

        // Register theme framework hooks
        add_action( 'admin_init', array( $this, 'prepareOptions' ) );
        add_action( 'wp_ajax_wpop_theme_save_options', array( $this, 'saveOptions' ) );
        add_action( 'widgets_init', array( $this, 'registerWidgets' ) );
        add_action( 'wp_head', array( $this, 'addHeader' ) );
        add_action( 'wp_footer', array( $this, 'addFooter' ) );
        add_action( 'init', array($this, 'addStylesheets' ) );

        // Enqueue the webfont loader
        wp_enqueue_script( 'webfont_loader', WPOP_ASSETS . '/js/webfont-loader.js', false, false, true );

        if ( is_dir( WPOP_THEME_FUNCTIONS ) ) {
            // Load the function files automatically
            $files = WPop_Utils::getFiles( WPOP_THEME_FUNCTIONS, array( 'php' ) );
            foreach ( $files as $file ) {
                include_once $file;
            }
        }

        // Register curent theme hooks
        foreach ( $this->_hooks as $action => $function ) {
            if ( function_exists( $function ) ) {
                add_action( $action, $function );
            }
        }

        // Set initialization flag
        $this->_initialized = true;
    }

    /**
     * Add bundled options
     *
     * @access private
     */
    function _addBundledOptions()
    {
        foreach ( $this->_options as $i => $option ) {
            // Add the theme support and register the hook(s) if needed
            switch ( $option['type'] ) {
                case 'header':
                    add_theme_support( 'custom-header' );
                    break;

                case 'footer':
                    add_theme_support( 'custom-footer' );
                    break;

                case 'styling':
                    add_theme_support( 'custom-style' );
                    break;

                case 'slideshow':
                    add_theme_support( 'slideshow' );
                    break;

                case 'typography':
                    add_theme_support( 'custom-typography' );
                    break;
            }

            $options = $this->_getBundledOptions( $option['type'], $option );
            if ( is_array( $options ) ) {
                array_splice( $this->_options, $i, 1, $options );
            }
        }

        $options = $this->_getBundledOptions( 'scheme', array() );
        $options[0]['desc'] = sprintf( __( 'Get more theme visual styles by purchasing <a href="http://wordspop.com/themes/%s">the schemes</a> as low as $2.99.', WPOP_THEME_SLUG ), $this->slug() );
        $this->_options = array_merge( $this->_options, $options );
    }

    /**
     * Get bundled options per section
     *
     * @param string $section Section name
     * @param array $info Options
     * @return mixed An array if section options found otherwise FALSE
     */
    function _getBundledOptions( $section, $info )
    {
        if ( !isset( $this->_bundledOptions[$section] ) ) {
            return false;
        }

        $options = array();
        foreach ( $this->_bundledOptions[$section] as $i => $option ) {
            if ( isset( $info['excepts']) && in_array( $option['name'], $info['excepts'] ) ) {
                continue;
            }

            $options[$i] = $option;

            if ( isset( $info['overrides'][$option['name']] ) ) {
                $override = $info['overrides'][$option['name']];
                if ( isset( $override['name'] ) ) {
                    // name is restricted to be override.
                    unset( $override['name'] );
                }

                $options[$i] = array_merge( $options[$i], $override );
            }
        }

        return $options;
    }

    /**
     * Hook: admin_init
     *
     * Prepare the options
     *
     * @access public
     */
    function prepareOptions()
    {
        foreach ( $this->_options as $option ) {
            if ( $option['type'] != 'heading' ) {
                if ( get_option( "wpop_theme_{$option['name']}", false ) === false ) {
                    $this->saveOption( $option['name'], isset( $option['std'] ) ? $option['std'] : '' );
                }
            }
        }
    }

    /**
     * Display theme settings page.
     *
     * @access public
     */
    function displaySettings()
    {
        require_once 'wpop.php';
        WPop::call( 'wpop_theme_settings', $this );
    }

    /**
     * Hook: widgets_init
     *
     * Register current distributed theme widgets.
     *
     * @access public
     */
    function registerWidgets()
    {
        foreach ( $this->_widgets as $widget => $params ) {
            $classname = "{$widget}_widget";
            include_once WPOP_THEME_WIDGETS . DS . $widget . '.php';
            if ( class_exists( $classname ) ) {
                register_widget( $classname );
            }

        }
    }

    /**
     * Hook: wp_ajax_wpop_theme_save_options
     *
     * @access public
     * @see WPop_Theme::normalize()
     */
    function saveOptions()
    {
        $data = array();
        if ( !empty( $_POST ) ) {
            parse_str( $_POST['data'], $data );
            $data = $this->_normalizeOptions( $data );
        }

        $updated = false;
        foreach ( $data as $option => $value ) {
            $res = $this->saveOption( $option, $value );
            $updated = $updated || $res;
        }


        if ( $updated ) {
            $message = array(
                'type' => 'succeed',
                'text' => 'Settings has been saved successfully.'
            );
        } else {
            $message = array(
                'type' => 'error',
                'text' => 'No option changed, update canceled.'
            );
        }

        echo json_encode( $message );
        exit;
    }

    /**
     * Normalize post data
     *
     * Rewrite the value for saving into database according the data type
     *
     * @param $post array Post data
     * @access private
     * @return array
     */
    function _normalizeOptions( $post )
    {
        $res = array();
        foreach ( $this->_options as $option ) {
            $value = isset( $option['std'] ) ? $option['std'] : '';

            if ( $option['type'] == 'checkbox' ) {
                if ( array_key_exists( "wpop_theme_{$option['name']}", $post ) ) {
                    $res[$option['name']] = 1;
                } else {
                    $res[$option['name']] = 0;
                }
            } else if ( array_key_exists( "wpop_theme_{$option['name']}", $post ) ) {
                $value = $post["wpop_theme_{$option['name']}"];
                switch ( $option['type'] ) {
                    case 'date':
                        $time = mktime( 0, 0, 0, $value['month'], $value['day'], $value['year'] );
                        $res[$option['name']] = date( 'Y/m/d', $time );
                        break;
                    case 'checkbox':
                        $res[$option['name']] = (bool) $value;
                        break;
                    case 'character':
                        $res[$option['name']] = serialize( $value );
                        break;
                    default:
                        $res[$option['name']] = $value;
                        break;
                }
            }
        }

        return $res;
    }

    /**
     * Save option
     *
     * @param   string  $name Option name
     * @param   mixed   $value Option value
     * @return  bool
     * @access  public
     */
    function saveOption( $name, $value )
    {
        if ( is_array( $value ) || is_object( $value ) ) {
            $value = serialize( $value );
        }

        return update_option( "wpop_theme_{$name}", stripslashes( $value ) );
    }

    /**
     * Get theme option value
     *
     * Attempt to retrieve value from database or return default if not exists.
     *
     * @param   string  $name  Option name
     * @return  mixed
     * @access  public
     * @static
     */
    function getOption( $name )
    {
        if ( isset( $this ) ) {
            $theme = $this;
        } else {
            $theme = WPop_Theme::instance();
        }

        $res = get_option( "wpop_theme_{$name}" );

        if ( $res === false ) {
            $options = $theme->options();
            foreach ( $options as $option ) {
                if ( $option['name'] == $name && isset( $option['std'] ) ) {
                    return $option['std'];
                }
            }
        }

        return $res;
    }

    /**
     * Get available schemes
     *
     * @return array
     * @access public
     */
    function availableSchemes()
    {
        $schemes = array(
            'default' => WPOP_THEME_URL . '/screenshot.png'
        );

        if ( !is_dir( WPOP_THEME_SCHEME ) ) {
            return $schemes;
        }

        $d = dir( WPOP_THEME_SCHEME );
        while ( false !== ( $entry = $d->read() ) ) {
            if ( $entry != '.' && $entry != '..' && $entry != '.svn' && is_dir( WPOP_THEME_SCHEME . DS . $entry ) ) {
                if ( !file_exists( WPOP_THEME_SCHEME . DS . $entry . DS . 'screenshot.png' ) ) {
                    $screenshot = WPOP_ASSETS . '/images/no-screenshot.png';
                } else {
                    $screenshot = sprintf( '%s/scheme/%s/screenshot.png', WPOP_THEME_URL, $entry );
                }

                $schemes[$entry] = $screenshot;
            }
        }
        $d->close();

        return $schemes;
    }

    /**
     * Add favicon link
     *
     * @return string
     * @access private
     */
    function _favIcon() {
        $favicon = $this->getOption( 'favicon' );
        if ($favicon) {
            return sprintf( '<link rel="shortcut icon" href="%s" />' . "\n", $favicon );
        }
    }

    /**
     * Get custom header
     *
     * @return string
     * @access private
     */
    function _customHeader()
    {
        return $this->getOption( 'header_extras' );
    }

    /**
     * Get custom footer
     *
     * @return string
     * @access private
     */
    function _customFooter()
    {
        $retval = '';

        $extras = $this->getOption( 'footer_extras' );
        if ( $extras ) {
            $retval = $extras;
        }

        $tracking = $this->getOption( 'tracking_code' );
        if ( $tracking ) {
            $retval .= $tracking;
        }

        return $retval;
    }

    /**
     * Get custom styling
     *
     * @return string
     * @access private
     */
    function _customStyle()
    {
        if ( !$this->getOption( 'styling_enable' ) ) {
            return false;
        }

        $custom_css = $this->getOption( 'custom_css' );
        $link_color = $this->getOption( 'link_color' );
        $link_hover_color = $this->getOption( 'link_hover_color' );
        $background_color = $this->getOption( 'background_color' );
        $background_image = $this->getOption( 'background_image' );
        $background_repeat = $this->getOption( 'background_repeat' );
        $background_position = $this->getOption( 'background_position' );

        $css = '';
        if ( $link_color ) {
            $css .= "a { color: {$link_color}; }\n";
        }
        if ( $link_hover_color ) {
            $css .= "a:hover { color: {$link_hover_color}; }\n";
        }

        if ( $background_color || $background_image || $background_repeat || $background_position ) {
            $css .= 'body {';
            if ( $background_color ) {
                $css .= "background-color: {$background_color};";
            }
            if ( $background_image ) {
                $css .= "background-image: url({$background_image});";
            }
            if ( $background_repeat ) {
                $css .= "background-repeat: {$background_repeat};";
            }
            if ( $background_position ) {
                $css .= "background-position: {$background_position};";
            }
            $css .= '}' . "\n";
        }

        if ( $custom_css ) {
            $css .= $custom_css . "\n";
        }

        return $css;
    }

    /**
     * Get custom typography css
     *
     * @return string
     * @access private
     */
    function _customTypography()
    {
        $output = '';
        foreach ( $this->_options as $option ) {
            if ( $option['type'] == 'character' && isset( $option['selector'] ) ) {
                $output .= $this->_getTypographyCSS( $option['selector'], @unserialize( $this->getOption( $option['name'] ) ) );
            }
        }

        return $output;
    }

    /**
     * Get typography css
     *
     * @return string
     * @access private
     */
    function _getTypographyCSS( $selector, $options )
    {
        if ( !is_array( $options ) || !isset($options['enable']) || !$options['enable'] ) {
            return false;
        }

        if ( self::_isWebFont( $options['font'] ) ) {
            $res = $this->_addWebFont( $options['font'] );
            if ( !$res ) {
                return false;
            }
            $options['font'] = $res;
        }

        $css = "{$selector} {\n";
        
        if ( !empty( $options['font'] ) ) {
            $css .= "font-family: {$options['font']} !important;\n";
        }
        
        if ( !empty( $options['size'] ) ) {
            $css .= "font-size: {$options['size']}{$options['unit']} !important;\n";
        }

        switch ( $options['style'] ) {
            case 'normal':
                $css .= 'font-weight: normal !important;' . "\n" .
                        'font-style: normal !important;' . "\n";
                break;
            case 'bold':
                $css .= 'font-weight: bold !important;' . "\n" .
                        'font-style: normal !important;' . "\n";
                break;
            case 'italic':
                $css .= 'font-weight: normal !important;' . "\n" .
                        'font-style: italic !important;' . "\n";
                break;
            case 'bold italic':
                $css .= 'font-weight: bold !important;' . "\n" .
                        'font-style: italic !important;' . "\n";
        }

        if ( !empty( $options['color'] ) ) {
            $css .= "color: {$options['color']} !important;\n";
        }

        $css .= '}' . "\n";

        return $css;
    }

    /**
     * Find the whether font is a webfont or not
     *
     * @return bool
     * @access private
     */
    function _isWebFont( $font )
    {
        if ( ( $pos = strpos( $font, ':' ) ) !== false ) {
            return true;
        }

        return false;
    }

    /**
     * Add webfont to the list for loading
     *
     * @access private
     */
    function _addWebFont( $font )
    {
        if ( !preg_match( '/(\w+):\s+("*([\w\s]+)"*(,|$).*)/', $font, $matches ) ) {
            return false;
        }

        $this->_webFonts[ $matches[1] ][] = $matches[3];
        return $matches[2];
    }

    /**
     * Hook: init
     *
     * Enqueue stylesheets.
     *
     * @access public
     * @since  Version 1.0.0-beta3
     */
    function addStylesheets()
    {
        if ( !is_admin() ) {
            wp_enqueue_style( $this->_id, get_bloginfo( 'stylesheet_url' ), false, false, 'screen' );

            $scheme = $this->getOption( 'scheme' );
            if ( $scheme !== false && $scheme != 'default' ) {
                $stylesheet = WPOP_THEME_URL . '/scheme/' . wpop_get_option( 'scheme' ) . '/style.css';
                wp_enqueue_style( $this->_id . '_scheme', $stylesheet, array( $this->_id ), false, 'screen' );
            }
        }
    }

    /**
     * Hook: wp_head
     *
     * Add the custom generated header
     *
     * @access public
     */
    function addHeader()
    {
        echo $this->_favIcon();

        $custom_header = '';
        $custom_style = '';
        $custom_typography = '';

        if ( current_theme_supports( 'custom-header' ) ) {
            $custom_header = $this->_customHeader();
        }

        if ( current_theme_supports( 'custom-style' ) ) {
            $custom_style = $this->_customStyle();
        }

        if ( current_theme_supports( 'custom-typography' ) ) {
            $custom_typography = $this->_customTypography();

            if ( !empty($this->_webFonts ) ) {
                echo '<script type="text/javascript">' . "\n";
                echo 'WebFontConfig = {';
                $i = 0;
                foreach ( $this->_webFonts as $provider => $fonts ) {
                    echo "{$provider}: {";
                    switch ($provider) {
                        case 'google':
                          echo 'families: [ \'' .implode( '\',\'', $fonts ) . '\' ]';
                          break;
                    }
                    echo '}' . ( $i < count($this->_webFonts) - 1 ? ',' : '' );
                    $i++;
                }
                echo '};' . "\n";
                echo '</script>'. "\n";
            }
        }

        if ( $custom_header ) {
            echo $custom_header;
        }

        if ( $custom_style || $custom_typography ) {
            echo '<style type="text/css">' . "\n" .
                 $custom_style .
                 $custom_typography .
                 '</style>' . "\n";
        }
    }

    /**
     * Hook: wp_footer
     *
     * Add custom generated footer
     *
     * @access public
     */
    function addFooter()
    {
        $custom_footer = '';
        if ( current_theme_supports( 'custom-footer' ) ) {
            $custom_footer = $this->_customFooter();
        }

        if ( $custom_footer ) {
            echo $custom_footer;
        }
    }
}
