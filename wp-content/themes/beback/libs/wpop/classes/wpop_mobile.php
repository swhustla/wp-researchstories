<?php
/**
 * Wordspop Framework
 *
 * @category   Wordspop
 * @package    WPop_Mobile
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 */

/**
 * @category   Wordspop
 * @package    WPop_Mobile
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @author     Firman Wandayandi <firmanw@wordspop.com>
 */
class WPop_Mobile
{
    /**
     * User agent patterns
     *
     * @var array
     * @access private
     */
    var $_patterns = array(
        'ipad'              => '/ipad/i',                         // Apple iPad
        'iphone'            => '/iphone/i',                       // Apple iPhone
        'ipod'              => '/ipod/i',                         // Apple iPod
        'android'           => '/android/i',                      // Android device
        'blackberry_webkit' => '/blackberry.+applewebkit/i',      // BlackBerry WebKit (OS6 or later)
        'webos'             => '/webos/i',                        // Palm webOS (WebKit)
        'dolfin'            => '/dolfin/i',                       // Samsung Dolfin / bada OS (WebKit)
        'nokia'             => '/nokia.+applewebkit/i',           // Nokia WebKit
        'webkit'            => '/(iphone|ipad|ipod|android|webos|bada|blackberry|nokia)(.+)applewebkit|mobile(.+)safari/i', // WebKit
        'fennec'            => '/fennec/i',                       // Mozilla Fennec
        'blackberry'        => '/blackberry/i',                   // BlackBerry
        'microb'            => '/maemo[ ]?browser/i',             // Nokia MicroB
        'opera_mobile'      => '/opera[ ]?mobi/i',                // Opera Mobile
        'ie_mobile'         => '/iemobile/i',                     // IE Mobile
        'netfront'          => '/netfront/i',                     // NetFront
        'palm'              => '/palmos|palmsource/i',            // Palm
        'windows'           => '/windows ce|windows phone/i',     // Windows Smartphones
        'opera_mini'        => '/opera[ ]?mini/i',                // Opera Mini
        'opera'             => '/opera[ ]?mobi|opera[ ]?mini/i'   // Opera Mobile
    );

    /**
     * Current user agents list
     *
     * @var array
     * @access private
     */
    var $_agents = array();

    /**
     * Constructor
     *
     * @see WPop_Mobile::_detect()
     */
    function WPop_Mobile()
    {
        $this->_detect();
    }

    /**
     * Get this object instance
     *
     * @return  WPop_Mobile
     * @access  public
     * @static
     */
    function instance()
    {
        if ( !isset( $GLOBALS['wpop_mobile'] ) || !is_object( $GLOBALS['wpop_mobile'] ) || !is_a( $GLOBALS['wpop_mobile'], 'wpop_mobile' ) ) {
            $GLOBALS['wpop_mobile'] = new WPop_Mobile;
        }

        return $GLOBALS['wpop_mobile'];
    }

    /**
     * Detect the device/browser
     *
     * Detect the current device and process the handlers if available.
     *
     * @see WPop_Mobile::_isMatch()
     * @access private
     */
    function _detect()
    {
        foreach ( $this->_patterns as $agent => $pattern ) {
            if ( $this->_isMatch( $pattern ) ) {
                $this->_agents[] = $agent;
            }
        }
    }

    /**
     * Find the whether pattern is match with browser information
     *
     * @param string $pattern
     * @return boolean
     * @access private
     */
    function _isMatch( $pattern )
    {
        if ( preg_match( $pattern, $_SERVER['HTTP_USER_AGENT'] ) ) {
            return true;
        } else if ( isset( $_SERVER['HTTP_ACCEPT'] )  && preg_match( $pattern, $_SERVER['HTTP_ACCEPT'] ) ) {
            return true;
        }

        return false;
    }

    /**
     * Get the identified user agents
     *
     * @return array
     * @access public
     */
    function agents()
    {
        return $this->_agents;
    }

    /**
     * Find the whether is a mobile browser or desktop one
     *
     * @return bool
     * @access public
     */
    function isMobile()
    {
        return !empty( $this->_agents );
    }
}
