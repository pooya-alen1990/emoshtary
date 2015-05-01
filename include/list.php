<!-- Page Title -->
		<div class="section section-breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<h1>فهرست مشاغل</h1>
					</div>
				</div>
			</div>
		</div>
        
<div class="section">
	    	<div class="container">
	        	<div class="row">
<hr>
<ul style="list-style-type:none;">
<?php
	$query = "SELECT * FROM category ; ";
	$result = mysqli_query($connection,$query);
	while($row = mysqli_fetch_assoc($result)){
    	echo	
			"
				<li class='col-sm-4 pull-right category-item'><i class='fa fa-check'></i><a href='#' class='link'> $row[name] </a></li>
			
			";
	}
?>
</ul>	
</div></div></div>
