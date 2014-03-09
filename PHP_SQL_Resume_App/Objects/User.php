<?php
/**
 *
 * Author: Rick Villucci
 * Date: Spring 2014
 * 
 * This is a User Object for storing the user data members of the user
 * 
 */


 class User
 {

 //Variables Set By the DB
 public $RealName;
 public $UserName;
 public $ID;
 public $isAdmin;
 public $isGuest;

 
        //these variables represent all the available resumes, phone numbers, addresses, and jobs
        //availible to build a resume.
 public $resumeArray = array();
 public $phoneArray= array();
 public $addressArray= array();
 public $jobArray= array();

  
    //function for logging in to the server
public function getID()
{
   return $this ->ID;

}

//gets the users name
public function getName()
{
    return $this->RealName;

}

//adds the resume obj to the resume array
public function addResume($Num, $resume)
{
    $resumeArray["$Num"] = $resume;
}

//adds the resume obj to the resume array
public function addJob($Num, $job)
{
    $this->jobArray["$Num"] = $job;
}

public function get_resume($resumeNum)
{
 return $this->resumeArray[$resumeNum];   
}

//returns the job with the given name
public function get_job($name)
{
 return  $this ->jobArray[$name];
}

//gets address Obj by address number
public function get_address($key)
{
    return $this->addressArray[$key];
}

//gets Phone Obj by Phone number
public function get_phone($key)
{
    return $this->phoneArray[$key];
}


 }//end user class

?>