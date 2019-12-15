<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vidya extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 public function index()
	 {
		$sql = "select * from items  where item_id in(1,203,3,4,175,5,6,9,10,16,12,14,7,195,24,15,190,8,246,165,164,26,11,28,36,35,32,33,34,196,18,17,29,22,21,40,201,202,107,108,110)";
		$rs = $this->db->query($sql);
		echo "<style>body { font-family: Verdana,Geneva,sans-serif; }  
table {
    border-collapse: collapse;
    width:90%;
	align:center
}

th, td {
    text-align: left;
    padding: 8px;
	font-family: verdana;
	font-size: 11px;
}

tr:nth-child(even){background-color: #f2f2f2}

th {
    background-color: #4CAF50;
    color: white;
} </style>";
		echo '<html><body><table   border="1"><tr><th> Item Name</th></tr>';
		foreach($rs->result() as $row)
		{
				echo "<tr><td><a href='".site_url('vidya/view/'.$row->item_id)."' target='_blank'>".$row->item_name . " - ".$row->telugu_name."</a></td></tr>";
		}
		echo "</table>";
		 
	 }
	public function view($item_id)
	{
		//$item_id= $this->input->get('item_id');
		echo "<style>body {   }  
table {
    border-collapse: collapse;
    width: 90%;
}

th, td {
    text-align: left;
    padding: 8px;
	font-family: verdana;
	font-size: 11px;
}

tr:nth-child(even){background-color: #f2f2f2}

th {
    background-color: #4CAF50;
    color: white;
} </style>";
		$att_sql = "select * from school_attendence where entry_date='2017-01-28'";
		$att_rs = $this->db->query($att_sql);
		$attendence = array();
		foreach($att_rs->result() as $attrow)
		{
			$attendence[$attrow->school_id] = $attrow->present_count;
		}
		
		if(!isset($item_id)) $item_id = 1;
		
		$sql = "select * from vidya_report  vr inner join schools s on  s.school_id =vr.school_id where item_id='$item_id' order by s.school_id";
		$rs = $this->db->query($sql);
		$data = array();
		$item_name = array();
		$schools_name = array();
		$schools_code = array();
		foreach($rs->result() as $row)
		{
			$data[$row->item_id][$row->school_id][$row->edate] =  $row->qty;
			$item_name[$row->item_id] = $row->telugu_name." - ". $row->item_name;
			$schools_name[$row->school_id] = $row->school_name ;
			$schools_code[$row->school_id] = $row->school_code ;
		}
		 
		 foreach($data as $item_id=>$details){
				
				echo "<br><br><style> </style><h2>".$item_name[$item_id]."</h2><br><br><table   border='1'>";
		 echo "<thead><tr><th>Slno</th><th>Code</th><th>School Name</th>
		 <th>25-01-2017</th>
		 <th>26-01-2017</th>
		 <th>27-01-2017</th>
		 <th>28-01-2017</th>
		 <th>29-01-2017</th>
		 <th>30-01-2017</th>
		 <th>31-01-2017</th>
		 
		 
		 <th>Min</th>
		 <th>Max</th>
		 <th>Total</th>
		 <th>Avg per Day</th>
		 <th>Att - 28th</th>
		 <th>Att - Avg</th>
		 </tr>
		 </thead>";
				$details2 = $details;
				
					$count_num = 1;
					
					
					
					
				foreach($details as $school_id=>$details2)
				{
					
					 //print_a($details2);die;
					 echo "<tr><td>$count_num</td><td>".$schools_code[$school_id]."</td><td>".$schools_name[$school_id]."</td>";
					 
					 $arr_values = array();
					 
					 if(isset($details2['25-01-2017'])){
						 echo "<td>".$details2['25-01-2017']."</td>";
						  $arr_values[] = $details2['25-01-2017'];
					 }else { echo "<td>0.00</td>";}
					 
					  if(isset($details2['26-01-2017'])){
						 echo "<td>".$details2['26-01-2017']."</td>";
						  $arr_values[] = $details2['26-01-2017'];
					 }else { echo "<td>0.00</td>";}
					 
					  if(isset($details2['27-01-2017'])){
						 echo "<td>".$details2['27-01-2017']."</td>";
						  $arr_values[] = $details2['27-01-2017'];
					 }else { echo "<td>0.00</td>";}
					 
					  if(isset($details2['28-01-2017'])){
						 echo "<td>".$details2['28-01-2017']."</td>";
						  $arr_values[] = $details2['28-01-2017'];
					 }else { echo "<td>0.00</td>";}
					 
					  if(isset($details2['29-01-2017'])){
						 echo "<td>".$details2['29-01-2017']."</td>";
						  $arr_values[] = $details2['29-01-2017'];
					 }else { echo "<td>0.00</td>";}
					 
					  if(isset($details2['30-01-2017'])){
						 echo "<td>".$details2['30-01-2017']."</td>";
						  $arr_values[] = $details2['30-01-2017'];
					 }else { echo "<td>0.00</td>";}
					 
					  if(isset($details2['31-01-2017'])){
						 echo "<td>".$details2['31-01-2017']."</td>";
						  $arr_values[] = $details2['31-01-2017'];
					 }else { echo "<td>0.00</td>";}
					
					$qty_Avg = array_sum($arr_values)/7;
					$attendence_qty_Avg = $qty_Avg /$attendence[$school_id];
					  echo "<td>".min($arr_values)."</td>";
					  echo "<td>".max($arr_values)."</td>";
					  echo "<td>".array_sum($arr_values)."</td>";
					  echo "<td>".number_format($qty_Avg,3)."</td>";
					  echo "<td>".$attendence[$school_id]."</td>";
					  echo "<td>".number_format($attendence_qty_Avg,3)."</td>   </tr> ";
								 
						
							
					$count_num++;
					
				}
				
				
					 
				
				
				
		echo "</table><br><br><br><br>";		
				 
		 }
		 
		 
		 
		 
		
	}
}