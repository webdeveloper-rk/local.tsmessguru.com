<?php

class getStateWiseAttendanceResponse
{

    /**
     * @var AttendanceBean $return
     */
    protected $return = null;

    /**
     * @param AttendanceBean $return
     */
    public function __construct($return)
    {
      $this->return = $return;
    }

    /**
     * @return AttendanceBean
     */
    public function getReturn()
    {
      return $this->return;
    }

    /**
     * @param AttendanceBean $return
     * @return getStateWiseAttendanceResponse
     */
    public function setReturn($return)
    {
      $this->return = $return;
      return $this;
    }

}
