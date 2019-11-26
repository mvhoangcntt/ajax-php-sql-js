// url
var listData = BASE_URL+"products/jsonDatatable";

$(document).ready(function() {
	$('#example').DataTable( {
		"processing": true,
		"serverSide": true,
        "ajax": listData,
        "language":{
        	"search": "Tìm kiếm :",
        	"info":      "Đang hiển thị từ _START_ đến _END_ của _TOTAL_ mục",
        	"infoEmpty": "Đang hiển thị từ 0 đến 0 của 0 mục",
        	"lengthMenu":  "Hiển thị _MENU_ mục",
        	"paginate": {
		        "first":      "Đầu tiên",
		        "last":       "Cuối cùng",
		        "next":       "Tiếp theo",
		        "previous":   "Trước "
		    },
        },
        "dom": '<lf><t><ip>',
        "lengthMenu": [ 5, 10, 20, 50, 100 ], 
        "columns": [
            { "data": "product_id" },
            { "data": "name" },
            { "data": "content" },
            { "data": "catalog" },
            { "data": "image_link", "render": function ( data, type, row, meta ) {
			      return '<img style="width: 50px" src="../../image/'+data+'"/>';
			    } },
			{ "data": "size", "render":function ( data, type, row, meta ){
					var chuoi = '';
					for(const item of data){
						chuoi += item.text_size + ' ';
					}
					return chuoi;
				}
			},
            { "data": "maker_id"},
            { "data": "price"},
            { "data": "created"},
            { "data": "view"},
            { "data": "total"},
            { "data": "product_id", "render": function ( data, type, row, meta ) {
			      return '<input class="update  btn" type="button" name="" id="'+data+'" value="Sửa">/<input class="delete btn" type="button" name="" id="'+data+'" value="Xóa">';
			    } },
        ]
    })
});


