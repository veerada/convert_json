
<?php
mysql_connect("localhost","root","")or die(mysql_error());
mysql_select_db("history_trello")or die(mysql_error());
mysql_query("SET NAMES utf8");

$sprint_month = isset($_POST['sprint_month']) ? $_POST['sprint_month'] : "";
$sprint_year = isset($_POST['sprint_year']) ? $_POST['sprint_year'] : "";
$querysprint_history = mysql_query("SELECT sprint_name,sprint_id,start_sprint FROM sprint WHERE sprint_year='{$sprint_year}' AND month_id='{$sprint_month}'")or die(mysql_error());
$Rows = mysql_num_rows($querysprint_history);
if ($Rows > 0) {
	while(list($sprint_name,$sprint_id,$start_sprint)=mysql_fetch_row($querysprint_history)){
		echo "<option value='$sprint_name'>$sprint_name</option>";
	}
}else{
	if($sprint_month=="selectmonth"){
		echo "<option>--เลือก Sprint--</option>";
	}else{
		echo "<option>ไม่มีใบงานในเดือนนี้</option>";
	}
    
}