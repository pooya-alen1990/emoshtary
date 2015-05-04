<?php 
	$etelaat = '';
$etelaat_query = " SELECT * FROM `email`" ;
$etelaat_result = mysqli_query($connection,$etelaat_query);
while($etelaat_row = mysqli_fetch_assoc($etelaat_result)){
		$etelaat .="<li class='col-sm-6 col-xs-12 col-md-4 pull-right'>
							<i style='color:#4183C4' class='fa fa-download'></i>
					<a href='http://barincard.com/email/bank/$etelaat_row[url]' class='link'> دانلود $etelaat_row[name] </a></li>";
	}

?>
<div class="col-xs-10 col-xs-offset-1 contact">
	<h3 class="header-big">امکانات ویژه</h3>
    <div class="col-xs-12">
    <h4 class="header-small">دانلود بانک ایمیل</h4>
    <p>با کلیک بر روی هر کدام از لینک های زیر ایمیل های آن را دانلود کنید.</p>
    <br>
        <div class="  category">
            <ul>
                <?php echo $etelaat; ?>
            </ul>
        </div>
        <div class="clearfix"></div><br>
    	<h4 class="header-small pull-right">دانلود نرم افزار ارسال ایمیل گروهی</h4>
    	<div class="col-xs-12 text-center" style="font-size:18px">
            <i style='color:red' class='fa fa-download'></i><a href='http://barincard.com/email/abzar.zip' class='link'> دانلود نرم افزار ارسال ایمیل گروهی </a>
        </div>
	</div>
</div>
