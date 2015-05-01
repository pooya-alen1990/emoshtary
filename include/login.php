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
          <strong>خطا!</strong> کد ملی یا رمز عبور اشتباه می باشد.
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
						<h1>ورود</h1>
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
                  <strong> توجه! </strong> اگر تاکنون عضو نشده اید ابتدا کد فعال سازی را با تماس با شماره 2-88759591 تهیه نمایید و از صفحه ثبت نام اقدام به ثبت نام در سایت فرمایید.
                </div>
                <?php echo $error; ?>
            </div>
        </div>
        
				<div class="row">
					<div class="col-sm-5 pull-right">
						<div class="basic-login">
							<form role="form" role="form" method="post">
								<div class="form-group">
		        				 	<label for="login-username"><i class="icon-user"></i> <b>کد ملی</b></label>
									<input class="form-control" id="login-username" type="text" name="melli_code" placeholder="">
								</div>
								<div class="form-group">
		        				 	<label for="login-password"><i class="icon-lock"></i> <b>کلمه عبور</b></label>
									<input class="form-control" id="login-password" type="password" name="password" placeholder="">
								</div>
								<div class="form-group">
									
									<input type="submit" name="submit" class="btn-submit btn" value="ورود">
									<div class="clearfix"></div><br>
                                    <!--<a href="page-password-reset.html" class="forgot-password">فراموشی کلمه عبور</a>-->
               اگر تاکنون عضو نشده اید، همین حالا  کنید<a href="?page=generate" class="forgot-password"> ثبت نام </a>کنید 
                                    
								</div>
							</form>
						</div>
					</div>
					
				</div>
			</div>
		</div>