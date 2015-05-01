<div class="col-xs-10 col-xs-offset-1 contact">
	<h3 class="header-big">اضافه کردن تصویر</h3>
    <div class="col-xs-12">
    <h4 class="header-small">با افزودن تصاویر کسب و کار خود را رونق بخشید.</h4>
    <br>
      

<div class="container">
		<div class="form-container">
			<form enctype="multipart/form-data" name='imageform' role="form" id="imageform" method="post" action="<?php echo $prefix; ?>/include/ajax.php">
				<div class="form-group">
					<p>لطفا تصویر مورد نظر را انتخاب نمایید: </p>
					<input class='file' multiple type="file" class="form-control" name="images[]" id="images" placeholder="لطفا تصویر مورد نظر را انتخاب نمایید: ">
					<span class="help-block"></span>
				</div>
				<div id="loader" style="display: none;">
					منتظر بمانید...
				</div>
				<input type="submit" value="آپلود" name="image_upload" id="image_upload" class="btnImage"/>
			</form>
		</div>
		<div class="clearfix"></div>
		<div id="uploaded_images" class="uploaded-images">
			<div id="error_div">
			</div>
			<div id="success_div">
			</div>
		</div>
	</div>
<input type="hidden" id='base_path' value="<?php echo BASE_PATH; ?>">
</div>
</div>