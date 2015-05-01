<?php  
	require_once('core/core.php');
	$province_query = " SELECT * FROM `province`" ;
	$province_result = mysqli_query($connection,$province_query);
	
	function convertIdToName($connection,$id,$tableName){
		$query = " SELECT name FROM `$tableName` WHERE id = '$id' LIMIT 1"  ;
		$result = mysqli_query($connection,$query);
		$row = mysqli_fetch_assoc($result);
		return $row['name'];
		}
	if(isset($_SESSION['MM_ID'])){}
	################################
	$main_query = "SELECT * FROM `advertises`" ;
	$main_result =  mysqli_query($connection,$main_query);
	
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>شبکه ارتباط با مشتری</title>
    <meta name="keywords" contenet="برین ,برین کارت ,کارت تخفیف , تخفیف کارت ,بیشترین تخفیف, کارت تخفیف برین" http-equiv="Content-Type" content="text/html">
    <meta name="description" content="برین ,برین کارت ,کارت تخفیف , تخفیف کارت ,بیشترین تخفیف, کارت تخفیف برین">
    <meta name="author" content="rayweb.ir">
    <link rel="icon" href="<?php echo $prefix; ?>/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="<?php echo $prefix; ?>/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $prefix; ?>/css/custom.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $prefix; ?>/css/slider.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $prefix; ?>/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $prefix; ?>/css/component.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $prefix; ?>/font-awesome/css/font-awesome.min.css">
    <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="<?php echo $prefix; ?>/js/jquery.js"></script>
</head>
<body>
	<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo $prefix; ?>/">
      	<img src="<?php echo $prefix; ?>/images/logo.png" width="70">
      </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      
      
      <ul class="nav navbar-nav navbar-right cbp-tm-menu" id="cbp-tm-menu">
      
     <?php //if(isset($_GET['province'])){
		//  $city_query = " SELECT * FROM `city` WHERE province = '$_GET[province]' ; " ;
		//  $city_result = mysqli_query($connection,$city_query);
     // echo '<li>
            //  <a href="#" style="padding-right:0px;">';
			
             // 	if(isset($_GET['city'])){
				//	echo convertIdToName($connection,$_GET['city'],'city');
				//	}else{
				//		echo 'همه';
				//		}
			  
			//  echo ' <i class="fa fa-caret-down"></i></a>
            //  <ul class="cbp-tm-submenu"> ';   
            
			//  	while($city_row = mysqli_fetch_assoc($city_result)){
			//			echo "<li><a href='?province=$_GET[province]&city=$city_row[id]'>$city_row[name]</a></li>";
		//			}
			 	
        //    echo '                     
       //       </ul>
      //    </li><li><a href="#"> انتخاب شهرستان <i class="fa fa-angle-double-left"></i></a></li>
  //     ';
   //    }
  //     ?>
       	<li>
              <a href="#" style="padding-right:0px;">
              <?php
              	if(isset($_GET['province'])){
				//	echo convertIdToName($connection,$_GET['province'],'province');
					}else{
				//		echo 'همه';
						}
			  ?>
              <!-- <i class="fa fa-caret-down"></i>--></a>
              <ul class="cbp-tm-submenu">    
              <?php 
			  	while($province_row = mysqli_fetch_assoc($province_result)){
				//		echo "<li><a href='?province=$province_row[id]'>$province_row[name]</a></li>";
					}
			  ?>                  
              </ul>
          </li>
        <!-- <li><a href="#"> انتخاب استان <i class="fa fa-angle-double-left"></i></a></li>-->
         <?php 
			 if(!isset($_SESSION['MM_ID'])){
				 header('Location: http://emoshtary.ir');
			 }else{
				 $user_query = "SELECT * FROM users WHERE id = '$_SESSION[MM_ID]' ; ";
				 $user_result = mysqli_query($connection , $user_query);
				 $user_row = mysqli_fetch_assoc($user_result);
				 echo '<li><a href="'.$prefix.'/signout.php"><i class="fa fa-sign-out"></i> خروج</a></li>
				 	   <li><a href="'.$prefix.'/page/users/mypage/"><i class="fa fa-user"></i> '.$user_row['first_name'].' '.$user_row['last_name'].'</a></li>';
				 }
         ?>
       
      </ul>
    
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
	<nav class="nav-right text-center col-xs-1 pull-right">
    	<div class="nav-ico">
        <a href="http://emoshtary.ir">
        	<img src="<?php echo $prefix; ?>/images/logo.png" width="90">
            <br>
            <p class="logo">شبکه</p>
            <p class="logo">ارتباط با مشتری</p>
        </a>
        </div>
        <hr>
        <?php 
			if(isset($_SESSION['MM_ID'])){
				echo "
				<div class='nav-ico'>
        	<a href='$prefix/page/users/mypage/'>
        		<p><i class='fa fa-user'></i></p>
                <p>صفحه من</p>
            </a>
        </div>
				";
				}
		?>
    	
        <?php
        if(isset($_SESSION['MM_ID'])){
			$id = $_SESSION['MM_ID'];
		$user_adv_query = "SELECT advertises.id, advertises.name, advertises.cat_id, 
					advertises.sub_cat_id, advertises.slogan, advertises.city_id, advertises.province_id, 
					advertises.address, advertises.phone, advertises.mobile, advertises.email, 
					advertises.website, advertises.keywords, advertises.google_map, advertises.image,
					 city.id, city.name AS city_name, city.province,category.name AS cat_name,
					 province.id, province.name AS province_name
					  FROM `advertises`
					
						INNER JOIN `city` ON advertises.city_id = city.id
						INNER JOIN `province` ON advertises.province_id = province.id
						INNER JOIN `category` ON category.id = advertises.cat_id
					  WHERE advertises.user_id = $id ; ";
			$user_adv_result = mysqli_query($connection,$user_adv_query);
			$user_adv_row =  mysqli_fetch_assoc($user_adv_result);
			 
			if(isset($user_adv_row)){
				echo '<div class="nav-ico">
					<a href="'.$prefix.'/page/asnaf/addImage/">
						<p><i class="fa fa-plus"></i></p>
						<p>اضافه کردن تصویر</p>
					</a>
				</div>';
			}else{
				echo '<div class="nav-ico">
					<a href="'.$prefix.'/page/asnaf/add/">
						<p><i class="fa fa-plus"></i></p>
						<p>درج آگهی</p>
					</a>
				</div>';
				
				}
			
	}else{
		echo '<div class="nav-ico">
					<a href="'.$prefix.'/page/asnaf/add/">
						<p><i class="fa fa-plus"></i></p>
						<p>درج آگهی</p>
					</a>
				</div>';
		}
		
		?>
        
        
        
        <div class="nav-ico">
        	<a href="http://emoshtary.ir/index.php?page=contactus">
        		<p><i class="fa fa-phone"></i></p>
                <p>ارتباط با ما</p>
            </a>
        </div>
    </nav>
    <section class="main col-xs-11">
    
 		<?php
	if(isset($_GET['page'])){
	  if(is_file('include/'.$_GET['page'].'.php')){
		  include 'include/'.$_GET['page'].'.php';
		  }else{
		  die('صفحه مورد نظر وجود ندارد');
		  }
	  }else{
		  include 'include/home.php';
		  }


