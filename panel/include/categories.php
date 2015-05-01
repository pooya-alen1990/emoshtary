<div class="col-xs-10 col-xs-offset-1 contact category">
<h3 class="header-big">فهرست مشاغل : </h3>
<hr>
<?php
	$query = "SELECT * FROM category ; ";
	$result = mysqli_query($connection,$query);
	while($row = mysqli_fetch_assoc($result)){
    	echo	
			"<ul>
				<li class='col-sm-4 pull-right category-item'><i class='fa fa-check'></i><a href='#' class='link'> $row[name] </a></li>
			</ul>
			";
	}
?>		
</div>