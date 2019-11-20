// ngày tháng của boostrap
	$(function () {  
	$("#datepicker").datepicker({         
	autoclose: true,         
	todayHighlight: true 
	}).datepicker('update', new Date());
	});

//add thông tin update sản phẩm
$(document).on("click",".update",function(){
	$(".form_size").remove();
	$( ".formthem" ).addClass( "formthem2" );
	$( ".maunen" ).addClass( "maunen2" );
	var product_id = $(this).attr("id");
	$("input[name='submit']").attr("id",product_id);
	$.ajax({
        type: "GET",
        url: 'http://localhost/codelgniter/index.php/products/json_update/'+ product_id +'',   
        success: function(response){ 
        	var jsonData = JSON.parse(response);
        	console.log(jsonData);
        	delete jsonData.image_link;// input file k thể gán giá trị
        	for(var key in jsonData){
        		$("input[name='"+key+"']").val(jsonData[key]);
				$("textarea[name='"+key+"']").val(jsonData[key]);
				$("select[name='"+key+"']").val(jsonData[key],'selected');
        	}
        	$(".rem").remove();
			for(var i = 0; i < jsonData.quantity.length; i++){
				if (jsonData.quantity[i] && jsonData.text_size[i]) {
					$(".totong").append("<div class='rem'><div class='form_size'><div class='imput_right'><input alt='0' type='text' id='quantity' class='form-control' value='"+jsonData.quantity[i]+"' name='quantity[quantity_"+i+"]' placeholder='Số lượng'></div><div class='input_left'><input title='0' type='text' id='textsize' class='form-control' value='"+jsonData.text_size[i]+"' name='textsize[textsize_"+i+"]' placeholder='size'></div><i class='fa fa-times'></i></div><div class='chualoi quantity_"+i+" textsize_"+i+"'></div></div>");
				}
				size = i;
			}
			$(".nameFrom").text("Form update");
			$("input[name='submit']").val("Update");
        }
	})
})
// add kích cỡ sản phẩm trong form
var size = 0;
$(".add_size").click(function(){
	size++;
	$(".totong").append("<div class='rem'><div class='form_size'><div class='imput_right'><input alt='0' type='text' id='quantity' class='form-control' name='quantity[quantity_"+size+"]' placeholder='Số lượng'></div><div class='input_left'><input title='0' type='text' id='textsize' class='form-control' name='textsize[textsize_"+size+"]' placeholder='size'></div><i class='fa fa-times'></i></div><div class='chualoi quantity_"+size+" textsize_"+size+"'></div></div>");
	
	console.log(size);
});
// xóa kích cỡ trong form
$(document).on("click",".fa",function(){
    $( this ).parents('.rem').remove();// close
})
// xóa sản phẩm
$(document).on("click",".delete",function(){
	var product_id = $(this).attr("id");
	console.log(product_id);
	$.ajax({
		type : "GET",
		url : 'http://localhost/Codelgniter/index.php/products/delete_pr/'+ product_id +'',
		success: function(response){
			var jsonData = JSON.parse(response);
	        if (jsonData.type === 'errors') {
	        	alert("Đã có lỗi !");
	        }else{
	        	alert("Xóa thành công !");
	        	location.reload();
	        }
		}
	})
})

// hiển thị form
$(".maunen").click(function(){
    $( ".formthem" ).removeClass( "formthem2" );
	$( ".maunen" ).removeClass( "maunen2" );
	location.reload();
})

$("#btn").click(function(){
	// gán giá trị mặc định trong form
    $( ".formthem" ).addClass( "formthem2" );
	$( ".maunen" ).addClass( "maunen2" );

	$("input[name='name']").val("");
	$("textarea[name='content']").val("");
	
	$("select[name='catalog']").val("1");
	$("select[name='catalog']").selected = true;
	
	$("select[name='maker_id']").val("1");
	$("select[name='maker_id']").selected = true;
	$("input[name='price']").val("");
	$("input[name='total']").val("");

	$(".nameFrom").text("Form insert");
	$("input[name='submit']").val("Insert");
})
// thêm sản phẩm
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
	
	if (content === "") {
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
			console.log(key + " : " + errArray[i][key]);
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
			// data.append("quantity",quantity);
			// data.append("text_size",text_size);
			$.ajax({
				type: 		"POST",
				enctype: 	"multipart/form-data",
				url: 		"http://localhost/codelgniter/index.php/products/create/",
				data: data,
				processData: false,
		        contentType: false,
		        cache: false,
		        timeout: 800000,
		        success: function (response){
		        	var jsonData = JSON.parse(response);
		        		console.log(jsonData);
		        		console.log(jsonData.content);
		        		var chuoi = 'quantity';
         				var chuoi2 = 'textsize';
		        	if (jsonData.type === 'errors') {
		        		for(var key in jsonData.value){
		        			console.log(key +" : "+ jsonData.value[key]);

		        			if (key.slice(0,8) === chuoi || key.slice(0,8) === chuoi2) {
		        				$("."+key+"").append("<div class='err'>"+ jsonData.value[key] +"</div>");
		        			}else{
		        				$("input[name='"+key+"']").parent().append("<div class='err'>"+ jsonData.value[key] +"</div>");
		        			}
						    if (key === "content") {
						    	$("textarea[name='content']").parent().append("<div class='err'>"+ jsonData.value.content +"</div>");
						    }
						}
		        	}
		        	if(jsonData.type === 'success'){
		        		$( ".formthem" ).removeClass( "formthem2" );
						$( ".maunen" ).removeClass( "maunen2" );
						location.reload();
						alert("Thêm thành công !");
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
				url: 		"http://localhost/codelgniter/index.php/products/update/"+product_id+"",
				data: data,
				processData: false,
		        contentType: false,
		        cache: false,
		        timeout: 800000,
		        success: function (data){
		        	var jsonData = JSON.parse(data);
		        	if (jsonData.type === 'errors') {
		        		var chuoi = 'quantity';
         				var chuoi2 = 'textsize';
		        		for(var key in jsonData.value){
		        			if (key.slice(0,8) === chuoi || key.slice(0,8) === chuoi2) {
		        				$("."+key+"").append("<div class='err'>"+ jsonData.value[key] +"</div>");
		        			}else{
		        				$("input[name='"+key+"']").parent().append("<div class='err'>"+ jsonData.value[key] +"</div>");
		        			}
						    if (key === "content") {
						    	$("textarea[name='content']").parent().append("<div class='err'>"+ jsonData.value.content +"</div>");
						    }
						}
		        	}
		        	if(jsonData.type === 'success'){
		        		$( ".formthem" ).removeClass( "formthem2" );
						$( ".maunen" ).removeClass( "maunen2" );
						location.reload();
						alert("Thay đổi thành công !");
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
