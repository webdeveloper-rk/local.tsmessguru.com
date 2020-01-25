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

</style>
<?php if(date('d-m-Y')=="02-04-2019") { ?>
<h2 style="color:#FF0000;font-weight:bold;">
మీరు  April  1st 2019 రోజున ఎంటర్ చెయ్యబడిన  Purchase Entries  మరియు Consumption entries మరల ఎంటర్  చేయవలయును . <br><br>

ఈ క్రింది Click Here లింక్ ని క్లిక్ చేసి entries  చెయ్యగలరు .  
</h2>

<div   title="April 1st Purchase entries">
  <h1>For Filling April 1st 2019 purchase entries , please <a href="<?php echo site_url("purchase_entry_april1st");?>">Click Here </a></h1></p>
</div>



 <h3 style="color:#FF0000;font-weight:bold;">ఈ రోజు   అనగా  April  2nd  2019 నకు  సంబంధించిన entries  యధావిధిగా చెయ్యగలరు . ఎటువంటి మార్పు లేదు </h3>
 <h3 style="color:#FF0000;font-weight:bold;">For  today all  session   timings  extended  upto  6:00 PM . </h3>
<?php } ?>
<h3>Purchase entries for <?php echo date('D');?> - <?php echo date('d-m-Y');?></h3>
 
<?php echo $this->session->flashdata('message');?>
<div class="box">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
			  
			  <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row">
				<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" 
				aria-label="Rendering engine: activate to sort column descending" style="width: 126px;">Item Name</th>
				<!--<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Browser: activate to sort column ascending" style="width: 159px;">Current Balance</th>-->
				 
				
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Qty</th> 
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Platform(s): activate to sort column ascending" style="width: 139px;">Rate</th>
				 <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="Engine version: activate to sort column ascending" style="width: 106px;">Total Amount</th>
				<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
				aria-label="CSS grade: activate to sort column ascending" style="width: 75px;">Action</th></tr>
                </thead>
                <tbody>
                 <?php 
				 
				 foreach($rset->result() as $item) {
						$encoded_item_id =  $this->ci_jwt->jwt_web_encode($item->item_id);	
				 ?>                <tr role="row" class="odd">
                  <td class="sorting_1"><a href='<?php echo site_url();?>purchase_entry/purchase_entryform/<?php echo $encoded_item_id;?>'><?php echo $item->telugu_name."-".$item->item_name;?></a></td>
                  <!--<td>Curebnt balance</td>-->
              <td>
					<?php 
					  if(isset($today_purchases[$item->item_id]->purchase_quantity)) { echo $today_purchases[$item->item_id]->purchase_quantity;} else { echo "0.00";} ;;
					?>
				   </td> 
				 <td><?php 
					  if(isset($today_purchases[$item->item_id]->purchase_price)) { echo $today_purchases[$item->item_id]->purchase_price;} 
					  else { 
						echo  '0.00';
					  }
					  
					?><!--/<?php echo $item_prices[$item->item_id]; ?>--></td>
                  
                   <td><?php 
					  if(isset($today_purchases[$item->item_id]->purchase_quantity)) { echo $today_purchases[$item->item_id]->purchase_quantity * $today_purchases[$item->item_id]->purchase_price;} else { echo "0.00";} ;;
					?></td> 
                  <td><a href='<?php echo site_url();?>purchase_entry/purchase_entryform/<?php echo $encoded_item_id;?>'>Update</a></td>
                </tr>
				 <?php } ?>
				
                </tbody>
                
              </table>
			  
            </div>
			
			
            <!-- /.box-body -->
          </div>
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
	   "order": [[ 3, "desc" ]],
      "autoWidth": true
    });
  });
</script>

<script>
  $( function() {
    $( "#dialog" ).dialog();
  } );
  </script>
  
	 