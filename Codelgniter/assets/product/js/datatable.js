// url
var listData    = BASE_URL+"products/jsonDatatable";
var json_update = BASE_URL+"products/json_update/";
var ajax_delete = BASE_URL+"products/delete_pr/";
var ajax_create = BASE_URL+"products/create/";
var ajax_update = BASE_URL+"products/update/";
var image_link = '';
$(document).ready(function() {
	var table = $('#example').DataTable( {
		"processing": true,
		"serverSide": true,
        "ajax": {
        	"url": listData,
        	"data": function(d){
				d.catalog  = $("select[name='filter_catalog']").val();
				d.maker_id = $("select[name='filter_maker_id']").val();
				d.size     = $("select[name='filter_size']").val();
	        }
        },
        
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
            		image_link = data;
			    	return '<img style="width: 50px" src="'+BASE_URL+'image/'+data+'"/>';
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
			      return '<input class="update  btn" type="button" name="" id="'+data+'" value="Sửa">/<input class="delete btn" type="button" image="'+image_link+'" name="" id="'+data+'" value="Xóa">';
			    } },
        ],
        "columnDefs": [
        	{ targets: [5,11], orderable: false},
        ]
    })

    $("select[name='filter_catalog']").change(function(){
    	table.ajax.reload();
    })
    $("select[name='filter_maker_id']").change(function(){
    	table.ajax.reload();
    })
    $("select[name='filter_size']").change(function(){
    	table.ajax.reload();
    })

    // ----------- sử lý bắt sự kiện thêm sửa xóa ---------
	// add form update thông tin sản phẩm
	$(document).on("click",".update",function(){
		$(".rem").remove();
		$( ".form_hide" ).addClass( "form_show" );
		$( ".screen_hide" ).addClass( "screen_show" );
		var product_id = $(this).attr("id");
		$("input[name='submit']").attr("id",product_id);
		$.ajax({
	        type: "GET",
	        url: json_update + product_id,   
	        success: function(response){ 
	        	var jsonData = JSON.parse(response);
	        	delete jsonData.image_link;// input file k thể gán giá trị
	        	for(var key in jsonData){
	        		$("input[name='"+key+"']").val(jsonData[key]);
					$("textarea[name='"+key+"']").val(jsonData[key]);
					$("select[name='"+key+"']").val(jsonData[key],'selected');
	        	}
	        	// bóc mảng size ra lấy thông tin form size
	        	for(var i in jsonData.size){
	        		$(".totong").append("<div class='rem'><div class='form_size'><div class='imput_right'><input type='text' id='quantity' class='form-control' value='"+jsonData.size[i]['quantity']+"' name='quantity["+i+"]' placeholder='Số lượng'></div><div class='input_left'><input title='0' type='text' id='textsize' class='form-control' value='"+jsonData.size[i]['text_size']+"' name='textsize["+i+"]' placeholder='size'></div><i class='fa fa-times'></i></div></div>");
	        	}
	        	// lấy kick thước mảng
	        	size = jsonData.size.length -1;
				$(".nameFrom").text("Form update");
				$("input[name='submit']").val("Update");
	        }
		})
	})
	// add kích cỡ sản phẩm trong form
	var size = 0;
	$(".add_size").click(function(){
		size++;
		$(".totong").append("<div class='rem'><div class='form_size'><div class='imput_right'><input type='text' id='quantity' class='form-control' name='quantity["+size+"]' placeholder='Số lượng'></div><div class='input_left'><input type='text' id='textsize' class='form-control' name='textsize["+size+"]' placeholder='size'></div><i class='fa fa-times'></i></div></div>");
	});
	// xóa kích cỡ trong form
	$(document).on("click",".fa",function(){
	    $( this ).parents('.rem').remove();
	})
	const FUNCT = {
		rm : function(){
			$( "#validation").trigger("reset");
			$(".rem").remove();
			$(".totong").append("<div class='rem'><div class='form_size'><div class='imput_right'><input type='text' id='quantity' class='form-control' name='quantity[0]' placeholder='Số lượng'></div><div class='input_left'><input type='text' id='textsize' class='form-control' name='textsize[0]' placeholder='size'></div><i class='fa fa-times'></i></div></div>");
		}
	}
	// remove form
	$(".screen_hide").click(function(){
	    $( ".form_hide" ).removeClass( "form_show" );
		$( ".screen_hide" ).removeClass( "screen_show" );
		$( "#validation").trigger("reset");
		$(".rem").remove();
		$(".totong").append("<div class='rem'><div class='form_size'><div class='imput_right'><input type='text' id='quantity' class='form-control' name='quantity[0]' placeholder='Số lượng'></div><div class='input_left'><input type='text' id='textsize' class='form-control' name='textsize[0]' placeholder='size'></div><i class='fa fa-times'></i></div></div>");
	})

	// xóa sản phẩm
	$(document).on("click",".delete",function(){
		var product_id = $(this).attr("id");
		var image_link = $(this).attr("image");
		$.ajax({
			type : "GET",
			url : ajax_delete + product_id +"/"+ image_link,
			success: function(response){
				var jsonData = JSON.parse(response);
		        if (jsonData.type === 'errors') {
		        	alert(jsonData.message);
		        }else{
		        	alert(jsonData.message);
		        	table.ajax.reload();
		        }
			}
		})
	})

	// mở form insert 
	$("#btn").click(function(){
		// add form
	    $( ".form_hide" ).addClass( "form_show" );
		$( ".screen_hide" ).addClass( "screen_show" );

		$(".nameFrom").text("Form insert");
		$("input[name='submit']").val("Insert");
	})
	// kiểm tra dữ liệu hợ lệ
	$(".submit").click(function(event){
		$(".err").remove();
		var name = $("input[name='name']").val();
		var content = $("textarea[name='content']").val();
		var catalog = $("select[name='catalog']").val();
		var image_link = $("input[name='image_link']").val();
		var maker_id = $("input[name='maker_id']").val();
		var price = $("input[name='price']").val();
		var total = $("input[name='total']").val();

		var errArray = [];
		if (name === '') {
			errArray.push({name : 'Không được để trống !'});
		}else{
			if (name.length < 5) {
				errArray.push({name : 'Độ dài lớn hơn 5 ký tự !'});
			}
		}
		
		if (content === '') {
			errArray.push({content : 'Không được để trống !'});
		}else{
			if (content.length < 5) {
				errArray.push({content : 'Độ dài lớn hơn 5 ký tự !'});
			}
		}
		if ($("input[name='submit']").val() === "Insert") {
			if (image_link === '') {
				errArray.push({image_link : 'Mời chọn ảnh !'});
			}
		}			
		if (price === '') {
			errArray.push({price : 'Không được để trống !'});
		}
		if (total === '') {
			errArray.push({total : 'Không được để trống !'});
		}
								
		for(var i = 0; i< errArray.length; i++){
			for(var key in errArray[i]){
			    $("input[name='"+key+"']").parent().append("<div class='err'>"+ errArray[i][key] +"</div>");
			    if (key === "content") {
			    	$("textarea[name='content']").parent().append("<div class='err'>"+ errArray[i][key] +"</div>");
			    }
			}
		}
		
		if (errArray.length == 0) {
			event.preventDefault();
			if ($("input[name='submit']").val() === "Insert") {
				var form = $('#validation')[0];
				var data = new FormData(form);
				$.ajax({
					type: 		"POST",
					enctype: 	"multipart/form-data",
					url: 		ajax_create,
					data: data,
					processData: false,
			        contentType: false,
			        cache: false,
			        timeout: 800000,
			        success: function (response){
			        	var jsonData = JSON.parse(response);
			        	if (jsonData.type === 'errors') {
			        		alert(jsonData.message);
			        		for(var key in jsonData.value){
			        			$("input[name='"+key+"']").parent().append("<div class='err'>"+ jsonData.value[key] +"</div>");
							    if (key === "content") {
							    	$("textarea[name='content']").parent().append("<div class='err'>"+ jsonData.value.content +"</div>");
							    }
							}
			        	}
			        	if(jsonData.type === 'success'){
			        		$( ".form_hide" ).removeClass( "form_show" );
							$( ".screen_hide" ).removeClass( "screen_show" );
							FUNCT.rm();
							alert(jsonData.message);
							table.ajax.reload();
			        	}
			        },
			        error: function (e){
			        	console.log("error : ", e);
			        }
				})
			}else{
				// update sản phẩm
				var form = $('#validation')[0];
				var data = new FormData(form);
				var product_id = $("input[name='submit']").attr("id");
				console.log(product_id);
				$.ajax({
					type: 		"POST",
					enctype: 	"multipart/form-data",
					url: 		ajax_update + product_id,
					data: data,
					processData: false,
			        contentType: false,
			        cache: false,
			        timeout: 800000,
			        success: function (data){
			        	var jsonData = JSON.parse(data);
			        	if (jsonData.type === 'errors') {
			        		alert(jsonData.message);
			        		for(var key in jsonData.value){
				        		$("input[name='"+key+"']").parent().append("<div class='err'>"+ jsonData.value[key] +"</div>");
							    if (key === "content") {
							    	$("textarea[name='content']").parent().append("<div class='err'>"+ jsonData.value.content +"</div>");
							    }
							}
			        	}
			        	if(jsonData.type === 'success'){
			        		$( ".form_hide" ).removeClass( "form_show" );
							$( ".screen_hide" ).removeClass( "screen_show" );
							FUNCT.rm();
							alert(jsonData.message);
							table.ajax.reload();
			        	}
			        },
			        error: function (e){
			        	console.log("error : ", e);
			        }
				})
			}
				
		}else{
			event.preventDefault();
		}
	});

});

