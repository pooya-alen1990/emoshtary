<?php
if(!isset($_SESSION['MM_ID'])){
	header('Location: http://emoshtary.ir/?page=signup');
}
 $id = $_SESSION['MM_ID'];
 
 
 
 $user_info_query = "SELECT users.id, users.first_name, users.last_name, users.melli_code, users.email, users.mobile, users.city_id, users.province_id, users.address, city.id , city.name, city.province, province.id, province.name AS province_name
  FROM `users`
	INNER JOIN `city` ON users.city_id = city.id
	INNER JOIN `province` ON city.province = province.id
  WHERE users.id = '$id' ; ";

 $user_info_result = mysqli_query($connection,$user_info_query);
 $user_info_row =  mysqli_fetch_assoc($user_info_result);
 

 #######################################################
 
 $user_adv_query = "SELECT advertises.id, advertises.name, advertises.cat_id, advertises.sub_cat_id, advertises.slogan, advertises.city_id, advertises.province_id, advertises.address, advertises.phone, advertises.mobile, advertises.email, advertises.website, advertises.keywords, advertises.google_map, advertises.image,
 city.id, city.name AS city_name, city.province,category.name AS cat_name,
 province.id, province.name AS province_name
  FROM `advertises`

	INNER JOIN `city` ON advertises.city_id = city.id
	INNER JOIN `province` ON advertises.province_id = province.id
	INNER JOIN `category` ON category.id = advertises.cat_id
  WHERE advertises.user_id = $id ; ";
 $user_adv_result = mysqli_query($connection,$user_adv_query);
 $user_adv_row =  mysqli_fetch_assoc($user_adv_result);

?>

<div class="col-xs-12">
	<h3 class="header-big">پنل مدیریت بانک مشاغل ایران</h3>
    
    

<div class="col-xs-8 panel-detail">
    <h4 class="header-small"><i class="fa fa-gear"></i> اطلاعات شغلی : </h4>
  
    <?php 
		if(!isset($user_adv_row)){
			echo "
			
				<p>شما هنوز اطلاعاتی وارد نکرده اید. در صورت نیاز <a href='/panel/?page=add'>کلیک</a> کنید.</p>
			";
			}else{
			
	?>
	<p class="col-sm-6 pull-right"><span class="detail-title">نام واحد شغلی :  </span><?php echo "$user_adv_row[name]"; ?> </p>
    <p class="col-sm-6 pull-right"><span class="detail-title"> زمینه فعالیت : </span><?php echo "$user_adv_row[cat_name]"; ?> </p>
    <p class="col-sm-6 pull-right"><span class="detail-title"> شعار : </span><?php echo "$user_adv_row[slogan]"; ?> </p>
    <p class="col-sm-6 pull-right"><span class="detail-title"> استان : </span><?php echo "$user_adv_row[province_name]"; ?> </p>
    <p class="col-sm-6 pull-right"><span class="detail-title"> شهرستان :</span> <?php echo "$user_adv_row[city_name]"; ?> </p>
    <p class="col-sm-6 pull-right"><span class="detail-title"> آدرس :</span> <?php echo "$user_adv_row[address]"; ?> </p>
    <p class="col-sm-6 pull-right"><span class="detail-title"> تلفن :</span> <?php echo "$user_adv_row[phone]"; ?> </p>
    <p class="col-sm-6 pull-right"><span class="detail-title"> موبایل :</span> <?php echo "$user_adv_row[mobile]"; ?> </p>
    <p class="col-sm-6 pull-right"><span class="detail-title"> ایمیل : </span><?php echo "$user_adv_row[email]"; ?> </p>
    <p class="col-sm-6 pull-right"><span class="detail-title"> وب سایت : </span>
    	<a href="<?php echo "$user_adv_row[website]"; ?>"><?php echo "$user_adv_row[website]"; ?></a>
    </p>
    <p class="col-sm-6 pull-right"><span class="detail-title"> کلمات کلیدی : </span><?php echo "$user_adv_row[keywords]"; ?> </p>
    <p class="col-sm-6 pull-right"><span class="detail-title pull-right"> تصویر نشان تجاری : </span>
		<img width="100" height="100" src="images/advertise/<?php echo "$user_adv_row[image]"; ?>.jpg">
    </p>
    <?php } ?>
    <div class="text-center">
    <button type="button" class="btn btn-submit" data-toggle="modal" data-target="#myModal">
  ویرایش اطلاعات شخصی
</button>
	
</div>
</div>


<div class="col-xs-4 panel-detail">
    <h4 class="header-small"><i class="fa fa-user"></i> اطلاعات شخصی : </h4>
    
	<p><span class="detail-title"> نام : </span><?php echo "$user_info_row[first_name]"; ?> </p>
    <p><span class="detail-title"> نام خانوادگی : </span><?php echo "$user_info_row[last_name]"; ?> </p>
    <p><span class="detail-title"> کد ملی : </span><?php echo "$user_info_row[melli_code]"; ?> </p>
    <p><span class="detail-title"> ایمیل :</span> <?php echo "$user_info_row[email]"; ?> </p>
    <p><span class="detail-title"> موبایل : </span><?php echo "$user_info_row[mobile]"; ?> </p>
    <p><span class="detail-title"> شهر : </span><?php echo "$user_info_row[name]"; ?> </p>
    <p><span class="detail-title"> استان : </span><?php echo "$user_info_row[province_name]"; ?> </p>
    <p><span class="detail-title"> آدرس :</span> <?php echo "$user_info_row[address]"; ?> </p>
    <p><span class="detail-title"> پسورد :</span> <?php echo "*******"; ?> </p>
    <div class="text-center">
    <button type="button" class="btn btn-submit signin" data-toggle="modal" data-target="#myModal">
  ویرایش اطلاعات شخصی
