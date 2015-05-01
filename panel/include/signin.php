<?php
$error = '';
if(isset($_POST['submit'])){

    
	$melli_code = $_POST['melli_code'];
    $password = $_POST['password'];
   
        $user_query = "SELECT * FROM `users` WHERE `melli_code` = '$melli_code' AND `password` = '$password'";
        $user_result = mysqli_query($connection , $user_query);
		$user_row = mysqli_fetch_assoc($user_result);
		if($user_row){
			$_SESSION['MM_ID'] = $user_row['id'];
			header("Location: $prefix/page/user/mypage/");
		}

	    else{
			 $error = '
        <div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>خطا!</strong> عدم تطابق کلمه عبور با تکرار آن .
        </div>
        ';
       }
    }


?>

<div>
	<div class="col-md-8 col-md-offset-2 contact">
    <div class="col-sm-6 col-sm-offset-6">
		<h2>ورود به بانک مشاغل ایران</h2>
   		 <hr>
         <form class="form-horizontal" method="post">
 
   <div class="form-group">
    <label for="melli_code" class="col-sm-3 control-label pull-right">کد ملی</label>
    <div class="col-sm-9">
      <input type="text" class="form-control pull-right" name="melli_code" required>
    </div>
  </div>
  <div class="form-group">
    <label for="password" class="col-sm-3 control-label pull-right">رمز عبور</label>
    <div class="col-sm-9">
      <input type="password" class="form-control pull-right" name="password" required>
    </div>
  </div>
 	 
 	<p>اگر عضو نشده اید، همین حالا <a href="<?php echo $prefix; ?>/index.php?page=signup">ثبت نام</a> کنید</p>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="submit" name="submit" class="btn-submit" value="ورود">
    </div>
  </div>
</form>
   	 	
	</div>
    <div class="clearfix"></div>
    <?php echo $error ; ?>
</div>