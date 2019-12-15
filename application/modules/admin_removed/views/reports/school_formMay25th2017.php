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
<h3>School wise reports</h3>
<form method="post" action="" onsubmit="return validateform(this)">
<div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Select district and choose dates</h3>
          	 

			 
             <div id="wrap" class="container">            
<br><br>
            
            <div class="row">
                <div class="col-sm-5">
                     <label  >From date</label>
				  <input type="text" name="from_date"   id="from_date"  value="" class="datepicker">
                </div>
                
                <div class="col-sm-2">
                    <label>To date</label>
				  <input type="text" name="to_date"   id="to_date"  value="" class="datepicker">
                </div>
                
                <div class="col-sm-5">
                    
                </div>
            </div>
            
          
    </div> 
			
			 <div id="wrap" class="container"> 
			 <label>Schools</label>
            <div class="row">
                <div class="col-sm-5">
                    <select name="school[]" id="school" class="form-control" size="8" multiple="multiple">
                       <?php foreach($school_rs->result() as $row)
			 {
				echo "<option value='".$row->school_id."'>".$row->name." - ".$row->village." - ".$row->district_name."</option>" ;
			 }
			 ?>
                    </select>
                </div>
                
                <div class="col-sm-2">
                    <button type="button" id="school_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
                    <button type="button" id="school_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
                    <button type="button" id="school_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
                    <button type="button" id="school_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
                </div>
                
                <div class="col-sm-5">
                    <select name="school_selected[]" id="school_to" class="form-control" size="8" multiple="multiple"></select>
                </div>
            </div>
            
          
    </div> 
			
			 <div id="wrap" class="container">            
<label  >Items</label>
            
            <div class="row">
                <div class="col-sm-5">
                    <select name="items[]" id="search" class="form-control" size="8" multiple="multiple">
                      <?php foreach($items->result() as $item) {
						echo "<option value='".$item->item_id."'>".$item->item_name."-".$item->telugu_name."</option>";
					}

						?>
                    </select>
                </div>
                
                <div class="col-sm-2">
                    <button type="button" id="search_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
                    <button type="button" id="search_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
                    <button type="button" id="search_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
                    <button type="button" id="search_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
                </div>
                
                <div class="col-sm-5">
                    <select name="items_selected[]" id="search_to" class="form-control" size="8" multiple="multiple"></select>
                </div>
            </div>
            
          
    </div> 
			  <!-- Date range -->
              <div class="box-footer">
                 
                <input type="submit" class="btn btn-info pull-right" value="Display Report" name="submit"> 
                <input type="submit" class="btn btn-info pull-right" value="Download Report" name="submit"> 
				 
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
  <script>
  
    function validateform(frm)
  {
	  
	    $('#school_to option').prop('selected',true);
	    $('#search_to option').prop('selected',true);
	 
	    if(document.getElementById("from_date").value=="")
	  {
		  alert("Please choose From Date");
		  document.getElementById("from_date").focus();
		  return false;
	  }
	    if(document.getElementById("to_date").value=="")
	  {
		  alert("Please choose To date");
		  document.getElementById("to_date").focus();
		  return false;
	  }
	  var option_selected_count = $('#school_to :selected').length;
	   if(option_selected_count==0)
	  {
		  alert("Please choose atleast one school");
		  document.getElementById("school_to").focus();
		  return false;
	  }
	  var option_selected_count = $('#search_to :selected').length;
	   if(option_selected_count==0)
	  {
		  alert("Please choose atleast one item");
		  document.getElementById("search_to").focus();
		  return false;
	  }
	  
	  return true;
  }
  function checkall(chkobj)
  {
	 // alert(chkobj.checked);
	    $('#items option').prop('selected', chkobj.checked);
  }
   function checkall2(chkobj)
  {
	 // alert(chkobj.checked);
	    $('#items_selected option').prop('selected', chkobj.checked);
  }
  
  
  function checkallupdate()
  {
	  var option_count = $('#items > option').length;
	  var option_selected_count = $('#items :selected').length;
	  if(option_count==option_selected_count)
	  {
		 // alert("equalo");
		 $("#chkall").prop('checked', true);
	  }
	  else{
		  //  alert("not equal");
		  $("#chkall").prop('checked', false);
	  }
	
  }
  function additem()
  {
	  $('#items :selected').each(function(selected_index, selected_item) {
			alert(selected_item +"-"+ selected_item.value+"-")
    });
  }
  </script>
  <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.js"></script>
<script type="text/javascript" src="<?php echo site_url();?>dist/js/multiselect.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    // make code pretty
    window.prettyPrint && prettyPrint();

    $('#search').multiselect({
        search: {
            left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
            right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
        }
    });
	
	   $('#school').multiselect({
        search: {
            left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
            right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
        }
    });
	
	
});


</script>
  