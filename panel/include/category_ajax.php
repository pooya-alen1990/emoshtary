<?php 
include('../core/core.php');

$options = '';	
$category = $_POST['category'];
$query = "SELECT * FROM `sub_category` WHERE category_id='$category' ";
$result = mysqli_query($connection,$query);
while($row = mysqli_fetch_assoc($result)){
	$options .= "<option value='$row[id]'>$row[name]</option>";
	}
echo $options;

?>