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
 * @see WP_Query
 */
include_once ABSPATH . WPINC . DS . 'query.php';

/**
 * Wordspop slider.
 *
 * @category   Wordspop
 * @package    WPop_Slider
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @author     Firman Wandayandi <firmanw@wordspop.com>
 */
class WPop_Slider extends WP_Query
{
    /**
     * Constructor
     *
     * @param string $opt_source Option name of slider source.
     * @param string $opt_entries Option name of slider entries.
     *
     * @return WPop_Slider
     */
    function WPop_Slider( $opt_source = 'slider_source', $opt_entries = 'slider_entries', $extras = null )
    {
        // Set default options
        $opt_source = $opt_source !== null ? $opt_source : 'slider_source';
        $opt_entries = $opt_entries !== null ? $opt_entries : 'slider_entries';
        
        // Get the options
        $source = wpop_get_option( $opt_source );
        $entries = wpop_get_option( $opt_entries );

        // Parse extra arguments
        $args = array();
        if ( $extras !== null ) {
            $args = wp_parse_args( $extras );
        }
        
        $ids = explode( ',', $entries );
    
        switch ( $source ) {
            case 'posts':
            case 'pages':
                parent::init();

                foreach ( $ids as $id ) {
                    $post = get_post($id);
                    if ($post) {
                        $this->posts[] = sanitize_post($post, 'raw');
                        $this->post_count++;
                        $this->found_posts = $this->post_count;
                    }
                }

                update_post_caches( $this->posts, 'slider', true, true);

                if ( $this->post_count > 0 ) {
                    $this->post = $this->posts[0];
                }

                break;

            case 'categories':
                if ( !empty($args) && isset( $args['cat'] ) ) {
                    $args[ 'cat' ] = $entries . ',' . $args[ 'cat' ];
                } else {
                    $args[ 'cat' ] = $entries;
                }

                return parent::WP_Query( $args );
                break;

            case 'tags':
                if ( !empty($args) && isset( $args['tag__in'] ) ) {
                    $args[ 'tag__in' ] = $entries . ',' . $args[ 'tag__in' ];
                } else {
                    $args[ 'tag__in' ] = $entries;
                }

                return parent::WP_Query( $args );
                break;
        }
    }
}
