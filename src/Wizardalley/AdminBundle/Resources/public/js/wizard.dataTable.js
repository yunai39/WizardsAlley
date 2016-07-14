


var renderColumn = function ( data, type, row ) {
    var templateName = data['template'],
        template = _.template(
        $( "script." + templateName ).html()
    );
    return template(data['render']);
};
var option = $('#wizardDataTable table').data('datatable-option');
var tmp = [];
$.each(option['columns'], function(index,data) {
    tmp.push({"data": data['data'], "render": renderColumn});
});
option['columns'] = tmp;
$('#wizardDataTable table').DataTable(option);
