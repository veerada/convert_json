<?php
echo "<br><br>";
echo "<div class='container-fluid'>";
	echo "<div class='col-md-1'>";
	echo "</div>";
	echo "<div class='col-md-10'>";
		echo "<div class='panel panel-success'>";
		  echo "<div class='panel-heading'><h5><b>IMPORT FILE JSON</b></h5></div>";
		  echo "<div class='panel-body'>";
		  echo "<br>";
		  	echo "<div class='container-fluid'>";
		  		echo "<form method='post' action='index.php?file=insert_json'  enctype='multipart/form-data'>";
		  		echo "<div class='col-md-6'>";
		  			echo "<p align='right'>เลือกไฟล์ที่ต้องการจะแปลงข้อมูล(json) :</p> ";
		  		echo "</div>";
		  		echo "<div class='col-md-6'>";
		  			echo "<input type='file' name='import_file'>";
		  		echo "</div>";
		  		echo "<div class='col-md-12'>";
		  			echo "<font color='#ff6633'><center>***กรุณาเลือกไฟล์เฉพาะ ไฟล์ที่มีนามสกุล .json ที่ได้มาจาก Trello เพียงเท่านั้น***</center></font>";
		  		echo "</div>";
		  			echo "<br><br><br><br>";		
					echo "<p align='center'><input class='btn btn-success' type='submit' name='Submit' value='แปลงไฟล์ข้อมูล'></p>";
				echo "</form>";
			echo "</div>";
		  echo "</div>";
		echo "</div>";
	echo "<div class='col-md-1'>";
	echo "</div>";
echo "</div>";	

?>