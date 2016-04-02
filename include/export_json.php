<?php
$date = (date("d-m-Y"))."_".((date("H"))+6).".".(date("i"));
$file = $_POST['title']."_$date";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename='$file.xls'");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Process Export json</title>
</head>

<body>
<?php
mysql_connect("localhost","root","")or die(mysql_error());
mysql_select_db("history_trello")or die(mysql_error());
mysql_query("SET NAMES utf8");
if(!empty($_POST['card_data'])){
	if(!empty($_POST['form_search'])){
		if(!empty($_POST['form_oprator'])){
			$oprator = "AND $_POST[form_oprator] LIKE '%$_POST[form_search]%'";
		}else{
			$oprator = "AND (report.project_id LIKE '%$_POST[form_search]%' OR report.tor_id LIKE '%$_POST[form_search]%' OR report.assign_id LIKE '%$_POST[form_search]%' OR report.component LIKE '%$_POST[form_search]%' OR report.status_card LIKE '%$_POST[form_search]%' OR members.name_ldc LIKE '%$_POST[form_search]%' OR report.team LIKE '%$_POST[form_search]%')";
		}
	}else{
		$oprator="";
	}

	echo "<table x:str border='1'>";
		echo "<tr style='background:#eee'><th> NO. </th><th> Project </th><th> TOR ID </th><th> Job ID </th><th> Title </th><th> Points </th><th> Members </th><th> Member </th><th> Labels </th><th> Phase </th><th> Tier[M,V,C] </th><th> Fibonacci<br>Point </th><th> Process<br>Number </th><th>Element <br>Number </th><th colspan=3 > Line of code </th><th> Man Hour </th><th> status </th></tr>";
		echo "<tr style='background:#eee'><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th>Actual</th><th>FP</th><th>%Diff</th><th></th><th></th></tr>";
	$i=0;
	$result = mysql_query("SELECT report.file_id,report.line_id,report.project_id,report.tor_id,report.assign_id,report.component,report.point_card,report.status_card,members.member_initials,members.name_ldc,report.team FROM report LEFT JOIN members ON report.member_fullname = members.member_fullname  WHERE report.file_id='$_POST[file_id]' $oprator ORDER BY report.team ASC ,members.member_initials ASC")or die(mysql_error());
		while(list($file_id,$number,$pro_id,$tor_id,$ass_id,$card_data,$point,$cards_list,$member_initials,$cards_member,$cards_label)=mysql_fetch_row($result)){
				echo "</tr>";
				echo "<td>".($i+1)."</td>";
				echo "<td>$pro_id</td>";
				echo "<td>$tor_id</td>";
				echo "<td>$ass_id</td>";
				echo "<td>$card_data</td>";
				echo "<td>$point</td>";
				echo "<td>$member_initials</td>";
				echo "<td>$cards_member</td>";
				echo "<td>$cards_label</td>";
				if($cards_label=="C&#39;Kirk"||$cards_label=="Sulu"){
					echo "<td style='background:#eee'></td>";
					echo "<td style='background:#eee'></td>";
					echo "<td style='background:#eee'></td>";
					echo "<td style='background:#eee'></td>";
					echo "<td style='background:#eee'></td>";
					echo "<td style='background:#eee'></td>";
					echo "<td style='background:#eee'></td>";
					echo "<td style='background:#eee'></td>";
					echo "<td style='background:#eee'></td>";
				}else{
					echo "<td>Code</td>";
					$tier = substr($ass_id,1,1);
					echo "<td>$tier</td>";
					echo "<td>$point</td>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td></td>";
				}

				echo "<td>$cards_list</td>";
				echo "</tr>";
				$i++;
		}
		
		echo "</table>";
		echo "<script> alert('Export data to Excel complate'); window.location='index.php';</script>";
}else{
	echo "<script> alert('Export data to Excel ERROR'); window.location='index.php';</script>";
}
		
	
		
?>
<div></div>
</body>
</html>