<?php

include 'core/core.php';

for($i = 0 ; $i < 2000; $i++){
	
	$rand = mt_rand(10000000,(100000000 - 1));
	
	echo $rand;
	
	$query = "INSERT INTO `users`(`id`, `activation_code`) VALUES ('','$rand') ; ";
	mysqli_query($connection,$query);
	
	}
	
?>