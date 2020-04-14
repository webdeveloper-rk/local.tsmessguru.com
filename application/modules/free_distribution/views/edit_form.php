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
<h3>Free Distribution Form</h3>
<?php 
$errors = validation_errors();
if($errors !=""){
?>
 <div class="validation_errors"><?php echo validation_errors(); ?>  </div>
<?php } ?>
<?php echo $this->session->flashdata('message');?>

<form method="post" action=""  id="updateentries" name="updateentries" id="updateentries" onsubmit="return validateform(this)" enctype="multipart/form-data">
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
                   <label>&nbsp;&nbsp;Date of supply :&nbsp;&nbsp; &nbsp;&nbsp; <span style="color:#ff0000"><b><?php echo $date_selected;?></b></span></label>
                  
                 
              </div>
              <!-- /.form group -->
 <div class="box-body"> 
			 <label>&nbsp;&nbsp;Item:&nbsp;&nbsp;  <span style="color:#ff0000">&nbsp;&nbsp;&nbsp;<b><?php echo $item_details->item_name." - " .$item_details->telugu_name;?></b></span></label>
                 
			</div>	
			
			
			 <div class="box-body"> 
			 <label>&nbsp;&nbsp;Opening Quantity :&nbsp;&nbsp;  <span style="color:#ff0000">&nbsp;&nbsp;&nbsp;<b><?php echo $form_data['opening_quantity'];?></b></span></label>
                 
			</div>	
              <div class="box-body">
				   
  <div class="form-group">
                  <label for="exampleInputEmail1">ISSUED TO : </label><br><br>
				  <input type="checkbox" name="whom[]" class='whom-group' value="Parents" id="whom_parents"><label for="whom_parents">&nbsp;&nbsp;&nbsp;&nbsp;Parents of the child</label><br>
				  <input type="checkbox" name="whom[]" class='whom-group' value="District Collector" id="whom_collector"><label for="whom_collector">&nbsp;&nbsp;&nbsp;&nbsp;District Collector</label> <br>
				  <input type="checkbox" name="whom[]" class='whom-group' value="Grama Panchayathi" id="Grama Panchayat"><label for="Grama Panchayat">&nbsp;&nbsp;&nbsp;&nbsp;Grama Panchayathi</label> <br>
				  <input type="checkbox" name="whom[]" class='whom-group' value="Police Dept" id="Police Dept"><label for="Police Dept">&nbsp;&nbsp;&nbsp;&nbsp;Police Dept</label> <br>
				  <input type="checkbox" name="whom[]" class='whom-group' value="MRO Office" id="MRO Office"><label for="MRO Office">&nbsp;&nbsp;&nbsp;&nbsp;MRO Office</label> <br>
			</div>
			
				<div class="form-group" style="padding:10px;">
                  
                  <div class="">
                    <label for="inputEmail3" class="col-sm-2 control-label"><b>District name</b></label>
						<select name="district_name" required>
						<option value="">Please select district</option>
							<?php 
							$drs11 = $this->db->query("SELECT * FROM `districts`");
							foreach($drs11->result() as $ddrow)
							{
								?><option value="<?php echo $ddrow->name;?>"><?php echo $ddrow->name;?></option><?php 
							}
							
							?>
						</select>
                  </div>
                </div>
				
				<div class="form-group" style="padding:10px;">
                  
                  <div class=" ">
                    <label for="inputEmail3" class="col-sm-2 control-label"><b>Name of the person</b></label>
					<input type="text" value=""    placeholder="Enter   name" name="person_name" required   style="width:350px;"><br><br>
					<label for="inputEmail3" class="col-sm-2 control-label"><b>Contact number / Office number</b></label>
					<input type="number" maxlength="13" value=""   placeholder="Enter   contact number" name="contact_number" required  style="width:350px;" >  
                  </div>
                </div>
				
				
			  <div class="form-group">
                  <label for="exampleInputEmail1">Upload the Receipt : </label>
						<input type="file" name="document_file" id="document_file" required>
						Allowed jpg,gif,png,pdf,zip types
			</div>
			   <div class="form-group" style="padding: 0px;">
                  <!--<label for="inputEmail3" class="col-sm-2 control-label">Purchase</label>-->

                  <div class="">
                    <input type="hidden"  value="<?php echo $form_data['pqty'];?>" id="pqty" placeholder="Enter Purchase quantity" name="pqty" required="" style="width:200px;">
					<input type="hidden"  value="<?php echo $form_data['pprice'];?>"  id="pprice" placeholder="Enter Purchase price per kg" name="pprice" required="" style="width:200px;">  
                  </div>
                </div>
				
				<div class="form-group" style="padding:10px;">
                  
                  <div class="">
                    <label for="inputEmail3" class="col-sm-2 control-label"><b>Quantity</b></label>
					<input type="text" value="<?php echo $form_data['bf_qty'];?>"  id="bf_qty" placeholder="Enter   quantity" name="bf_qty" required=""  ><br><br>
					<label for="inputEmail3" class="col-sm-2 control-label"><b>Price per 1 KG/Litre</b></label>
					<input type="text" value="<?php echo $form_data['bf_price'];?>"  id="bf_price" placeholder="Enter   price per kg" name="bf_price" required=""  >  
                  </div>
                </div>

				<div class="form-group" style="padding:10px;">
                 <!-- <label for="inputEmail3" class="col-sm-2 control-label">Lunch</label>-->

                  <div class="">
                    <input type="hidden"  value="<?php echo $form_data['lu_qty'];?>" id="pqty" placeholder="Enter Lunch quantity" name="lu_qty" required="" style="width:200px;">
					<input type="hidden"  value="<?php echo $form_data['lu_price'];?>"  id="pprice" placeholder="Enter Lunch price per kg" name="lu_price" required="" style="width:200px;">  
                  </div>
                </div>		
				<div class="form-group" style="padding:10px;">
                 <!-- <label for="inputEmail3" class="col-sm-2 control-label">Snacks</label>-->

                  <div class="">
                    <input type="hidden" value="<?php echo $form_data['sn_qty'];?>" id="pqty" placeholder="Enter Snacks quantity" name="sn_qty" required="" style="width:200px;">
					<input type="hidden" value="<?php echo $form_data['sn_price'];?>"  id="pprice" placeholder="Enter Snacks price per kg" name="sn_price" required="" style="width:200px;">  
                  </div>
                </div>		

				<div class="form-group" style="padding:10px;">
                 <!-- <label for="inputEmail3" class="col-sm-2 control-label">Dinner</label>-->

                  <div class="">
                    <input type="hidden" value="<?php echo $form_data['di_qty'];?>"  id="pqty" placeholder="Enter Dinner quantity" name="di_qty" required="" style="width:200px;">
					<input type="hidden" value="<?php echo $form_data['di_price'];?>"  id="pprice" placeholder="Enter Dinner price per kg" name="di_price" required="" style="width:200px;">  
                  </div>
                </div>						
				 
               
           
              </div>
			 <div id="changepwdnotifier" style="margin-left:30px;"></div>
			  <!-- Date range -->
              <div class="box-footer">&nbsp;&nbsp;&nbsp;&nbsp;
                 
                <input type="submit" class="btn btn-info pull-right" value="Submit" name="submit"> <a href='<?php echo site_url('free_distribution');?>' onclick="return confirm('Are you sure to cancel?')" class="btn btn-info pull-right">Cancel</a>&nbsp;&nbsp;&nbsp;&nbsp;
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
	  frm  = document.getElementById("updateentries");
	     
			if($('.whom-group:checked').length == 0)
			{
					alert("Please check atleast one Issued to  field");
					  
					  return false;
			}
			var ext = $('#document_file').val().split('.').pop().toLowerCase();
			///alert(ext);
