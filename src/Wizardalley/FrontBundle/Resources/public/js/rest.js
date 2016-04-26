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
        this._$currentPlugin = null;
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
        /**
         *
         */
        init: function() {
            //Si l'utilisateur a un token on affiche la page en fonction du href

            // Sinon affichage du login
        },

        /**
         *
         * @param _password
         * @param _username
         */
        authentificate: function(_password, _username) {
            var _this = this,
                route = Routing.generate('api_login_check');
            $.ajax({
                method: "POST",
                url: route,
                data: { _password: _password, _username: _username },
                success: function (data, textStatus, jqXHR) {
                    _this.writeToken(data['token']);
                    _this.displayPage(
                        $('.wizardsalley-main-container'),
                        'home-base-template'
                    );
                    _this.loadHomePage();
                }
            });

        },

        /**
         *
         * @param $remplacementBlock
         * @param templateId
         */
        displayPage: function($remplacementBlock, templateId){
            var compiledTemplate = _.template($('#' + templateId).html());
            $remplacementBlock.html(compiledTemplate({}));
        },

        /**
         * @param url
         * @param data
         * @param $remplacementBlock
         * @param templateId
         */
        loadPage: function(url, data, $remplacementBlock, templateId) {
            var _this = this;
            $.ajax({
                method: "POST",
                data: data,
                beforeSend: function (request)
                {
                    request.setRequestHeader('Authorization', 'Bearer ' + _this.getToken());
                },
                url: url,
                success: function(data){
                    // recuperer le contenu
                    var content = data['content'],
                        compiledTemplate = _.template($('#' + templateId).html());
                    $remplacementBlock.html(compiledTemplate(content,{
                        escape: false, // use a false evaluated value
                        evaluate: /(.)^/ // or a not matching regex
                    }));
                },
                complete: function(data){
                    console.log(data);
                }
            });
        },

        /**
         * @param url
         * @param data
         * @param $remplacementBlock
         * @param templateId
         */
        addPanel: function(url, data, $remplacementBlock, templateId) {
            var _this = this;
            $.ajax({
                method: "POST",
                data: data,
                beforeSend: function (request)
                {
                    request.setRequestHeader('Authorization', 'Bearer ' + _this.getToken());
                },
                url: url,
                success: function(data){
                    // recuperer le contenu
                    var content = data['content'],
                        compiledTemplate = _.template($('#' + templateId).html());
                    $remplacementBlock.append(compiledTemplate(content,{
                        escape: false, // use a false evaluated value
                        evaluate: /(.)^/ // or a not matching regex
                    }));
                },
                complete: function(data){
                    console.log(data);
                }
            });
        },

        loadHomePage: function(){
            if( this._$currentPlugin != null) {
                this._$currentPlugin.remove();
            }
            this._$currentPlugin = $('.wizardsalley-main-container').homePagePlugin();
        },

        /**
         * Recuperer le token
         * @returns token
         */
        getToken: function() {
            return Cookies.get('wizard_token');
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
        },

        /**
         * Fonction permettant de charger les informations utilisateurs
         */
        getInfoUser: function(){

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