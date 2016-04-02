<?php
if($_GET['file_id']=="total"){
	$count = mysql_query("SELECT file_id FROM history ")or die(mysql_error());
	$number=mysql_num_rows($count);
	if($number==0){
		echo "<center><h2>ยังไม่มีรายการที่ถูกอัพโหลด</h2></center>";
	}else{
		echo "<center><h2>รายการที่ถูกอัพโหลด</h2></center>";
		echo "<div class='container-fluid'>";

			echo "<table class='table table-bordered table-striped table-hover' cellpadding='10' align='center' >";
			echo "<thead style='color:#444'><th ><center>ชื่อไฟล์เก่า</th><th><center>ชื่อไฟล์ใหม่</th><th><center>รายการ</th><th><center>เวลาที่อัพโหลด</th><th><center>ลบ</th></thead>";
			echo "<tbody>";
			$history=mysql_query("SELECT * FROM history ORDER BY file_id DESC")or die(mysql_error());
			while(list($file_id,$file_original,$file_json,$name,$date_upload)=mysql_fetch_row($history)){
				echo"<tr><td>$file_original</td><td><a href='index.php?file=detail&file_id=$file_id&title=$name'>$file_json</td><td>$name</a></td><td>$date_upload</td><td><center><a href='index.php?file=delete_json&file_id=$file_id' onclick='return confirm(\"คุณต้องการที่จะลบข้อมูลใช่หรือไม่\")'><img src='img/delete.png' width='25' height='25'></a></center></td></tr>";
			}
			echo "</tbody>";
			echo "</table>";
		echo "</div>";
		}
	
}else{
	if(!empty($_GET['search'])){
		if(!empty($_GET['oprator'])){
			$oprator = "AND $_GET[oprator] LIKE '%$_GET[search]%'";
		}else{
			$_GET['oprator']="";
			$oprator = "AND (report.project_id LIKE '%$_GET[search]%' OR report.tor_id LIKE '%$_GET[search]%' OR report.assign_id LIKE '%$_GET[search]%' OR report.component LIKE '%$_GET[search]%' OR report.status_card LIKE '%$_GET[search]%' OR members.name_ldc LIKE '%$_GET[search]%' OR report.team LIKE '%$_GET[search]%')";
		}
	}else{
		$oprator="";
		$_GET['search']="";
		$_GET['oprator']="";
	}
	

	echo "<div class='container-fluid'>";
	echo "<br><center><h2><b>$_GET[title]</b></h2></center>";
	echo "<form class='form-inline' action='index.php' method='get'>";
	echo "<p align='center'><input class='form-control' type='text' size='30' name='search' placeholder='Search...' value='$_GET[search]'> &nbsp;&nbsp;&nbsp;<button class='btn btn-default'  type='submit'><img src='img/search.png' width='20' height='20'>Search</button></p>";

	echo "<p align='center'>
	<input type='hidden' name='file' value='detail'>
	<input type='hidden' name='file_id' value='$_GET[file_id]'>
	<input type='hidden' name='title' value='$_GET[title]'>
	<input type='radio' name='oprator' value='report.project_id'>Project ID &nbsp;&nbsp;
	<input type='radio' name='oprator' value='report.tor_id'>TOR ID &nbsp;&nbsp;
	<input type='radio' name='oprator' value='report.assign_id'>Assignment ID &nbsp;&nbsp;
	<input type='radio' name='oprator' value='report.component'>Component &nbsp;&nbsp;
	<input type='radio' name='oprator' value='report.status_card'>Status &nbsp;&nbsp;
	<input type='radio' name='oprator' value='members.name_ldc'>Member &nbsp;&nbsp;
	<input type='radio' name='oprator' value='report.team'>Team</p><br>";

	echo "</form>";

	if(!empty($_GET['search'])){
		$query_count = mysql_query("SELECT report.file_id FROM report LEFT JOIN members ON report.member_fullname = members.member_fullname WHERE report.file_id='$_GET[file_id]' $oprator ")or die(mysql_error());
		$count=mysql_num_rows($query_count);
		if($count!=0){
			echo "<center>ผลการค้นหามีทั้งหมด $count รายการ</center>";
		}else{
			$count=0;
			echo "<center>ไม่พบรายการที่ค้นหา</center>";
		}	
	}else{
		$count=1;
	}
	if($count!=0){
		echo "<form action='include/export_json.php' method='post'>";
		echo "<input type='hidden' name='title' value='$_GET[title]' >";
		echo "<p align='right'><button class='btn btn-success'  type='submit'><img src='img/excel.png' width='20' height='20'> Export Excel</button></p>";
		echo "<table class='table table-condensed' x:str> ";
			echo "<thead><tr style='color:#444'><th> Number </th><th> ProjectId </th><th> TORID </th><th> AssignID </th><th> Component </th><th> Point </th><th> Status </th><th> Member </th><th> Team </th></thead><tbody>";
		
		$i=1;
		$result = mysql_query("SELECT report.file_id,report.line_id,report.project_id,report.tor_id,report.assign_id,report.component,report.point_card,report.status_card,members.name_ldc,report.team FROM report LEFT JOIN members ON report.member_fullname = members.member_fullname WHERE report.file_id='$_GET[file_id]' $oprator ORDER BY report.line_id ASC")or die(mysql_error());
		while(list($file_id,$number,$pro_id,$tor_id,$ass_id,$card_data,$point,$cards_list,$cards_member,$cards_label)=mysql_fetch_row($result)){
			echo "<td align='center'>$i</td>";
			echo "<td>$pro_id</td>";
			echo "<td>$tor_id</td>";
			echo "<td>$ass_id</td>";
			echo "<td>$card_data</td>";
			echo "<td align='center'>$point</td>";
			echo "<td>$cards_list</td>";
			echo "<td>$cards_member</td>";
			echo "<td>$cards_label</td>";
			echo "<input type='hidden' name='file_id' value='$_GET[file_id]'>";
			echo "<input type='hidden' name='form_search' value='$_GET[search]'>";
			echo "<input type='hidden' name='form_oparator' value='$_GET[oprator]'>";
			echo "<input type='hidden' name='card_data' value='1'>";
			echo "</tr>";
			$i++;
		}
			echo "</tbody>";
		echo "</table></form>";
		echo "</div>";

	}	
}
?>