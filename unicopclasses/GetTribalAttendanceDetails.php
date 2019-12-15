<?php

class GetTribalAttendanceDetails
{

    /**
     * @var string $UserName
     */
    protected $UserName = null;

    /**
     * @var string $Password
     */
    protected $Password = null;

    /**
     * @var string $Date
     */
    protected $Date = null;

    /**
     * @param string $UserName
     * @param string $Password
     * @param string $Date
     */
    public function __construct($UserName, $Password, $Date)
    {
      $this->UserName = $UserName;
      $this->Password = $Password;
      $this->Date = $Date;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
      return $this->UserName;
    }

    /**
     * @param string $UserName
     * @return GetTribalAttendanceDetails
     */
    public function setUserName($UserName)
    {
      $this->UserName = $UserName;
      return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
      return $this->Password;
    }

    /**
     * @param string $Password
     * @return GetTribalAttendanceDetails
     */
    public function setPassword($Password)
    {
      $this->Password = $Password;
      return $this;
    }

    /**
     * @return string
     */
    public function getDate()
    {
      return $this->Date;
    }

    /**
     * @param string $Date
     * @return GetTribalAttendanceDetails
     */
    public function setDate($Date)
    {
      $this->Date = $Date;
      return $this;
    }

}
