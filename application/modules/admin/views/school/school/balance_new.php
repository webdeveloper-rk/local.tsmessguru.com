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

<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><b>Consumed Till date for current Month -123</b></h3>
            </div>
            <!-- /.box-header -->
 
			 
              <div class="box-body">
                 <div>
				 <table><tr>
					<td><select name="month" id="month" >
					<option value="">Select Month</option>
					<?php 
					$months = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May",
									"06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");
					foreach($months as $key_month =>$month_name) { 
					$selected = '';
					if($key_month == $month){ $selected =  " selected =selected ";}
					echo '<option value="'.$key_month .'" '. $selected .'>'.$month_name.'</option>';
					  } ?>
					  </select></td><td>
					 
					  
					 <select name="year" id="year" >
					<option value="">Select Year</option>
					<?php 
					for($i=2016;$i<=date('Y');$i++)
					{ 
							$selected = '';
							if( $i == $year){ 
												$selected =  " selected =selected ";
											}
								echo '<option value="'.$i .'" '. $selected .'>'.$i.'</option>';
					}
					?>
					  </select> 
					  </td><td> <input type="button" value= "Get Report"  onclick='redirectform()'></td></tr></table>
				 </div>
				    <!-- Date -->
              <div class="form-group">
                <label>Total Consumed:</label>

                <div class="input-group date">
                 <?php echo number_format($consumed_amount,2);?>
              </div>
			  </div>
			  
			   <div class="form-group">
                <label>Allowed Amount</label>

                <div class="input-group date">
                  <?php echo number_format($allowed_amount,2);?>
              </div>
			  </div>
			   <div class="form-group">
                <label>Remaing Balance</label>

                <div class="input-group date">
                 <?php echo number_format($balance,2);?>
              </div>
			  </div>
             
            
			 
			
          </div> 
		  
		  <script>
		  function redirectform()
		  {
			  window.location.href='<?php echo site_url('admin/school/todaybalance_new');?>/'+$("#month").val()+ "/"+$("#year").val()
		  }
		  
		  
		  </script>