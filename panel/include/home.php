
  
	<div class="rotator" dir="ltr">
               
    </div>
        
   
    <div class="advertise">
    <h3 class="header-big">آخرین آگهی های ثبت شده در شبکه ارتباط با مشتری</h3>
    <br><br>
    <?php 
	while($main_row = mysqli_fetch_assoc($main_result)){
		
		echo "
		
		<div class='col-md-4'>
        	<div class='panel panel-default'>
            <div class='panel-heading'>
              <h3 class='panel-title text-center'>آموزش</h3>
            </div>
            <div class='panel-body'>
            	<div class='special text-center'>
					<img src='$prefix/images/advertise/$main_row[image].jpg' width='200'  height='200' class='watermark'>
               		<p><a href='#'>$main_row[name]</a></p>
           		 </div>
                 
            </div>
            </div>
		 </div>
		
		";
		
		}
		?>
    	
        <div class="col-md-4">
        	<div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title text-center">آموزش</h3>
            </div>
            <div class="panel-body">
            	<div class="special text-center">
					<img src="images/logo.jpg" class="watermark" width="200" height="200">
               		<p>آگهی اول</p>
           		 </div>
                 
            </div>
            </div>
		 </div>
         <div class="col-md-4">
        	<div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title text-center">آموزش</h3>
            </div>
            <div class="panel-body">
            	<div class="special text-center">
					<img src="images/logo.jpg" class="watermark" width="200" height="200">
               		<p>آگهی اول</p>
           		 </div>
                 
            </div>
            </div>
		 </div>
         <div class="col-md-4">
        	<div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title text-center">آموزش</h3>
            </div>
            <div class="panel-body">
            	<div class="special text-center">
					<img src="images/logo.jpg" width="100%">
               		<p>آگهی اول</p>
           		 </div>
                 
            </div>
            </div>
		 </div>
         <div class="col-md-4">
        	<div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title text-center">آموزش</h3>
            </div>
            <div class="panel-body">
            	<div class="special text-center">
					<img src="images/logo.jpg" width="100%">
               		<p>آگهی اول</p>
           		 </div>
                 
            </div>
            </div>
		 </div>
         <div class="col-md-4">
        	<div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title text-center">آموزش</h3>
            </div>
            <div class="panel-body">
            	<div class="special text-center">
					<img src="images/logo.jpg" width="100%">
               		<p>آگهی اول</p>
           		 </div>
                 
            </div>
            </div>
		 </div><div class="col-md-4">
        	<div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title text-center">آموزش</h3>
            </div>
            <div class="panel-body">
            	<div class="special text-center">
					<img src="images/logo.jpg" width="100%">
               		<p>آگهی اول</p>
           		 </div>
                 
            </div>
            </div>
		 </div>
         <div class="col-md-4">
        	<div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title text-center">آموزش</h3>
            </div>
            <div class="panel-body">
            	<div class="special text-center">
					<img src="images/logo.jpg" width="100%">
               		<p>آگهی اول</p>
           		 </div>
                 
            </div>
            </div>
		 </div>
         </div>
    <div class="clearfix"></div>
    <section class="parallax">
    	<h1 class="text-center"><span style="color:#FACE0E">شبکه ارتباط با مشتری</span> <span style="color:#FFF !important">محلی برای توسعه حرفه شما</span></h1>
        <div class="col-sm-3 col-sm-offset-2 text-center adv">
        	<h3>امکانات رایگان</h3>
        	<p>با عضویت در شبکه ارتباط با مشتری اطلاعات شغلی خود را وارد و به روز رسانی کنید</p>
        </div>
        <div class="col-sm-3 col-sm-offset-2 text-center adv">
        	<h3>معرفی حرفه خود با ایران 118</h3>
        	<p>با شبکه ارتباط با مشتری حرفه خود را به تمام ایرانیان معرفی نمایید</p>
        </div>
        <div class="col-xs-12 text-center">
        	<a href="#" class="btn btn-submit">عضویت در شبکه ارتباط با مشتری</a>
        </div>
    </section>
