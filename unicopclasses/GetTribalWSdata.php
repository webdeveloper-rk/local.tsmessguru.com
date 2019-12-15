<?php

class GetTribalWSdata extends \SoapClient
{

    /**
     * @var array $classmap The defined classes
     */
    private static $classmap = array (
  'GetTribalAttendanceDetails' => '\\GetTribalAttendanceDetails',
  'GetTribalAttendanceDetailsResponse' => '\\GetTribalAttendanceDetailsResponse',
  'GetTribalAttendanceDetailsResult' => '\\GetTribalAttendanceDetailsResult',
  'GetStudentProfileDetails' => '\\GetStudentProfileDetails',
  'GetStudentProfileDetailsResponse' => '\\GetStudentProfileDetailsResponse',
  'GetStudentProfileDetailsResult' => '\\GetStudentProfileDetailsResult',
);

    /**
     * @param string $wsdl The wsdl file to use
     * @param array $options A array of config values
     */
    public function __construct(array $options = array(), $wsdl = null)
    {
    
  foreach (self::$classmap as $key => $value) {
    if (!isset($options['classmap'][$key])) {
      $options['classmap'][$key] = $value;
    }
  }
      $options = array_merge(array (
  'features' => 1,
), $options);
      if (!$wsdl) {
        $wsdl = 'http://52.163.226.227/TribalWebServices/GetTribalWSdata.asmx?wsdl';
      }
      parent::__construct($wsdl, $options);
    }

    /**
     * @param GetTribalAttendanceDetails $parameters
     * @return GetTribalAttendanceDetailsResponse
     */
    public function GetTribalAttendanceDetails(GetTribalAttendanceDetails $parameters)
    {
      return $this->__soapCall('GetTribalAttendanceDetails', array($parameters));
    }

    /**
     * @param GetStudentProfileDetails $parameters
     * @return GetStudentProfileDetailsResponse
     */
    public function GetStudentProfileDetails(GetStudentProfileDetails $parameters)
    {
      return $this->__soapCall('GetStudentProfileDetails', array($parameters));
    }

}
