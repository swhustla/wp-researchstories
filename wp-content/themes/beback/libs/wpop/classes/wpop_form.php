<?php
/**
 * Wordspop Framework
 *
 * @category   Wordspop
 * @package    WPop_Form
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @version    $Id:$
 * @todo       More docs
 */

/**
 * @see WPop_Utils
 */
require_once 'wpop_utils.php';

/**
 * @category   Wordspop
 * @package    WPop_Form
 * @copyright  Copyright (c) 2010-2011 Wordspop
 * @license    http://www.opensource.org/licenses/gpl-2.0.php GNU GPL version 2
 * @author     Firman Wandayandi <firmanw@wordspop.com>
 */
class WPop_Form
{
    /**
     * Output the input
     *
     * @param string $name Option name
     * @param array $option Options
     * @param mixed $value Current value (default: null)
     *
     * @return string
     * @access public
     */
    function input( $name, $option, $value = null )
    {
        if ( $value === null ) {
          $value = self::_value( $name, $option );
        }

        switch ( $option['type'] ) {
            case 'text':
                return self::text( $name, $option, $value );
                break;
            case 'textarea':
                return self::textarea( $name, $option, $value );
                break;
            case 'select':
                return self::select( $name, $option, $value );
                break;
            case 'radio':
                return self::radio( $name, $option, $value );
                break;
            case 'checkbox':
                return self::checkbox( $name, $option, $value );
                break;
            case 'color':
                return self::color( $name, $option, $value );
                break;
            case 'upload':
                return self::upload( $name, $option, $value );
                break;
            case 'date':
                return self::date( $name, $option, $value );
                break;
            case 'character':
                $values = array(
                    'font'  => '',
                    'size'  => '',
                    'unit'  => '',
                    'style' => '',
                    'color' => ''
                );

                if ( is_string( $value ) ) {
                    $value = @unserialize( $value );
                    if ( is_array( $value ) ) {
                        $values = array_merge( $values, $value );
                    }
                }
                return self::character( $name, $option, $values );
                break;
            case 'scheme':
                return self::scheme( $name, $option, $value );
                break;
            case 'slider_entries':
                return self::sliderEntries( $name, $option, $value );
                break;
        }
    }

    /**
     * Output the input text
     *
     * @param string $name Option name
     * @param array $option Options
     * @param mixed $value Current value (default: null)
     *
     * @return string
     * @access public
     */
    function text( $name, $option, $value = null )
    {
        return sprintf( '<input type="text" id="%1$s" name="%2$s" value="%3$s" %4$s />', 
                  self::_id( $name, $option ), $name, $value, self::_attributes( $option )
               );
    }

    /**
     * Output the textarea input
     *
     * @param string $name Option name
     * @param array $option Options
     * @param mixed $value Current value (default: null)
     *
     * @return string
     * @access public
     */
    function textarea( $name, $option, $value = null )
    {
        return sprintf( '<textarea id="%1$s" name="%2$s" %4$s>%3$s</textarea>',
                  self::_id( $name, $option) , $name, $value, self::_attributes($option)
               );
    }

    /**
     * Output the select input
     *
     *
     * @param string $name Option name
     * @param array $option Options
     * @param mixed $value Current value (default: null)
     *
     * @return string
     * @access public
     */
    function select( $name, $option, $value = null )
    {
        $html = sprintf( '<select id="%1$s" name="%2$s">' . "\n", self::_id( $name, $option ), $name );
        $current = $value;
        $options = self::_options( $option['options'] );
        $html .= self::options( $options, $current );
        $html .= '</select>' . "\n";

        return $html;
    }

    /**
     * Output checkbox input
     *
     *
     * @param string $name Option name
     * @param array $option Options
     * @param mixed $value Current value (default: null)
     *
     * @return string
     * @access public
     */
    function checkbox( $name, $option, $value = null )
    {
        $checked = '';
        if ( !empty( $value ) ) {
            $checked = ' checked="checked"';
        }
        return sprintf( '<input type="checkbox" id="%1$s" name="%2$s" value="1"%4$s /><label for="%1$s">%3$s</label>',
                  self::_id( $name, $option ), $name, $option['desc'], $checked
               );
    }

    /**
     * Output radio input
     *
     *
     * @param string $name Option name
     * @param array $option Options
     * @param mixed $value Current value (default: null)
     *
     * @return string
     * @access public
     */
    function radio( $name, $option, $value = null )
    {
        $html = '';
        $options = self::_options( $option['options']) ;
        foreach ( $options as $val => $caption ) {
            $checked = '';
            if ( $val == $value ) {
                $checked = ' checked="checked"';
            }

            $class = '';
            if ( isset( $option['align'] ) && $option['align'] == 'vertical' ) {
                $class = ' class="vertical"';
            }

            $html .= sprintf( '<span%6$s><input type="radio" id="%1$s" name="%2$s" value="%3$s"%5$s%7$s /><label for="%1$s">%4$s</label></span>',
                        WPop_Utils::slugify( "{$name}_{$val}" ), $name, $val, $caption, self::_attributes( $option ), $class, $checked
                     );
        }

        return $html;
    }

