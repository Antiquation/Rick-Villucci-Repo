<?php
/**
 *
 * Author: Rick Villucci
 * Date: Spring 2014
 * 
 * This is a Resume Object for storing the phone nummbers of the user
 * 
 */

require_once("Position.php");
require_once("Phone.php");
require_once("Address.php");
require_once("History.php");


 class Resume
 {

 //Variables
 public $Name;//loaded by DB
 public $DateCreated;
 public $position;
 public $address;
 public $jobHistory;//depricated - ment to be used to cusom add each job - now just using all the jobs
 public $phone;
 
 //Loads from DB For data redydration
 public $ResumeNumber;
 public $PhoneNumber;
 public $AddressNumber;
 

    /**
	 * Zero Arg Constructor for creating new Resume number
	 */
	public function __construct()
	{
         $this ->position = new Position();
         $this ->phone = new Phone();
         $this ->address = new Address;
         $this ->jobHistory = new History();
	}


	/**
    * takes the name and checks it for validity. If valid returns a valid class and stores it
    * else returns an error class
    */
    function set_name( $name, &$error )
	{
		if (strlen($name) > 0)
		{
            //sanitizes the name
			$this->name = filter_var(trim($name), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH);

                //return a valid css class
			return "class='valid'";
		}
		else
		{
            $error = "Enter Name";
			return "class='error'";
		}
	
	}//end set name

    function add_job_to_history($name, $jobObj)
    {
        $this->jobHistory->add_job($name,$jobObj);
        
    }
    
    //adds the address object to the resume
    function add_address($addressObj)
    {
        
        $this->address = $addressObj;
        
    }
    
    //adds the Phone object to the resume
    function add_phone($phoneObj)
    {
        
        $this->phone = $phoneObj;
        
    }
    
    //adds the Position object to the resume
    function add_position($positionObj)
    {
        
        $this->position = $positionObj;
        
    }
    
    //****GETTERS------------------------------------------------------------------
    
    //get the date the item was created
    function getDate()
    {
        
        return $this->DateCreated;   
    }
    
    //gets the name and returns it
    function getName()
    {
       return $this->Name;
        
    }
    
    //returns the resume number for the resume
    function getResumeNumber()
    {
        return $this->ResumeNumber;    
    }
    //gets the address
    function getAddress()
    {
        return $this->address;
        
    }
    //returns the phone number
    function getPhone()
    {
        return $this->PhoneNumber;   
        
    }
    //returns the history containing all the jobs
    function getHistory()
    {
        
        return $this->jobHistory;   
    }
    //gets the position object
    function getPosition()
    {
        return $this->position;   
        
    }
 }//end class Resume

?>