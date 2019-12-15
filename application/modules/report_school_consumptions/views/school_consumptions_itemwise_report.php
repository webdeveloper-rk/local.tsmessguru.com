<style>

@media print
{    
    #example1_filter  
    {
        display: none !important;
    }
 .dataTables_info
    {
        display: none !important;
    }
	
	 .paginate_button 
    {
        display: none !important;
    }
}
</style>
<section class="content">
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">  Monthly  Consumption Reports  </h3>
			  
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             
			<form   role="form" class="form-horizontal"   action=""  method="post"  >
              <div class="box-body">
				  <div id="changepwdnotifier"></div>
				  
	  
					  <div class="box-body">
			  
			  			  <div  >

							<table >
							<?php 
							
							if($this->session->userdata("user_role") != "school")
					{
							?>
								<tr>
									<td>School Code :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
									<td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo school_selection($this->input->post("school_id"));?></td>
									
								</tr>
					<?php } ?>
								<?php if($this->config->item("site_name")=="apsocial"){ ?>
								<tr>
									<td>	<br>Report Type :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
									<td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
									<br> 
											<input type="radio" value="both"  checked name="report_type"  id="both"><label for="both">Both</label>
											<input type="radio" value="local"  <?php if($this->input->post("report_type")=="local") { echo " checked "; } ?>  name="report_type" id="local"><label for="local">Local</label>
											<input type="radio" value="central"   <?php if($this->input->post("report_type")=="central") { echo " checked "; } ?>  name="report_type" id="central"><label for="central">Central</label>
									</td>
									
								</tr>
								<?php } ?>
								
								
								<tr><td style="height:20px">&nbsp;</td></tr>
								<tr>
									<td>Month & Year: :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
									<td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 	<select name="month" required>
						<option value="">Select Month</option>
						<?php 
						   $month_selected = $this->input->post('month'); 
						 	  $months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");	
						
						for($lmonth=1;$lmonth<13;$lmonth++)
						{
							$month_name = $months[$lmonth];
							if($lmonth < 10)
									$lmonth = "0".$lmonth;
								
								$selected_text = '';
							if($month_selected == $lmonth){
								$selected_text = ' selected ';
							}
							
							?>
						<option <?php echo $selected_text ;?>   value="<?php echo $lmonth;?>"><?php echo $months[$lmonth];?></option>
						<?php } ?>
					</select>
                   	<select name="year"  required>
						<option value="">Select Year</option>
						<?php 
						 $year_selected = $this->input->post('year'); 
						$sdyear = 2017;
						$pyear = date('Y');
						
						for($lyear=$sdyear;$lyear<=$pyear;$lyear++)
						{
								$selected_text = '';
							if($year_selected == $lyear){
								$selected_text = ' selected ';
							}
							?>
						<option  <?php echo $selected_text ;?>    value="<?php echo $lyear;?>"><?php echo $lyear;?></option>
						<?php } ?>
					</select></td>
									
								</tr>
							</table>

				 
                </div>
				 
           						 
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                
                <button type="submit" name="submit"  value="getreport" class="btn btn-info pull-right">Get Report</button> 
			 
                <button type="submit"  name="submit"  value="download"  class="btn btn-info pull-right">Download</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
		  
		  <?php if($display_result==true){ ?>
		  
		  <div class="box">
            <div class="box-header">
             <h4><b><?php echo $rdata['item_name'];?></b> Consumption Report of <b>
			 <span style="color:#FF0000;"><?php if($rdata['report_for']!="") { echo "-". $rdata['report_for'];} ?></span></b></b> </h4>
            </div>
            <!-- /.box-header -->
            
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Sno</th>
				
			 
			 
				 
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Item name </th> 
				 
		 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 110px;" >Consumed Quantity in KG's</th> 
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 136px;" align="right">Consumed Amount</th> 
				
				  
				 
</tr>
                </thead>
                <tbody>
                 <?php 
				 $sno = 1;
				 $total_consumed = 0;
				 
				 $rset = $rdata['rset'];
				 foreach($rset->result() as $item) {
 				 
				  $total_consumed  =  $total_consumed  + $item->consumed_price;
				  
				  
				 ?>
				 <tr  >
				 <td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo $sno;?></td>
				<td class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="widtd: 126px;"><?php echo $item->telugu_name. "-".$item->item_name;?></td>
				 
				<td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="widtd: 139px;"><?php echo $item->consumed_quantity;?></td>
				 
				 <td class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="widtd: 139px;" align="right"><?php echo  number_format($item->consumed_price,2);?></td>
</tr>
				 <?php $sno++;
				  } 
				  ?>
				  
                </tbody>
                
              </table>
			  <table class="table table-bordered table-striped dataTable" >
						   <tr  >
							 <td class="sorting"  aria-controls="example1" rowspan="1" colspan="1" 
							aria-label="Platform(s): activate to sort column ascending" style="widtd: 139px;"> </td>
							<td class="sorting"  aria-controls="example1" rowspan="1" colspan="1" 
							aria-label="Platform(s): activate to sort column ascending" style="widtd: 139px;"> </td>
							<td class="sorting"  aria-controls="example1" rowspan="1" colspan="1" 
							aria-label="Platform(s): activate to sort column ascending" style="widtd: 139px;"><b>Total Consumed Amount</b>  </td>
							<td class="sorting"  aria-controls="example1" rowspan="1" colspan="1" 
							aria-label="Platform(s): activate to sort column ascending" style="widtd: 139px;"><b><?php echo number_format($total_consumed,2);?> </b> </td> 
								</tr>
			</table>
			  
				 
		
				 
				
			  
            </div>
		  <?php } ?>
            <!-- /.box-body -->
          </div>
		  <script>
  $(function () {
  //  $("#example1").DataTable();
    $('#example1').DataTable({
		"pageLength": 3000,
      "paging": true,
      "lengthChange": false,
      "searching": true,
	  
      
      "info": true,
	   
      "autoWidth": true
    });
  });
</script>
	 
 
</section>
 
  