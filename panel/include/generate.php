<?php

	
$error = '';
if(isset($_POST['submit'])){

    $activation_code = $_POST['activation_code'];
	
				$user_query = "SELECT * FROM `users` WHERE `activation_code` = '$activation_code' ; ";
				$user_result = mysqli_query($connection , $user_query);
				$user_row = mysqli_fetch_assoc($user_result);
				
	if($user_row){
		
		$_SESSION['MM_SIGNUP'] = $user_row['id'];
		header("Location: $prefix/page/user/signup/");
			
        
    }else{
        $error = '
        <div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>خطا!</strong> کد فعال سازی وارد شده نا معتبر می باشد.
        </div>
        ';
    }
}

?>
	<div class="col-md-10 col-md-offset-1 contact">
    <div class="col-sm-5" style="margin-top:13px;">
    <?php if(isset($_GET['msg'])){ echo "<p class='text-center'> برای درج آگهی ابتدا باید ثبت نام کنید </p>"; } ?>
    	<div class="text-center panel panel-default" >
            	<div class="panel-heading">امکانات وِیژه بانک مشاغل ایران</div>

				<div class="panel-body" dir="rtl">
                	
                	<p align="center">معرفی حرفه خود به صورت رایگان</p>
					<p align="center">امکان ارسال پیامک تبلیغاتی برای اصناف </p>
                    <p align="center">دسترسی به اطلاعات اصناف</p>
                    <p align="center">امکان ارسال وایبر تبلیغاتی برای اصناف</p>
                    <p align="center">امکان ارسال ایمیل تبلیغاتی برای اصناف</p>
					
                 
       		</div>
        </div>
    
    
</div>
    
    <div class="col-sm-7">
		<h3 class="header-big">تایید کد فعالسازی</h3>
   		 <hr>
         <form class="form-horizontal" method="post">
  <div class="form-group">
    <label for="activation_code" class="col-sm-3 control-label pull-right">کد فعالسازی : </label>
    <div class="col-sm-9">
      <input type="text" class="form-control pull-right" name="activation_code" required>
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="submit" name="submit" class="btn-submit" value="تایید کد فعالسازی">
    </div>
  </div>
</form>
		
   	 	</div>
        <div class="clearfix"></div>
        <?php echo $error; ?>
	</div>