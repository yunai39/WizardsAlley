/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

optionsToastr = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-top-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
};



var util = (function(){
    return {
        "init": function() {

        },
        "likePage": function(page_id) {
            util.ajaxHandle('POST', "page_id="+page_id+"", Routing.generate('page_like'));
        },
        "loadMorePublication": function(_id) {
            util.loadMore(Routing.generate('page_publication_get', {'id': _id}), 'GET', null);
        },
        "loadMore": function(_url,_method,_handler){
            $result = util.ajaxHandle(_method,null, _url,_handler);
            return $result;
        },
        "ajaxHandle": function(_method, _data, _url, _success_function) {
            $.ajax({
                method: _method,
                data: _data,
                url: _url,
                error: function(jqXHR, textStatus, errorThrown){
                    util.handleErrorResponse(jqXHR, textStatus, errorThrown);
                },
                success: function(data, textStatus, jqXHR){
                    if (typeof data['data'] != 'undefined' && data['data']) {
                        if (typeof data['data']['message'] != 'undefined') {
                            util.handleSuccesResponse(data, textStatus, jqXHR);
                        }
                    }
                    window[_success_function](data);
                }
            });
        },
        "handleSuccesResponse": function(data, textStatus, jqXHR) {
                toastr.option = optionsToastr;
                toastr.success(data['data']['message']);
        },
        "handleErrorResponse": function(jqXHR, textStatus, errorThrown) {
            data = JSON.parse(jqXHR.responseText);
            if (typeof data['data'] != 'undefined' && data['data']) {
                if (typeof data['data']['message'] != 'undefined') {
                    toastr.option = optionsToastr;
                    toastr.error(data['data']['message']);
                }
            }
        },
    }
})();