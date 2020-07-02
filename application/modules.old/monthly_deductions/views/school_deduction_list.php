  
  <style>  .red  {		color:#FF0000;  }  </style> <div class='table-responsive'><h3> <span class='red'><b><?php echo $school_name; ;?> <span class='red'><b><?php echo $month_year_text; ;?> </span>   </span></h3>
  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
	  
	<tr>
		<td>
			<table id="example2"  class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example2_info"> 
				 
				<tr class='bold'>
					<td aria-controls="example1">SLNO</td>
					
					<td aria-controls="example1">Date</td>					 
					 <td aria-controls="example1">Item Name</td>					 <td aria-controls="example1">Deduction Amount</td>					  
					
					 
				</tr>
				<?php $i=1; 
				$total_amount = 0;
				foreach($deduction_rs->result() as   $deduction_data){ 				 									 //print_a($school_data,1);?>
							<tr > 
							<td><?php echo $i;?></td> 
							<td><?php echo $deduction_data->edate;?></td>							<td><?php echo $deduction_data->item_name;?></td> 							 							<td><?php echo  number_format( $deduction_data->amount,2) ;?></td> 							 </tr>
				<?php $i++; } ?>
				 
			</table>
		
		
		
		
		
		</td></tr>
		

</table></div> 