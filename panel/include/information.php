<?php 
	$etelaat = '';
$etelaat_query = " SELECT * FROM `etelaat`" ;
$etelaat_result = mysqli_query($connection,$etelaat_query);
while($etelaat_row = mysqli_fetch_assoc($etelaat_result)){
		$etelaat .="<li class='col-sm-6 col-xs-12 col-md-4'>
							<i style='color:#4183C4' class='fa fa-download'></i>
					<a href='http://barincard.com/bank_mashaghel/$etelaat_row[url]' class='link'> دانلود $etelaat_row[name] </a></li>";
	}

?>
<div class="col-xs-10 col-xs-offset-1 contact">
	<h3 class="header-big">امکانات ویژه</h3>
    <div class="col-xs-12">
    <h4 class="header-small">دانلود اطلاعات مشاغل کل کشور</h4>
    <p>با کلیک بر روی هر کدام از لینک های زیر اطلاعات کامل مشاغل آن صنف را دانلود کنید.</p>
    <br>
        <div class="  category">
            <ul>
                <?php echo $etelaat; ?>
            </ul>
        </div>
</div>
</div>
