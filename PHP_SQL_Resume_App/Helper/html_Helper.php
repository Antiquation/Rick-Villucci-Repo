<?php
/**
 *
 * Author: Rick Villucci
 * Date: Spring 2014
 * 
 * This file has all the reuseable HTML and session info for building various pages
 * 
 */
//starts the new session
session_start();

//session_regenerate_id(true);//changes the session ID of the user for security 

if (! isset($_SESSION['User_Obj']) )
{
    $_SESSION['User_Obj'] = new User();
    
}

//global variables

//checks if the user is a guest
$isGuest = $_SESSION['User_Obj'] ->isGuest;
$isAdmin = $_SESSION['User_Obj'] ->isAdmin;
$ref = "";
//supresses nay errors
if(!isset($_SERVER['HTTP_REFERER']))
{
 $_SERVER['HTTP_REFERER'] = "";   
}
$ref = $_SERVER['HTTP_REFERER'];

//if the refering page was admin then change to not guest
if( basename($ref) == "Admin.php" && $isGuest == true)
{
    $_SESSION['User_Obj'] ->isGuest = false;   
}

if($isGuest)
{
   
        $loginform = "LogMeIn";
        $buttonID = "logmein";
        $RealName = "Guest User" ;
   
}
else 
{
    $RealName = $_SESSION['User_Obj']->getName();
    $loginform = "Logout";
    $buttonID = "logout";
}

$logErr = "";
$message = "";



//This changes the boxes color upon error
//and is also used to stop logic if error exists
$errClass = "";



    
//ifNewUser
if(isset($_SESSION['New_User']))
{
    $type = $_SESSION['New_User'];
}
else{
    $type = false;
}

/**
 * This function builds all the basics of the html page that 
 *are common to all the pages accross the application and allows for 
 *additional HTML content and javascript links
 ***---Additional = filler content specific to page
 ***---Style = is the file style sheet identfiers which can change depending on the local of the displayed page
 ***---Link = this is any additional javascript type identifiers
 ***---New = This identifies the user as new and sends additional information
 **/
function build_html_page_header($additional,$style,$link,$new,$menu,$log)
{
    global $RealName;
    global $loginform;
    global $buttonID;
    
    //changes the path in we are on index
    if($link)
    {
        $mainjs = "<script src='Helper/buttonHelp.js' type='text/javascript'></script>";
        $link = "";
    }
    else{
        $mainjs = "<script src='../Helper/buttonHelp.js' type='text/javascript'></script>
        <script src='../Helper/adminProcess.js' type='text/javascript'></script>";
    }
    
    
    $out = "";
    if($log)
    {
        $out = "
        <form method='post' id='$loginform'>
      <div id='logout'>
       <label for='$buttonID'>$RealName</label>
    <input type='button' id='$buttonID' name='$loginform'  value='$loginform' />
    </div>
     <!--Hidden button for submit from jquery--!>
     <input type='submit' id='LogoutSubmit' hidden  name='$loginform' 'value='$loginform'/>
    </form>
    ";
    }

       $script = "
 <script src='//code.jquery.com/jquery-1.9.1.js'></script> 
              $mainjs
               <script src='http://jquery.bassistance.de/validate/jquery.validate.js'></script>
                <script src='http://jquery.bassistance.de/validate/additional-methods.js'></script>";
  
      
       
  $newUser = "";
    if(isset($new) && $new == true)
    {
        //This displays three boxes up top which show progress
        $newUser = "
       
         <h2 class='Welcome'>Welcome $RealName Thank You For Joining Us...Please Log In</h2>
        
     ";
        
    }
 return "
 	  <!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset='utf-8' />
        <title>Rick Villucci PS5</title>
            $style
              $link $script
    </head>
    <body>

        <!-- wraps the site so that it scales with the size of the page -->
  <div id='wrapperIndex'>
    $out $menu 
     
    
    <div id='header'>
      <h3>Resume Builder With Data Base PS5</h3>
      CS4540 - Spring 2014 By: Rick Villucci
    </div><br />
   $newUser
    </br>
    $additional

        </div><!--End of wrapper-->
    </body>
</html>
 		";
}


