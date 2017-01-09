/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




var admin = (function(){
    return {
        "init": function() {
            var _admin = this;
            $(document).find('.btn-load-more-entity').on('click', function(event){
                var lastId = $(this).data('last-id'),
                    entityType = $(this).data('entity-type');
                _admin.loadMoreEntity(entityType, this, lastId);
            });

        },
        "loadMoreEntity": function(entity_type, element, last_id) {
            var url = Routing.generate('load_more_entity'+entity_type,{lastId: last_id});
            util.ajaxHandle(
                    'GET',
                    null,
                    url,
                    function(data) {
                        var lastId;
                        $.each(data['data']['message'], function(item){
                            lastId = item.id;
                            var template = _.template($('script.addEntity'));
                            $('.records_list tbody').append(template(item));
                        });
                        $(element).data('last-id', lastId);
                    }
            );
        }
    }
})();
admin.init();