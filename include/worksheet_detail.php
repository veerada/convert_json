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
	if(!empty($_POST['chk_blacklog'])){
		$chk_blacklog =$_POST['chk_blacklog'];
		if($num_chk>0){
			$chk .=" OR ";
			$status .= " , ";
		}
		$num_chk++;
		$chk .= "status_card='$chk_blacklog'";
		$status .= $chk_blacklog;

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
	}
	$chk.=")";
	if(!empty($_POST['chk_all'])){
		$chk = "";
		$status = "Blacklog,Checkin,Doing,Testing,Done";
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

	$q_card = mysql_query("SELECT project_id,tor_id,assign_id,component,point_card,status_card FROM report WHERE file_id='$file_id' AND member_fullname='$member_fullname' $chk ORDER BY status_card='Done',status_card='Tester',status_card='Doing',status_card='Checkin',status_card='Blacklog'")or die(mysql_error());

	$rows = mysql_num_rows($q_card);
	if(empty($rows)){
		echo "<center><h4><b>ใน sprint นี้สถานะ $status ไม่มีใบงานของ $member_name</b></h4></center><br>";
	}else{
		echo "<center><h4><b>สถานะใบงาน $status ของ $member_name</b></h4></center><br>";
		while (list($project_id,$tor_id,$assign_id,$component,$point_card,$status_card)=mysql_fetch_row($q_card)) {
			
?>
<div class="container-fluid">
	<div class="col-md-1"></div>
	<div class="col-md-10">
	สถานะใบงาน : <?php echo "$status_card";  ?>
		<table class="table table-bordered"  align="center" border="1" >
			<tr><td rowspan="2"> <img src="img/logo_small_size.png" /> </td>
					<td colspan="5" >  Worksheet </td>
			</tr>
			<tr>
					<td colspan="5">Sapphire Research and Development Co., Ltd.</td>
			</tr>
			<tr>
					<td colspan="6"  ><h3> Worksheet </h3></td>
			</tr>
			<tr>
					<td colspan="6"> <h5>Sapphire Research and Development Co., Ltd.</h5></td>
			</tr>
			<tr height="20">
					<td colspan ="3"></td>
			        <td> Date </td>
			        <td colspan="2" ></td>
			</tr>
			<tr height="20">
					<td>Worksheet No</td>
			        <td colspan="2" ><?php echo "$assign_id"; ?></td>
			        <td>Project Code</td>
			        <td colspan="2" ><?php echo "$project_id"; ?></td>
			</tr>
			<tr>
					<td>Assign To</td>
			        <td colspan="2"><?php echo "$member_name"; ?></td>
			        <td>TOR ID</td>
			        <td colspan="2"><?php echo "$tor_id"; ?></td>
			</tr>
			<tr>
					<td>Assignment</td>
			        <td colspan="2"></td>
			        <td>ผู้ตรวจงาน</td>
			        <td colspan="2"></td>
			</tr>
			<tr>
					<td>Level (1,2,3,…..9) </td>
			        <td colspan="2"><?php echo "$point_card"; ?></td>
			        <td>Module</td>
			        <td colspan="2"></td>
			</tr>
			<tr>
					<td>System Name</td>
			        <td colspan="2"></td>
			        <td rowspan="3">Effect</td>
			        <td colspan="2" >No effect</td>
			</tr>
			<tr>
					<td colspan="3"></td>
			        <td colspan="2">effect</td>
			</tr>
			<tr>
					<td>Root Cause</td>
			        <td colspan="2" height="20"></td>
			        <td colspan="3" height="20"></td>
			</tr>
			<tr>
					<td >Path/Link (Optional)</td>
			        <td colspan="2" height="20"></td>  
			        <td>Line Of Code (LOC)</td>
			        <td colspan="2" height="20"></td>
			</tr>
			<tr>
					<td colspan="3" height="20"></td>
			        <td>Process</td>
			        <td colspan="2" height="20"></td>
			</tr>
			<tr>
					<td colspan="3" height="20"></td>
			         <td>Element</td>
			        <td colspan="2" height="20"></td>
			</tr>
			<tr>
					<td colspan="6" al align="center">Planned</td>
			</tr>
			<tr>
					<td width="16.66%">Start Date</td>
					<td width="16.66%"></td>
			        <td width="16.66%">Finish Date</td>
			        <td width="16.66%"></td>
			        <td width="16.66%">Duration</td>
			        <td width="16.66%"></td>
			</tr>
			<tr>
					<td colspan="6" align="center">Actual</td>
			</tr>
			<tr>
					<td width="16.66%">Start Date</td>
					<td width="16.66%"></td>
			        <td width="16.66%">Finish Date</td>
			        <td width="16.66%"></td>
			        <td width="16.66%">Duration</td>
			        <td width="16.66%"></td>
			</tr>
					<td>Description</td>
					<td colspan="5"><?php echo "$component"; ?></td>
			</tr>

			</tr>
					<td>Description of done</td>
					<td colspan="5"></td>
			</tr>
			</tr>
					<td rowspan="3">ระดับความเข้าใจ</td>
					<td colspan="2">1. เข้าใจบ้าง ไม่เข้าใจบ้าง</td>
			      	<td colspan="3">4. เข้าใจเลย</td>
			</tr>
			</tr>
					<td colspan="2">2. เข้าใจและพอทาได้</td>
			      	<td colspan="3">5. เข้าใจมาก และมีส่วนร่วมในการออกแบบ</td>
			</tr>
			</tr>
					<td colspan="5">3. เข้าใจเป็นส่วนใหญ่</td>
			</tr>
		</table>
	</div>
</div>


<?php
		}
	}
}

?>
