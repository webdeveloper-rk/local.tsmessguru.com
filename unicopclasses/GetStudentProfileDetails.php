<?php

class GetStudentProfileDetails
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
     * @var int $Class
     */
    protected $Class = null;

    /**
     * @var string $SchoolCode
     */
    protected $SchoolCode = null;

    /**
     * @param string $UserName
     * @param string $Password
     * @param string $Date
     * @param int $Class
     * @param string $SchoolCode
     */
    public function __construct($UserName, $Password, $Date, $Class, $SchoolCode)
    {
      $this->UserName = $UserName;
      $this->Password = $Password;
      $this->Date = $Date;
      $this->Class = $Class;
      $this->SchoolCode = $SchoolCode;
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
     * @return GetStudentProfileDetails
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
     * @return GetStudentProfileDetails
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
     * @return GetStudentProfileDetails
     */
    public function setDate($Date)
    {
      $this->Date = $Date;
      return $this;
    }

    /**
     * @return int
     */
    public function getClass()
    {
      return $this->Class;
    }

    /**
     * @param int $Class
     * @return GetStudentProfileDetails
     */
    public function setClass($Class)
    {
      $this->Class = $Class;
      return $this;
    }

    /**
     * @return string
     */
    public function getSchoolCode()
    {
      return $this->SchoolCode;
    }

    /**
     * @param string $SchoolCode
     * @return GetStudentProfileDetails
     */
    public function setSchoolCode($SchoolCode)
    {
      $this->SchoolCode = $SchoolCode;
      return $this;
    }

}
