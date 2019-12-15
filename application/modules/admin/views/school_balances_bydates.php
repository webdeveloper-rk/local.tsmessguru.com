<style>
.alert-success
{
	 color: #FFF;
    background-color: #4CAF50;
    border-color: #ebccd1;
}
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}
.daily table{
	border-collapse: collapse;
	 border: 1px;
}
.daily   td{
	padding:5px;
}

</style>


<?php echo $this->session->flashdata('message');
//print_r($item_info);
?>

<h3>Item   : <span style="color:#0000FF"><?php echo $item_info->telugu_name. " - " .$item_info->item_name ;?></span></h3>
<h4>From   : <span style="color:#0000FF"><?php echo $from_date ;?></span></h4>
<h4>To   : <span style="color:#0000FF"><?php echo $to_date ;?></span></h4>
  <a href="<?php echo site_url('admin/subadmin/itembalancesformdates');?>"><b>Go back</b></a>
 

<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">SNO</th>
			 	
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">District Name</th> 
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">School Name</th> 
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">School Code</th> 
				
				
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Opening Balance</th>
				
				
				
					<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Consumed</th>
				
				
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Closing Balance</th>
				 
				</tr>
                </thead>
                <tbody>
                 <?php 
				// print_a($bs_data_rows);
				  ksort($bs_data_rows);
				   //print_a($bs_data_rows);
				 $no =1 ;
				 foreach($bs_data_rows  as $school_code => $itemdetails) { 

						// print_a($itemdetails);	
			 ?>                <tr role="row" class="odd">
                  <td class="sorting_1"> 
				 <b> <?php echo  $no;// $itemdetails->item_id;?></b></td>
                  
				<td class="sorting_1">  
				 <b> <?php echo   $district_names[$district_ids[$itemdetails->school_id] ] ;?></b></td>
                  
                   <td>
					 <?php echo $school_names[$itemdetails->school_id]; ?> 
				   </td> 
				     <td>
					 <?php echo $school_codes[$itemdetails->school_id]; ?> 
				   </td> 
				   
				    <td>
					 <?php echo $balance_sheet_excel[$itemdetails->school_id]['opening_quantity']; ?> 
				   </td> 
				   
				   
				    <td>
					 <?php echo $balance_sheet_excel[$itemdetails->school_id]['consumed_qty']; ?> 
				   </td> 
				    <td>
					 <?php echo $itemdetails->closing_quantity; ?> 
				   </td> 
				     
                </tr>
				 <?php 
				  $no++;
				 } ?>
				
                </tbody>
                
              </table>
			  
            </div>
            <!-- /.box-body -->
          </div>
		  
	 