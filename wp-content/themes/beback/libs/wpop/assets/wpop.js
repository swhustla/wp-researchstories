(function ($) {
    jQuery.fn.center = function () {
        this.css('top', Math.ceil( ( $(window).height() - this.height() - 200 ) / 2 + $(window).scrollTop() ) + 'px');
        this.css('left', Math.ceil( ( $(window).width() - this.width() - 665 ) / 2 + $(window).scrollLeft() ) + 'px');
        return this;
    }

    WPop_Nav = {
        groups: [],
        init: function() {
            $('li', '#wpop_nav').each(function(i) {
                var group = $(this).attr('id').substring(9);
                var option = '#wpop_options_' + group;

                // hide all option elements
                $(option).hide();

                // show the first
                if (i == 0) {
                    $(option).show();
                    $(this).addClass('current');
                } else if (i == 1) {
                    $(this).addClass('alt')
                }

                // add to array
                WPop_Nav.groups.push(group);
            });
            
            $('a', '#wpop_nav').each(function() {
                $(this).bind('click', function() {
                    WPop_Nav.current(this);
                    return false;
                });
            });
        },
        before: function(el) {
            var k = $.inArray($(el).attr('id').substring(9), WPop_Nav.groups) - 1;
            if (k < 0) return null;
            return WPop_Nav.groups[k];
        },
        after: function(el) {
            var k = $.inArray($(el).attr('id').substring(9), WPop_Nav.groups) + 1;
            if (k == WPop_Nav.groups.length ) return null;
            return WPop_Nav.groups[k];
        },
        current: function(el) {
            var li = $(el).parent().get(0);
            var group = $(li).attr('id').substring(9);
            var vis = $('div:visible', '#wpop_content').attr('id').substring(13);
            var after = this.after(li);

            $('#wpop_options_' + vis).fadeOut('fast', function() {
                $('#wpop_nav_' + vis).removeClass('current');
                $(li).addClass('current');

                $('li', '#wpop_nav').removeClass('alt');
                if (after) $('#wpop_nav_' + after).addClass('alt');

                $('#wpop_options_' + group).fadeIn('fast');
            });
        }
    };
    
    WPop_ColorPicker = {
        current: '',
        init: function() {
            $('.wpop_colorpicker').ColorPicker({
                onBeforeShow: function(picker) {
                    WPop_ColorPicker.current = this;

                    var color = $(this).children('div').css('backgroundColor');
                    if (color == 'transparent') color = '#ffffff';

                    $(this).ColorPickerSetColor(WPop_ColorPicker.fixRGB(color));
                },
                onShow: function (picker) {
                    $(picker).fadeIn('fast');
                    return false;
                },
                onHide: function (picker) {
                    $(picker).fadeOut('fast');
                    return false;
                },
                onChange: function (hsb, hex, rgb) {
                    if (hex == 'NaNNaNNaN') return;

                    $(WPop_ColorPicker.current).children('div').css('backgroundColor', '#' + hex);
                    $(WPop_ColorPicker.current).next('input').attr('value','#' + hex);
                }
            });
            
            $('.wpop_color').bind('change keypress', function() {
                try {
                    $(this).prev('div').children('div').animate({backgroundColor: $(this).val()}, 'fast');
                } catch(e) {
                    $(this).prev('div').children('div').css('backgroundColor', $(this).val());
                }
            })
        },
        fixRGB: function(rgb) {
            var color = rgb.slice(4, -1).split(',');
            return {
              r: parseInt(color[0]),
              g: parseInt(color[1]),
              b: parseInt(color[2])
            };
        }
    };
    
    WPop_Uploader = {
        init: function() {
            $('.upload_remove').bind('click', function() {
                WPop_Uploader.remove(this);
            });
                
            $('.upload_button').click(function() {
                var button = this;
                var input = $(this).prev('input');
                var post_id = $(this).next('input').val();
                var title = $($(this).parents('.option').get(0)).prev('h3').text();

                tb_show(title, 'media-upload.php?post_id=' + post_id + '&amp;type=image&amp;TB_iframe=1');

                window.send_to_editor = function(html) {
                    tb_remove();
                    var img = $('img', html).attr('src');
                    $(input).val(img);
                    WPop_Uploader.display(input);
                }

                return false;
            });
        },
        display: function(ref) {
            var $preview = $(ref).nextAll('div');

            $preview.html('<div style="display: none;"><a href="' + $(ref).val() + '" class="upload_fullsize" target="_blank" title="View full size"><img src="' + $(ref).val() + '" /></a><a href="#" class="upload_remove" title="Remove">Remove</a></div>');
            $('div', $preview).fadeIn();

            $('.upload_remove', $preview).bind('click', function() {
                WPop_Uploader.remove(this);
                return false;
            });
        },
        remove: function(ref) {
            var block = $(ref).parents('.input').get(0);
            $('.upload_preview div', block).fadeOut('fastx', function() {
                $(this).remove();
                $('.upload', block).val('');
            });
        }
    };
    
    WPop_Scheme = {
        init: function() {
            $('.scheme').click(function() {
                WPop_Scheme.set($(this).attr('id').substring(7));
                return false;
            });
        },
        set: function(scheme) {
            $('.section_scheme .current').fadeOut('fast', function() {
                $('.section_scheme .current').appendTo('#scheme-' + scheme).fadeIn('fast');
                $('.section_scheme input').val(scheme);
            });
        }
    };
    
    var WPopSliders = [];
    $.fn.WPopSlider = function() {
        // Find all slider sources.
        var slider_sources = [];
        this.each(function(i) {
            if ( $.inArray($(this).attr('name'), slider_sources) == -1 ) {
                slider_sources.push($(this).attr('name'));
            }
        });

        $.each(slider_sources, function(i) {
  
            WPopSliders[i] = {
                source_name: null,
                entries_name: null,
                $source: null,
                $entries: null,
                source: '',
                init: function(source_name) {
                    var me = this;

                    me.source_name = source_name;
                    me.findEntriesElement();
                    me.$source = $('input[name=' + me.source_name + ']');
                    me.$entries = $('input[name=' + me.entries_name + ']');

                    // If has option checked on load
                    if ($('.slider_source:checked', me.$source.parents('.input')).length > 0) {
                        me.source = $('.slider_source:checked', me.$source.parents('.input')).val();
                        me.showOptions(false);
                    }
                    
                    // Enable sortable
                    $('.wpop_sortable', me.$entries.parents('.input')).sortable({
                        update: function(event, ui) { me.updateEntries(this); }
                    });
                    
                    me.bindRadios();
                    me.bindEntries();
                    
                    $('.slider_entries_add .button', me.$entries.parents('.input')).click(function() {
                        me.addEntry();
                    });
                },
                findEntriesElement: function() {
                    var me = this;

                    $('.slider_entries_reference').each(function() {
                        if ( 'wpop_theme_' + $(this).text() == me.source_name ) {
                            me.entries_name = $(this).prev().attr('name');
                            return true;
                        }
                    });

                    return false;
                },
                bindRadios: function() {
                    var me = this;
                    $(':radio', me.$source.parents('.input')).click(function() {
                        if (me.source == $(this).val()) return;
                        me.source = $(this).val();
                        me.showOptions(true);
                    });
                },
                showOptions: function(reset) {
                    var me = this;

                    var select_name = me.entries_name + '_' + me.source;

                    if ($('.slider_entries_options:visible', me.$entries.parent('.input')).length > 0) {
                        $('.slider_entries_options:visible', me.$entries.parent('.input')).fadeOut('normal', function() {
                            $('select[name=' + select_name + ']').fadeIn();
                        });
                    } else {
                        $('select[name=' + select_name + ']').fadeIn();
                    }

                    if (reset) {
                        var $list = $('.slider_entries ul', me.$entries.parent('.input'));
                        $('li', $list).fadeOut('normal', function() {
                            me.$entries.val('');
                            $list.empty();
                        });
                    }
                },
                bindEntries: function() {
                    var me = this;

                    $('.slider_entries_remove', me.$entries.parents('.input')).unbind();
                    $('.slider_entries_remove', me.$entries.parents('.input')).click(function() {
                        var value = $(this).next('.slider_entry_value').text();
                        $('.slider_entries_remove').parents('li').each(function() {
                            if ($('.slider_entry_value', $(this)).text() == value) {
                                $(this).fadeOut('normal', function() {
                                    me.removeEntry(value);
                                    $(this).remove();
                                });
                            }
                        });

                        return false;
                    });
                },
                getEntries: function() {
                    var me = this;
                    var values = [];
                    if (/,/.test(me.$entries.val())) {
                        values = me.$entries.val().split(',');
                    } else if (me.$entries.val() != '') {
                        values.push(me.$entries.val());
                    }
                    return values;
                },
                addEntry: function() {
                    var me = this;

                    // get the selected value
                    var $selected = $(':selected', $('select:visible', me.$entries.parents('.input')));

                    // find the list of entries
                    var $list = $('.slider_entries ul', me.$entries.parent('.input'));

                    // skip if the entry already added
                    var entries = me.getEntries();
                    if ($.inArray($selected.val(), entries) > -1) return false;

                    // prepare the entry for append
                    var $entry = $('#slider_entries_dummy_entry').clone().attr('id', '').show();
                    $('h4', $entry).html($selected.text());
                    $('.slider_entry_value', $entry).text($selected.val());

                    // append to list
                    $entry = $('<li></li>').wrapInner($entry);
                    $entry.hide().addClass('widget entry').appendTo($list);
                    $entry.fadeIn();
                    
                    // Add to entries value
                    entries.push($selected.val());
                    me.$entries.val(entries.join(','));
                    
                    // Rebind the entries
                    me.bindEntries();
                },
                removeEntry: function(value) {
                    var me = this;
                    var values = me.getEntries();
                    var i = $.inArray(value, values)

                    // remove the id
                    if (i > -1) values.splice(i, 1);

                    me.$entries.val(values.join(','));
                },
                updateEntries: function(element) {
                    var me = this;
                    var values = [];

                    $('.slider_entry_value', element).each(function() {
                        values.push($(this).text());
                    });

                    me.$entries.val(values.join(','));
                }
            }

            WPopSliders[i].init(this.toString());

        });
    };

    WPop_Slider = {
        init: function() {
            $('.slider_source').WPopSlider();
        }
    };

    WPop_Form = {
        init: function() {
            $('#wpop_theme_form').submit(function() {
                WPop.flashMessage('Saving your settings, please wait.', 'loading');
                $.post('admin-ajax.php', {
                        action: 'wpop_theme_save_options',
                        data: $("#wpop_theme_form *").serialize()
                    },
                    function(res) {
                        WPop.flashMessage(res.text, res.type);
                    }
                , 'json');

                return false;
            });
        }
    };

    WPop = {
        init: function() {
            $('#wpop_message').center();

            WPop_Nav.init();
            WPop_ColorPicker.init();
            WPop_Uploader.init();
            WPop_Scheme.init();
            WPop_Form.init();
            WPop_Slider.init();

            $('#wpop_container').show();
        },
        flashMessage: function(msg, type) {
            var popup = $('#wpop_message');

            $(popup).html(msg).center();
            $(popup).removeClass('wpop_message_loading wpop_message_succeed wpop_message_error wpop_message_info');
            $(popup).addClass('wpop_message_' + type);
            $(popup).fadeIn('fast', function() {
                window.setTimeout(function(){
                    $(popup).fadeOut('fast'); 
                }, 1500);
            });
        }
    };
})(jQuery);

jQuery(document).ready(function() { 
    WPop.init();
});
