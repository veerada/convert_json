<?php
if($_GET['file_id']=="total"){
	$count = mysql_query("SELECT file_id FROM history ")or die(mysql_error());
	$number=mysql_num_rows($count);
	if($number==0){
		echo "<center><h2>ยังไม่มีรายการที่ถูกอัพโหลด</h2></center>";
	}else{

		echo "<div class='container-fluid'>";

			echo "<table class='table table-bordered table-striped table-hover' cellpadding='10' align='center' >";
			echo "<thead style='color:#444'><th ><center>ชื่อไฟล์เก่า</th><th><center>ชื่อไฟล์ใหม่</th><th><center>รายการ</th><th><center>เวลาที่อัพโหลด</th><th><center>ลบ</th></thead>";
			echo "<tbody>";

			$page_history=mysql_query("SELECT * FROM history ORDER BY file_id DESC")or die(mysql_error());
			$count = mysql_num_rows($page_history);
			$total_page = ceil($count/15);
			if(empty($_GET['page_id'])){
				$page_id=1;
				$start_record=0;
			}else{
				$page_id=$_GET['page_id'];
				$start_record=($page_id-1)*15;
			}
			echo "<br><center><h2>รายการที่ถูกอัพโหลดมีจำนวนทั้งหมด $count รายการ</h2></center><br>";
			$history=mysql_query("SELECT * FROM history ORDER BY file_id DESC LIMIT $start_record,15")or die(mysql_error());
			while(list($file_id,$file_original,$file_json,$name,$date_upload)=mysql_fetch_row($history)){
				$number_file_original = strlen($file_original);
				$number_file_json = strlen($file_json);
				if($number_file_json>35){
					$file_json = substr_replace($file_json,"...",35);
				}
				if($number_file_original>30){
					$file_original = substr_replace($file_original,"...",30);
				}
				echo"<tr><td>$file_original</td><td><a href='index.php?file=detail&file_id=$file_id&title=$name'>$file_json</td><td>$name</a></td><td>$date_upload</td><td><center><a href='index.php?file=delete_json&file_id=$file_id' onclick='return confirm(\"คุณต้องการที่จะลบข้อมูลใช่หรือไม่\")'><img src='img/can.png' width='25' height='25'></a></center></td></tr>";
			}
			echo "</tbody>";
			echo "</table>";
			echo "<center>";
			echo "<div class='btn-group' role='group' >";

				echo "<a href='index.php?file=detail&file_id=total&page_id=1' type='button' class='btn btn-default'>หน้าแรก</a>";
				if($page_id!=1){
					$back=$page_id-1;
				}else{
					$back=1;
				}
				echo "<a href='index.php?file=detail&file_id=total&page_id=$back' type='button' class='btn btn-default'><<</a>";
			
			for($i=1;$i<=$total_page;$i++){
				if($page_id==$i){
					echo "<a href='index.php?file=detail&file_id=total&page_id=$i' type='button' class='btn btn-primary'>$i</a>";
				}else{
					echo "<a href='index.php?file=detail&file_id=total&page_id=$i' type='button' class='btn btn-default'>$i</a>";
				}	
			}
				if($page_id!=$total_page){
					$next=$page_id+1;
				}else{
					$next=$total_page;
				}
				echo "<a href='index.php?file=detail&file_id=total&page_id=$next' type='button' class='btn btn-default'>>></a>";
				echo "<a href='index.php?file=detail&file_id=total&page_id=$total_page' type='button' class='btn btn-default'>หน้าสุดท้าย</a>";
			echo "</div>";
			echo "</center>";
		echo "</div>";
		}
		echo "<br><br><br>";
}else{
	if(!empty($_GET['search'])){
		$_GET['search'] = str_replace("'", "&#39;", $_GET['search']);
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
		echo "<table width='100%'>";
		echo "<tr><td><p><a href='index.php?file=point&file_id=$_GET[file_id]'><button type='button' class='btn btn-info'><img src='img/business.png' width='20' height='20'> <b>คะแนน Team performance</b></button></a></p></td>";
		echo "<td><p align='right'><button class='btn btn-success'  type='submit'><img src='img/icon-excel.png' width='20' height='20'> Export Excel</button></p><td></tr>";
		echo "</table>";
		echo "<div class='detail_card' style='width:100%;overflow:auto'>";
		echo "<table class='table table-condensed table-striped' x:str> ";
			echo "<thead><tr style='color:#444' ><th><center> Number </center></th><th><center> ProjectId </center></th><th><center> TORID </center></th><th> <center>AssignID </center></th><th><center> Component </center></th><th><center> Point</center> </th><th><center> Status </center></th><th><center> Member </center></th><th> <center>Team </center></th><th> <center>Edit</center> </center></th></thead><tbody>";
		
		$i=1;
		$result = mysql_query("SELECT report.file_id,report.line_id,report.project_id,report.tor_id,report.assign_id,report.component,report.point_card,report.status_card,members.name_ldc,report.team FROM report LEFT JOIN members ON report.member_fullname = members.member_fullname WHERE report.file_id='$_GET[file_id]' $oprator ORDER BY report.line_id ASC")or die(mysql_error());
		while(list($file_id,$number,$pro_id,$tor_id,$ass_id,$card_data,$point,$cards_list,$cards_member,$cards_label)=mysql_fetch_row($result)){
			echo "<td align='center'>$i</td>";
			echo "<td>$pro_id</td>";
			echo "<td>$tor_id</td>";
			echo "<td>$ass_id</td>";
			$card_data = str_replace(","," , ",$card_data);
			echo "<td>$card_data</td>";
			echo "<td align='center'>$point</td>";
			echo "<td>$cards_list</td>";
			echo "<td>$cards_member</td>";
			echo "<td>$cards_label</td>";
			echo "<td><center><a href='index.php?file=edit_card&file_id=$file_id&number=$number&title=$_GET[title]'><img src='img/draw.png' width='25' height='25'></a><center></td>";
			echo "<input type='hidden' name='file_id' value='$_GET[file_id]'>";
			echo "<input type='hidden' name='form_search' value='$_GET[search]'>";
			echo "<input type='hidden' name='form_oparator' value='$_GET[oprator]'>";
			echo "<input type='hidden' name='card_data' value='1'>";
			echo "</tr>";
			$i++;
		}
			echo "</tbody>";
		echo "</table><div></form>";
		echo "</div>";
	}	
}
?>
