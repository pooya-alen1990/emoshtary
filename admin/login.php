<?php
session_start();
$msg='';
	error_reporting(E_ALL);
	ini_set('display_errors','1');
### CONNECT TO DB ###

	
	
	if($_SERVER['SERVER_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_ADDR'] == '::1'){
	define("LOCAL_MODE" , 1);
	}else{
	define("LOCAL_MODE" , 0);
	}
	
	
	##### Configs ######
	if(LOCAL_MODE == 0){
		define('HOST_NAME','localhost');
		define('USER_NAME','emoshtary');
		define('PASSWORD','emoshtary1394');
		define('DB_NAME','emoshtary');
		$prefix = 'http://emoshtary.ir/panel';
		define('BASE_PATH','http://emoshtary.ir/panel');
	}else if(LOCAL_MODE == 1){
		define('HOST_NAME','localhost');
		define('USER_NAME','root');
		define('PASSWORD','');
		define('DB_NAME','emoshtary');
		$prefix = '/emoshtary/panel';
		define('BASE_PATH','http://localhost/emoshtary/panel');
	}
	
	$mysqli=new mysqli(HOST_NAME,USER_NAME,PASSWORD,DB_NAME) or die("Connection Failed...!");
	$mysqli->set_charset("utf8");

	
function toSafeString($mysqli,$string){
		$string=$mysqli->real_escape_string($string);
		$string=htmlentities($string,ENT_QUOTES,"utf-8");
		$string=trim($string);
		return $string;
	}
	
if(isset($_POST['submit'])){
	//$melli_code=toSafeString($mysqli,$_POST['melli_code']);
	$mobile=toSafeString($mysqli,$_POST['mobile']);
	$password=toSafeString($mysqli,$_POST['password']);
	$password_hash=sha1($password);
	
	$admins_query="SELECT * FROM admins WHERE mobile='$mobile' AND password='$password_hash' LIMIT 1;";
	$admins_result=$mysqli->query($admins_query);
	$admins_row=$admins_result->num_rows;
	$admins_fetch_rows=$admins_result->fetch_assoc();
	
	if($admins_row){
				
			$_SESSION['MM_admin_first_name']=$admins_fetch_rows['first_name'];
			$_SESSION['MM_admin_last_name']=$admins_fetch_rows['last_name'];
			$_SESSION['MM_admin_id']=$admins_fetch_rows['id'];
			$_SESSION['MM_admin_mobile']=$admins_fetch_rows['mobile'];
			
			$roles_id=$admins_fetch_rows['roles_id'];
			
			$roles_query = "SELECT
									 rolespermissions.id
									,rolespermissions.roles_id
									,rolespermissions.permissions_id
									,permissions.ENtitle
									,permissions.id
									,permissions.title 
										FROM rolespermissions 
											INNER JOIN permissions
												ON rolespermissions.permissions_id = permissions.id
													WHERE rolespermissions.roles_id = '$roles_id' ; ";
			$roles_result = $mysqli->query($roles_query);	
			$check = array();
			while($roles_row = $roles_result ->fetch_assoc()){
				$permissions_name = $roles_row['ENtitle'];
				array_push($check,$permissions_name);
				$_SESSION['permissions'] = $check;
				}
			header("Location: index.php");
		} 
	else {
		$msg = '
											<div class="col-lg-12 col-md-6">
												<br style="margin:20px 0;">
												<div class="alert alert-danger alert-dismissable">
												  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
												 نام کاربری یا کلمه عبور اشتباه است
												</div>
											</div>
										';
		}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Pooya Sabramooz">
    <link rel="icon" href="favicon.ico">

    <title>صفحه ورود پنل مدیریتی شبکه ارتباط با مشتری</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body dir="rtl" style="padding-top:0;">
	
    <div class="container">
	
      <form class="form-signin" role="form" method="post">
      <img src="images/logo.jpg"  alt="" class="img-circle text-center" width="300"/>
        <h2 class="form-signin-heading text-center">شبکه ارتباط با مشتری</h2>
        <input type="text" name="mobile" maxlength="11" class="form-control" placeholder="شماره موبایل" required data-error-empty="پر کردن این فیلد اجباری است" autofocus>
        <input type="password" name="password" class="form-control" placeholder="رمز عبور" data-error-empty="پر کردن این فیلد اجباری است" required>
        <!--<label class="checkbox">
          <input type="checkbox" value="remember-me"> Remember me
        </label>-->
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">ورود</button>
      </form>
	<?php echo $msg; ?>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>
