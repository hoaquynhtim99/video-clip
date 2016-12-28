/**
 * @Project VIDEO CLIPS AJAX 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 01, 2014, 04:33:14 AM
 */

function nv_topic_del(a) {
    if( ! confirm(nv_is_del_confirm[0]) ){
        return false;
    }
    
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topic&nocache=' + new Date().getTime(), 'del=1&tid=' + a, function(res) {
        "OK" == res ? window.location.href = window.location.href : alert(nv_is_del_confirm[2]);
    });
    
    return false;
}

function nv_chang_weight(a) {
    nv_settimeout_disable("weight" + a, 5E3);
    var b = document.getElementById("weight" + a).options[document.getElementById("weight" + a).selectedIndex].value;
    
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topic&nocache=' + new Date().getTime(), 'changeweight=1&tid=' + a + '&new=' + b, function(res) {
        "OK" != res && alert(nv_is_change_act_confirm[2]);
        clearTimeout(nv_timer);
        window.location.href = window.location.href
    });
    
    return false;
}

function nv_chang_status(a) {
    nv_settimeout_disable("change_status" + a, 5E3);
    
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topic&nocache=' + new Date().getTime(), 'changestatus=1&tid=' + a, function(res) {
        "OK" != res && (alert(nv_is_change_act_confirm[2]), window.location.href = window.location.href);
    });
}

