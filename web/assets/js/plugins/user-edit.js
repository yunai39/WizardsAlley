/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var adminUserEdit = (function(){
    return {
        "init": function() {
            var _admin = this;
            $(document).find('button.list-button').on('click', function(event){
                console.log('listener');
                // Charger le tableau
                _admin.loadTable($(this).data('url'), $(this).data('template'));
            });
        },
        "loadTable": function(_url, _template) {
            $.ajax({
                method: 'GET',
                url: _url,
                error: function(jqXHR, textStatus, errorThrown){
                    util.handleErrorResponse(jqXHR, textStatus, errorThrown);
                },
                success: function(data, textStatus, jqXHR){
                    var template = _.template(
                        $( "script." + _template ).html()
                    );
                    $('.table-information').html(template(data));
                }
            });
        }
    }
})();
adminUserEdit.init();