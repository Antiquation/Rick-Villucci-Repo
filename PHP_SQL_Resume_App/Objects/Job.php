<?php


/**
 *
 * Author: Rick Villucci
 * Date: Spring 2014
 * 
 * This is an Job Object for storing the addresses of the user
 * 
 */


 class Job
 {

 //Variables are loaded if preexisting by the database
    public $Name;
    public $StartMonth;
    public $StartYear;
    public $EndMonth;
    public $EndYear;
    public $JobDescription;
 

    /**
    * takes the name and checks it for validity. If valid returns a valid class and stores it
    * else returns an error class
    */
    function set_name( $name, &$error )
	{
		if (strlen($name) > 0)
		{
            //sanitizes the name
			$this->Name = filter_var(trim($name), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH);

                //return a valid css class
			return "class='valid'";
		}
		else
		{
            $error = "Enter Name";
			return "class='error'";
		}
	
	}//end function set name



    //this is loaded via a drop down menu but just to be sure I have checked and sanitized the input
    function set_start_month($startMonth,&$error)
    {
         if (strlen($startMonth) > 0 && $startMonth != "Select A Month")
		{
              if (!preg_match('/[a-zA-Z]/i', $startMonth))
                {
                    //return a invalid css class
			       return "class='error'";
                }
                else
                {
                   $this->StartMonth = filter_var(trim($startMonth), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

                      //return a valid css class
			        return "class='valid'";
                }
         
        }
        else
        {
             //return a invalid css class
			       return "class='error'";
        }

    }//end set start month funtion



    
    //this is loaded via a drop down menu but just to be sure I have checked and sanitized the input
    function set_end_month($endMonth,&$error)
    {
         if (strlen($endMonth) > 0 && $endMonth != "Select A Month")
		{
              if (!preg_match('/[a-zA-Z]/i', $endMonth))
                {
                    //return a invalid css class
			       return "class='error'";
                }
                else
                {
                   $this->EndMonth = filter_var(trim($endMonth), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

                      //return a valid css class
			        return "class='valid'";
                }
         
        }
        else
        {
             //return a invalid css class
			       return "class='error'";
        }

    }//end set start month funtion


    //sets the start year
    function set_start_year($startYear,&$error)
    {
         if (strlen($startYear) > 0)
		{
           //check if the name contains special characters
            if (!preg_match('/^\d{4}$/', $startYear))
            {
               
                $error = "Start Year Is Not Valid";
            }
            else
            {   //sanitize and feed back up the pipe to display in the form and save on the server
             $this->StartYear = filter_var(trim($startYear), FILTER_SANITIZE_NUMBER_INT);   
             
              //return a valid css class
			return "class='valid'"; 
            }
        }//end if >0
          else
        {
             
                $error = "Start Year Must Be Greater Than Zero";

              //return a invalid css class
			       return "class='error'";
        }//end else

    }//end set start year function



    //sets the end year
    function set_end_year($endYear,&$error)
    {
         if (strlen($endYear) > 0)
		{
           //check if the name contains special characters
            if (!preg_match('/^\d{4}$/', $endYear))
            {
               
                $error = "End Year Is Not Valid";
            }
            else
            {   //sanitize and feed back up the pipe to display in the form and save on the server
             $this->EndYear = filter_var(trim($endYear), FILTER_SANITIZE_NUMBER_INT);    

              //return a valid css class
			return "class='valid'";
            }
        }//end if >0
          else
        {
             
                $error = "End Year Must Be Greater Than Zero";

              //return a invalid css class
			       return "class='error'";
        }//end else

    }//end set start year function



    //sets the job description
    function set_job_description($jobDiscription,&$error)
    {
         if (strlen($jobDiscription) > 0)
		{
              //sanitize and feed back up the pipe to display in the form and save on the server
             $this->JobDiscription = filter_var(trim($jobDiscription), FILTER_SANITIZE_NUMBER_INT);    

              //return a valid css class
			return "class='valid'";
        }
        else
        {
              $error = "You Must Enter a Job Description";

              //return a invalid css class
			       return "class='error'";
        }

    }//end set job description

 }//end class job
?>