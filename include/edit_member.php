<br>
<div class="container-fluid">
	
<form action="index.php?file=update_member" method="post">
<table class="table table-striped table-bordered">
<tbody>
<?php
$query_member = mysql_query("SELECT * FROM members ORDER BY team DESC,member_fullname ASC")or die(mysql_error());
$count = 1;
	echo "<tr>";
while (list($member_id,$member_initials,$member_fullname,$loc_name,$team)=mysql_fetch_row($query_member)) {
	echo "<td>
		<div class='container-fluid' style='padding:0px'>
			<div class='col-md-4' style='padding:0px'>
				<div class='col-md-7'>
					<p style='margin-top:5px'><label >อักษรย่อ : </label></p>
					<p style='margin-top:15px'><label >ทีม : </label></p>
				</div>
				<div class='col-md-5' style='padding:0px; ' >
					<p ><div style='width:100%;border:1px solid #ccc;border-radius:3px; padding:6px; margin:-10px;background:#f5f5f5;box-shadow:inset 0px 0.5px 1px #ddd;color:#666;font-size:12px;' >$member_initials</div></p>
					<p ><input class='form-control input-sm' type='text' name='team[]' value='$team' style='margin-top:20px;margin-left:-40px;width:101px'></p>
				</div>		
			</div>
			<div class='col-md-8'>
				<div class='col-md-5'>
					<p style='margin-top:5px'><label >ชื่อบน Trello :</label></p>
					<p style='margin-top:15px'><label >ชื่อบนใบงาน :</label></p>
				</div>
				<div class='col-md-7' style='padding:0px; ' >
					<input type='hidden' name='member_name[]' value='$member_fullname'>
					<p ><div style='width:100%;border:1px solid #ccc;border-radius:3px; padding:6px; margin-top:-10px;background:#f5f5f5;box-shadow:inset 0px 0.5px 1px #ddd;color:#666;font-size:12px;padding-left:10px;' >$member_fullname</div></p>
					<p ><input class='form-control input-sm' type='text' name='loc_name[]' value='$loc_name' ></p>
				</div>	
			</div>
		</div>
		 </td>";
	if($count%2==0){
		echo "</tr><tr>";
	}
	$count++;
	
}

?>

</tbody>
</table>
<p align='center'><button type="submit" class="btn btn-success" >ยืนยันการแก้ไขข้อมูล</button> <a href='index.php'><button type="button" class="btn btn-danger" >ยกเลิกการแก้ไขข้อมูล</button></a></p><br><br><br>
</form>	
</div>