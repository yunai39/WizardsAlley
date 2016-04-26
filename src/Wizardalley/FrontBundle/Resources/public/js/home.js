// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;
( function( $, window, document, undefined ) {

    "use strict";
    var pluginName = "homePagePlugin",
        defaults = {
            propertyName: "value"
        };

    // The actual plugin constructor
    function Plugin ( element, options ) {
        this._$element = $(element);
        this.settings = $.extend( {}, defaults, options );
        this._defaults = defaults;
        this._lastId = -1;
        this._name = pluginName;

        this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend( Plugin.prototype, {

        remove: function(){
            this._destroy();
        },
        init: function() {
            var _this = this;
            this._$element.find('.wizardsalley-home-button-loadMore').on('click', function(){
                _this.loadMorePublication();
            });


            this._$element.find('form#wizardalley_publicationbundle_add_small_publication').on('submit', function(e){
                e.preventDefault();
                _this.addSmallPublication(this);
            });
        },

        addSmallPublication: function(form) {

        },

        /**
         * @param url
         * @param data
         * @param $remplacementBlock
         * @param templateId
         */
        addPanel: function(url, data, $remplacementBlock, templateId) {
            var _this = this,
                token = $(document).data('plugin_restPlugin').getToken();
            $.ajax({
                method: "POST",
                data: data,
                beforeSend: function (request)
                {
                    request.setRequestHeader('Authorization', 'Bearer ' + token);
                },
                url: url,
                success: function(data){
                    _this._lastId = data.last_id;
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

        /**
         * @param id_publication
         */
        loadMorePublication: function(){
            this.addPanel(
                Routing.generate('wizard_api_get_publication_view', {'publication_id': this._lastId}),
                {},
                this._$element.find('.wizardsalley-home-container-publication'),
                'home-publication-container-mini'
            );
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
