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


<?php echo $this->session->flashdata('message');?>

<h3>CARE TAKER Authorisation Entries [ <?php echo $report_date;?> ] </h3>
<!--<b> Authorised schools count : <?php echo $rset->num_rows();?></b> || 
<b> NON Authorised schools count : <?php echo $rset->num_rows();?></b> -->
<a href="<?php echo site_url('admin/subadmin/authorisations_entries');?>"><b>Go back</b></a>
 

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
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Login</th> 
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Breakfast</th>
				 
				
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Lunch</th>
				
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Snacks</th>
				
				
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Dinner</th>
				
				
				</tr>
                </thead>
                <tbody>
                 <?php 
				 
				 foreach($rset->result() as $school) {  ?>                <tr role="row" class="odd">
                  <td class="sorting_1"> 
				 <b> <?php echo $school->schoolname;?></b></td>
                  
				
                   <td>
					 <?php echo $school->school_code; ?> 
				   </td> 
				    <td>
					 <?php 
					 $dsql = "select count(*) as logged_count from   login_sessions where school_id='".$school->school_id."' and operator_type='CT' and  date_format(log_time,'%Y-%m-%d') = '$rdate'";
					 $drsw = $this->db->query($dsql);
					 $logged_count = $drsw->row()->logged_count;
					 
					 echo $logged_count; 
					 
					 ?> 
				   </td> 
				    <td>
					 <?php echo ucfirst($school->session_1_status) . " <br>  ".$school->session_1_time; ?> 
				   </td> 
				     
					  <td>
					<?php echo ucfirst($school->session_2_status) . " <br>  ".$school->session_2_time; ?> 
				   </td> 
				    <td>
					<?php echo ucfirst($school->session_3_status) . " <br>  ".$school->session_3_time; ?> 
				   </td> 
				    <td>
					<?php echo ucfirst($school->session_4_status) . " <br>  ".$school->session_4_time; ?> 
				   </td> 
                </tr>
				 <?php } ?>
				
                </tbody>
                
              </table>
			  
            </div>
            <!-- /.box-body -->
          </div>
		  
	 