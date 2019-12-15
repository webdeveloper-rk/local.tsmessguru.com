<?php

class HasService extends \SoapClient
{

    /**
     * @var array $classmap The defined classes
     */
    private static $classmap = array (
  'AttendanceBean' => '\\AttendanceBean',
  'getStateWiseAttendance' => '\\getStateWiseAttendance',
  'getStateWiseAttendanceResponse' => '\\getStateWiseAttendanceResponse',
);

    /**
     * @param array $options A array of config values
     * @param string $wsdl The wsdl file to use
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
        $wsdl = 'http://182.18.156.60:9090/swfWebService/services/HasService?wsdl';
      }
      parent::__construct($wsdl, $options);
    }

    /**
     * @param getStateWiseAttendance $parameters
     * @return getStateWiseAttendanceResponse
     */
    public function getStateWiseAttendance(getStateWiseAttendance $parameters)
    {
      return $this->__soapCall('getStateWiseAttendance', array($parameters));
    }

}
