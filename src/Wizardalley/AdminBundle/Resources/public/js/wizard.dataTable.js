/*!
 * jQuery lightweight plugin boilerplate
 * Original author: @ajpiano
 * Further changes, comments: @addyosmani
 * Licensed under the MIT license
 */

;(function ( $, window, document, undefined ) {
    var pluginName = "adminDatatable",
        defaults = {
            propertyName: "value"
        };

    // The actual plugin constructor
    function Plugin( element, options ) {
        this.element = element;
        this.$table = $(this.element).find('table');
        // the default options for future instances of the plugin
        this.options = $.extend( {}, defaults, options) ;
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    Plugin.prototype = {

        init: function() {
            var renderColumn = function ( data, type, row ) {
                var templateName = data['template'],
                    template = _.template(
                        $( "script." + templateName ).html()
                    );
                return template(data['render']);
            };
            var option = this.$table.data('datatable-option'),
                tmp = [],
                self = this;
            $.each(option['columns'], function(index,data) {
                tmp.push({"data": data['data'], "render": renderColumn});
            });
            option['columns'] = tmp;
            this.datatableOption = option;
            this.modal = $('#modal-wizard');
            this.$table.DataTable(this.datatableOption);
            console.log(this.$table);
            this.$table.on('click', 'a.link-modal', function(){
                self.displayModal($(this));
            });
        },

        displayModal: function($button) {
            // some logic
            var template = _.template(
                $( "script." + $button.data('template') ).html()
            );
            this.modal.find('.modal-body').html(template($button.data('data')));
            this.modal.find('.modal-title').html($button.data('title'));
            this.modal.find('form').attr('action', $button.data('action'));
            this.modal.modal('show');
        }
    };

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                    new Plugin( this, options ));
            }
        });
    };

})( jQuery, window, document );

$('#wizardDataTable').adminDatatable();