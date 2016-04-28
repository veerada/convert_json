<?php
function connect_db(){
	mysql_connect("localhost","root","")or die(mysql_error());
	mysql_select_db("history_trello")or die(mysql_error());
	mysql_query("SET NAMES utf8");
}

function get_file($file){
	include("include/".$file.".php");

	
}
function push_day($day_amount,$date){
	$year = substr($date, "0","4");
	$month = substr($date, "5","2");
	$day = substr($date, "8","2");

	$month_amount = array(31,28,31,30,31,30,31,31,30,31,30,31);
    if($year%4==0){
    	$mount_amount[1]=29;
    }
    $day+=$day_amount;
    $index = $month-1;

    if($day>$month_amount[($month-1)]){
        $day=$month_amount[($month-1)]-$day;
        $month++;
    }
    if($month>12){
        $month=1;
        $year++;
    }
    
    return "$year-$month-$day";

	

}
?>