
<div class="container-fluid">
	<div class="row add">
		<div class="col-lg-2">
			
		</div>
		<div class="col-lg-8 col-md-12 text-center">
			<h2>Danh sách sản phẩm</h2>
		</div>
		<div class="col-lg-2 text-center button">
			<input type="button" id="btninsert" class="btn bg-dark" name="" value="Thêm">
		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th nowrap>ID</th>
					<th nowrap>Tên sản phẩm</th>
					<th nowrap>Nội dung</th>
					<th nowrap>Danh mục</th>
					<th nowrap>Hình ảnh</th>
					<th nowrap>Kích cỡ</th>
					<th nowrap>Nhà sản xuất</th>
					<th nowrap>Giá (vnđ)</th>
					<th nowrap>Ngày tạo</th>
					<th nowrap>Lượt xem</th>
					<th nowrap>Tổng</th>
					<th nowrap>Hành động</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($products as $news_item): ?>
					<tr>
						<td><?php echo $news_item['product_id']; ?></td>
						<td><?php echo $news_item['name'] ?></td>
						<td><?php echo $news_item['content']; ?></td>
						<td><?php echo $news_item['catalog']; ?></td>
						<td><img style="width: 50px" src="<?php echo base_url('image/'.$news_item['image_link']); ?>"></td>
						<td>
							<?php 
							for ($i=0; $i < count($news_item['size']) ; $i++) { 
								echo $news_item['size'][$i]->text_size." ";
							}
							?>
						</td>
						<td><?php echo $news_item['maker_id']; ?></td>
						<td><?php echo $news_item['price']; ?></td>
						<td><?php echo $news_item['created']; ?></td>
						<td><?php echo $news_item['view']; ?></td>
						<td><?php echo $news_item['total']; ?></td>
						<td>
							<input class="btnupdate  btn" type="button" name="" id="<?php echo $news_item['product_id']; ?>" value="Sửa"> /
							<input class="btndelete btn" type="button" name="" image="<?php echo $news_item['image_link']; ?>" id="<?php echo $news_item['product_id']; ?>" value="Xóa">
						</td>
					</tr>
				<?php endforeach; ?>
				
			</tbody>
		</table>
	</div>
</div>
<div class="container" style="text-align: right">
	<div class="page"><?php echo $page; ?></div>
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
						<input type="file" name="image_link" id="image_link" >
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
						<div class="add_form_size" id="0">+ Thêm ...</div>
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
						<input type="submit" name="submit" value="Thêm" class="btn form-control btn-primary btnsubmit">
					</div>
				</div>
			</form>
		</div>

	</div>
</div>

