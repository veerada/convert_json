<?php


	$substr_year=substr($_POST['set_start_sprint'], 0, 4);

	if($substr_year%4==0){
		$queryupdate_month = mysql_query("UPDATE month SET day_amount='29' WHERE month_id ='2'")or die(mysql_error()); 
	}else{
		$queryupdate_month = mysql_query("UPDATE month SET day_amount='28' WHERE month_id ='2'")or die(mysql_error());
	}

	$query_start_day=mysql_query("SELECT start_date_id FROM start_date WHERE YEAR(start_date)='$substr_year'")or die(mysql_error());
	$num_start_day = mysql_num_rows($query_start_day);

	$day = array("Monday"=>"วันจันทร์","Tuesday"=>"วันอังคาร","Wednesday"=>"วันพุธ","Thursday"=>"วันพฤหัสบดี","Friday"=>"วันศุกร์","Saturday"=>"วันเสาร์","Sunday"=>"วันอาทิตย์");
	foreach ($day as $key => $value) {
		if($_POST['setday_start_sprint']==$key){
			$_POST['setday_start_sprint']=$value;
		}
	}

	if(empty($num_start_day)){
		mysql_query("INSERT INTO start_date VALUES('','$_POST[set_start_sprint]','$_POST[setday_start_sprint]')")or die(mysql_error());
	}else{
		mysql_query("UPDATE start_date SET start_date='$_POST[set_start_sprint]', day_start='$_POST[setday_start_sprint]' WHERE YEAR(start_date)='$substr_year'")or die(mysql_error());
	}

	

	$query_start_date = mysql_query("SELECT DAY(start_date),MONTH(start_date),YEAR(start_date) FROM start_date WHERE YEAR(start_date)='$substr_year'")or die(mysql_error());
	list($day,$month,$year)=mysql_fetch_row($query_start_date);

	$query_sprint=mysql_query("SELECT * FROM sprint WHERE sprint_year='$year'")or die(mysql_error());
	$num_sprint = mysql_num_rows($query_sprint);
	if(empty($num_sprint)){
		$insert_sprint ="INSERT INTO sprint VALUES";
		$start_sprint =1;
		$end_loop=1;
			
		while( $end_loop == 1){
			$query_month = mysql_query("SELECT day_amount FROM month WHERE month_id = '$month' ")or die(mysql_error());
			list($day_amount)=mysql_fetch_row($query_month);
			
			
			if($end_loop==1){
				if($day > $day_amount){
					$day -= $day_amount;
					$month++;
					
				}

				$insert_sprint.="('','Sprint$start_sprint','$month','$year','$year-$month-$day',";
				$day+=5;
				if($day > $day_amount){
					$day -= $day_amount;
					$month++;
					if($month>12){
						$year++;
						$month=1;
						$end_loop=0;	
					}
				}
				$insert_sprint.="'$year-$month-$day')";
				if($end_loop==0){
					$month=13;
				}
				if($month<12){
					$insert_sprint.=",";
				}elseif($month==12&&$day!=31){
					$insert_sprint.=",";
				}
				if($month==12&&$day==31){
					$end_loop=0;
				}
				$day+=2;
				$start_sprint++;

			}
			
			
		}
		mysql_query($insert_sprint)or die(mysql_error());

	}else{
		echo "<script>alert('เคยทำการบันทึกไปแล้ว'),window.location='index.php?file=list_worksheet'</script>";
	}

	echo "<script>alert('บันทึกวันเริ่มต้นสัปดาห์แรก เรียบร้อย'),window.location='index.php?file=list_worksheet'</script>";




?>