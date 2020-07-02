<style>
@media  print
{    
  .noprint
  {
        display: none !important;
    }
}
</style>
<section class="content noprint" >
<div class="box box-info noprint">
            <div class="box-header with-border">
              <h3 class="box-title">Menu Item Track Entries </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
			<form   role="form" class="form-horizontal"   action=""  method="post" onsubmit="return validate(this)">
              <div class="box-body">
				  <div id="changepwdnotifier"></div>

				  
				   <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Item </label>

                  <div class="col-sm-10">
                     <select name="item_id" required>
					 <option value="">Please select item</option>
					 <?php 
					 $selected_item = $this->input->post("item_id");
					 $selected_date = $this->input->post("school_date");
					 foreach($item_list->result() as $item){
						 $selcted = '';
						 if($selected_item == $item->item_id)
						 {
							 $selcted = ' selected ';
						 }
						 
						 ?>
							<option <?php echo  $selcted ;?> value="<?php echo $item->item_id;?>"><?php echo $item->item_name."-".$item->telugu_name;?></option>
					 <?php } ?>
					 </select>
                  </div>
                </div>
				
						 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Date</label>

                  <div class="col-sm-10">
                    <input type="text" class="datepicker form-control" value="<?php echo  $selected_date ;?>" id="school_date" placeholder="Select Date" name="school_date" required>
                  </div>
                </div>
				 
           
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                
                <button type="submit" class="btn btn-info pull-right">Get Report</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
 
</section>

<!-- jQuery 1.10.2 -->
 
     
<script type="text/javascript">
 
    function validate(form) {
       
	   if(form.school_code.value.trim()=="")
	   {
		   alert("Please enter school_code");
		   form.school_code.focus();
		   return false;
	   }
	    if(form.school_date.value.trim()=="")
	   {
		   alert("Please select date");
		   form.school_date.focus();
		   return false;
	   }
    }
</script>
 <script>
  $( function() {
			$( ".datepicker" ).datepicker({ 
			startDate: '09-01-2016',
			endDate: '+0d'});
  } );
  </script>
 

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

<?php if($result==1) { ?>
<h3><span style="color:#FF0000;"><?php echo ucfirst($item_name);?></span> -  Used  schools [ <?php echo $report_date;?> ] </h3>
<b> Used schools count : <?php echo $rset->num_rows();?></b> <a class='noprint btn btn-info ' href="#" onclick="javascript:window.print()"><b>Print</b></a>
 

<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
			
				 
				  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">SNo</th> 
				
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">School Code</th> 
				
				 	<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">School Name</th>
				 	<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Purchase</th>
				 
				 	<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Used Quantity</th>
				 
				</tr>
                </thead>
                <tbody>
                 <?php 
				 if($rset->num_rows()==0)
				 {
					echo " <tr role='row'><td colspan='5'>No Records Found</td></tr>";
				 }
				 $i=1;
				 foreach($rset->result() as $school) {  ?>                <tr role="row" class="odd">
               
				 <td>
					 <?php echo $i; ?> 
				   </td> 
                   <td>
					 <?php echo $school->school_code; ?> 
				   </td> 
				       <td class="sorting_1"> 
				 <b> <?php echo $school->name;?></b></td>
                  
				        <td class="sorting_1"> 
				 <b> <?php echo $school->total_purchase;?></b></td>
				 
				    <td class="sorting_1"> 
				 <b> <?php echo $school->total_qty;?></b></td>
				 
                </tr>
				 <?php 
				 $i++;
				 } ?>
				
                </tbody>
                
              </table>
			  
            </div>
            <!-- /.box-body -->
          </div>
		  
	 
<?php } ?>
