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

<h3>Amountwise Top Spenders  on <?php echo $report_date;?>   </h3>
<b> Total schools count : <?php echo $rset->num_rows();?></b> <a href="<?php echo site_url('admin/spenders/amountwise');?>"><b>Go back</b></a>
 

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
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Amount used</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">&nbsp;</th>
				 
				</tr>
                </thead>
                <tbody>
                 <?php 
				 
				 foreach($rset->result() as $school) {  ?>               				 <tr role="row " class="odd ">
                  <td class="sorting_1"> 
				 <b> <?php echo $school->name;?></b></td>
                  
				
                   <td>
					 <?php echo $school->school_code; ?> 
				   </td> 
				    <td>
					 <?php 
					 
					 // echo $school->school_id,"--";
					 echo number_format($school->amount_used,2 ,'.', '') 	; ?> 
				   </td> 
				    <td>
					  					 <a href="<?php echo site_url("admin/spenders/items_list_popup/".$school->school_id."/".$rdate);?>" data-toggle="modal" data-target="#myModal" data-remote="false" class="btn btn-default">    View Items</a>
				   </td> 
				     
                </tr>
				 <?php } ?>
				
                </tbody>
                
              </table>
			  
            </div>
            <!-- /.box-body -->
          </div>		  		  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">  <div class="modal-dialog">    <div class="modal-content">      <div class="modal-header">        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        <h4 class="modal-title" id="myModalLabel" style="font-weight:bold;">Used Items List</h4>      </div>      <div class="modal-body" style="height:500px;overflow:auto;">        ...      </div>      <div class="modal-footer">        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>       </div>    </div>  </div></div><script>// Fill modal with content from link href$("#myModal").on("show.bs.modal", function(e) {    var link = $(e.relatedTarget);    $(this).find(".modal-body").load(link.attr("href"));});</script>		  
		  
	 