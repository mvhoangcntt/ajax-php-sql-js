<div class="container-fluid">
	<div class="row text-center"><h1>Danh sách sản phẩm</h1></div>
	<div class="row text-right">
		<div style="padding-bottom: 10px; padding-right: 15px">
			<input type="button" id="btn" class="btn bg-dark" name="" value="Thêm">
		</div>
	</div>
	<table id="example" class="display" style="width:100%">
	<thead>
	    <tr>
	        <th>ID</th>
	        <th nowrap>Tên sản phẩm</th>
	        <th nowrap>Nội dung</th>
	        <th nowrap>Danh mục</th>
	        <th nowrap>Hình ảnh</th>
	        <th nowrap>Size</th>
	        <th nowrap>nhà ản xuất</th>
	        <th nowrap>Gía (VND)</th>
	        <th nowrap>Ngày tạo</th>
	        <th nowrap>Lượt xem</th>
	        <th nowrap>Tổng SP</th>
	        <th nowrap>Hành động</th>
	    </tr>
	</thead>

	</table>
</div>

<div class="screen_hide"></div>
<div class="form_hide">
	<div>
		<div class="box">
			<h1 class="text-center nameFrom">Form insert</h1>
			<form id="validation" enctype="multipart/form-data" accept-charset="utf-8" autocomplete="off">
				<?php //echo form_open_multipart('products/create'); ?>
				<div class="form-group">
					<div>
						<label for="name">Tên sản phẩm :</label>
						<input type="text" name="name" id="name" class="form-control" value="<?php echo set_value('name') ?>" placeholder="Áo thun">
					</div>
					<div>
						<label for="content">Nội dung :</label>
						<textarea class="form-control" name="content" rows="5" id="content" placeholder="Giới thiệu sản phẩm"></textarea>
					</div>
					<div>
						<label for="catalog">Danh mục :</label>
						<select id="catalog" name="catalog" class="form-control">
							<option value="1">Áo khoác</option>
							<option value="2">Quần tây</option>
						</select>
					</div>
					<div>
						<label for="image_link">Hình ảnh :</label>
						<input type="file" name="image_link" id="image_link" class="form-control">
					</div>
					<div>
						<label>Kích cỡ :</label>
						<div>
							<div class="totong">
								<div class="rem">
									<div class="form_size">
										<div class="imput_right">
											<input type="text" id="quantity" class="form-control" name="quantity[0]" placeholder="Số lượng">
										</div>
										<div class="input_left">
											<input type="text" id="textsize" class="form-control" name="textsize[0]" placeholder="size">
										</div>
										<div>
											<i class="fa fa-times"></i>
										</div>
									</div>
								</div>
							</div>
							
						</div>
						<div class="add_size" id="0">+ Thêm ...</div>
					</div>
					<div>
						<label for="maker_id">Nhà sản xuất :</label>
						<select id="maker_id" name="maker_id" class="form-control">
							<option value="1">Hà Nội</option>
							<option value="2">Thái Nguyên</option>
						</select>
					</div>
					<div>
						<label for="price">Giá (VND):</label>
						<input type="text" class="form-control" id="price" name="price" placeholder="10000">
					</div>
					<div>
						<label for="total">Số lượng sản phẩm :</label>
						<input type="text" class="form-control" id="total" name="total" placeholder="10">
					</div>
					<div style="padding-top: 10px">
						<input type="submit" name="submit" value="Thêm" class="btn form-control btn-primary submit">
						<div>
							<?php echo form_open('form'); ?>
						</div>
					</div>
				</div>
			</form>
		</div>

	</div>
</div>