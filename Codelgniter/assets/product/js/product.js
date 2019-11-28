// url
var json_update = BASE_URL+"products/json_update/";
var ajax_delete = BASE_URL+"products/delete_pr/";
var ajax_create = BASE_URL+"products/create/";
var ajax_update = BASE_URL+"products/update/";

// add form update thông tin sản phẩm
$(document).on("click",".btnupdate",function(){
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
$(".add_form_size").click(function(){
	size++;
	$(".totong").append("<div class='rem'><div class='form_size'><div class='imput_right'><input type='text' id='quantity' class='form-control' name='quantity["+size+"]' placeholder='Số lượng'></div><div class='input_left'><input type='text' id='textsize' class='form-control' name='textsize["+size+"]' placeholder='size'></div><i class='fa fa-times'></i></div></div>");
});
// xóa kích cỡ trong form
$(document).on("click",".fa",function(){
    $( this ).parents('.rem').remove();
})

// remove form
$(".screen_hide").click(function(){
    $( ".form_hide" ).removeClass( "form_show" );
	$( ".screen_hide" ).removeClass( "screen_show" );
	$( "#validation").trigger("reset");
	$(".rem").remove();
	$(".totong").append("<div class='rem'><div class='form_size'><div class='imput_right'><input type='text' id='quantity' class='form-control' name='quantity[0]' placeholder='Số lượng'></div><div class='input_left'><input type='text' id='textsize' class='form-control' name='textsize[0]' placeholder='size'></div><i class='fa fa-times'></i></div></div>");
})

// xóa sản phẩm
$(document).on("click",".btndelete",function(){
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
	        	location.reload();
	        }
		}
	})
})

// mở form insert 
$("#btninsert").click(function(){
	// add form
    $( ".form_hide" ).addClass( "form_show" );
	$( ".screen_hide" ).addClass( "screen_show" );

	$(".nameFrom").text("Form insert");
	$("input[name='submit']").val("Insert");
})
// kiểm tra dữ liệu hợ lệ
$(".btnsubmit").click(function(event){
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
		        		$( ".formthem" ).removeClass( "formthem2" );
						$( ".maunen" ).removeClass( "maunen2" );
						location.reload();
						alert(jsonData.message);
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
		        		$( ".formthem" ).removeClass( "formthem2" );
						$( ".maunen" ).removeClass( "maunen2" );
						location.reload();
						alert(jsonData.message);
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
