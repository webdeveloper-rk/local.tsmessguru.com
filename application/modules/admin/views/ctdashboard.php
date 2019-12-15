<?php if(0) { ?>
<h2>Timings</h2>
<table class='table table-bordered table-striped dataTable no-footer'>
<thead>
<tr><th>Session </th><th>Start Time </th><th>End Time</th></tr>
</thead><tbody>
<?php 
$table_rs = $this->db->query("SELECT name,date_format(ct_start_hour,'%r') as start_hour ,date_format(ct_end_hour,'%r') as end_hour  FROM  food_sessions  ");
foreach($table_rs->result() as $trow) { 

?>
<tr>
	<td><?php echo $trow->name;?></td>
	<td><?php echo $trow->start_hour;?></td>
	<td><?php echo $trow->end_hour;?></td>
<?php } ?>
</tbody>
</table>
<?php } else {?>
<h1>Welcome to Annapurna</h1>
<?php } ?>