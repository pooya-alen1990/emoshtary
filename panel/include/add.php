<?php

if(!isset($_SESSION['MM_ID'])){

	header("Location: $prefix/page/user/signup/");
}		 

$error = '';

$provinces = '';
$provinces_query = " SELECT * FROM `province`" ;
$provinces_result = mysqli_query($connection,$provinces_query);
while($provinces_row = mysqli_fetch_assoc($provinces_result)){
		$provinces .="<option value='$provinces_row[id]'>$provinces_row[name]</a>";
	}
$categories = '';
$categories_query = " SELECT * FROM `category`" ;
$categories_result = mysqli_query($connection,$categories_query);
while($categories_row = mysqli_fetch_assoc($categories_result)){
		$categories .="<option value='$categories_row[id]'>$categories_row[name]</a>";
	}
				
if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $cat_id = $_POST['cat_id'];
	$location = $_POST['address'];
    $slogan = $_POST['slogan'];
	$city_id = $_POST['city_id'];
    $province_id = $_POST['province_id'];
    $address = $_POST['address'];
	$phone = $_POST['phone'];
	$mobile = $_POST['mobile'];
	$email = $_POST['email'];
	$website = $_POST['website'];
	$keywords = $_POST['keywords'];
	$picture = $_FILES['product_image'];
    $register_date = time();	
	
    if($picture['error'] == "0"){
		
		$picture['name'] = time().'.jpg';
		$address = "images/advertise/$picture[name]";
		move_uploaded_file($picture['tmp_name'],$address);
		
        $advertise_query = "INSERT INTO `advertises`(`id`, `name`, `cat_id`, `sub_cat_id`, `slogan`, `city_id`, `province_id`, `address`, `phone`, `mobile`, `email`, `website`, `keywords`, `register_date`, `google_map`, `activate`, `user_id`,`image`) VALUES ('','$name','$cat_id','0','$slogan','$city_id','$province_id','$location','$phone','$mobile','$email','$website','$keywords','$register_date','','0','$_SESSION[MM_ID]','$picture[name]')";
		echo $advertise_query;
        $advertise_result = mysqli_query($connection , $advertise_query);
		var_dump($advertise_result);
        if($advertise_result){
            $error = '
            <div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>کاربر گرامی !</strong>ثبت اطلاعات واحد شغلی با موفقیت انجام شد .  
            </div>
            ';
        }
    }else{
        $error = '
        <div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>خطا!</strong>
        </div>
        ';
    }
}

?>
	<div class="col-md-10 col-md-offset-1 contact">

    <div class="col-sm-12">
		<h3 class="header-big">ثبت اطلاعات واحد شغلی</h3>
   		<div class="col-sm-12">
         <p><i class="fa fa-star"></i> لطفا اطلاعات خود را با زبان فارسی وارد نمایید.</p>
         <p><i class="fa fa-star"></i>در قسمت کلمات کلیدی، کلمات را با یک فاصله و یک خط تیره (-) از هم جدا نمایید.</p>
         <p><i class="fa fa-star"></i>حجم تصویر تجاری کمتر از 300 کیلوبایت باشد.</p>
         <br>
         <form class="form-horizontal" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label for="name" class="col-sm-3 control-label pull-right">نام واحد شغلی</label>
    <div class="col-sm-9">
      <input type="text" class="form-control pull-right" name="name" required>
    </div>
  </div>
  <div class="form-group">
    <label for="cat_id" class="col-sm-3 control-label pull-right">زمینه فعالیت</label>
    
    <div class="col-sm-9">
      <select type="text" class="form-control pull-right" name="cat_id" id="category">
      	<option value="-1">لطفا زمینه فعالیت را انتخاب نمایید</option>
     <?php echo  $categories ?>
      </select>
    </div>
  </div>
  
  <div class="form-group">
    <label for="city" class="col-sm-3 control-label pull-right">استان و شهر</label>
    <div class="col-sm-4">
      <select type="text" class="form-control pull-right" disabled name="city_id" id="city">
      </select>
    </div>
    <div class="col-sm-5">
      <select type="text" class="form-control pull-right" name="province_id" id="province">
      	<option value="-1">استان را انتخاب کنید</option>
        <?php echo $provinces; ?>
      </select>
    </div>
  </div>
  
   <div class="form-group">
    <label for="address" class="col-sm-3 control-label pull-right">آدرس</label>
    <div class="col-sm-9">
      <input type="text" class="form-control pull-right" name="address" required>
    </div>
  </div>
   <div class="form-group">
    <label for="email" class="col-sm-3 control-label pull-right">ایمیل</label>
    <div class="col-sm-9">
      <input type="email" class="form-control pull-right" name="email">
    </div>
  </div>
   <div class="form-group">
    <label for="phone" class="col-sm-3 control-label pull-right">تلفن</label>
    <div class="col-sm-5">
      <input type="text" class="form-control pull-right" name="phone" required>
    </div>
    <label for="mobile" class="col-sm-1 control-label">موبایل</label>
    <div class="col-sm-3">
      <input type="text" class="form-control pull-right" name="mobile" required>
    </div>
    
  </div>
  <div class="form-group">
    <label for="slogan" class="col-sm-3 control-label pull-right">شعار تبلیغاتی شما</label>
    <div class="col-sm-9">
      <input type="text" class="form-control pull-right" name="slogan" required>
    </div>
  </div>
  
  <div class="form-group">
    <label for="website" class="col-sm-3 control-label pull-right">آدرس وب سایت</label>
    <div class="col-sm-9">
      <input type="text" class="form-control pull-right" style="font-family:verdana;" placeholder="http://iranm118.ir" dir="ltr" name="website">
    </div>
  </div>
  
  <div class="form-group">
    <label for="keywords" class="col-sm-3 control-label pull-right">کلمات کلیدی</label>
    <div class="col-sm-9">
      <input type="text" class="form-control pull-right" name="keywords">
    </div>
  </div>
  
  <div class="form-group">
    <label for="image" class="col-sm-3 control-label pull-right">تصویر نشان تجاری</label>
    <div class="col-sm-9">
      <input type="file" class=" pull-right" name="product_image">
    </div>
  </div>

 	
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="submit" name="submit" class="btn-submit" value="ثبت نهایی اطلاعات">
    </div>
  </div>
</form>
		</div>
   	 	</div>
        <div class="clearfix"></div>
        <?php echo $error; ?>
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
