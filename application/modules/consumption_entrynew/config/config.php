<?php
$config['saturday_exemption'] = false;
$config['sunday_exemption'] = false;
$config['holiday_exemption'] = false;
$config['day_max_limit'] =  50000;
$config['fuel_item_excemption'] = true;
$config['dpc_rates_enabled'] = true;
$config['dpc_rates_start_date'] = '2019-04-01';
$config['day_start_time'] = '13:00:00'; 

$date = date('Y-m-d');
if($date == "2019-12-06" || $date == "2019-12-07")
{
	$config['day_max_limit'] =  200000;
}
?>