    /**
     * Output color picker
     *
     * @param string $name Option name
     * @param array $option Options
     * @param mixed $value Current value (default: null)
     *
     * @return string
     * @access public
     */
    function color( $name, $option, $value = null )
    {
        $color = '';
        if ( $value ) {
            $color = sprintf( ' style="background-color: %s;"', $value );
        }

        $html = sprintf( '<div id="%s_picker" class="wpop_colorpicker"><div%s></div></div>',
                  self::_id( $name, $option ), $color
                );
        $html .= sprintf( '<input type="text" id="%1$s" name="%2$s" value="%3$s" class="wpop_color" />',
                    self::_id( $name, $option ), $name, $value
                 );
        return $html;
    }

    /**
     * Output upload input
     *
     * @param string $name Option name
     * @param array $option Options
     * @param mixed $value Current value (default: null)
     *
     * @return string
     * @access public
     */
    function upload( $name, $option, $value = null )
    {
        $img = '';
        if ( $value ) {
            if ( WPop_Utils::attachmentExists( $value ) ) {
                $img = sprintf(
                          '<div><a href="%1$s" title="View full size" class="upload_fullsize" target="_blank">' .
                          '<img src="%1$s" /></a><a href="#" class="upload_remove" title="%2$s">%2$s</a>' .
                          '</div>',
                          $value, __( 'Remove', WPOP_THEME_SLUG )
                       );
            } else {
                $img = sprintf( '<div class="wpop_filenotfound">%s</div>', __( 'File not found', WPOP_THEME_SLUG ) );
            }
        }

        $html = sprintf( '<input type="text" id="%1$s" name="%2$s" value="%3$s" class="upload" />',
                  self::_id( $name, $option ), $name, $value
                );
        $html .= sprintf( '<input type="button" id="%s_upload" class="button upload_button" value="%s" />',
                    self::_id( $name, $option ), __( 'Upload', WPOP_THEME_SLUG )
                 );
        $html .= sprintf( '<input type="hidden" id="%s_post" class="ignore" value="%d" />',
                    self::_id( $name, $option ), WPop::getDummyPost( self::_id( $name, $option) )
                 );
        $html .= sprintf( '<div id="%s_preview" class="upload_preview">%s</div>', self::_id( $name, $option ), $img );
        return $html;
    }

    /**
     * Output date input
     *
     * @param string $name Option name
     * @param array $option Options
     * @param mixed $value Current value (default: null)
     *
     * @return string
     * @access public
     */
    function date( $name, $option, $value )
    {
        $date = array(
            'month' => date( 'n' ),
            'day'   => date( 'j' ),
            'year'  => date( 'Y' )
        );

        if ( $value ) {
            $time = strtotime( $value );
            $date['month'] = date( 'n', $time );
            $date['day'] = date( 'j', $time ) ;
            $date['year'] = date( 'Y', $time );
        }

        $html = sprintf( '<select class="date_month" id="%1$s[month]" name="%2$s[month]">%3$s</select>',
                  self::_id( $name, $option ), $name,
                  self::options( WPop::call( 'wpop_months_options' ), $date['month'] )
                );
        $html .= sprintf( '<select class="date_day" id="%1$s[day]" name="%2$s[day]">%3$s</select>',
                    self::_id( $name, $option ), $name,
                    self::options( WPop::call( 'wpop_days_options' ), $date['day'] )
                 );
        $html .= sprintf( '<input type="text" id="%1$s[year]" name="%2$s[year]" class="date_year" maxlength="4" value="%3$s" />',
                    self::_id( $name, $option ), $name, $date['year']
                 );
        return $html;
    }

