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
<h3>Purchase and consumption entries Form</h3>
<?php 
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>
<?php echo $this->session->flashdata('message');?>

<form method="post" action="" name="updateentries" id="updateentries" onsubmit="return validateform(this)">
<input type="hidden" name="entry_id" value="<?php echo $form_data['entry_id'];?>">
<div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Enter item  Quantity and prices</h3>
            </div>
            <div class="box-body"> 
			 <label>&nbsp;&nbsp;School:&nbsp;&nbsp;  <span style="color:#ff0000"><b><?php echo $school_info->sname;?></b></span></label>
                 
			</div>				  
              <!-- /.form group -->

			   <!-- Date -->
              <div class="box-body"> 
                   <label>&nbsp;&nbsp;Date:&nbsp;&nbsp; &nbsp;&nbsp; <span style="color:#ff0000"><b><?php echo $date_selected;?></b></span></label>
                  
                 
              </div>
              <!-- /.form group -->
 <div class="box-body"> 
			 <label>&nbsp;&nbsp;Item:&nbsp;&nbsp;  <span style="color:#ff0000">&nbsp;&nbsp;&nbsp;<b><?php echo $item_details->item_name." - " .$item_details->telugu_name;?></b></span></label>
                 
			</div>	
			
			
			 <div class="box-body"> 
			 <label>&nbsp;&nbsp;Opening Quantity :&nbsp;&nbsp;  <span style="color:#ff0000">&nbsp;&nbsp;&nbsp;<b><?php echo $form_data['opening_quantity'];?></b></span></label>
                 
			</div>	
              <div class="box-body">
				   
<div class="form-group" style="padding:10px;">
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>

                  <div class="col-sm-10">
                   <label for="inputEmail3" class="col-sm-2 control-label"><b>Quantity</b></label>
					 <label for="inputEmail3" class="col-sm-2 control-label"><b>Price per 1 KG</b></label>
                  </div>
                </div>
			   <div class="form-group" style="padding:10px;">
                  <label for="inputEmail3" class="col-sm-2 control-label">Purchase</label>

                  <div class="col-sm-10">
                    <input type="text"  value="<?php echo $form_data['pqty'];?>" id="pqty" placeholder="Enter Purchase quantity" name="pqty" required="" style="width:200px;">
					<input type="text"  value="<?php echo $form_data['pprice'];?>"  id="pprice" placeholder="Enter Purchase price per kg" name="pprice" required="" style="width:200px;">  
                  </div>
                </div>
				
				<div class="form-group" style="padding:10px;">
                  <label for="inputEmail3" class="col-sm-2 control-label">Breakfast</label>

                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $form_data['bf_qty'];?>"  id="pqty" placeholder="Enter Breakfast quantity" name="bf_qty" required="" style="width:200px;">
					<input type="text" value="<?php echo $form_data['bf_price'];?>"  id="pprice" placeholder="Enter Breakfast price per kg" name="bf_price" required="" style="width:200px;">  
                  </div>
                </div>

				<div class="form-group" style="padding:10px;">
                  <label for="inputEmail3" class="col-sm-2 control-label">Lunch</label>

                  <div class="col-sm-10">
                    <input type="text"  value="<?php echo $form_data['lu_qty'];?>" id="pqty" placeholder="Enter Lunch quantity" name="lu_qty" required="" style="width:200px;">
					<input type="text"  value="<?php echo $form_data['lu_price'];?>"  id="pprice" placeholder="Enter Lunch price per kg" name="lu_price" required="" style="width:200px;">  
                  </div>
                </div>		
				<div class="form-group" style="padding:10px;">
                  <label for="inputEmail3" class="col-sm-2 control-label">Snacks</label>

                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $form_data['sn_qty'];?>" id="pqty" placeholder="Enter Snacks quantity" name="sn_qty" required="" style="width:200px;">
					<input type="text" value="<?php echo $form_data['sn_price'];?>"  id="pprice" placeholder="Enter Snacks price per kg" name="sn_price" required="" style="width:200px;">  
                  </div>
                </div>		

				<div class="form-group" style="padding:10px;">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dinner</label>

                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $form_data['di_qty'];?>"  id="pqty" placeholder="Enter Dinner quantity" name="di_qty" required="" style="width:200px;">
					<input type="text" value="<?php echo $form_data['di_price'];?>"  id="pprice" placeholder="Enter Dinner price per kg" name="di_price" required="" style="width:200px;">  
                  </div>
                </div>						
				 
               
           
              </div>
			 <div id="changepwdnotifier" style="margin-left:30px;"></div>
			  <!-- Date range -->
              <div class="box-footer">&nbsp;&nbsp;&nbsp;&nbsp;
                 
                <input type="submit" class="btn btn-info pull-right" value="Submit" name="submit"> <a href='<?php echo site_url('purchase_consumption');?>' onclick="return confirm('Are you sure to cancel?')" class="btn btn-info pull-right">Cancel</a>&nbsp;&nbsp;&nbsp;&nbsp;
				 <div id="error_div"> </div>
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
		<script src="<?php echo site_url(); ?>assets/admin/js/jquery-1.10.2.min.js"></script>


<script src="<?php echo site_url(); ?>assets/admin/js/jquery.form.js"></script>  
 <script>
  $( function() {
			$( ".datepicker" ).datepicker({ 
			startDate: '09-01-2016',
			endDate: '+0d'});
  } );
  </script>
  
		
		
 
  
   <script>
  function validateform(frm)
  {
	  if(frm.pqty.value().trim()=="")
	  {
			alert("Please enter Purchase quantity. if no purchases enter 0 value");
			frm.pqty.focus();
			return false;
	  }
	  
	  
	  return true;
  }
</script>
	 

<script type="text/javascript">


    $(document).ready(function() {


        $('#updateentries').ajaxForm({beforeSubmit : function(arr, $form, options){
                $("#changepwdnotifier").html("<div class='alert alert-warning'><h3>Updating please wait...</h3></div>");
                 $("#error_div").html('');


          },dataType: 'json', success: processJson});


        


    });


    function processJson(data) {

		//alert(data);
        if (data.success) {


            $("#changepwdnotifier").html(data.message);


             setTimeout(function() {


                window.location = "<?php echo site_url('purchase_consumption'); ?>";


            }, 3000); 


			 


			 document.getElementById("updateentries").reset();


        } else {


            $("#changepwdnotifier").html(data.message);
            $("#error_div").html(data.html_table);


        }


    }


</script>

  