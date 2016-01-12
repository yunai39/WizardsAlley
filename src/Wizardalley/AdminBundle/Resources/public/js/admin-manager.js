/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




var admin = (function(){
    return {
        "init": function() {

        },
        "loadMoreEntity": function(entity_type, last_id) {
            var url = Routing.generate('loadMoreEntity'+entity_type,{'lastId': lastId});
            util.ajaxHandle(
                    'GET',
                    null,
                    url,
                    function(data) {
                        $.each(data['data']['message'], function(item){
                            var template = _.template($('script.addEntity'));
                            template(item);
                        });
                    }
            );
        }
    }
})();