    /**
     * Output character input
     *
     *
     * @param string $name Option name
     * @param array $option Options
     * @param mixed $value Current value (default: null)
     *
     * @return string
     * @access public
     */
    function character( $name, $option, $value )
    {
        include_once WPOP_BUNDLED . DS . 'fonts.php';

        $id = self::_id( $name, $option );
        $sizes = array( '' => 'Default...' );
        for ( $i = 9; $i <= 70; $i++ ) {
            $sizes[$i] = $i;
        }

        $color = '';
        if ( $value['color'] ) {
            $color = sprintf( ' style="background-color: %s;"', $value['color'] );
        }

        $checked = '';
        if ( isset( $value['enable'] ) && $value['enable'] ) {
            $checked = ' checked="checked"';
        }

        $html = sprintf( '<select id="%s_font" name="%s[font]" class="character_font">' . "\n" . '%s</select>' . "\n",
                  $id, $name, self::options( $GLOBALS['wpop_fonts'], $value['font'] )
                );
        $html .= sprintf( '<select id="%s_size" name="%s[size]" class="character_size">' . "\n" . '%s</select>' . "\n",
                    $id, $name, self::options( $sizes, $value['size'] )
                 );
        $html .= sprintf( '<select id="%s_unit" name="%s[unit]" class="character_unit">' . "\n" . '%s</select>' . "\n",
                    $id, $name, self::options( array( 'px' => 'px', 'em' => 'em' ), $value['unit'] )
                 );
        $html .= sprintf( '<select id="%s_style" name="%s[style]" class="character_style">' . "\n" . '%s</select>' . "\n",
                 $id, $name,
                 self::options( array(
                    ''     => __( 'Default...', WPOP_THEME_SLUG ),
                    'normal'      => __( 'Normal', WPOP_THEME_SLUG ),
                    'bold'        => __( 'Bold', WPOP_THEME_SLUG ),
                    'italic'      => __( 'Italic', WPOP_THEME_SLUG ),
                    'bold italic' => __( 'Bold Italic', WPOP_THEME_SLUG )
                    ),  $value['style'] )
                 );
        $html .= sprintf( '<div id="%s_picker" class="wpop_colorpicker"><div%s></div></div>', $id, $color);
        $html .= sprintf( '<input type="text" id="%s_color" name="%s[color]" value="%s" class="character_color" />',
                    $id, $name, $value['color']
                 );
        $html .= sprintf(
                   '<input type="checkbox" id="%1$s_enable" name="%2$s[enable]" class="character_enable"%3$s />' .
                   '<label for="%1$s_enable">%4$s</label>',
                    $id, $name, $checked, __( 'Enable', WPOP_THEME_SLUG )
                 );

        return $html;
    }

