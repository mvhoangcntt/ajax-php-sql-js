<html>
<head>
<title>My Form</title>
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
</head>
<body>
<?php //print_r($password); ?>
<?php //echo form_open('news/index'); ?>
<form method="POST" enctype="multipart/form-data" id="validation">
<h5>Username</h5>
<div>
<input type="text" name="username" value="<?php echo set_value('username'); ?>" size="50" />
</div>

<h5>Password</h5>
<input type="text" name="password" value="<?php echo set_value('password'); ?>" size="50" />

<div><input class="submit" type="submit" value="Submit" /></div>
</form>

</body>
</html>

<script type="text/javascript">
$(".submit").click(function(){
	$(".err").remove();
	event.preventDefault();
	var form = $('#validation')[0];
	var data = new FormData(form);
	$.ajax({
		type: 		"POST",
		enctype: 	"multipart/form-data",
		url: 		"<?php echo site_url("/news/abc"); ?>",
		data: data,
		processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        success: function (data){
        	var jsonData = JSON.parse(data);
        	console.log(jsonData.username);
        	if (jsonData.type === 'errors') {
        		$("input[name='username']").parent().append("<div class='err'>"+ jsonData.value.username +"</div>");
        	}else{
        		// nọi dung cần sử lý thành công
        	}
        	
        },
        error: function (e){
        	console.log("error : ", e);
        }
	})
})
</script>