</button>
</div>
</div>
<div class="clearfix"></div>
<div class="col-xs-12 offer">
	 <h3 class="header-small"><i class="fa fa-star"></i> امکانات ویژه : </h3>
    	<!--<div class="col-sm-3 text-center">
        	<div class="text-center">
        	<img src="images/viber.jpg" width="150" class="img-thumbnail">
            <h4><a href="?page=viber" class="btn-special">دانلود نرم افزار ارسال انبوه وایبر <br>+<br> بانک شماره تلفن <br>+<br> آموزش استفاده</a></h4>
            </div>
        </div>-->
        <div class="col-sm-3 text-center">
        	<div class="text-center">
        	<img src="images/sms.jpg" width="150" class="img-thumbnail">
            <h4><a href="?page=sms" class="btn-special" style="padding-top:55px">
            ارائه یک پنل اس ام اس با شماره اختصاصی</a></h4>
            </div>
        </div>
        
        <div class="col-sm-3 text-center">
        <div class="text-center">
       		<img src="images/information.jpg" width="150" class="img-thumbnail">
            <h4><a href="?page=information" class="btn-special" style="padding-top:50px">دانلود اطلاعات مشاغل کل کشور به تفکیک شهر، استان و صنف</a></h4>
            </div>
        </div>
        <div class="col-sm-3 text-center">
        	<div class="text-center">
        	<img src="images/email.jpg" width="150" class="img-thumbnail">
            <h4><a href="?page=email" class="btn-special">دانلود بانک ایمیل به تفکیک مشاغل <br> + <br> نرم افزار ارسال <br> + <br> آموزش استفاده</a></h4>
            </div>
        </div>
        <div class="col-sm-3 text-center">
        	<div class="text-center">
        	<img src="images/internet.jpg" width="150" class="img-thumbnail">
            <h4><a href="?page=internet" class="btn-special" style="padding-top:55px">دسترسی به 3 ماه اینترنت رایگان</a></h4>
            </div>
        </div>
        <div class="col-sm-3 text-center">
        	
        </div>
        <div class="col-sm-3 text-center">
        	<div class="text-center">
        	<img src="images/bon.jpg" width="150" class="img-thumbnail">
            <h4><a href="?page=barincard" class="btn-special" style="padding-top:55px">
            دسترسی به کارت تخفیف گروهی و کشوری برین کارت</a></h4>
            </div>
        </div>
        <!--<div class="col-sm-4 text-center">
        	<div class="text-center">
        	<img src="images/web.jpg" width="250" class="img-thumbnail">
            <h4><a href="page/offer/webdesign/" class="btn-special" style="padding-top:55px">طراحی وب سایت با 30% تخفیف</a></h4>
            </div>
        </div>-->
       <!--<div class="col-sm-3 text-center">
        	<div class="text-center">
        	<img src="images/sms.jpg" width="150" class="img-thumbnail">
            <h4><a href="?page=sms" class="btn-special" style="padding-top:55px">
            ثبت</a></h4>
            </div>
        </div>-->
        <!--<div class="col-sm-3 text-center">
        	<div class="text-center">
        	<img src="images/sms.jpg" width="150" class="img-thumbnail">
            <h4><a href="?page=sms" class="btn-special" style="padding-top:55px">
            امکان ثبت نام و درج آگهی در سایت بن تایم</a></h4>
            </div>
        </div>-->
        
	
</div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> ویرایش اطلاعات شخصی </h4>
      </div>
      <div class="modal-body">
      	<form class="form-horizontal" method="post">
  <div class="form-group">
    <label for="first_name" class="col-sm-3 control-label pull-right">نام</label>
    <div class="col-sm-9">
      <input type="text" class="form-control pull-right" name="first_name" required>
    </div>
  </div>
  <div class="form-group">
    <label for="last_name" class="col-sm-3 control-label pull-right">نام خانوادگی</label>
    <div class="col-sm-9">
      <input type="text" class="form-control pull-right" name="last_name" required>
    </div>
  </div>
   <div class="form-group">
    <label for="melli_code" class="col-sm-3 control-label pull-right">کد ملی</label>
    <div class="col-sm-9">
      <input type="text" class="form-control pull-right" name="melli_code" required>
    </div>
  </div>
   <div class="form-group">
    <label for="email" class="col-sm-3 control-label pull-right">ایمیل</label>
    <div class="col-sm-9">
      <input type="email" class="form-control pull-right" name="email">
    </div>
  </div>
   <div class="form-group">
    <label for="mobile" class="col-sm-3 control-label pull-right">تلفن همراه</label>
    <div class="col-sm-9">
      <input type="text" class="form-control pull-right" name="mobile" required>
    </div>
  </div>
  <div class="form-group">
    <label for="password" class="col-sm-3 control-label pull-right">رمز عبور</label>
    <div class="col-sm-9">
      <input type="password" class="form-control pull-right" name="password" required>
    </div>
  </div>
  <div class="form-group">
    <label for="repassword" class="col-sm-3 control-label pull-right">تکرار رمز عبور</label>
    <div class="col-sm-9">
      <input type="password" class="form-control pull-right" name="repassword" required>
    </div>
  </div>
 	<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="submit" name="submit" class="btn-submit" value="ثبت تغییرات">
    </div>
  </div>
</form>
      </div>
     
    </div>
  </div>
</div>