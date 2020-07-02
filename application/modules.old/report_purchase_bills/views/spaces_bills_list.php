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
.rowred td{
	color:#FF0000;
	font-weight:bold;
}
</style>


<?php echo $this->session->flashdata('message');?>

<h3>Purchase Bills[ <?php echo $report_date;?> ] </h3>
<b> Total schools count : <?php echo $rset->num_rows();?></b> <a href="<?php echo site_url('report_purchase_bills/spaces_bills');?>"><b>Go back</b></a>
 

<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">School Name</th>
				<!--<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Browser: activate to sort column ascending" style="width: 159px;">Current Balance</th>-->
				 
				
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">School Code</th> 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Total Items</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Total Uploads</th>
				 
				</tr>
                </thead>
                <tbody>
                 <?php 
				 
				 foreach($rset->result() as $school) {  ?>                <tr role="row " class="odd <?php if($uploads[$school->school_id]=="" || $uploads[$school->school_id]==0){ echo " rowred ";}?>">
                  <td class="sorting_1"> 
				 <b> <?php echo $school->name;?></b></td>
                  
				
                   <td>
					 <?php echo $school->school_code; ?> 
				   </td> 
				    <td>
					 <?php 
					 
					 // echo $school->school_id,"--";
					 echo isset($item_counts[$school->school_id])?$item_counts[$school->school_id]:0;	 ?> 
				   </td> 
				    <td>
					 <?php  echo isset($uploads[$school->school_id])?$uploads[$school->school_id]:0;  ?>   | 					 <a href="<?php echo site_url("report_purchase_bills/spacesgallery/".intval($school->school_id)."/".$encoded_date);?>">View Bills</a>
				   </td> 
				     
                </tr>
				 <?php } ?>
				
                </tbody>
                
              </table>
			  
            </div>
            <!-- /.box-body -->
          </div>
		  
	 