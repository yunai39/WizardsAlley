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

        loadMorePublication: function(){
            $(document).data('plugin_restPlugin').loadPage(
                Routing.generate('wizard_api_get_publication_view', {'page': 1}),
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
