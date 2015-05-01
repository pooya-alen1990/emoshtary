<?php if($users_list == false){ die('شما مجوز دسترسی به این صفحه را ندارید.'); } ?>
<h3 class="sub-header">لیست اعضا</h3>
شبکه ارتباط با مشتری
    <table class="table table-striped table-hover table-bordered tablesorter">
    		<thead>
				<tr>
					<th>ردیف</th>
                    <th>کد فعال سازی</th>
					<th>نام و نام خانوادگی</th>
                    <th>کد ملی</th>
                    <th>ایمیل</th>
					<th>موبایل</th>
                    <th>تاریخ ثبت نام</th>
				</tr>
            </thead>
            <tbody>
<?php

	
	$users_query = "SELECT * FROM `users`";
	
	$users_result = $mysqli->query($users_query);
	
	while($users_row = $users_result->fetch_assoc()){
			
		if($users_row['register_date'] != '0'){	
				$users_row['register_date'] = jdate("Y/m/d",$users_row['register_date']);	
		echo "
			  <tr>
						<td>$users_row[id]</td>
						<td>$users_row[activation_code]</td>
						<td>$users_row[first_name] $users_row[last_name]</td>
						<td>$users_row[melli_code]</td>
						<td>$users_row[email]</td>
						<td>$users_row[mobile]</td>
						<td>$users_row[register_date]</td>
			  </tr>";	
		}
	}
	
?>
</tbody>
</table>