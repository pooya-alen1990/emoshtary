<?php

	
$error = '';
if(isset($_POST['submit'])){

    $activation_code = $_POST['activation_code'];
	
				$user_query = "SELECT * FROM `users` WHERE `activation_code` = '$activation_code' ; ";
				$user_result = mysqli_query($connection , $user_query);
				$user_row = mysqli_fetch_assoc($user_result);
				
	if($user_row){
		
		$_SESSION['MM_SIGNUP'] = $user_row['id'];
		header("Location: ?page=signup");
			
        
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
<!-- Page Title -->
		<div class="section section-breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h1>تایید کد فعال سازی</h1>
					</div>
				</div>
			</div>
		</div>
        
        <div class="section">
	    	<div class="container">
            <div class="row">
        	<div class="col-sm-12">
                <div class="alert alert-success alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <strong> توجه! </strong> اگر تاکنون موفق به دریافت کد فعال سازی نشده اید ابتدا کد فعال سازی را با تماس با شماره 2-88759591 تهیه نمایید و از صفحه ثبت نام اقدام به ثبت نام در سایت فرمایید.
                </div>
                <?php echo $error; ?>
            </div>
        </div>
        
        
        <div class="row">
					<div class="col-sm-5 pull-right">
						<div class="basic-login">
							<form role="form" role="form" method="post">
								<div class="form-group">
		        				 	<label for="login-username"><i class="icon-user"></i> <b>کد فعال سازی</b></label>
									<input class="form-control" id="login-username" type="text" name="activation_code" placeholder="">
								</div>
								<div class="form-group">
									
									<input type="submit" name="submit" class="btn-submit btn" value="تایید">
									<div class="clearfix"></div>
                                    
								</div>
							</form>
						</div>
					</div>
                    <div class="col-sm-7">
						<div class="basic-login">
							<h3 class="text-center">امکانات وِیژه شبکه ارتباط با مشتری</h3>

                                    <ol class="test-center" style="margin-right:30px;">
                                        
                                        <li align="center">معرفی حرفه خود به صورت رایگان</li>
                                        <li align="center">امکان ارسال پیامک تبلیغاتی برای اصناف </li>
                                        <li align="center">دسترسی به اطلاعات اصناف</li>
                                        <li align="center">امکان ارسال وایبر تبلیغاتی برای اصناف</li>
                                        <li align="center">امکان ارسال ایمیل تبلیغاتی برای اصناف</li>
                                        <li align="center">3ماه اینترنت ADSL رایگان</li>
                                        <li align="center">دریافت سامانه پیام کوتاه</li>
                                        <li align="center">امکان ارسال پیام برای تمامی اعضای سایت</li>
                                    </ol>    
								
						</div>

				</div>
			</div>
		</div><br><br><br>