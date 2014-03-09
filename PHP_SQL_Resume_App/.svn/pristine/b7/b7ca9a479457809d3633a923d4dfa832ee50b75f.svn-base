<?php

/**
 *
 * Author: Rick Villucci
 * Date: Spring 2014
 * 
 * This is a Phone Object for storing the phone nummbers of the user
 * 
 */


 class Phone
 {

 //Variables
 public $Name;
 public $PhoneNumber;
 public $Actual;//this is the string that represents the number



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
	
	}

 
 
 }//end class phone

?>