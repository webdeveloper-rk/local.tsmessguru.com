 <?php
 $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");
 ?><style>
					.atttable th {
									padding-top: 11px;
									padding-bottom: 11px;
									background-color: #4CAF50;
									color: white;
									border: 1px solid #ddd;
									text-align: left;
									padding: 8px;
					}
					.atttable tr:nth-child(even) {
									background-color: #f2f2f2;
					}

					.atttable { 	
									font-family: Verdana, Geneva, sans-serif;
									font-size: 11px;
					}
					.atttable td {
									padding:10px;
					}

					@media print{

.atttable th {
									padding-top: 11px;
									padding-bottom: 11px;
									background-color: #4CAF50;
									color: white;
									border: 1px solid #ddd;
									text-align: left;
									padding: 8px;
					}
					.atttable tr:nth-child(even) {
									background-color: #f2f2f2;
					}

					.atttable { 	
									font-family: Verdana, Geneva, sans-serif;
									font-size: 11px;
					}
					.atttable td {
									padding:10px;
					}
					}					
		</style> 
		
		<h1> Missed Month Report : <?php echo $report_date;?><table class='atttable'>
<thead>
<tr>
<th>SNO </th>
<th>School Name</th>
<th>Missed Days Count</th>
 <!--<th>Missed Days</th> -->
</tr>

<?php $i=1; foreach($days_list as $school_id=>$school_data)
{
	if($school_data['not_used_count']==0)
			continue;
?>
<tr>
<td><?php echo $i;?></td>
<td><?php echo $school_data['school'];?></td>
<td><?php echo $school_data['not_used_count'];?></td>
 <!--<td><?php 
 $missed_days_array = array();
 foreach($school_data['days'] as $date=>$usedqty)
 {
	 if($usedqty==0)
		$missed_days_array[] = $date;
 }
 
echo implode("<br>",$missed_days_array);?> </td> -->
</tr>

<?php  $i++;
}

?>
</table>