+function($) {
    'use strict';
    
    var Validate = function(element, options) {
        var self    = this,
            editor  = false

        this.$element = $(element)
        this.options = options
        
        this.$element.attr('novalidate', true) // disable automatic native validation
        
        this.rebuildForm()
        
        this.$element.on('submit.bs.validate', $.proxy(this.onSubmit, this))
        
        this.$element.find("div.required,div.checkbox,div.radio,div.ckeditor,input:not(:button,:submit,:reset),select,textarea").each(function() {
            var element   = this,
                tagName   = $(this).prop('tagName'),
                name
            
            if( tagName == 'DIV' ){
                if( $(element).is('.ckeditor') ){
                    if( typeof CKEDITOR == 'object' && ( name = $(element).find('textarea:first').prop('id') ) && CKEDITOR.instances[name] ){
                        CKEDITOR.instances[name].on('change', function(){
                            self.hideError(element)
                        })
                    }
                }else{
                    $(element).on('click.bs.validate', function(e) {
                        self.hideError(element)
                    })            
                }
            }else if( tagName == 'SELECT' ){
                $(element).on('click.bs.validate change.bs.validate', function(e) {
                    if( e.which != 13 ){
                        self.hideError(element)
                    }
                })
            }else{
                $(element).on('keydown.bs.validate', function(e) {
                    if( e.which != 13 ){
                        self.hideError(element)
                    }
                })
            }
        })
    }
        
    Validate.VERSION  = '4.0.23'
    
    Validate.DEFAULTS = {
        type   : 'normal'      // normal|ajax|file
    }
    
    Validate.MAIL_FILTER = /^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$/
    
    Validate.prototype.onSubmit = function(e) {
        if( ! this.validate() ){
            e.preventDefault()
            return
        }
        
        this.updateElement()
        
        if( this.options.type == 'ajax' ){
            e.preventDefault()
             this.submitAjax()
        }else if( this.options.type == 'file' ){
            this.submitFile()
        }
        
        return true
    }
    
    Validate.prototype.rebuildForm = function(){
        var html = '';
        if( ! $('.form-element', this.$element).length || ! $('.form-result', this.$element).length ){
            this.$element.find('.form-group').each(function(){
                html += $(this).context.outerHTML;
            })
            this.$element.html('<div class="form-result"></div><div class="form-element">' + html + '</div>');
        }
    }
    
    Validate.prototype.validate = function(){
        var self = this
        var error = 0
        
        this.$element.find(".required").each(function(){
            if( "password" == $(this).prop("type") ){
                $(this).val(self.trim(self.stripTags($(this).val())));
            }
    
            if (!self.check(this)){
                error ++
                if( typeof $(this).data('mess') != 'undefined' && $(this).data('mess') != '' ){
                    $(this).attr("data-current-mess", $(this).data('mess')).data('current-mess', $(this).data('mess'))
                }
                self.showError(this, error)
                
                return
            }else{
                self.hideError(this)
            }
        })
        
        return error ? false : true
    }
    
    Validate.prototype.hideAllError = function(){
        $(".has-error", this.$element).removeClass("has-error")
        $(".required", this.$element).tooltip("destroy")
    }
    
    Validate.prototype.hideError = function( element ){
        $(element).tooltip("destroy")
        
        if( $(element).parent().is('.input-group') ){
            $(element).parent().parent().parent().removeClass("has-error")
        }else{
            $(element).parent().parent().removeClass("has-error");
        }
    }
    
    Validate.prototype.showError = function( element, order ){
        var name
        
        if( $(element).parent().is('.input-group') ){
            $(element).parent().parent().parent().addClass("has-error")
        }else{
            $(element).parent().parent().addClass("has-error");
        }
        
        $(element).tooltip({
            placement: "bottom",
            title: function() {
                return ( typeof $(this).data('current-mess') != 'undefined' && $(element).data('current-mess') != '' ) ? $(element).data('current-mess') : ( 'undefined' == typeof nv_required ? 'This field is required!' : nv_required )
            },
            trigger: 'manual'
        });
        
        $(element).tooltip("show")
        if( order == 1 ){
            if( $(element).prop("tagName") == 'DIV' ){
                if( $(element).is('.ckeditor') ){
                    if( typeof CKEDITOR == 'object' && ( name = $(element).find('textarea:first').prop('id') ) && CKEDITOR.instances[name] ){
                        CKEDITOR.instances[name].focus()
                    }
                }else{
                    $("input", element)[0].focus()
                }
            }else{
                $(element).focus()
            }
        }
    }
    
    Validate.prototype.check = function( element ){
        var pattern = $(element).data('pattern'),
            value   = $(element).val(),
             tagName = $(element).prop('tagName'),
             type    = $(element).prop('type'),
             name, text
                
        if ("INPUT" == tagName && "email" == type) {
            if (!Validate.MAIL_FILTER.test(value)) return false
        } else if ("SELECT" == tagName) {
            if (!$("option:selected", element).length) return false
        } else if ("DIV" == tagName && $(element).is(".radio")) {
            if (!$("[type=radio]:checked", element).length) return false
        } else if ("DIV" == tagName && $(element).is(".checkbox")) {
            if (!$("[type=checkbox]:checked", element).length) return false
        } else if ("DIV" == tagName && $(element).is(".ckeditor")) {
            if( typeof CKEDITOR == 'object' && ( name = $(element).find('textarea:first').prop('id') ) && CKEDITOR.instances[name] ){
                text = CKEDITOR.instances[name].getData()
                text = this.trim( text )
                
                if( text != '' ){
                    return true
                }
            }
            return false
        } else if ("INPUT" == tagName || "TEXTAREA" == tagName)
            if ("undefined" == typeof pattern || "" == pattern) {
                if ("" == value) return false
            } else if (!(new RegExp(pattern)).test(value)) return false
        return true        
    }
    
    Validate.prototype.submitAjax = function(){
        var action  = this.$element.prop('action'),
            method  = this.$element.prop('method'),
            data    = this.$element.serialize(),
            self    = this
        
        if( typeof action == 'undefined' ){
            throw new Error('Missing action attitude for submit form')
        }
        if( typeof method == 'undefined' ){
            throw new Error('Missing method attitude for submit form')
        }
        
        if( action == '' ){
            action = window.location.href
        }

        this.hideAllError()
        this.$element.find("input,button,select,textarea").prop("disabled", true)
        
        $.ajax({
            type: method,
            cache: false,
            url: action,
            data: data,
            dataType: 'json',
            success: function(res) {
                var $this, type;
                
                self.$element.find("input,button,select,textarea").prop("disabled", false)
                if( ( res.status != 'error' && res.status != 'ok' ) || typeof res.message != 'string' || typeof res.input != 'string' ){
                    throw new Error('Response data is invalid!!')
                }
                if( res.status == 'error' ){
                    if( res.input != '' && $('[name="' + res.input + '"]', self.$element).length ){
                        $this = $('[name="' + res.input + '"]:first', self.$element)
                        type = $this.prop('type')
                        
                        if( type == 'checkbox' || type == 'radio' || $this.parent().parent().is('.ckeditor') ){
                            $this = $this.parent().parent();
                        }
                        
                        $this.attr("data-current-mess", res.message).data('current-mess', res.message)
                        self.showError($this, 1)
                    }else{
                        $('.form-result', self.$element).html('<div class="alert alert-danger">' + res.message + '</div>').show()
                        $("html, body").animate({ scrollTop: $('.form-result', self.$element).offset().top }, 500)
                    }
                    
                    return
                }
                
                $('.form-result', self.$element).html('<div class="alert alert-success">' + res.message + '</div>').show()
                $("html, body").animate({ scrollTop: $('.form-result', self.$element).offset().top }, 500, function(){
                    setTimeout(function() {
                        $('.form-element', self.$element).slideUp(500);
                    }, 200)
                    
                    setTimeout(function() {
                        window.location.href = ( typeof res.redirect == 'string' && res.redirect != '' ) ? res.redirect : window.location.href
                    }, 4000)
                })
            }
        })
    }
    
    Validate.prototype.updateElement = function(){
        var name

        if( typeof CKEDITOR == 'object' ){
            $('.ckeditor' , this.$element).each(function(){
                if( ( name = $(this).find('textarea:first').prop('id') ) && CKEDITOR.instances[name] ){
                    CKEDITOR.instances[name].updateElement()
                }
            })
        }
    }
    
    Validate.prototype.stripTags = function(str, allowed_tags) {
        var key = '', allowed = false;
        var matches = [];
        var allowed_array = [];
        var allowed_tag = '';
        var i = 0;
        var k = '';
        var html = '';
    
        var replacer = function(search, replace, str) {
            return str.split(search).join(replace);
        };
        // Build allowes tags associative array
        if (allowed_tags) {
            allowed_array = allowed_tags.match(/([a-zA-Z0-9]+)/gi);
        }
    
        str += '';
    
        // Match tags
        matches = str.match(/(<\/?[\S][^>]*>)/gi);
    
        // Go through all HTML tags
        for (key in matches) {
            if (isNaN(key)) {
                // IE7 Hack
                continue;
            }
    
            // Save HTML tag
            html = matches[key].toString();
    
            // Is tag not in allowed list ? Remove from str !
            allowed = false;
    
            // Go through all allowed tags
            for (k in allowed_array) {
                // Init
                allowed_tag = allowed_array[k];
                i = -1;
    
                if (i != 0) {
                    i = html.toLowerCase().indexOf('<' + allowed_tag + '>');
                }
                if (i != 0) {
                    i = html.toLowerCase().indexOf('<' + allowed_tag + ' ');
                }
                if (i != 0) {
                    i = html.toLowerCase().indexOf('</' + allowed_tag);
                }
    
                // Determine
                if (i == 0) {
                    allowed = true;
                    break;
                }
            }
    
            if (!allowed) {
                str = replacer(html, "", str);
                // Custom replace. No regexing
            }
        }
    
        return str;
    }
    
    Validate.prototype.trim = function(str, charlist) {
        var whitespace, l = 0, i = 0;
        str += '';
    
        if (!charlist) {
            whitespace = " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
        } else {
            charlist += '';
            whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
        }
    
        l = str.length;
        for ( i = 0; i < l; i++) {
            if (whitespace.indexOf(str.charAt(i)) === -1) {
                str = str.substring(i);
                break;
            }
        }
    
        l = str.length;
        for ( i = l - 1; i >= 0; i--) {
            if (whitespace.indexOf(str.charAt(i)) === -1) {
                str = str.substring(0, i + 1);
                break;
            }
        }
    
        return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
    }
    
    function Plugin( option ){
        return this.each(function(){
            var $this   = $(this)
            var options = $.extend({}, Validate.DEFAULTS, $this.data(), typeof option == 'object' && option)
            var data    = $this.data('bs.validate')
            
            if (!data && option == 'destroy') return
            if (!data) $this.data('bs.validate', (data = new Validate(this, options)))
            if (typeof option == 'string') data[option]()
        })
    }
    
    var old = $.fn.validate
    
    $.fn.validate = Plugin
    $.fn.validate.Constructor = Validate
    
    // VALIDATE NO CONFLICT
    // =================
    $.fn.validate.noConflict = function() {
        $.fn.validate = old
        return this
    }
    
    // VALIDATE DATA-API
    // ==============
    $(window).on('load', function() {
        $('form[data-toggle="validate"]').each(function() {
            var $form = $(this)
            Plugin.call($form, $form.data())
        })
    })
}(jQuery);

$(document).ready(function(){
    
});