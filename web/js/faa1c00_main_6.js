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
        /**
         *
         */
        "init": function() {

        },
        /**
         *
         * @param page_id
         */
        "likePage": function(page_id) {
            this.ajaxHandle('POST', "page_id="+page_id+"", Routing.generate('page_like'));
        },
        /**
         *
         * @param _id
         */
        "loadMorePublication": function(_id) {
            this.loadMore(Routing.generate('page_publication_get', {'id': _id}), 'GET', null);
        },

        "loadMoreSearch": function(researchType, _field, _page) {
            var _handler = function(data) {
                $('#wizard_search_result').prepend(data['extra']['html']);
            };
            this.loadMore(Routing.generate('wizardalley_search_'+researchType, {'field':_field , 'page': _page}), 'GET', _handler);

        },
        /**
         *
         * @param _url
         * @param _method
         * @param _handler
         * @returns {*}
         */
        "loadMore": function(_url,_method,_handler){
            $result = util.ajaxHandle(_method,null, _url,_handler);
            return $result;
        },
        /**
         *
         * @param _page
         */
        "loadMorePublicationHome": function(_page){
            var _handler = function(data) {
                console.log(data);
                $('.publication-block-wrapper').append(data['extra']['html']);
            };
            util.ajaxHandle('GET',null,Routing.generate('wizard_get_publication_view', {'page': _page}), _handler);
        },
        /**
         *
         * @param _method
         * @param _data
         * @param _url
         * @param _success_function
         */
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
                    _success_function(data);
                    //window[_success_function](data);
                }
            });
        },
        /**
         *
         * @param _page
         * @returns {*}
         */
        "loadMoreInfo": function(_page){

            var handler = function(_data){
                console.log(_data);
                $('.information-block-wrapper').append(_data['data']['contenu']);
            };
            $result = util.ajaxHandle(
                "GET",
                null,
                Routing.generate('wizardalley_information_page', {'page': _page}),
                handler
            );
            return $result;

        },
        /**
         *
         * @param data
         * @param textStatus
         * @param jqXHR
         */
        "handleSuccesResponse": function(data, textStatus, jqXHR) {
                toastr.option = optionsToastr;
                toastr.success(data['data']['message']);
        },
        /**
         *
         * @param jqXHR
         * @param textStatus
         * @param errorThrown
         */
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