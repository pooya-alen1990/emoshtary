<?php
session_start();
ob_start();


if($_SERVER['SERVER_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_ADDR'] == '::1'){
	define("LOCAL_MODE" , 1);
	}else{
	define("LOCAL_MODE" , 0);
}
define('BASE_PATH','http://localhost/118asnafeiran/');
##### Configs ######
if(LOCAL_MODE == 0){
	define('HOST_NAME','localhost');
	define('USER_NAME','emoshtary');
	define('PASSWORD','emoshtary1394');
	define('DB_NAME','emoshtary');
	$prefix = 'http://emoshtary.ir/panel';
}else if(LOCAL_MODE == 1){
	define('HOST_NAME','localhost');
	define('USER_NAME','root');
	define('PASSWORD','');
	define('DB_NAME','emoshtary');
	$prefix = '/emoshtary/panel';
}


$connection = mysqli_connect(HOST_NAME,USER_NAME,PASSWORD,DB_NAME) or die('CONNECTION FAILED!!!');
mysqli_set_charset($connection,'utf8');
##########################



include('jdf.php');
?>