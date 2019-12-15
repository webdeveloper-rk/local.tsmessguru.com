<?php

class getStateWiseAttendance
{

    /**
     * @var string $date
     */
    protected $date = null;

    /**
     * @param string $date
     */
    public function __construct($date)
    {
      $this->date = $date;
    }

    /**
     * @return string
     */
    public function getDate()
    {
      return $this->date;
    }

    /**
     * @param string $date
     * @return getStateWiseAttendance
     */
    public function setDate($date)
    {
      $this->date = $date;
      return $this;
    }

}
