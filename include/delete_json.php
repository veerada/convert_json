<?php
	
	mysql_query("DELETE  FROM history WHERE file_id='$_GET[file_id]'")or die(mysql_error());
	mysql_query("DELETE  FROM report WHERE file_id='$_GET[file_id]'")or die(mysql_error());

	echo "<script>alert('ลบข้อมูลออกจากฐานข้อมูลแล้ว');window.location='index.php?file=detail&file_id=total';</script>";

?>