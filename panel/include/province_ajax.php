<?php 
include('../core/core.php');

$options = '';	
$province = $_POST['province'];
$query = "SELECT * FROM `city` WHERE province='$province' ";
$result = mysqli_query($connection,$query);
while($row = mysqli_fetch_assoc($result)){
	$options .= "<option value='$row[id]'>$row[name]</option>";
	}
echo $options;

?>