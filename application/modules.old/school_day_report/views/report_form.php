<style>.daily table td{	padding:5px;}</style><section class="content noprint">
<div class="box box-info noprint">
            <div class="box-header with-border">
              <h3 class="box-title">Session wise consumtion items report</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             <?php echo $this->session->flashdata('message'); ?><div style="color:#ff0000;padding:10px;font-weight:bold;"><?php echo validation_errors();?></div>
			<form   role="form" class="form-horizontal noprint"   action=""  method="post"  >
              <div class="box-body">
				  <div id="changepwdnotifier"></div><?php if($this->session->userdata("user_role")!='school') { ?>
				<div class="form-group">                  <label for="inputEmail3" class="col-sm-2 control-label">School</label>                  <div class="col-sm-10">                  <?php echo  school_selection($school_id);?>                  </div>                </div><?php } ?>
			
				 <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Date</label>

                  <div class="col-sm-10">
                    <input type="text" class="datepicker form-control" id="school_date" placeholder="Select Date" name="school_date"  required value="<?php echo $school_date;?>">
                  </div>
                </div>
				 
           
              </div>
              <!-- /.box-body -->
              <div class="box-footer noprint">
                
                <button type="submit" class="btn btn-info pull-right">Get Report</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
 
</section>

<!-- jQuery 1.10.2 -->
 
      
 <script>
  $( function() {
			$( ".datepicker" ).datepicker({ 
			startDate: '09-01-2016',
			endDate: '+0d'});
  } );
  </script>
<?php if($result_display ==true) { ?><h3><?php echo $report_date;?> - Session wise consumtion items [ <?php echo $school_name;?> ] </h3> <div class="box">            <div class="box-header">                         </div>            <!-- /.box-header -->            <div class="box-body">               			   <a href='#' class='btn btn-info pull-right noprint' onclick='javascript:window.print();'>Print</a>			  			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">                <thead>                <tr role="row">				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Item Name</th>				<!--<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 				aria-label="Browser: activate to sort column ascending" style="width: 159px;">Current Balance</th>-->				 												 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Opening Balance</th> 								<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Purchase Balance</th>				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Total Qty</th>				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Consumed Qty</th>								 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Total Consumed Qty</th>								 				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 				aria-label="CSS grade: activate to sort column ascending" style="width: 75px;">Closing Balance Qty</th>				  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Total Consumed Amount</th>				</tr>                </thead>                <tbody>                 <?php 				 				 foreach($rset->result() as $item) {  				 						$tqty =  $item->session_1_qty + $item->session_2_qty  + $item->session_3_qty  + $item->session_4_qty;						if($tqty > 0) { 				 ?>                <tr role="row" class="odd">                  <td class="sorting_1"> 				 <b> <?php echo $item->telugu_name."-".$item->item_name;?></b></td>                  <!--<td>Curebnt balance</td>                  <td><?php echo $item->item_name; ?> </td>-->				                   <td>					 <?php echo $item->opening_quantity; ?> 				   </td> 				    <td>					 <?php echo $item->purchase_quantity; ?> 				   </td> 				    <td>					 <?php echo  ($item->opening_quantity+$item->purchase_quantity); ?> 				   </td> 				   	<td> <table  class='daily' width="200px;" border="1px">					<tr style="background-color:#CCCCCC;font-weight:bold;"><td>Session</td><td>Quantity</td><td>Price</td><td>Total</td></tr>						<tr><td style="padding:5px;">Breakfast</td><td style="padding:5px;"> <?php echo $item->session_1_qty; ?> </td> <td><?php echo number_format($item->session_1_price,2); ?> </td><td> <?php echo number_format($item->session_1_qty * $item->session_1_price,2); ?> </td></tr>						<tr><td style="padding:5px;">Lunch</td><td style="padding:5px;"> <?php echo $item->session_2_qty; ?> <td><?php echo number_format($item->session_2_price,2); ?> </td> <td><?php echo number_format($item->session_2_qty * $item->session_2_price,2); ?> </td></td></tr>						<tr><td style="padding:5px;">Snacks</td><td style="padding:5px;"> <?php echo $item->session_3_qty; ?> </td><td><?php echo number_format($item->session_3_price,2); ?> </td> <td><?php echo number_format($item->session_3_qty * $item->session_3_price,2); ?> </td></tr>						<tr><td style="padding:5px;">Dinner/Supper</td><td style="padding:5px;"> <?php echo $item->session_4_qty; ?> </td><td><?php echo number_format($item->session_4_price,2); ?> </td> <td><?php echo number_format($item->session_4_qty * $item->session_4_price,2); ?> </td></tr>						</table>					</td>                   <td><?php 						echo number_format((  $item->session_1_qty + $item->session_2_qty  + $item->session_3_qty  + $item->session_4_qty ),3);					?></td>                                                     <td> <?php echo $item->closing_quantity; ?> </td>  				  <td> <?php echo number_format(  $item->today_consumed,2); ?> </td>                </tr>				 <?php 				 						}				 } ?>				                </tbody>                              </table>			              </div>            <!-- /.box-body -->          </div>		  <script>  $(function () {  //  $("#example1").DataTable();    $('#example1').DataTable({		"pageLength": 300,      "paging": true,      "lengthChange": false,      "searching": false,      "ordering": false,      "info": true,	        "autoWidth": true    });  });</script>	 
<?php } ?>