?>
	
    </section>
    <div class="clearfix"></div>
    <footer>
    	<div class="col-xs-11 text-center">
        	<div class="col-sm-8 pull-right">
            	<h2>لینک های مرتبط</h2>
                <hr>
                <ul class="col-sm-6 text-center pull-right">
                	<li><a href="http://emoshtary.ir/index.php?page=contactus">درباره بانک مشاغل ایران</a></li>
                    <li><a href="http://emoshtary.ir/index.php?page=generate">ثبت نام</a></li>
			        <li><a href="http://emoshtary.ir/index.php?page=login">ورود</a></li>
                    
                </ul>
                <ul class="col-sm-6 text-center pull-right">
                    <li><a href="http://emoshtary.ir/index.php?page=list">فهرست مشاغل</a></li>
                    <li><a href="http://emoshtary.ir/index.php?page=search">جستجو</a></li>
                    <li><a href="http://emoshtary.ir/index.php?page=contactus">ارتباط با ما</a></li>
                    
                </ul>
            </div>
            <div class="col-sm-4 pull-right">
           		<h2>ارتباط با ما</h2>
                <hr>	
                <p>آدرس : خیابان شهید بهشتی ، بعد از خیابان میرعماد، پلاک 294، طبقه پنجم ، واحد 501</p>
                <p>تلفن : 2-88759591 - 021</p>
                <p>خط ویژه : 88507922 - 021</p>
                 <p>ایمیل : info@118asnafeiran.ir</p>
                
            </div>
        </div>
        <div class="clearfix"></div>
        <p class="text-center social"><a href="#"><img src="<?php echo $prefix; ?>/images/fb.png"></a><a href="#"><img src="<?php echo $prefix; ?>/images/tw.png"></a><a href="#"><img src="<?php echo $prefix; ?>/images/go.png"></a></p>
    </footer>
    <div class="footer-end">
        	<h5 class="text-center">تمامی حقوق این وب سایت متعلق به شرکت یگانه نوآوران پویا می باشد.</h5>
            <h5 class="text-center">طراح : <a href="http://rayweb.ir">رای وب</a></h5>
        </div>
      
<script>
	var config = {
		"opacity": 70,
		"position": "topleft",
		"path": "/118asnafeiran/images/watermark.png"			};		
	$(document).ready(function(){
		$(document).watermark(config);
	});
</script>
   
<script src="<?php echo $prefix; ?>/js/watermark.jquery.min.js"></script> 
<script src="<?php echo $prefix; ?>/js/jquery.easing.1.3.js"></script>
<script src="<?php echo $prefix; ?>/js/bootstrap.min.js"></script>
<script src="<?php echo $prefix; ?>/js/custom.js"></script>
<script src="<?php echo $prefix; ?>/js/modernizr.custom.js"></script>
<script src="<?php echo $prefix; ?>/js/cbpTooltipMenu.min.js"></script>
<script src="<?php echo $prefix; ?>/js/jquery.form.min.js"></script>
<script src="<?php echo $prefix; ?>/js/script.js"></script>

<script>
	var menu = new cbpTooltipMenu( document.getElementById( 'cbp-tm-menu' ) );
</script>
</body>
</html>