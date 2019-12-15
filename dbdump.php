<?php
set_time_limit(0);
mysql_connect("localhost","annapurn_tribal","rZcb0gUN");
mysql_select_db("annapurn_tribal") or die(mysql_error());
echo "Db Connected";

$rs = mysql_query("select * from dic where status=0 order by entry_date desc");
while($data = mysql_fetch_array($rs)){

//echo "<pre>";print_r($data);
$sess_id = $data['session_id'];

$old_qty = $data['old_stock_qty'];
$old_price = $data['old_stock_price'];

$new_qty = $data['new_stock_qty'];
$new_price = $data['new_stock_price'];

$school_id = $data['school_id'];
$item_id = $data['item_id'];
$entry_date = $data['entry_date'];



$sql_u = "update bs set session_".$sess_id."_old_qty='$old_qty',
								   session_".$sess_id."_old_price='$old_price',
								   session_".$sess_id."_new_qty='$new_qty',
								   session_".$sess_id."_new_price='$new_price'
								   where school_id='$school_id' and item_id='$item_id' and entry_date='$entry_date'";	
 mysql_query($sql_u ) or die(mysql_error());
 
 $dsql  = "update dic set status=1  where school_id='$school_id' and item_id='$item_id' and entry_date='$entry_date'";	
 mysql_query($dsql ) or die(mysql_error());
 
	
}

?>