//***************************************COMMON FORM SUBMIT LOGIC********************

    //if submitting to log out from the application
if (isset ( $_REQUEST ['Logout'] ))
{
   
    //kills the session and all variables
        session_unset();
        session_destroy(); 
        header("Location: ../index.php");
        exit();
        
   
}

//if submitting for login from the logme in button during guest browsing
if (isset ( $_REQUEST ['LogMeIn'] ))
{
    $_SESSION['POO'] = basename($_SERVER['PHP_SELF']);
    header("Location: ../index.php");
    exit();
    
    
}

//if trying to view resume
if (isset ( $_REQUEST ['Resume_View'] ) )
{
    //sets the resume number to this variable so it can be pulled by the viewer
    $_SESSION['View_Resume'] = $_REQUEST ['Resume_Selected'];//sends the resume to be viewed
   
}

//if Submitting for guest view from the index or admin pages
if (isset ( $_REQUEST ['Guest'] ))
{
    
   
    
    //if not an admin then don't open in new window
    if(!$isAdmin)
    {
        //create guest user
        $Guest =  new User();
        $Guest->RealName = "Guest";
        $Guest->isGuest = true;
        //set the session User
        $_SESSION['User_Obj'] = $Guest;
        // Redirect to display resume page
        header("Location: View/AddressEdit.php");
    }
    else
    {
        $_SESSION['User_Obj']->isGuest = true;
    }
    
    
    
}//end if Guest



//***************************************END COMMON FORM SUBMIT LOGIC********************



//**************************************COMMON MENU CREATION LOGIC**********************
/**
 * takes the file name and admin status and returns the nav menu
*--$active = the file name of the current page
*--$AdminEdit = Boolean if the page is an admin only page returns admin nav bar
*--$UserObj = the current session user object
 **/
function makeNav($active,$AdminEdit,$userObj)
{
    $navArray = array(
        "Resume Archive" => "ResumeArchive.php",
        "Address" => "AddressEdit.php",
        "Phone" => "PhoneEdit.php",
        "Job" => "JobEdit.php",
        "Help/Info" => "help.html",
        "Admin" => "Admin.php"
        
        );
   
    
            //if they are not an admin delete the admin link
        if(!$userObj->isAdmin)
        {
            unset( $navArray["Admin"]);
        }
        
        //if they are a guest then remove the multiple resume capability and admin link
        if($userObj->isGuest)
        {
            unset($navArray["Admin"]);
            unset($navArray["Resume Archive"]);
            $navArray = array("Create Resume" => "ResumeEdit.php") + $navArray;
          
        }
    
    //BUILDS THE MENU
  //sets the begining of the menu
        $result = "
         <!--Menu-->
            <div id='cssmenu'>
            <ul>";
        
        
        //iterates through the array and adds additional items
        foreach ($navArray as $key => $value) 
        {      
           
            $selection = ($active == $value) ? " class='active'" : "";
            $result = $result . "<li $selection><a href='$value'><span>$key</span></a></li>";
        }
        //add closing brace
        $result = $result . " 
         </ul>
            </div>";
        
        return $result;//return the nave menu
}

//**************************************END COMMON MENU CREATION LOGIC**********************




//***********************GET USER INFO**********************

//Populates the existing user values from the Database and loads into the session