if($.inArray(ext, ['gif','png','jpg','jpeg','pdf','zip']) == -1) {
	  
    alert("Receipt Allowed Extentions are 'gif','png','jpg','jpeg','pdf','zip' only");
	return false;
}


	  if($("#bf_qty").val().trim()=="")
	  {
			alert("Please enter   quantity. ");
			frm.bf_qty.focus();
			return false;
	  }
	//  alert(isNormalInteger($("#bf_qty").val().trim()));
	 // alert($("#bf_qty").val());
	  
	  
		var bf_qty = $("#bf_qty").val();
		if(jQuery.isNumeric(bf_qty) == false){
				alert("Please enter   quantity greater than zero");
				$("#bf_qty").focus(); 
				return false;
		}

		if (bf_qty <= 0)
		{
			alert("Please enter   quantity greater than zero");
			$("#bf_qty").focus();
			return false;
		}
 
	  var bf_price = $("#bf_price").val();
		if(jQuery.isNumeric(bf_price) == false){
				alert("Please enter   Price greater than zero");
				$("#bf_price").focus(); 
				return false;
		}

		if (bf_price <= 0)
		{
			alert("Please enter   Price greater than zero");
			$("#bf_price").focus();
			return false;
		}
	     
	  
	  return true;
  }
  
  function isNormalInteger(str) {
    var n = Math.floor(Number(str));
    return n !== Infinity && String(n) === str && n >= 0;
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


                window.location = "<?php echo site_url('free_distribution'); ?>";


            }, 3000); 


			 


			 document.getElementById("updateentries").reset();


        } else {


            $("#changepwdnotifier").html(data.message);
            $("#error_div").html(data.html_table);


        }


    }


</script>

  