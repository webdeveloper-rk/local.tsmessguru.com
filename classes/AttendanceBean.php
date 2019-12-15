<?php

class AttendanceBean
{

    /**
     * @var int $absencecount
     */
    protected $absencecount = null;

    /**
     * @var string $attdate
     */
    protected $attdate = null;

    /**
     * @var int $classid
     */
    protected $classid = null;

    /**
     * @var string $classname
     */
    protected $classname = null;

    /**
     * @var int $district_id
     */
    protected $district_id = null;

    /**
     * @var string $district_name
     */
    protected $district_name = null;

    /**
     * @var int $halfdayleavecount
     */
    protected $halfdayleavecount = null;

    /**
     * @var int $leavecount
     */
    protected $leavecount = null;

    /**
     * @var int $presencecount
     */
    protected $presencecount = null;

    /**
     * @var int $schoolid
     */
    protected $schoolid = null;

    /**
     * @var string $schoolname
     */
    protected $schoolname = null;

    /**
     * @var int $totalcount
     */
    protected $totalcount = null;

    /**
     * @var int $zone_id
     */
    protected $zone_id = null;

    /**
     * @var string $zone_name
     */
    protected $zone_name = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return int
     */
    public function getAbsencecount()
    {
      return $this->absencecount;
    }

    /**
     * @param int $absencecount
     * @return AttendanceBean
     */
    public function setAbsencecount($absencecount)
    {
      $this->absencecount = $absencecount;
      return $this;
    }

    /**
     * @return string
     */
    public function getAttdate()
    {
      return $this->attdate;
    }

    /**
     * @param string $attdate
     * @return AttendanceBean
     */
    public function setAttdate($attdate)
    {
      $this->attdate = $attdate;
      return $this;
    }

    /**
     * @return int
     */
    public function getClassid()
    {
      return $this->classid;
    }

    /**
     * @param int $classid
     * @return AttendanceBean
     */
    public function setClassid($classid)
    {
      $this->classid = $classid;
      return $this;
    }

    /**
     * @return string
     */
    public function getClassname()
    {
      return $this->classname;
    }

    /**
     * @param string $classname
     * @return AttendanceBean
     */
    public function setClassname($classname)
    {
      $this->classname = $classname;
      return $this;
    }

    /**
     * @return int
     */
    public function getDistrict_id()
    {
      return $this->district_id;
    }

    /**
     * @param int $district_id
     * @return AttendanceBean
     */
    public function setDistrict_id($district_id)
    {
      $this->district_id = $district_id;
      return $this;
    }

    /**
     * @return string
     */
    public function getDistrict_name()
    {
      return $this->district_name;
    }

    /**
     * @param string $district_name
     * @return AttendanceBean
     */
    public function setDistrict_name($district_name)
    {
      $this->district_name = $district_name;
      return $this;
    }

    /**
     * @return int
     */
    public function getHalfdayleavecount()
    {
      return $this->halfdayleavecount;
    }

    /**
     * @param int $halfdayleavecount
     * @return AttendanceBean
     */
    public function setHalfdayleavecount($halfdayleavecount)
    {
      $this->halfdayleavecount = $halfdayleavecount;
      return $this;
    }

    /**
     * @return int
     */
    public function getLeavecount()
    {
      return $this->leavecount;
    }

    /**
     * @param int $leavecount
     * @return AttendanceBean
     */
    public function setLeavecount($leavecount)
    {
      $this->leavecount = $leavecount;
      return $this;
    }

    /**
     * @return int
     */
    public function getPresencecount()
    {
      return $this->presencecount;
    }

    /**
     * @param int $presencecount
     * @return AttendanceBean
     */
    public function setPresencecount($presencecount)
    {
      $this->presencecount = $presencecount;
      return $this;
    }

    /**
     * @return int
     */
    public function getSchoolid()
    {
      return $this->schoolid;
    }

    /**
     * @param int $schoolid
     * @return AttendanceBean
     */
    public function setSchoolid($schoolid)
    {
      $this->schoolid = $schoolid;
      return $this;
    }

    /**
     * @return string
     */
    public function getSchoolname()
    {
      return $this->schoolname;
    }

    /**
     * @param string $schoolname
     * @return AttendanceBean
     */
    public function setSchoolname($schoolname)
    {
      $this->schoolname = $schoolname;
      return $this;
    }

    /**
     * @return int
     */
    public function getTotalcount()
    {
      return $this->totalcount;
    }

    /**
     * @param int $totalcount
     * @return AttendanceBean
     */
    public function setTotalcount($totalcount)
    {
      $this->totalcount = $totalcount;
      return $this;
    }

    /**
     * @return int
     */
    public function getZone_id()
    {
      return $this->zone_id;
    }

    /**
     * @param int $zone_id
     * @return AttendanceBean
     */
    public function setZone_id($zone_id)
    {
      $this->zone_id = $zone_id;
      return $this;
    }

    /**
     * @return string
     */
    public function getZone_name()
    {
      return $this->zone_name;
    }

    /**
     * @param string $zone_name
     * @return AttendanceBean
     */
    public function setZone_name($zone_name)
    {
      $this->zone_name = $zone_name;
      return $this;
    }

}
