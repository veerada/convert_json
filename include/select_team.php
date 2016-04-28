<?php

mysql_connect("localhost","root","")or die(mysql_error());
mysql_select_db("history_trello")or die(mysql_error());
mysql_query("SET NAMES utf8");

$member = isset($_POST['member']) ? $_POST['member'] : "";
if($member=="nomember"){
	echo "ไม่พบข้อมูล";
}else{
	$query_team = mysql_query("SELECT team FROM members WHERE member_fullname='{$member}'")or die(mysql_error());
	$Rows = mysql_num_rows($query_team);
	if ($Rows > 0) {
		while(list($team)=mysql_fetch_row($query_team)){
			$team = str_replace("&#39;","'",$team);
			echo "$team";
		}
	}
}
?>