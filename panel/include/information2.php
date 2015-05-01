<?php 
	$provinces = '';
$provinces_query = " SELECT * FROM `province`" ;
$provinces_result = mysqli_query($connection,$provinces_query);
while($provinces_row = mysqli_fetch_assoc($provinces_result)){
		$provinces .="<option value='$provinces_row[id]'>$provinces_row[name]</option>";
	}
	$categories = '';
$categories_query = " SELECT * FROM `category`" ;
$categories_result = mysqli_query($connection,$categories_query);
while($categories_row = mysqli_fetch_assoc($categories_result)){
		$categories .="<option value='$categories_row[id]'>$categories_row[name]</option>";
	}
?>
<div class="col-xs-10 col-xs-offset-1 contact">
	<h3 class="header-big">امکانات ویژه</h3>
    <div class="col-xs-12">
    <h4 class="header-small">دانلود اطلاعات مشاغل کل کشور</h4>
    <p>با استفاده از فرم زیر اطلاعات مورد نظر خود را دانلود نمایید.</p>
    <br>
	<form id="contact-form" role="form" method="post">
   		 <div class="form-group">
    <label for="city" class="col-sm-3 control-label pull-right">استان و شهر</label>
    <div class="col-sm-4">
      <select type="text" class="form-control pull-right" disabled name="city_id" id="city">
      </select>
    </div>
    <div class="col-sm-5">
      <select type="text" class="form-control pull-right" name="province_id" id="province">
      	<option value="all">همه</option>
        <?php echo $provinces; ?>
      </select>
    </div>
  </div>
  	<br><br>
  	<div class="form-group clearfix">
    <label for="cat_id" class="col-sm-3 control-label pull-right">زمینه فعالیت</label>
    
    <div class="col-sm-9">
      <select type="text" class="form-control pull-right" name="cat_id" id="category">
      	<option value="-1">لطفا زمینه فعالیت را انتخاب نمایید</option>
     <?php echo  $categories ?>
      </select>
    </div>
  </div>
  
  	<div class="form-group">
    <label for="cat_id" class="col-sm-3 control-label pull-right">اطلاعات مورد نیاز</label>
    
    <div class="col-sm-9">
      <input type="checkbox" id="checkbox10" class="css-checkbox"/>
      <label for="checkbox10" name="checkbox10_lbl" class="css-label"> نام واحد شغلی </label>
      <input type="checkbox" id="checkbox11" class="css-checkbox"/>
      <label for="checkbox11" name="checkbox11_lbl" class="css-label">تلفن</label>
      <input type="checkbox" id="checkbox12" class="css-checkbox"/>
      <label for="checkbox12" name="checkbox12_lbl" class="css-label">تلفن</label>
      <input type="checkbox" id="checkbox13" class="css-checkbox"/>
      <label for="checkbox13" name="checkbox13_lbl" class="css-label">تلفن</label>
      <input type="checkbox" id="checkbox14" class="css-checkbox"/>
      <label for="checkbox14" name="checkbox14_lbl" class="css-label">تلفن</label>
      <input type="checkbox" id="checkbox15" class="css-checkbox"/>
      <label for="checkbox15" name="checkbox15_lbl" class="css-label">تلفن</label>

    </div>
  </div>
     
        <div class="clearfix"></div>
        <input name="submit" type="submit" value="ارسال پیام" class="btn btn-submit">
    </form>
</div>
</div>
<script>
	$("#province").change(function(){
		val = $("#province").val();
    $.post("<?php echo $prefix; ?>/include/province_ajax.php",{province : val},
    function(data, status){
		$('#city').removeAttr('disabled');
		$('#city').html(data);
    });
});	

$("#category").change(function(){
		val = $("#category").val();
    $.post("include/category_ajax.php",{category : val},
    function(data, status){
		$('#sub_category').removeAttr('disabled');
		$('#sub_category').html(data);
    });
});	
</script>
