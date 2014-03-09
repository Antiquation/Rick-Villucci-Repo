<?php

require("../Objects/User.php");//user class for storing the user data
require("../Objects/Address.php");//allows access to internal obj
require("../Objects/Position.php");//allows access to internal obj
require("../Objects/Job.php");//allows access to internal obj
require("../Objects/Phone.php");//allows access to internal obj
require("../Objects/History.php");//allows access to internal obj
require("../Objects/Resume.php");//allows access to internal obj
require("../Helper/html_Helper.php");//for building the various HTML


//****INITIATE THE SESSION****
//if the session variables exist then the variables are loaded into the html
//________________________________________________________________________________

//SessionHandler
$userObj = $_SESSION['User_Obj'];

//***CONTACT VARIABLES______________
if(isset($_SESSION['View_Resume']))
{
    
    //this sets the resume number and loads the resume object into the viewer
    $resumeNumber = $_SESSION['View_Resume'];
    
    //get the resume OBJ
    $Resume = $userObj->resumeArray[$resumeNumber];
    
    //SET VARIABLES-----------------
    
    //get the users name
    $nameUser = $userObj->getName();
    
    //get address OBJ
    $address = $Resume->getAddress();
        //set address variables
        $address1 = $address->Address1;
        $address2 = $address->Address2;
        $city = $address->City;
        $selectedState = $address->State;
 
    //get the phone number Obj
    $phoneObj = $Resume->phone;
    $phone = $phoneObj->Actual;
    
    $jobArray = $userObj->jobArray;//extract the job array from the user
       
        //send the job array to the function to parse
      $thisJob = makeJobList($jobArray);//This makes the jobs and loads them into the variable for html creation
          
      //get the position OBJ
    $Position = $Resume->getPosition();
        //get position variables
        $positionTitle = $Position->Name;
        $PositionD = $Position->Description;

}


//________________________________________________________________________________
//****END INITIATE THE SESSION***


 //populates the jobs into a table that is displayed in the resume
function makeJobList($jobArray)
{
    
    $result= "";
    $Num = 1;//counter for listing the jobs
    
     //iterate and build the html job list from the history of jobs
	foreach ($jobArray as $job) 
    {
         $jobNu = "Job Number:". $Num;
        
        $startMonth = $job->StartMonth;
        $startYear = $job->StartYear;
        $endMonth = $job->EndMonth;
        $endYear = $job->EndYear;
        $jobDiscription = $job->JobDescription;
        
        //starts the html string
        if($Num == 1)
        {
         $result = "<table class='jobTable'>";   
        }
        
		$result = $result . "
                <tr>
                    <td><h4 id='jbH' >$jobNu</h4></td>
                </tr>
                <tr>
                    <td><p id='start'>From: &nbsp;$startMonth&nbsp;&nbsp; OF: &nbsp;$startYear.</p></td>
                    <td><p id='end'>To: &nbsp;$endMonth&nbsp;&nbsp; OF: &nbsp;$endYear</p></td>
                </tr>
                <tr>
                    <td><h4 id='did' >Description:</h4></td>
                </tr>
                <tr>
                    <td><p id='jobD'>$jobDiscription</p></td>
                </tr>
                    \n";
        
        $Num ++;//iterate
  
	}
    
        //end the table
    $result = $result . "</table>";
    
	return $result;
    
}//end creat job list
   


  

//***BUILD THE HTML******************
//________________________________________________________


//for adding the style sheets - this can change based on the path
$style = "
        <link rel='stylesheet' type='text/css' href='resumeMain.css' />
        <link rel='stylesheet' type='text/css' href='styles.css' />";



//This is the main content for this page which will be added to the resused helper code
$additional ="
       <div class='resume'>
        <div class='contact'>
        <h2 id='name'>$nameUser</h2>
            <h3 id='address'>$address1</h3>
            <h3 id='H1'>$address2</h3>
            <h3 id='city_state'>$city &nbsp;&nbsp;&nbsp; $selectedState</h3>
            <h3 id='phone'>Phone:$phone</h3>
        </div>
            <br/>
            <br/>
        <table class='PositionDes'>
        <tr>
            <td>
            <h3 id='PD'>Position Desired:</h3>
            </td>
            <td>
            <p id='title'> $positionTitle</p>
            </td>
         </tr>
         <tr>
            <td>
             <h3 id='PDS'>Position Description:</h3>
             </td>
             <td>
            <p id='disc'> $PositionD</p>
            </td>
        </tr>
        </table>
        <div class='employment'>
            <h2 id='employ'>Employment History:</h2>
            $thisJob
        </div>
    </div>
    
  
    </p>
    

  ";





//builds the page content
$content =  build_html_page_header($additional,$style,"","","",false);

//Dispays all html content
echo
"
 $content
 ";


    
  
?>