    /**
     * Output scheme input
     *
     * @param string $name Option name
     * @param array $option Options
     * @param mixed $value Current value (default: null)
     *
     * @return string
     * @access public
     */
    function scheme( $name, $option, $value )
    {
        $options = self::_options( $option['options'] );
        $html = sprintf( '<input type="hidden" id="%1$s" name="%2$s" value="%3$s" />',
                  self::_id( $name, $option ), $name, $value
                );
        $html .= '<ul>';
        $i = 0;
        foreach ( $options as $scheme => $image ) {
            $class = $current = '';
            if ( $i >= count( $options ) - 2 && ( $i % 2 == 0 || $i == count( $options ) - 1 ) ) {
                $class = ' class="last"';
            }

            if ( $scheme == $value ) {
                $current = sprintf('<span class="current">%s</span>', __( 'Current', WPOP_THEME_SLUG ) );
            }

            $html .= sprintf(
                        '<li%1$s><a id="scheme-%2$s" href="#" class="scheme" title="%2$s">' .
                          '<span class="caption">%2$s</span>' . 
                          '<img src="%3$s" width="240" height="180" title="%2$s" alt="%2$s"/>%4$s</a>' .
                        '</li>',
                        $class, $scheme, $image, $current
                     );
            $i++;
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Output the slider entries
     *
     * @param string $name Option name
     * @param array $option Options
     * @param mixed $value Current value (default: null)
     *
     * @return string
     * @access public
     */
    function sliderEntries( $name, $option, $value )
    {
        $html = sprintf( '<input type="hidden" id="%1$s" name="%1$s" value="%2$s" />',
                  self::_id( $name, $option ), $value
                );
        $html .= sprintf( '<span id="%s_reference" class="slider_entries_reference" style="display: none;">%s</span>', self::_id( $name, $option ), $option['reference'] );
        $html .= '<div class="slider_entries">' . "\n" .
                    '<ul class="wpop_sortable">' . "\n" .
                    self::_sliderEntries( wpop_get_option( $option['reference'] ), $value ) .
                    '</ul>' . "\n" .
                 '</div>' . "\n" .
                 '<div class="slider_entries_add">' . "\n" .
                    sprintf( '<select name="%s_posts" class="slider_entries_options" style="display: none;">', self::_id( $name, $option ) ) . "\n" .
                      self::options( WPop::call( 'wpop_posts_options' ), '' ) . "\n" .
                    '</select>' . "\n" .
                    sprintf( '<select name="%s_pages" class="slider_entries_options" style="display: none;">', self::_id( $name, $option ) ) . "\n" .
                      self::options( WPop::call( 'wpop_pages_options' ), '' ) . "\n" .
                    '</select>' . "\n" .
                    sprintf( '<select name="%s_categories" class="slider_entries_options" style="display: none;">', self::_id( $name, $option ) ) . "\n" .
                      self::options( WPop::call( 'wpop_categories_options' ), '' ) . "\n" .
                    '</select>' . "\n" .
                    sprintf( '<select name="%s_tags" class="slider_entries_options" style="display: none;">', self::_id( $name, $option ) ) . "\n" .
                      self::options( WPop::call( 'wpop_tags_options' ), '' ) . "\n" .
                    '</select>' . "\n" .
                    sprintf( '<input type="button" value="%s" class="button">', __( 'Add', WPOP_THEME_SLUG ) ) . "\n" .
                 '</div>' . "\n";

        return $html;
    }

    /**
     * Output the slider entry
     *
     * @param string $source Slider source
     * @param mixed $value Slider entries
     *
     * @return string
     * @access private
     */
    function _sliderEntries( $source, $value )
    {
        if ( $value === '' ) {
            return '';
        }

        $values = '';
        $entries = array();
        if ( is_array( $value ) ) {
            $values = implode( ',', $value );
        } else {
            $values = $value;
        }

        $tmp = explode( ',', $values );
        foreach ( $tmp as $val ) {
            $entries[$val] = '';
        }

        switch ( $source ) {
            case 'posts':
            case 'pages':
                foreach ( $entries as $id => $val ) {
                    $post = get_post( $id );
                    if ( $post === null ) {
                        $entries[$id] = false;
                    } else {
                        $entries[$id] = $post->post_title;
                    }
                }
                break;
            case 'categories':
                $res = get_categories( 'hide_empty=0&include=' . $values );
                foreach ( $res as $val ) {
                    $entries[$val->cat_ID] = $val->name;
                }
                break;
            case 'tags':
                foreach ( $entries as $id => $val ) {
                    $tag = get_term_by( 'id', $id, 'post_tag' );
                    if ( $tag === null ) {
                        $entries[$id] = false;
                    } else {
                        $entries[$id] = $tag->name;
                    }
                }
                break;
        }

        $html = '';
        foreach ( $entries as $id => $title ) {
            if ( $title === false ) {
                continue;
            }

            $html .= '<li class="widget entry">' . "\n" .
                       '<div class="widget-top">' . "\n" .
                         '<div class="widget-title-action">' . "\n" .
                           sprintf( '<a class="widget-action slider_entries_remove" href="#" title="%1$s">%1$s</a>', __( 'Remove', WPOP_THEME_SLUG ) ) . "\n" .
                           '<span class="slider_entry_value" style="display: none;">' . $id . '</span>' . "\n" .
                         '</div>' . "\n" .
                         '<div class="widget-title"><h4>' . $title . '</h4></div>' . "\n" .
                       '</div>' . "\n" .
                     '</li>' . "\n";
        }

        return $html;
    }

    /**
     * Output the option element
     *
     * @param array $options List of options
     * @param mixed $current Selected value
     *
     * @return string
     * @access public
     */
    function options( $options, $current )
    {
        $html = '';
        foreach ( $options as $value => $caption ) {
            $selected = '';
            if ( $value == $current ) {
                $selected = ' selected="selected"';
            }

            $html .= sprintf( '<option value="%1$s"%3$s>%2$s</option>' . "\n",
                        esc_html( $value ), esc_html( $caption ), $selected
                     );
        }
        return $html;
    }

    /**
     * Get the option value
     *
     * @param string $name Option name
     * @param array $option Option
     *
     * @return mixed
     * @access private
     */
    function _value( $name, $option )
    {
        $value = '';
        if ( trim( get_option( $name ) ) != '' ) {
            $value = get_option( $name );
        } else if ( array_key_exists( 'std', $option ) ) {
            $value = $option['std'];
        }

        return $value;
    }

    /**
     * Get the attributes
     *
     * @param array $option Option
     *
     * @return string
     * @access private
     */
    function _attributes( $option )
    {
        if ( !array_key_exists( 'attrs', $option ) ) {
            return '';
        }

        if ( !is_array( $option['attrs'] ) ) {
            return $option['attrs'];
        }

        $html = '';
        foreach ( $option['attrs'] as $name => $value ) {
            $html .= "{$name}=\"{$value}\"";
        }

        return $html;
    }

    /**
     * Get the option list
     *
     * @param mixed $options The options
     *
     * @return array
     * @access private
     */
    function _options( $options )
    {
        if ( is_string( $options ) || is_callable( $options ) ) {
            require_once 'wpop.php';
            $res = WPop::call( $options );

            // Make the returns is an array
            settype( $res, 'array' );
            return $res;
        } else if ( is_array( $options ) ) {
            return $options;
        } else {
            wp_die( __( 'Invalid options', WPOP_THEME_SLUG ) );
        }
    }

    /**
     * Get the id
     *
     * @param string $name Option name
     * @param string $option Option
     *
     * @return string
     * @access private
     */
    function _id( $name, $option )
    {
        $id = $name;
        if ( isset( $option['id'] ) ) {
            $id = $option['id'];
        }

        return $id;
    }
}
