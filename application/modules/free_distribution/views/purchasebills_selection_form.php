<?php 
$from_date = '';
$to_date = '';
if($this->input->post('fromdate')!=null)
	$from_date = $this->input->post('fromdate');
if($this->input->post('todate')!=null)
	$to_date = $this->input->post('todate');

?>
<style>
.bold td
{
	font-weight:bold;
}
.red
{
	color:#FF0000;
}
</style>
<h3>Free Distribution</h3>
<form method="post" action="">
 <?php 
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php 
echo $this->session->flashdata("message");

} ?>
<div class="box box-primary">
            <div class="box-header">
			<?php echo $this->session->flashdata('message');?>
              <h3 class="box-title">Choose item and Date</h3>
            </div>
            <div class="box-body">
              <!-- Date -->
              <div class="form-group">
                <label>School:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-institution"></i>
                  </div>
                   <b><?php 
				   $school_info = $this->db->query("select * from schools where school_id=?",array($this->session->userdata("school_id")))->row();
				   echo $school_info->school_code ." - ".$school_info->name ;?></b>
                </div>
                <!-- /.input group -->
              </div>
			  
			 

			  
			    <div class="form-group">
                <label>Item:</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar-plus-o"></i>
                  </div>
                   <select required name="item_id" style="height:32px" class="form-control pull-right " >
					<option value="">Choose Item</option>
				<?php  foreach($items->result() as $row){
					echo "<option value='".$row->item_id."'>".$row->item_name."-".$row->telugu_name."</option>";
			 } ?>
 				   </select>
                </div>
                <!-- /.input group -->
				
				  <!-- Date -->
              <div class="form-group">
                <label>Date of supply :</label>

                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <!--<input type="text" required name="todate" value="<?php echo $to_date;?>"    class="form-control pull-right datepicker"    >-->
				  <select name="day" required style="height:30px">
						<option value="">Select Day</option>
						<?php 
							$today_day  = date('d');
							for($i=1;$i<=30;$i++)
							{
								?><option value="<?php echo $i;?>"><?php echo $i;?></option><?php
							}
						?>
						</select>
						<select name="month" required style="height:30px">
						<option value="">Select Month</option>
						<option value="04" selected >April</option>
						<option value="05" selected >May</option>
						 
						</select>
						<select name="year" required style="height:30px">
						<option value="">Select Year</option>
						<option value="2020" selected>2020</option>
						 
						</select>
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->
				
				
              </div>
			  
			  
              <!-- Date range -->
              <div class="box-footer">
                 
                <input type="submit" class="btn btn-info pull-right" value="Submit" name="submit"> 
				 
              </div>
              <!-- /.form group -->

              <!-- Date and time range -->
              
              <!-- /.form group -->

              <!-- Date and time range -->
              
              <!-- /.form group -->

            </div>
            <!-- /.box-body -->
          </div>
		  </form>
 <script>
  $( function() {
			$( ".datepicker" ).datepicker({ 
			startDate: '09-01-2016',
			endDate: '+0d'});
  } );
  </script>
  <a href="javascript:void(0)" onclick="window.print()" class="btn btn-info pull-right noprint no-print" >Print</a>
  <table class="table table-bordered table-striped  "  >
	<tr class='bold'><td align="center">TTWREIS SCHOOL,<?php echo $this->session->userdata("user_name"); ?></td></tr>
	<tr class='bold'><td align="center"><b>FREE Distribution</b></span></td></tr>
	<tr>
		<td>
			<table id="example1"  class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info"> 
				<tr class='bold'>
					 
					<td  >SNO</td>
					<td  >Item name</td>
					<td  >Supply Date</td>
					<td  >Issued To</td>
					<td  >Issued To Name</td>
					<td  >District</td>
					<td  >Quantity</td>
					<td  >Rate</td>
					<td  >Total </td>
				</tr>
				 
				<?php $i=1;
				$sub_total =0;
				$total = 0;
				$items = $this->db->query("select fd.*,date_format(entry_date,'%d-%M-%Y') as entry_date_dp,it.item_name  from free_distributions fd inner join items it on it.item_id = fd.item_id where school_id=?",array($this->session->userdata("school_id")));				
				foreach($items->result() as $printitem){ 
				 
				 $sub_total = $printitem->quantity*$printitem->price;
				 $total = $total + $sub_total;
								?>
							<tr >
								<td><?php echo $i;?></td>
								<td><b><?php echo  $printitem->item_name;?></b></td>
								<td><?php echo  $printitem->entry_date_dp;?></td>
								<td><?php echo  $printitem->to_whom;?></td>
								<td><?php echo  $printitem->person_name;?></td>
								<td><?php echo  $printitem->district_name;?></td>
								<td><?php echo  $printitem->quantity;?></td>
								<td><?php echo  $printitem->price;?></td>
								<td><?php echo round($sub_total,2);?></td>
								 
								 
							</tr>
				<?php $i++; } ?>
				<tr >
								 
								<td colspan="8" align="right"><b>Total Amount : </b></td>
								<td align="left"><b>&nbsp;&nbsp;&nbsp;<?php echo round($total,2);?></b></td>
								 
								 
							</tr>
			</table>
		
		
		
		
		
		</td></tr>
		

</table>
 <script>
  $(function () {
  //  $("#example1").DataTable();
    $('#example1').DataTable({
		"pageLength": 300,
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
	   "order": [[ 4, "desc" ]],
      "autoWidth": true
    });
  });
</script>
	 
 