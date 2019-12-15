<?php

class GetStudentProfileDetailsResponse
{

    /**
     * @var GetStudentProfileDetailsResult $GetStudentProfileDetailsResult
     */
    protected $GetStudentProfileDetailsResult = null;

    /**
     * @param GetStudentProfileDetailsResult $GetStudentProfileDetailsResult
     */
    public function __construct($GetStudentProfileDetailsResult)
    {
      $this->GetStudentProfileDetailsResult = $GetStudentProfileDetailsResult;
    }

    /**
     * @return GetStudentProfileDetailsResult
     */
    public function getGetStudentProfileDetailsResult()
    {
      return $this->GetStudentProfileDetailsResult;
    }

    /**
     * @param GetStudentProfileDetailsResult $GetStudentProfileDetailsResult
     * @return GetStudentProfileDetailsResponse
     */
    public function setGetStudentProfileDetailsResult($GetStudentProfileDetailsResult)
    {
      $this->GetStudentProfileDetailsResult = $GetStudentProfileDetailsResult;
      return $this;
    }

}
