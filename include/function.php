<?php
function connect_db(){
	mysql_connect("localhost","root","")or die(mysql_error());
	mysql_select_db("history_trello")or die(mysql_error());
	mysql_query("SET NAMES utf8");
}

function get_file($file){
	include("include/".$file.".php");

	
}
?>