<h3 class="sub-header">تغییر رمز عبور</h3>
<?php
$error ='';
	
	#### FUNCTIONS ########
	function toSafeString($mysqli,$string){
			$string=$mysqli->real_escape_string($string);
			$string=htmlentities($string,ENT_QUOTES,"utf-8");
			$string=trim($string);
			return $string;
		}
	if(isset($_POST['submit'])){
if(isset($_POST['old_password'])&&$_POST['password']!=''&&$_POST['repassword']!=''){
		$id=$_SESSION['MM_admin_id'];
		$old_password_query="SELECT password,id from admins WHERE id='$id';";
		$old_password_result=$mysqli->query($old_password_query);
		$old_password_row=$old_password_result->fetch_assoc();
		$old_password=toSafeString($mysqli,$_POST['old_password']);
		$old_password_hash=sha1($old_password);
		$password=toSafeString($mysqli,$_POST['password']);
		$password_hash=sha1($password);
		$repassword=toSafeString($mysqli,$_POST['repassword']);
		if($old_password_hash==$old_password_row['password']){
				if($password==$repassword){
					$change_password_query="UPDATE admins
								SET password='$password_hash' WHERE id='$id';";
					$change_password_result=$mysqli->query($change_password_query);
						if($change_password_result){
							$error = '<div class="col-lg-12 col-md-6">
												<br style="margin:20px 0;">
												<div class="alert alert-success alert-dismissable">
												  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
												رمز عبور شما با موفقیت بروزرسانی شد.
												</div>
											</div>';
							}else{
								$error = "<hr>
											<div class='col-lg-12 col-md-6'>
												<br style='margin:20px 0;'>
												<div class='alert alert-danger alert-dismissable'>
												  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
													خطا در سیستم ، لطفا بعدا تلاش کنید.
												</div>
											</div>";
								}
					}else{
								$error = "<hr>
											<div class='col-lg-12 col-md-6'>
												<br style='margin:20px 0;'>
												<div class='alert alert-danger alert-dismissable'>
												  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
													کلمه عبور جدید و تکرار آن با هم مطابقت نمیکند.
												</div>
											</div>";
					}
		}else{
									$error = "<hr>
											<div class='col-lg-12 col-md-6'>
												<br style='margin:20px 0;'>
												<div class='alert alert-danger alert-dismissable'>
												  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
													کلمه عبور فعلی نادرست است.
												</div>
											</div>";
			}
}else{
	$error = "<hr>
											<div class='col-lg-12 col-md-6'>
												<br style='margin:20px 0;'>
												<div class='alert alert-danger alert-dismissable'>
												  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
													لطفا تمامی فیلد های مورد نظر را پر کنید.
												</div>
											</div>";
}
	}
	
?>
<div class="col-xs-3 col-xs-offset-9">
    <form method="post" role="form" class="form">
        <label for="old_password">کلمه عبور فعلی :</label><input type="password" name="old_password" class="form-control" required>
        <label for="password">کلمه عبور جدید :</label><input type="password" name="password" class="form-control" required>
        <label for="repassword">تکرار کلمه عبور جدید :</label><input type="password" class="form-control" name="repassword" required><br>
        <input type="submit" name="submit" value="تایید" class="form-control btn btn-md btn-primary">
    </form>
</div>
<?php echo $error; ?>