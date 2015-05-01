<?php
	$users_list = false;
	$change_privileges = false;
	
	
	$home_active='';$messages_active='';
	$users_list_active='';$change_privileges_active='';
	
	if(in_array('users_list',$_SESSION['permissions'])){ $users_list = true; }
	if(in_array('change_privileges',$_SESSION['permissions'])){ $change_privileges = true; }
		
?>
<div class="col-sm-3 col-md-2 sidebar">
  <ul class="nav nav-sidebar">
<?php 	
	
	 if(isset($_GET['page']) && $_GET['page']=='home'){ $home_active = 'active'; } 
     	echo "<li class='$home_active' ><a href='index.php?page=home'>داشبورد من</a></li>"; 
    
	
	 if(isset($_GET['page']) && $_GET['page']=='messages'){ $messages_active = 'active'; } 
     	echo "<li class='$messages_active' ><a href='index.php?page=messages'>پیام های من</a></li>"; 
     
		
	if(isset($_GET['page']) && $_GET['page']=='users_list'){ $users_list_active = 'active'; } 
     	if($users_list == true){ echo "<li class='$users_list_active' ><a href='index.php?page=users_list'>لیست اعضا</a></li>";}

	
	if(isset($_GET['page']) && $_GET['page']=='change_privileges'){ $change_privileges_active = 'active'; }
    	if($change_privileges == true){ echo "<li class='$change_privileges_active' ><a href='index.php?page=change_privileges'>تغییر سطح دسترسی</a></li>";}
		
?>   
  </ul>
</div>