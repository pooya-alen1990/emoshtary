<?php
include 'panel/core/core.php';
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>شبکه ارتباط با مشتری</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/icomoon-social.css">
        

        <link rel="stylesheet" href="css/leaflet.css" />
		<!--[if lte IE 8]>
		    <link rel="stylesheet" href="css/leaflet.ie.css" />
		<![endif]-->
		<link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/custom.css">
        <link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css">

        <script src="js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        <script src="js/jquery-1.9.1.min.js"></script>
        <script src="js/responsiveslide.js"></script>
        
        <script>
			$(function () {
		
			  // Slideshow 1
			  $("#slider1").responsiveSlides({
				auto: true,
				pager: true,
				nav: true,
				timeout: 2000, 
				speed: 500,
				maxwidth: 1600,
				namespace: "centered-btns"
			  });
			})
	  </script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        

        <!-- Navigation & Logo-->
        <div class="mainmenu-wrapper">
	        <div class="container">
	        	<div class="menuextras">
					<div class="extras" style="font-size:15px;">
						<ul>
							<span style="color:#F04F52">کد فعال سازی خود را در <a style="color:#5D67D9" href="?page=generate">این</a> قسمت وارد نمایید >>></span>
                            <!--<li><i class="fa fa-location"></i> <a href="#">انتخاب استان</a></li>-->
                            <li><i class="fa fa-sign-in"></i> <a href="?page=login">ورود</a></li>
                            <li><i class="fa fa-user"></i> <a href="?page=generate">ثبت نام</a></li>
			        		
			        	</ul>
					</div>
		        </div>
		        <nav id="mainmenu" class="mainmenu">
					<ul>
						
						<li class="active">
							<a href="?page=home">خانه</a>
						</li>
						<li>
							<a href="?page=list">مشاغل</a>
						</li>
						<li class="has-submenu">
							<a href="?page=search">جستجو</a>
						</li>
						<li>
							<a href="?page=contactus">ارتباط با ما</a>
						</li>
                        <li>
							<a href="?page=code">دریافت کد فعال سازی</a>
						</li>
                        <li>
							<a href="?page=help">مراحل ثبت نام در سایت (راهنما)</a>
						</li>
                        
					</ul>
                    
				</nav>
                
                 <a href="#" style="position:absolute;left:15px;top:2px;">
                     <img src="img/logo.png" width="90" alt="Multipurpose Twitter Bootstrap Template">
                 </a>
                      
			</div>
		</div>
        
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

	    <!-- Footer -->
	    <div class="footer">
	    	<div class="container">
		    	<div class="row">
		    		<div class="col-footer col-md-3 col-xs-6">
		    			<h3>دمو امکانات سایت</h3>
		    			<div class="portfolio-item">
							<div class="portfolio-image">
								<a href="?page=help"><img src="img/slide2.jpg" alt="Project Name"></a>
							</div>
						</div>
		    		</div>
		    		<div class="col-footer col-md-3 col-xs-6">
		    			<h3>راهنما</h3>
		    			<ul class="no-list-style footer-navigate-section">
		    				<li><a href="?page=home">خانه</a></li>
		    				<li><a href="?page=list">لیست مشاغل</a></li>
		    				<li><a href="?page=search">جستجو</a></li>
		    				<li><a href="?page=login">ورود</a></li>
		    				<li><a href="?page=generate">ثبت نام</a></li>
		    				<li><a href="?page=contactus">ارتباط با ما</a></li>
		    			</ul>
		    		</div>
		    		
		    		<div class="col-footer col-md-4 col-xs-6">
		    			<h3>اطلاعات تماس</h3>
		    			<p class="contact-us-details">
	        				<b>آدرس: </b> خیابان شهید بهشتی ، بعد از خیابان میر عماد ، پلاک 294 ، طبقه پنجم ، واحد 501<br/>
	        				<b>تلفن: </b> 2-88759591<br/>
	        				<b>فکس: </b> 88759592<br/>
	        				<b>ایمیل: </b> <a href="mailto:info@emoshtary.ir">info@emoshtary.ir</a>
                            <br>
                            <b>واحد پشتیبانی: </b> 44958948
	        			</p>
		    		</div>
		    		<div class="col-footer col-md-2 col-xs-6">
		    			<h3>شبکه های اجتماعی</h3>
		    			<div class="col-social-icons">
		    				<a href="https://www.facebook.com/raywebco" target="_blank"><i class="fa fa-facebook"></i></a>
                            <a href="https://plus.google.com/communities/107271610650119455714"><i class="fa fa-google-plus"></i></a><br>
                            <a href="#"><i class="fa fa-linkedin"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
		    			</div>
		    		</div>
		    	</div>
		    	<div class="row">
		    		<div class="col-md-12">
		    			<div class="footer-copyright">&copy; 1394 تمامی حقوق برای شرکت یگانه نوآوران پویا محفوظ است.</div>
                        <div class="text-center">طراح : <a href="http://rayweb.ir">رای وب</a></div>
		    		</div>
		    	</div>
		    </div>
	    </div>

        <!-- Javascripts -->
        
        <script src="js/bootstrap.min.js"></script>
        <!--<script src="http://cdn.leafletjs.com/leaflet-0.5.1/leaflet.js"></script>-->
        <!--<script src="js/jquery.fitvids.js"></script>
        <script src="js/jquery.sequence-min.js"></script>
        <script src="js/jquery.bxslider.js"></script>
        <script src="js/main-menu.js"></script>
        <script src="js/template.js"></script>-->

    </body>
</html>