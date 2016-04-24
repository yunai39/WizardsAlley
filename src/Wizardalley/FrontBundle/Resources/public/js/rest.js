// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;
( function( $, window, document, undefined ) {

    "use strict";
    var pluginName = "restPlugin",
        defaults = {
            propertyName: "value"
        };

    // The actual plugin constructor
    function Plugin ( element, options ) {
        var _this = this;
        this._$element = $(element);
        this.settings = $.extend( {}, defaults, options );
        this._defaults = defaults;
        this._name = pluginName;
        this._login_form = this._$element.find('.wizardsalley-login-form');
        this._login_form.find('input.wizardsalley-login-form-submit').on('click', function(event){
            event.preventDefault();
            var login =  _this._login_form.find("input[name='_username']").val(),
                password =  _this._login_form.find("input[name='_password']").val();
            _this.authentificate(login, password);
        });
        this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend( Plugin.prototype, {
        init: function() {

        },
        authentificate: function(_password, _username) {
            var _this = this,
                route = Routing.generate('api_login_check');
            $.ajax({
                method: "POST",
                url: route,
                data: { _password: _password, _username: _username },
                success: function (data, textStatus, jqXHR) {
                    _this.writeToken(data['token']);
                    _this.loadPage(
                        Routing.generate('api_test'),
                        '',
                        $('.wizardsalley-main-container'),
                        '<%= contenu %>'
                    )
                }
            });

        },


        loadPage: function(url, data, $remplacementBlock, templateHtml) {
            var _this = this;
            $.ajax({
                method: "POST",
                data: data,
                beforeSend: function (request)
                {
                    request.setRequestHeader('Authorization', _this.getToken());
                },
                url: url,
                success: function(data){
                    // recuperer le contenu
                    var content = data['content'],
                        compiledTemplate = _.template(templateHtml);
                    $remplacementBlock.html(compiledTemplate(data));
                },
                complete: function(data){
                    console.log(data);
                }
            });
        },

        /**
         * Recuperer le token
         * @returns token
         */
        getToken: function() {
            return 'Bearer ' + Cookies.get('wizard_token');
        },
        /**
         * Setter le token
         * @param _token
         */
        writeToken: function(_token) {
            Cookies.set('wizard_token', _token);
        },
        /**
         *
         */
        removeToken: function() {
            Cookies.remove('wizard_token');
        }
    } );


    $.fn[ pluginName ] = function( options ) {
        return this.each( function() {
            if ( !$.data( this, "plugin_" + pluginName ) ) {
                $.data( this, "plugin_" +
                    pluginName, new Plugin( this, options ) );
            }
        } );
    };


} )( jQuery, window, document );

// Instantiation du plugin
$(document).ready(function(){
   $(document).restPlugin();
});