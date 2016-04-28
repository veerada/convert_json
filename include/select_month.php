
<?php
mysql_connect("localhost","root","")or die(mysql_error());
mysql_select_db("history_trello")or die(mysql_error());
mysql_query("SET NAMES utf8");

$sprint_year = isset($_POST['sprint_year']) ? $_POST['sprint_year'] : "";
$querymonth_history = mysql_query("SELECT MONTH(file_lastview) FROM history WHERE YEAR(file_lastview)='{$sprint_year}' GROUP BY MONTH(file_lastview)")or die(mysql_error());
$Rows = mysql_num_rows($querymonth_history);
if ($Rows > 0) {
	while(list($month_id)=mysql_fetch_row($querymonth_history)){
		$Query = mysql_query("SELECT month_name FROM month WHERE month_id='$month_id'");
		list($month_name)=mysql_fetch_row($Query);
		echo "<option value='$month_id'>$month_name</option>";
	}
}else{
	if($sprint_year=="selectyear"){
		echo "<option>--เลือกเดือน--</option>";
	}else{
		echo "<option>ไม่มีใบงานในปีนี้</option>";
	}
    
}