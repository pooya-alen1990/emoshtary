<?php 
	$adv_query = "SELECT advertises.id, advertises.name, advertises.cat_id, advertises.sub_cat_id, advertises.slogan, advertises.city_id, advertises.province_id, advertises.address, advertises.phone, advertises.mobile, advertises.email, advertises.website, advertises.keywords, advertises.google_map, advertises.image,
 city.id, city.name AS city_name, city.province,category.name AS cat_name,
 province.id, province.name AS province_name
  FROM `advertises`

	INNER JOIN `city` ON advertises.city_id = city.id
	INNER JOIN `province` ON advertises.province_id = province.id
	INNER JOIN `category` ON category.id = advertises.cat_id
  WHERE advertises.id = '3' ; ";
 $adv_result = mysqli_query($connection,$adv_query);
 $adv_row =  mysqli_fetch_assoc($adv_result);

?>

<div class="col-xs-10 col-xs-offset-1 contact">
	<div class="col-xs-6 text-center">
    	<img src="<?php echo $prefix ?>/images/advertise/<?php echo "$adv_row[image]"; ?>.jpg" width="150" class="img-thumbnail">
    </div>
    <div class="col-xs-6">
    	<h3 class="header-small"><?php echo "$adv_row[name]"; ?></h3>
        <p><?php echo "$adv_row[slogan]"; ?></p>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-8 adv-info">
    	<p class="col-sm-6 pull-right"> نام واحد شغلی : <?php echo "$adv_row[name]"; ?> </p>
        <p class="col-sm-6 pull-right"> زمینه فعالیت : <?php echo "$adv_row[cat_name]"; ?> </p>
        <p class="col-sm-6 pull-right"> شعار : <?php echo "$adv_row[slogan]"; ?> </p>
        <p class="col-sm-6 pull-right"> استان : <?php echo "$adv_row[province_name]"; ?> </p>
        <p class="col-sm-6 pull-right"> شهرستان : <?php echo "$adv_row[city_name]"; ?> </p>
        <p class="col-sm-6 pull-right"> آدرس : <?php echo "$adv_row[address]"; ?> </p>
        <p class="col-sm-6 pull-right"> تلفن : <?php echo "$adv_row[phone]"; ?> </p>
        <p class="col-sm-6 pull-right"> موبایل : <?php echo "$adv_row[mobile]"; ?> </p>
        <p class="col-sm-6 pull-right"> ایمیل : <?php echo "$adv_row[email]"; ?> </p>
        <p class="col-sm-6 pull-right"> وب سایت : <?php echo "$adv_row[website]"; ?> </p>
        <p class="col-sm-6 pull-right"> کلمات کلیدی : <?php echo "$adv_row[keywords]"; ?> </p>
    </div>
    <div class="col-sm-4 adv-map">
    	<iframe src="<?php echo "$adv_row[google_map]"; ?>"></iframe>
    </div>
</div>