<?php
	include("function.php");
	require_once('../mpdf/mpdf.php');
	connect_db();
	ob_start();
?>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">

<style type="text/css">
<!--
@page rotated { size: landscape; }
.style1 {
	font-family: "TH SarabunPSK";
	font-size: 16pt;
	font-weight: bold;
}
.style2 {
	font-family: "TH SarabunPSK";
	font-size: 14pt;
	font-weight: bold;
}
.style3 {
	font-family: "TH SarabunPSK";
	font-size: 14pt;
	
}
.style5 {cursor: hand; font-weight: normal; color: #000000;}
.style9 {font-family: Tahoma; font-size: 12px; }
.style11 {font-size: 12px}
.style13 {font-size: 9}
.style16 {font-size: 9; font-weight: bold; }
.style17 {font-size: 12px; font-weight: bold; }
-->

</style>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body>
<?php

	if(empty($_POST['file_id'])&&empty($_POST['line_id'])){

	$sprint_year = $_POST['sprint_year'];
	$sprint_month = $_POST['sprint_month'];
	$sprint = $_POST['sprint'];
	$chk =" AND (";
	$num_chk=0;
	$all =0;

	foreach ($_POST['status'] as $value) {
		if($value!="All"){
			if($num_chk>0){
				$chk .= " OR ";
			}
			$chk .= "status_card='$value'";
			$num_chk++;
		}else{
			$all = 1;
		}
		
	}
	$chk.=")";
	if($all!=0){
		$chk="";
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
	}else{
	$q_card = mysql_query("SELECT project_id,tor_id,assign_id,component,point_card,status_card FROM report WHERE file_id='$_POST[file_id]' AND line_id='$_POST[line_id]'")or die(mysql_error());
	}
	$rows = mysql_num_rows($q_card);
	if(empty($rows)){
	}else{
		while (list($project_id,$tor_id,$assign_id,$component,$point_card,$status_card)=mysql_fetch_row($q_card)) {
	echo "$_POST[file_id] $_POST[line_id]";
?>
<table width="100%" cellspacing="0" border='1' cellpadding="5" style="font-size:12px;margin-top:-50px;">
	<tr>
		<td rowspan="2"><img src="../img/logo.png" width="100px" /> </td>
		<td colspan="5">Worksheet</td>
	</tr>
	<tr>
		<td colspan="5">Sapphire Research and Development Co., Ltd. </td>
	</tr>
	<tr>
		<td colspan="6" style="border-bottom:0px;"><b><font size='5' >Worksheet</font></b></td>
	</tr>
	<tr>
		<td colspan="6" style="border-top:0px;border-bottom:0px;"><b>Sapphire Research and Development Co., Ltd.</b></td>
	</tr>
	<tr>
		<td colspan="3" style="border-top:0px"></td>
		<td style="background:#ddd;border-top:2px solid black">Date</td>
		<td colspan="2" style="border-top:2px solid black"></td>
	</tr>
	<tr>
		<td style="background:#ddd">Worksheet No.</td>
		<td colspan="2"><?php echo "$assign_id"; ?></td>
		<td style="background:#ddd">Project Code</td>
		<td colspan="2"><?php echo "$project_id"; ?></td>
	</tr>
	<tr>
		<td style="background:#ddd">Assign To</td>
		<td colspan="2"><?php echo "$member_name"; ?></td>
		<td style="background:#ddd">TOR ID</td>
		<td colspan="2"><?php echo "$tor_id"; ?></td>
	</tr>
	<tr>
		<td style="background:#ddd">Assignment </td>
		<td colspan="2"></td>
		<td style="background:#ddd">ผู้ตรวจงาน</td>
		<td colspan="2"></td>
	</tr>
	<tr>
		<td style="background:#ddd">Level (1,2,3,…..9) </td>
		<td colspan="2"><?php echo "$point_card"; ?></td>
		<td style="background:#ddd">Module</td>
		<td colspan="2"></td>
	</tr>
	<tr>
		<td rowspan="2" valign='top' style="background:#ddd">System Name </td>
		<td colspan="2" rowspan="2"></td>
		<td rowspan="4" valign='top' style="background:#ddd">Effect </td>
		<td colspan="2"><input type="checkbox"> No effect</td>
	</tr>
	<tr>
		<td colspan="2"><input type="checkbox"> effect</td>
	</tr>
	<tr>
		<td valign='top' style="background:#ddd" height="100px">Root Cause</td>
		<td colspan="2"></td>
		<td colspan="2" style="border-bottom:0px"><p>&nbsp;</p></td>
	</tr>
	<tr>
		<td rowspan="4" valign='top' style="background:#ddd">Path/Link (Optional)</td>
		<td colspan="2" rowspan="4"></td>
		<td colspan="2" style="border-top:0px"><p>&nbsp;</p></td>
	</tr>
	<tr>
		<td style="background:#ddd">Line of code</td>
		<td colspan="2"></td>
	</tr>
	<tr>
		<td style="background:#ddd">Process</td>
		<td colspan="2"></td>
	</tr>
	<tr>
		<td style="background:#ddd">Element</td>
		<td colspan="2"></td>
	</tr>
	<tr>
		<td colspan="6" style="background:#999"><center>Planned</center></td>
	</tr>
	<tr>
		<td style="background:#ddd">Start Date </td>
		<td style="background:#FFFF99"></td>
		<td style="background:#ddd">Finish Date </td>
		<td style="background:#FFFF99"></td>
		<td style="background:#ddd">Duration</td>
		<td style="background:#FFFF99"></td>
	</tr>
	<tr>
		<td colspan="6" style="background:#999"><center>Actual</center></td>
	</tr>
	<tr>
		<td width="16.66%" style="background:#ddd">Start Date </td>
		<td width="16.66%" style="background:#FFFF99"></td>
		<td width="16.66%" style="background:#ddd">Finish Date </td>
		<td width="16.66%" style="background:#FFFF99"></td>
		<td width="16.66%" style="background:#ddd">Duration</td>
		<td width="16.66%" style="background:#FFFF99"></td>
	</tr>
	<tr>
		<td  style="background:#ddd" height="60px">Description</td>
		<td valign="top" colspan="5"><?php echo "$component"; ?></td>
	</tr>
	<tr>
		<td style="background:#ddd" height="60px">Definition of done</td>
		<td colspan="5"></td>
	</tr>
	<tr>
		<td rowspan="3" style="background:#ddd">ระดับความเข้าใจ</td>
		<td colspan="2"><input type="checkbox"> 1. เข้าใจบ้าง ไม่เข้าใจบา้ง</td>
		<td colspan="3"><input type="checkbox"> 4. เข้าใจเลย</td>
	</tr>
	<tr>
		<td colspan="2"><input type="checkbox"> 2. เข้าใจและพอทำได้</td>
		<td colspan="3"><input type="checkbox"> 5. เข้าใจมาก และมีส่วนร่วมในการออกแบบ</td>
	</tr>
	<tr>
		<td colspan="5"><input type="checkbox"> 3. เข้าใจเปน็ส่วนใหญ่</td>
	</tr>
</table>
<table  cellpadding="5" width="100%">
	<tr>
		<td><center>Assignment</center></td>
		<td width="25%"></td>
		<td><center>Assign To</center></td>
	</tr>
	<tr>
		<td><center>......................................................</center></td>
		<td></td>
		<td><center>......................................................</center></td>
	</tr>
	<tr>
		<td><center>(.................................................)</center></td>
		<td></td>
		<td><center>(.................................................)</center></td>
	</tr>
	<tr>
		<td><center>Team Leader</center></td>
		<td width="25%"></td>
		<td><center>Project Manager</center></td>
	</tr>
	<tr>
		<td><center>......................................................</center></td>
		<td></td>
		<td><center>......................................................</center></td>
	</tr>
	<tr>
		<td><center>(.................................................)</center></td>
		<td></td>
		<td><center>(.................................................)</center></td>
	</tr>
</table>

<?php
		}
	}


?>

</body>
</html>
<?Php
$html = ob_get_contents();
ob_end_clean();
$pdf = new mPDF('th', 'A4', '0', 'THSaraban');
$pdf->SetAutoFont();
$pdf->SetDisplayMode('fullpage');
$pdf->WriteHTML($html, 2);
$pdf->Output();
?>     