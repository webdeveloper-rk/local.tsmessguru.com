<?php

class GetTribalAttendanceDetailsResult
{

    /**
     * @var string $any
     */
    public $any = null;

    /**
     * @param string $any
     */
    public function __construct($any)
    {
      $this->any = $any;
    }

    /**
     * @return string
     */
    public function getAny()
    {
      return $this->any;
    }

    /**
     * @param string $any
     * @return GetTribalAttendanceDetailsResult
     */
    public function setAny($any)
    {
      $this->any = $any;
      return $this;
    }

}