//gets all the info for a user resume list from the DB
function loadUserDetail($db,&$object)
{
    $tempID = $object->getID();//pulls the id from the session object
    
    //___________________________________________________________________________________
    //creates All the Jobs the user has entered
    
    $stmt = $db->prepare ( "
SELECT JobNumber, Name, StartMonth, StartYear, EndMonth, EndYear, JobDescription FROM ps5_Job_Description WHERE JobNumber IN (Select JobNumber FROM ps5_Job Where ID='$tempID')");
    
    if($stmt->execute())
    {
        
        //set the fetch mode to create a Job class 
        $stmt->setFetchMode( PDO::FETCH_CLASS, 'Job');
        while($row = $stmt->fetch())
        {
            //add jobs to the session
            $numTemp = $row->JobNumber;
            $object->jobArray[$numTemp] = $row;
        }
        
    }//end execute
    unset($stmt);//kills the query statement
    
    //___________________________________________________________________________________
    //creates All the addresses the user has entered
    
    $stmt = $db->prepare ( "
    SELECT Name, Address1, Address2, City, State, AddressNumber FROM ps5_Address_Detail WHERE AddressNumber IN (Select AddressNumber FROM ps5_Address Where ID='$tempID')");
    
    if($stmt->execute())
    {
        
        //set the fetch mode to create a Address class
        $stmt->setFetchMode( PDO::FETCH_CLASS, 'Address');
        while($row = $stmt->fetch())
        {
            //add address to the session
            
            $object->addressArray[$row->AddressNumber] = $row;
        }
        
    }//end execute
    unset($stmt);//kills the query statement
    
    
    
    //___________________________________________________________________________________
    //creates All the Phones the user has entered
    
    $stmt = $db->prepare ( "SELECT Name, PhoneNumber, Actual FROM ps5_Phone WHERE ID='$tempID'");
    
    if($stmt->execute())
    {
        
        //set the fetch mode to create a Phone class 
        $stmt->setFetchMode( PDO::FETCH_CLASS, 'Phone');
        while($row = $stmt->fetch())
        {
            //add phone to the session
            $object->phoneArray[$row->PhoneNumber] = $row;
        }
        
    }//end execute
    unset($stmt);//kills the query statement

    
    //___________________________________________________________________________________
    //creates the Resume
    //gives me the name of all the resumes the user has
    
    $stmt = $db->prepare ( "
    SELECT Name, ResumeNumber, PhoneNumber, AddressNumber, DateCreated FROM ps5_Resume_Detail WHERE ID='$tempID'" );
    
    if($stmt->execute())
    {
        //set the fetch mode to create a Resume class 
        $stmt->setFetchMode( PDO::FETCH_CLASS, 'Resume');
        while($row = $stmt->fetch())
        {
            $object->resumeArray[$row->ResumeNumber] = $row;
            
        }
        
    }//end execute
    unset($stmt);//kills the query statement
    
    

    //___________________________________________________________________________________
    //adds the position to the users resume
    
    //selects only the position associated with the resume
    $stmt = $db->prepare ( "
   SELECT ResumeNumber, PositionNumber, Name, Description FROM ps5_Position NATURAL JOIN ps5_Position_Detail 
   WHERE ResumeNumber IN (Select ResumeNumber FROM ps5_Resume_Detail Where ID='$tempID')");
    
    if($stmt->execute())
    {
        
        //set the fetch mode to create a Position class 
        $stmt->setFetchMode( PDO::FETCH_CLASS, "Position");
        while($row = $stmt->fetch())
        {
            $resNum = $row->ResumeNumber;
            
            $tempResume = $object->get_resume($resNum);//gets resume by number from the session for user
            $tempResume->add_position($row); //adds the position object to the resume
            
        }
        
    }//end execute
    unset($stmt);//kills the query statement
    
    
    //___________________________________________________________________________________
    //Adds the addresses to the resume as designated
    
    foreach($object->resumeArray as $resume)
    {
        $addressNum = $resume->AddressNumber;//key for address stored in session object
        $phoneNum = $resume->PhoneNumber;//key for phone stored in session object
        $resume->add_address($object->get_address($addressNum));//sets the address to the resume
        $resume->add_phone($object->get_phone($phoneNum));//sets the phone to the resume
        
    }//end for each
    
    
    
    
}//end Load User Detail



?>