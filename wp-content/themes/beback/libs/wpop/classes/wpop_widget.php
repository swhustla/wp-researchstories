<?php
/**
 * Wordspop Framework
 *
 * @category   Wordspop
 * @package    WPop_Widget
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 */

/**
 * @category   Wordspop
 * @package    WPop_Widget
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @author     Firman Wandayandi <firmanw@wordspop.com>
 */
class WPop_Widget extends WP_Widget
{
    /**
     * Parameters
     *
     * @var     array
     * @access  protected
     */
    var $params = array();

    /**
     * Default values
     *
     * @var     array
     * @access  protected
     */
    var $defaults = array();

    /**
     * Initialization
     *
     * Any class extends this class should call this function on their constructor.
     *
     * function Example_Widget()
     * {
     *     WPop_Widget::init();
     * }
     *
     * @access protected
     */
    function init()
    {
        $theme = WPop_Theme::instance();
        $id = strtolower( substr( get_class( $this ), 0, -7 ) );

        $params = $theme->widgets( $id );
        $this->params = array_merge( $this->params, $params );

        // Clone the name since we will change it later but still need it for saving.
        foreach ( $this->params['options'] as $i => $option ) {
            $this->params['options'][$i]['slug'] = $option['name'];
        }

        $widget_ops = array(
            'classname' => $id
        );

        if ( isset( $this->params['description'] ) ) {
             $widget_ops['description'] = $this->params['description'];
        }

        if ( isset( $this->params['control'] ) ) {
            $control_ops = $this->params['control'];
        }

        parent::WP_Widget( "wpop_{$id}", $this->params['title'], $widget_ops, $control_ops );
    }

    /**
     * Render the widget on theme
     *
     * @access public
     */
    function widget( $args, $instance )
    {
       echo 'Please extends WPop_Widget::widget() on your widget class';
    }

    /**
     * Update the widget
     *
     * @access public
     */
    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        foreach ( $this->params['options'] as $option ) {
            // Get new value
            $value = $new_instance[$option['slug']];

            // Apply the filter if any
            if ( isset( $option['filters'] ) ) {
                $filters = preg_split( '/\s*,\s*/', $option['filters'] );
                foreach ( $filters as $function ) {
                    $value = WPop::call($function, $value);
                }
            }

            // Save the value
            $instance[ $option['slug'] ] = $value;
        }

        return $instance;
    }

    /**
     * Render the widget form options.
     *
     * @param   WP_Widget $instance
     * @access  public
     */
    function form($instance)
    {
        // Load the WPop_Form
        require_once 'wpop_form.php';

        $instance = wp_parse_args( (array) $instance, $this->defaults() );

        // Prepare the options to generate by WPop_Form
        foreach ( $this->params['options'] as $option ) {
            $name = $option['name'];
            $option['name'] = $this->get_field_name( $name );
            $option['id'] = $this->get_field_id( $name );

            if ( $option['type'] == 'text' ) {
                if ( isset( $option['attrs'] ) ) {
                    $option['attrs'] = array_merge( $option['attrs'], array( 'class' => 'widefat' ) );
                } else {
                    $option['attrs'] = array( 'class' => 'widefat' );
                }
            }

            // Create the html
            $html = sprintf( '<p><label for="%s">%s</label>%s', $option['id'], $option['title'], WPop_Form::input( $option['name'], $option, $instance[$option['slug']] ) );
            if ( isset( $option['description'] ) ) {
                $html .= sprintf( '<small>%s</small>', __( $option['desc'] ) );
            }
            $html .= '</p>' . "\n";

            // Echoes the html
            echo $html;
        }
    }

    /**
     * Get default value.
     *
     * @return  array
     * @access  protected
     */
    function defaults()
    {
        foreach ( $this->params['options'] as $option ) {
            if ( isset( $option['std'] ) ) {
                $this->defaults[ $option['slug'] ] = $option['std'];
            }
        }

        return $this->defaults;
    }
}
