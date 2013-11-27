<?php
/**
 * Wordspop Framework
 *
 * @category   Wordspop
 * @package    WPop_API
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 */

/**
 * @see WPop_Utils
 */
require_once 'wpop_utils.php';

/**
 * Wordspop API.
 *
 * @category   Wordspop
 * @package    WPop_API
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @author     Firman Wandayandi <firmanw@wordspop.com>
 */
class WPop_API
{
    /**
     * API url.
     *
     * @var string
     * @access private
     */
    var $_url;

    /**
     * Constructor
     *
     * @access public
     */
    function WPop_API()
    {
        $this->_url = WPOP_API_URL;
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
        if ( !isset( $GLOBALS['wpop_api'] ) || !is_object( $GLOBALS['wpop_api'] ) || !is_a( $GLOBALS['wpop_api'], 'wpop_api' ) ) {
            $GLOBALS['wpop_api'] = new WPop_Api;
        }

        return $GLOBALS['wpop_api'];
    }

    /**
     * Send the request to API url.
     *
     * @param string $action API action.
     * @param array $params API parameters.
     *
     * @return mixed
     * @access public
     */
    function sendRequest( $action, $params = array() )
    {
        global $wp_version;

        $theme = WPop_Theme::instance();
        $ua = sprintf( 'WordPress/%s (%s;%s) Wordspop/%s', $wp_version, get_bloginfo( 'url' ), $theme->name(), WPOP_VERSION );
        $request = array(
            'body'  => array(
                'params' => serialize( $params )
            ),
            'user-agent' => $ua
        );

        $response = wp_remote_post( "{$this->_url}/{$action}", $request );
        if ( is_wp_error( $response ) || ( $response['response']['code'] != 200 ) ) {
            return false;
        }

        if ( !isset( $response['body'] ) ) {
            return false;
        }

        return @unserialize( $response['body'] );
    }

    /**
     * Get theme updates status from remote api.
     *
     * Hook: update_themes
     *
     * @param object $checked Checked data.
     *
     * @return object
     * @access public
     */
    function getThemeUpdates( $checked )
    {
        $themes = get_themes();

        $params = array();
        foreach ( $themes as $theme ) {
            if ( $theme['Author Name'] == 'Wordspop' ) {
                $slug = $theme['Stylesheet'];
                $params[$slug] = array(
                    'name'  => $theme['Name'],
                    'version' => $theme['Version'],
                );
            }
        }

        $updates = new stdClass;
        $updates->last_checked = time();

        $res = $this->sendRequest( 'update-themes', $params );
        if ( $res ) {
            foreach ( $res as $slug => $theme ) {
                if ( $theme['license'] == 'Free' ) {
                    $checked->response[$slug] = $theme;
                }
            }

            $updates->response = $res;
        }

        set_site_transient( 'wpop_updates', $updates );

        if ( defined( 'MULTISITE' ) && MULTISITE ) { // Need to update the transient of main site meta.
            global $wpdb;

            $m_wpdb = $wpdb;
            $m_wpdb->siteid = 1;
  
            $value = maybe_serialize( $checked );
            $result = $m_wpdb->update( $m_wpdb->sitemeta, array( 'meta_value' => $value ), array( 'site_id' => $m_wpdb->siteid, 'meta_key' => '_site_transient_update_themes' ) );
        }

        return $checked;
    }
}
