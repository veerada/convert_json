<?php
if($_POST['sprint_month']=="selectmonth"||$_POST['sprint_year']=="selectyear"||empty($_POST['sprint_month'])||empty($_POST['sprint'])||empty($_POST['oparator_name'])||(empty($_POST['chk_blacklog'])&&empty($_POST['chk_checkin'])&&empty($_POST['chk_doing'])&&empty($_POST['chk_testing'])&&empty($_POST['chk_done'])&&empty($_POST['chk_all']))){
	echo "<script>alert('กรุณาเลือกเงื่อนไขให้ครบด้วย ขอบคุณ');window.location='index.php?file=list_worksheet'</script>";
}else{
	$sprint_year = $_POST['sprint_year'];
	$sprint_month = $_POST['sprint_month'];
	$sprint = $_POST['sprint'];
	$chk =" AND (";
	$status ="";
	$num_chk=0;
	$array_status = array();
	if(!empty($_POST['chk_blacklog'])){
		$chk_blacklog =$_POST['chk_blacklog'];
		if($num_chk>0){
			$chk .=" OR ";
			$status .= " , ";
		}
		$num_chk++;
		$chk .= "status_card='$chk_blacklog'";
		$status .= $chk_blacklog;
		array_push($array_status, $_POST['chk_blacklog']);
	}
	if(!empty($_POST['chk_checkin'])){
		$chk_checkin =$_POST['chk_checkin'];
		if($num_chk>0){
			$chk .=" OR ";
			$status .= " , ";
		}
		$num_chk++;
		$chk .= "status_card='$chk_checkin'";
		$status .= $chk_checkin;
		array_push($array_status, $_POST['chk_checkin']);
	}
	if(!empty($_POST['chk_doing'])){
		$chk_doing =$_POST['chk_doing'];
		if($num_chk>0){
			$chk .=" OR ";
			$status .= " , ";
		}
		$num_chk++;
		$chk .= "status_card='$chk_doing'";	
		$status .= $chk_doing;
		array_push($array_status, $_POST['chk_doing']);
	}
	if(!empty($_POST['chk_testing'])){
		$chk_testing =$_POST['chk_testing'];
		if($num_chk>0){
			$chk .=" OR ";
			$status .= " , ";
		}
		$num_chk++;
		$chk .= "status_card='$chk_testing'";
		$status .= $chk_testing;
		array_push($array_status, $_POST['chk_testing']);
	}
	if(!empty($_POST['chk_done'])){
		$chk_done =$_POST['chk_done'];
		if($num_chk>0){
			$chk .=" OR ";
			$status .= " , ";
		}
		$num_chk++;
		$chk .= "status_card='$chk_done'";
		$status .= $chk_done;
		array_push($array_status, $_POST['chk_done']);
	}
	$chk.=")";
	if(!empty($_POST['chk_all'])){
		$chk = "";
		$status = "Blacklog,Checkin,Doing,Testing,Done";
		array_push($array_status, $_POST['chk_all']);
	}
	$oparator_name =$_POST['oparator_name'];

	echo "<br>";
	$q_member = mysql_query("SELECT name_ldc,member_fullname FROM members WHERE member_id='$oparator_name'")or die(mysql_error());
	list($member_name,$member_fullname)=mysql_fetch_row($q_member);

	$q_sprint =mysql_query("SELECT start_sprint,end_sprint FROM sprint WHERE sprint_name='$sprint' AND sprint_year='$sprint_year' AND month_id='$sprint_month'")or die(mysql_error());
	list($start_sprint,$end_sprint)=mysql_fetch_row($q_sprint);
	
	$end_sprint = push_day(2,$end_sprint);

	$q_history =mysql_query("SELECT file_id FROM history WHERE file_lastview BETWEEN '$start_sprint' AND '$end_sprint' ORDER BY file_lastview DESC")or die(mysql_error());
	list($file_id)=mysql_fetch_row($q_history);
	if(empty($file_id)){
		$file_id = "0";
	}
	echo "<div class='container-fluid'>";
	$q_card = mysql_query("SELECT project_id,line_id,tor_id,assign_id,component,point_card,status_card FROM report WHERE file_id='$file_id' AND member_fullname='$member_fullname' $chk ORDER BY status_card='Done',status_card='Tester',status_card='Doing',status_card='Checkin',status_card='Blacklog'")or die(mysql_error());

	$rows = mysql_num_rows($q_card);
	if(empty($rows)){
		echo "<center><h4><b>ใน sprint นี้สถานะ $status ไม่มีใบงานของ $member_name</b></h4></center><br>";
	}else{
		echo "<table>";
			echo "<tr><td><font size='3'><b><p align='right'>สัปดาห์ที่ : </p></b></font></td><td><font size='3'><p> &nbsp;&nbsp;$_POST[sprint]</p></font></td></tr>";
			echo "<tr><td><font size='3'><b><p align='right'>เจ้าของใบงาน : </p></b></font></td><td><font size='3'><p> &nbsp;&nbsp;$member_name</p></font></td></tr>";
			echo "<tr><td><font size='3'><b><p align='right'>สถานะใบงาน : </p></b></font></td><td><font size='3'><p> &nbsp;&nbsp;$status</p></font></td></tr>";
		echo "</table>";
	echo "<form action='include/worksheet_pdf.php' method='post' target='_blank'>";
		echo "<input type='hidden' name='sprint_month' value='$_POST[sprint_month]'>";
		echo "<input type='hidden' name='sprint_year' value='$_POST[sprint_year]'>";
		echo "<input type='hidden' name='sprint' value='$_POST[sprint]'>";
		echo "<input type='hidden' name='oparator_name' value='$_POST[oparator_name]'>";
		foreach ($array_status as  $value) {
			echo "<input type='hidden' name='status[]' value='$value'>";
		}	
		echo "<p align='right'><button type='submit' class='btn btn-sm btn-danger' ><img src='img/pdf.png' height='20px'> แสดงใบงานทั้งหมด</button></p>";
	echo "</form>";
		echo "<table class='table table-bordered'>";
		$list_status="";
		while (list($project_id,$line_id,$tor_id,$assign_id,$component,$point_card,$status_card)=mysql_fetch_row($q_card)) {
			$status_card =empty($status_card)?"":$status_card;
			if($list_status!=$status_card){
				echo "<form action='include/worksheet_pdf.php' method='post' target='_blank'>";
				echo "<input type='hidden' name='sprint_month' value='$_POST[sprint_month]'>";
				echo "<input type='hidden' name='sprint_year' value='$_POST[sprint_year]'>";
				echo "<input type='hidden' name='sprint' value='$_POST[sprint]'>";
				echo "<input type='hidden' name='oparator_name' value='$_POST[oparator_name]'>";
				echo "<input type='hidden' name='status[]' value='$status_card'>";
				echo "<tr style='background:#eee'><td><b><font size='4'>สถานะใบงาน</font></b></td><td colspan='3'><font size='4'>$status_card</font></td><td><button type='submit' class='btn btn-sm btn-danger' ><img src='img/pdf.png' height='20px' ><font style='margin-right:21px'> แสดงใบงานของ $status_card</font><a/button></td></tr>";
				$list_status=$status_card;
				echo "</form>";
			}
			echo "<tr>";
				echo "<form action='include/worksheet_pdf.php' method='post' target='_blank'>";
				echo "<input type='hidden' name='file_id' value='$file_id'>";
				echo "<input type='hidden' name='line_id' value='$line_id'>";
				echo "<td><b>รหัสโปรเจค</b></td><td>$project_id</td><td><b>รหัสใบงาน</b></td><td>$assign_id</td><td><button type='submit' class='btn btn-sm btn-danger' ><img src='img/pdf.png' height='20px'> แสดงใบงานในรูปแบบ PDF</button></td>";
				echo "</form>";
			echo "</tr>";
			
		}
		echo "</table>";
	}
	echo "</div>";
}

?>
