<?php

class GetTribalAttendanceDetailsResponse
{

    /**
     * @var GetTribalAttendanceDetailsResult $GetTribalAttendanceDetailsResult
     */
    public  $GetTribalAttendanceDetailsResult = null;

    /**
     * @param GetTribalAttendanceDetailsResult $GetTribalAttendanceDetailsResult
     */
    public function __construct($GetTribalAttendanceDetailsResult)
    {
      $this->GetTribalAttendanceDetailsResult = $GetTribalAttendanceDetailsResult;
    }

    /**
     * @return GetTribalAttendanceDetailsResult
     */
    public function getGetTribalAttendanceDetailsResult()
    {
      return $this->GetTribalAttendanceDetailsResult;
    }

    /**
     * @param GetTribalAttendanceDetailsResult $GetTribalAttendanceDetailsResult
     * @return GetTribalAttendanceDetailsResponse
     */
    public function setGetTribalAttendanceDetailsResult($GetTribalAttendanceDetailsResult)
    {
      $this->GetTribalAttendanceDetailsResult = $GetTribalAttendanceDetailsResult;
      return $this;
    }

}
