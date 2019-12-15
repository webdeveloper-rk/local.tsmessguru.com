<?php
require "classes/HasService.php";
$obj = new HasService();
$results = $obj->getStateWiseAttendance();
echo "<pre>";print_r($results);

?>