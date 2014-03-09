<?php


/**
 *
 * Author: Rick Villucci
 * Date: Spring 2014
 * 
 * This is an Job History Object for storing the addresses of the user
 * 
 */


 class History
 {

 //Variables
 public $JobHistory;
 public $jobHistoryArray = array();//contains all the jobs associated with the resume


        //adds job to the array using the job name as the key and the object as the value
    function add_job($jobName,$job)
    {
        $this ->jobHistoryArray[$jobName] = $job; 
    }

         //removes job from the array using the job name as the key and the object as the value
    function remove_job($jobName)
    {

        unset( $this ->jobHistoryArray[$jobName]);
       
    }
    //returns the job array
    function getHistoryArray()
    {
        
        return $this->jobHistoryArray;   
    }
   
}//end class history

?>