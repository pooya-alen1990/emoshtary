<?php
require_once '../core/core.php';



$data = array();
if( isset( $_POST['image_upload'] ) && !empty( $_FILES['images'] )){
	//get the structured array
	$images = restructure_array(  $_FILES );
	$allowedExts = array("gif", "jpeg", "jpg", "png");
	
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	
	foreach ( $images as $key => $value){
		$i = $key+1;
		//create directory if not exists
		if (!file_exists('images')) {
			mkdir('images', 0777, true);
		}
		$image_name = $value['name'];
		//get image extension
		$ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
		//assign unique name to image
		$name = $i*time().'.'.$ext;
		//$name = $image_name;
		//image size calcuation in KB
		$image_size = $value["size"] / 1024;
		$image_flag = true;
		//max image size
		$max_size = 512;
		if( in_array($ext, $allowedExts) && $image_size < $max_size ){
			$image_flag = true;
		} else {
			$image_flag = false;
			$data[$i]['error'] = 'Maybe '.$image_name. ' exceeds max '.$max_size.' KB size or incorrect file extension';
		} 
		
		if( $value["error"] > 0 ){
			$image_flag = false;
			$data[$i]['error'] = '';
			$data[$i]['error'].= '<br/> '.$image_name.' Image contains error - Error Code : '.$value["error"];
		}
		
		if($image_flag){
			move_uploaded_file($value["tmp_name"], "images/".$name);
			$src = "images/".$name;
			$dist = "images/thumbnail_".$name;
			$data[$i]['success'] = $thumbnail = 'thumbnail_'.$name;
			thumbnail($src, $dist, 100);
			$sql="INSERT INTO images (`id`, `original_image`, `thumbnail_image`, `ip_address`, `advertise_id`) VALUES (NULL, '$name', '$thumbnail', '$ip', '$_SESSION[MM_ID]');";
			if (!mysqli_query($connection,$sql)) {
				die('Error: ' . mysqli_error($connection));
			} 
			
			
			
		}
	}
	mysqli_close($connection);
	echo json_encode($data);
	
} else {
	$data[] = 'No Image Selected..';
}



function restructure_array(array $images)
{
	$result = array();

	foreach ($images as $key => $value) {
		foreach ($value as $k => $val) {
			for ($i = 0; $i < count($val); $i++) {
				$result[$i][$k] = $val[$i];
			}
		}
	}

	return $result;
}



function thumbnail($src, $dist, $dis_width = 100 ){

	$img = '';
	$extension = strtolower(strrchr($src, '.'));
	switch($extension)
	{
		case '.jpg':
		case '.jpeg':
			$img = @imagecreatefromjpeg($src);
			break;
		case '.gif':
			$img = @imagecreatefromgif($src);
			break;
		case '.png':
			$img = @imagecreatefrompng($src);
			break;
	}
	$width = imagesx($img);
	$height = imagesy($img);




	$dis_height = $dis_width * ($height / $width);

	$new_image = imagecreatetruecolor($dis_width, $dis_height);
	imagecopyresampled($new_image, $img, 0, 0, 0, 0, $dis_width, $dis_height, $width, $height);


	$imageQuality = 100;

	switch($extension)
	{
		case '.jpg':
		case '.jpeg':
			if (imagetypes() & IMG_JPG) {
				imagejpeg($new_image, $dist, $imageQuality);
			}
			break;

		case '.gif':
			if (imagetypes() & IMG_GIF) {
				imagegif($new_image, $dist);
			}
			break;

		case '.png':
			$scaleQuality = round(($imageQuality/100) * 9);
			$invertScaleQuality = 9 - $scaleQuality;

			if (imagetypes() & IMG_PNG) {
				imagepng($new_image, $dist, $invertScaleQuality);
			}
			break;
	}
	imagedestroy($new_image);
}