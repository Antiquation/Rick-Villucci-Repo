<?php

/**
 *
 * Author: Rick Villucci
 * Date: Spring 2014
 * 
 * This is a Phone Object for storing the phone nummbers of the user
 * 
 */


 class Position
 {
 //Variables
 public $Name;
 public $Description;
 public $PositionNumber;
 public $ResumeNumber;



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



     //sets the job description
    function set_position_description($description,&$error)
    {
         if (strlen($description) > 0)
		{
              //sanitize and feed back up the pipe to display in the form and save on the server
             $this->description = filter_var(trim($description), FILTER_SANITIZE_NUMBER_INT);  

              //return a valid css class
			return "class='valid'";  
        }
        else
        {
              $error = "You Must Enter a Position Description";

              //return a invalid css class
			       return "class='error'";
        }

    }//end set job description

 }//end class position

?>