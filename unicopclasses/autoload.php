<?php


 function autoload_68e0321784673c45b147d02ba405888b($class)
{
    $classes = array(
        'GetTribalWSdata' => __DIR__ .'/GetTribalWSdata.php',
        'GetTribalAttendanceDetails' => __DIR__ .'/GetTribalAttendanceDetails.php',
        'GetTribalAttendanceDetailsResponse' => __DIR__ .'/GetTribalAttendanceDetailsResponse.php',
        'GetTribalAttendanceDetailsResult' => __DIR__ .'/GetTribalAttendanceDetailsResult.php',
        'GetStudentProfileDetails' => __DIR__ .'/GetStudentProfileDetails.php',
        'GetStudentProfileDetailsResponse' => __DIR__ .'/GetStudentProfileDetailsResponse.php',
        'GetStudentProfileDetailsResult' => __DIR__ .'/GetStudentProfileDetailsResult.php'
    );
    if (!empty($classes[$class])) {
        include $classes[$class];
    };
}

spl_autoload_register('autoload_68e0321784673c45b147d02ba405888b');

// Do nothing. The rest is just leftovers from the code generation.
{
}
