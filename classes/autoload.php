<?php


 function autoload_ea24fde60886f8137785af5a1377f3ab($class)
{
    $classes = array(
        'HasService' => __DIR__ .'/HasService.php',
        'AttendanceBean' => __DIR__ .'/AttendanceBean.php',
        'getStateWiseAttendance' => __DIR__ .'/getStateWiseAttendance.php',
        'getStateWiseAttendanceResponse' => __DIR__ .'/getStateWiseAttendanceResponse.php'
    );
    if (!empty($classes[$class])) {
        include $classes[$class];
    };
}

spl_autoload_register('autoload_ea24fde60886f8137785af5a1377f3ab');

// Do nothing. The rest is just leftovers from the code generation.
{
}
