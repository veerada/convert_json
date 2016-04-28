<br>
<div class="container-fluid">
	<div class="panel panel-success">
		<div class="panel-heading"><h3><b>เลือกรายการในการดูใบงาน</h3></b></div>
	 	<div class="panel-body">

	 	<div class="panel panel-warning">
		  <div class="panel-heading"><h4 style="margin:0px 5px;"><b>การตั้งค่า sprint</b></h4></div>
		  <div class="panel-body">
		  <?php
		    $query_start_sprint = mysql_query("SELECT DAY(start_date),MONTH(start_date),YEAR(start_date),day_start FROM start_date");
		    list($day,$start_month,$start_year,$start_day)=mysql_fetch_row($query_start_sprint);
		    $start_year+=543;

		    $query_month = mysql_query("SELECT month_id,month_name FROM month")or die(mysql_error());
		    while(list($month_id,$month_name)=mysql_fetch_row($query_month)){
		    	if($start_month==$month_id){
		    		$month = $month_name;
		    	}
		    }
		    echo "<b>ปีที่มีการเพิ่มไปแล้ว : </b>";
		    $query_year = mysql_query("SELECT sprint_year FROM sprint GROUP BY sprint_year")or die(mysql_error());
		    $num_sprint_year = mysql_num_rows($query_year);
		    if(!empty($num_sprint_year)){
		    	while(list($sprint_year)=mysql_fetch_row($query_year)){
		    		echo "$sprint_year&nbsp;&nbsp;";
		    	}
		    }else{
		    	echo "ไม่มีการเพิ่ม sprint ";
		    }
		    
		    //Sprint1 เริ่มต้นวัน $start_day ที่ $day เดือน $month พ.ศ. $start_year
		    echo " <br><input class='btn btn-sm btn-warning' type='button' data-toggle='modal' data-target='#edit_start_sprint' value='เพิ่มการเริ่มต้น sprint ตามปี'>";
		  ?>
		  	<div class="modal fade" id="edit_start_sprint" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="myModalLabel">แก้ไขวันเริ่มต้นการทำงานในสัปดาห์แรก</h4>
			      </div>
			      <div class="modal-body">
			      	<form action="index.php?file=update_sprint" method="post">
			        	เลือกวันที่เริ่มต้น : <input type="text" name="set_start_sprint" id="datepicker" />
			        	<input type="hidden" name='setday_start_sprint' id="alternate">
			        	<p align='right'><input type='submit' value="แก้ไขข้อมูล"></p>
			        </form>
			      </div>
			    </div>
			  </div>
			</div>
		  </div>
		</div>
	 	<form class="form-inline" action="index.php?file=worksheet_detail" method="post">
	    	<h4><b>เลือกรายการที่ต้องการดู</b></h4>
    		<div class="form-group">
    			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;พ.ศ. : 
				<select name='sprint_year' id='sprint_year' class="form-control"><option value="selectyear">--เลือกปี--</option>
    			<?php
    				$query_year=mysql_query("SELECT YEAR(date_upload) FROM history GROUP BY YEAR(date_upload)")or die(mysql_error());
    				while(list($year)=mysql_fetch_row($query_year)){
    					echo "<option value='$year' >$year</option>";
    				}
    			?>
    			</select>
	    		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;เดือน : 
    			<select name='sprint_month' id='sprint_month' class="form-control" ><option value="selectmonth">--เลือกเดือน--</option>
    			</select>
    			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;สัปดาห์ : 
    			<select name='sprint' id='sprint' class="form-control"><option >--เลือกสัปดาห์--</option>
	    		</select>
    		</div>
	    		
	    	<br><br>
	    	<h4><b>เลือกประเภทใบงาน</b></h4>
	    		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name='chk_blacklog' value="Blacklog">&nbsp;&nbsp;Blacklog
	    		&nbsp;&nbsp;&nbsp;<input type="checkbox" name='chk_checkin' value="Checkin">&nbsp;&nbsp;CheckIn
	    		&nbsp;&nbsp;&nbsp;<input type="checkbox" name='chk_doing' value="Doing">&nbsp;&nbsp;Doing
	    		&nbsp;&nbsp;&nbsp;<input type="checkbox" name='chk_testing' value="Tester">&nbsp;&nbsp;Testing
	    		&nbsp;&nbsp;&nbsp;<input type="checkbox" name='chk_done' value="Done">&nbsp;&nbsp;Done
	    		&nbsp;&nbsp;&nbsp;<input type="checkbox" name='chk_all' value="All">&nbsp;&nbsp;All
	    	<br><br>	
	    	<h4><b>เลือกผู้ทำใบงาน</b></h4>
	    		<table class='table table-striped ' align="center" style="width:90%;" >
	    		<tr>
<?php
				$number = 1;
				$sql = mysql_query("SELECT * FROM members")or die(mysql_error());
				while(list($member_id,$member_initials,$member_name,$member_loc)=mysql_fetch_row($sql)){
					echo "<td style='padding:10px;'><input type='radio' name='oparator_name' value='$member_id'>$member_loc</td>";
					if($number%4==0){
						echo "</tr><tr>";
					}
					$number++;	

				}
?>
				</table>
				<br>
				<center><input class='btn btn-success' type='submit' value="ค้นหา"> &nbsp;<a href='index.php'><input class='btn btn-danger' type='button' value="ยกเลิก"></a></center>
	  	</form>
	  	</div>
	</div>
</div>

