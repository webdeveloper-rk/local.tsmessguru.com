<?php $this->load->view("subadmin_sessionpics_form");?>
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

<h3>Session Pictures[ <?php echo $report_date;?> ] </h3>
 <a href="<?php echo site_url('admin/subadmin/sessionpics');?>"><b>Go back</b></a>

 
		  
	 