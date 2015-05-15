<?php

$provinces = '';
$provinces_query = " SELECT * FROM `province`" ;
$provinces_result = mysqli_query($connection,$provinces_query);
while($provinces_row = mysqli_fetch_assoc($provinces_result)){
		$provinces .="<option value='$provinces_row[id]'>$provinces_row[name]</a>";
	}
	
$error = '';
if(isset($_POST['submit'])){

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
	$melli_code = $_POST['melli_code'];
    $email = $_POST['email'];
	$mobile = $_POST['mobile'];
	$city_id = $_POST['city_id'];
	$province_id = $_POST['province_id'];
	$address = $_POST['address'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    $register_date = time();
    if($password == $repassword){
		
		$id = $_SESSION['MM_SIGNUP'];
		
        $user_query = "UPDATE `users` SET `first_name`='$first_name',`last_name`='$last_name',`melli_code`='$melli_code' ,`email`='$email',`mobile`='$mobile',`city_id`='$city_id',`province_id`='$province_id',`address`='$address',`password`='$password',`register_date`='$register_date' WHERE id = '$id' ; ";
		
        $user_result = mysqli_query($connection , $user_query);
        if($user_result){
           /* $error = '
            <div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>کاربر گرامی !</strong> ثبت نام با موفقیت انجام شد . خوش آمدید
            </div>
            ';*/
			$user_login_query = "SELECT * FROM `users` WHERE `melli_code` = '$melli_code' AND `password` = '$password'";
				$user_login_result = mysqli_query($connection , $user_login_query);
				$user_login_row = mysqli_fetch_assoc($user_login_result);
				if($user_login_row){
					
					$_SESSION['MM_SIGNUP'] = NULL;
 					unset($_SESSION['MM_SIGNUP']);
					
					$_SESSION['MM_ID'] = $user_login_row['id'];
					header("Location: $prefix/page/user/mypage/");
				}
        }
    }else{
        $error = '
        <div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>خطا!</strong> عدم تطابق کلمه عبور با تکرار آن .
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
						<h1>ثبت نام</h1>
					</div>
				</div>
			</div>
		</div>
        
        <div class="section">
	    	<div class="container">
				<div class="row">
					<div class="col-sm-7 pull-right">
						<div class="basic-login">
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
                                  <input type="submit" name="submit" class="btn-submit btn" value="ثبت نام">
                                </div>
                              </div>
                            </form>
						</div>
					</div>
					<div class="col-sm-5">
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
</script>