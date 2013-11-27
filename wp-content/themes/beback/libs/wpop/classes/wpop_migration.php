<?php
/**
 * Wordspop Framework
 *
 * @category   Wordspop
 * @package    WPop_Migration
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 */

/**
 * @category   Wordspop
 * @package    WPop_Migration
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @author     Firman Wandayandi <firmanw@wordspop.com>
 */
class WPop_Migration
{
    /**
     * List of migration to be done.
     *
     * @var array
     * @access private
     */
    var $_migration = array();

    /**
     * Flag whether migraion required or not
     *
     * @var bool
     * @access private
     */
    var $_required = false;

    /**
     * Constructor
     *
     * @access public
     */
    function WPop_Migration()
    {
        $migration = WPOP_THEME_CONFIG . DS . 'migration.php';
        if ( file_exists( $migration ) ) {
            require_once $migration;
            if ( isset( $wpop_migration ) ) {
                $this->_migration = $wpop_migration;
                $this->_required = true;
            }
        }
    }

    /**
     * Find the whether migration is required or not
     *
     * @return bool
     * @access public
     */
    function isRequired()
    {
        if ( !$this->_required ) {
            return false;
        }

        // Find the whether the previous theme settings available for import or not
        foreach ( $this->_migration as $previously => $newly ) {
            $value = get_option($previously);
            if ( $value !== false ) {
                // Found one option!
                return true;
            }
        }

        return false;
    }

    /**
     * Process the migration
     *
     * @return bool
     * @access public
     */
    function migrate()
    {
        $theme = WPop_Theme::instance();

        // Imports the theme options
        $updated = false;
        foreach ( $this->_migration as $previously => $newly ) {
            $value = get_option( $previously );
            if ( $value !== false ) {
                $res = $theme->saveOption( $newly, $value );
                $updated = $res || $updated;
            }
        }

        if ( $updated ) {
            $migrated =  WPop::getOption( 'migrated' );
            if ( !$migrated ) {
                $theme->notification( __('Successfully migrated from previous version', WPOP_THEME_SLUG ) );
            } else {
                $theme->notification( sprintf( __( 'Successfully migrated from version %s', WPOP_THEME_SLUG ), $migrated ) );
            }

            WPop::saveOption( 'migrated', $theme->version() );
        }

        return $updated;
    }
}
