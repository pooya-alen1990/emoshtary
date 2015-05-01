<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors','1');
date_default_timezone_set('Asia/Tehran');
include('include/jdf.php');
include('messages/msg.php');

		
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



#### FUNCTIONS ########
  function toSafeString($mysqli,$string){
		  $string=$mysqli->real_escape_string($string);
		  $string=htmlentities($string,ENT_QUOTES,"utf-8");
		  $string=trim($string);
		  return $string;
	  }
  function daradNadarad($input){
	  
	  if($input == 1){
		  return "<span style='color:green;'>دارد</span>";
		  }else if ($input == 0){
		  return "<span style='color:red;'>ندارد</span>";	
			  }
	  }
	  
	  
if(!isset($_SESSION['MM_admin_mobile'])){
	header('Location: login.php');
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
    <link rel="shortcut icon" href="assets/ico/favicon.ico">

    <title>پنل مدیریتی شبکه ارتباط با مشتری</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right" style="float:left !important">
            <li><a href="logout.php">خروج</a></li>
            <li><a href="#">داشبورد</a></li>
            <li><a href="#">تنظیمات</a></li>
            <li><a href="index.php?page=password_update">تغییر رمز عبور</a></li>
            <li><a href="index.php?page=home"><?php echo $_SESSION['MM_admin_first_name'].' '.$_SESSION['MM_admin_last_name']; ?></a></li>          
          </ul>
          <a class="navbar-brand" href="index.php" style="float:right; margin-left:20px;">پنل مدیریتی شبکه ارتباط با مشتری</a>
          <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="جستجو...">
          </form>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <?php include('include/sidebar.php'); ?>
        <div class="col-sm-9 col-md-10 main">

          <?php
		  	if(isset($_GET['page'])){
				
					if(is_file("include/$_GET[page].php")){
						include("include/$_GET[page].php");
					}elseif(is_file("include/tabriz/$_GET[page].php")){
						include("include/tabriz/$_GET[page].php");
					}else{
						echo 'صفحه مورد نظر وجود ندارد.';}
								
			}else{
				include('include/home.php');
			}
		  ?>
          	
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript">
            if (typeof jQuery == 'undefined') {
                document.write(unescape("%3Cscript src='js/jquery.js' type='text/javascript'%3E%3C/script%3E"));
            }
    </script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/tablesorter.js"></script>
    <script src="js/docs.min.js"></script>
    <script>
    $(document).ready(function() 
    { 
        $("table.tablesorter").tablesorter(); 
    } 
	);
    </script>
  </body>
</html>
