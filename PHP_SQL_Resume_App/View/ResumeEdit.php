<?php
/**
 *
 * Author: Rick Villucci
 * Date: Spring 2014
 * 
 * This page is where the user will go to Create and edit their resumes
 * ***THIS PAGE DETECTS IF NEW USER AND WILL CHANGE TO DIRECT THEM THROUGH INITIAL SETUP OF THEIR ACCOUNT
 * 
 */

require_once("../Objects/User.php");//user class for storing the user data
require("../Objects/Resume.php");//allows access to internal address object creation
require ("../Hidden/databasePW.php");
require_once("../Helper/Server.php");//server info
require_once("../Helper/html_Helper.php");//for building the various HTML

//****VARIABLES****-------------------------------------------------------

//This sets the currently editable resume and puts it into the session
$TempResume = new Resume();

$_SESSION['Temp_Resume'] = $TempResume;
//sets the user obj
$userObj = $_SESSION['User_Obj'];

$ResumeNum = ""; 
$resumeArray = $userObj->resumeArray;//loads the sessions resumeArray for display / edit
$addressArray = $userObj->addressArray; 
$phoneArray = $userObj->phoneArray; 
$ID =  $userObj->getID();


$Date = "";
$selectedAddress = "";
$selectedPhone = "";
$Name = "";
$positionTitle = "";
$PositionD = "";

//error variable definitions
$phoErr = "";
$addErr = "";
$posDErr = "";
$posNErr = "";
$nameErr = "";
$buttonDis = "";//disables the submit button if there is no addresses or phone numbers
$message = "";

if(count($addressArray) <1 || count($phoneArray) <1 || count( $userObj->jobArray) <1)
{
    $logErr = "Cannot Create Resume Without Creating Address, Phone Number, and Job";
    $buttonDis = "disabled";
}


//Actions to be done based on state of sesssion--------------------------------------

//********************************EDITING RESUME***********************************************
//_____________________________________________________________________________________________

//if edit number is set then we need to repopulate the form
if (isset ( $_SESSION['Edit_Number'] ) && (!isset( $_REQUEST ['Submit_Resume'])))
 {  
       
         $EditNumber =  $_SESSION['Edit_Number'];//sets the resumenumber we are editing
     
         $TempResume = clone $resumeArray[$EditNumber];//sets the current resume to a clone of the session
         $Name = $TempResume->Name;//gets the resume name
     
           //get the position OBJ
        $Position = $TempResume->position;
            //get position variables
            $positionTitle = $Position->Name;
            $PositionD = $Position->Description;
        //get address OBJ
        $address = $TempResume->address;
            $selectedAddress = $address->Name; //sets the dropdown to the currently selected address
   
        //gets the phone obj    
        $phone = $TempResume->phone;
            $selectedPhone = $phone->Name;//sets the dropdown to the current phone number associated with the resume
        
            //header Message
         $message = "Currently Editing: $Name";
     
         $button="
            <input type='button' $buttonDis onclick='warningOver()' value='Save Resume'/>";
         
         $backButton ="
          <input type='button' id='newUser' value='Back To Resume Archive' onclick=location.href='ResumeArchive.php'; />";
     
     
 }//end isset
 
//_____________________________________________________________________________________________
//********************************END EDITING RESUME*******************************************
 
 
 //********************************NEW RESUME***********************************************
 //_____________________________________________________________________________________________
 
 //if there is no edit number then we are creating a new resume
 //Load blank form
 elseif (!isset ( $_SESSION['Edit_Number'] ))
 {
     
     //header Message only if there address phone and jobs
     if(strlen($buttonDis) <1)
    {
     $message = "Creating A New Resume...";
    }
      //user is authrized takes resume from the archive page
        if(!$isGuest)
        {
         $button="
            <input type='submit' $buttonDis name='Submit_Resume' value='Save Resume'/>";
     
         $backButton ="
          <input type='button' id='newUser' value='Back To Resume Archive' onclick=location.href='ResumeArchive.php'; />";
        }
        //User is guest create from scratch
        else
        {
            $button="
            <input type='submit' $buttonDis name='Submit_Resume' value='Validate Resume'/>";
          
            $backButton ="
          <input type='button' id='newUser' value='Back' onclick=location.href='AddressEdit.php'; />";
        }
 }
 
 
 //_____________________________________________________________________________________________
 //********************************END NEW RESUME*******************************************
 
 
 
//**********************Upon Submit****************************
 //___________________________________________________________
 
           //if submitting for Resume Save or overwrite
 if (isset ( $_REQUEST ['Submit_Resume'] ))
 { 
     //get address OBJ
     $address = $TempResume->address;
     $selectedAddress = $address->Name; //sets the dropdown to the currently selected address
     
     //gets the phone obj    
     $phone = $TempResume->phone;
     $selectedPhone = $phone->Name;//sets the dropdown to the current phone number associated with the resume
     
     
     //validates the name field
     checkValue($Name,$nameErr,$errClass,$_REQUEST ['Resume_Name']);//checks the name of the resume
     
     //validates the name of the Position
     checkValue($positionTitle,$posNErr,$errClass,$_REQUEST ['Position_Name']);
     
     //validates the name of the Position Description
     checkValue($PositionD,$posDErr,$errClass,$_REQUEST ['Position_Description']);
     
     if($errClass == "")
     {
         $AddressNum = $_REQUEST ['Select_Address'];
         $PhoneNum = $_REQUEST ['Select_Phone'];
         
         //only editing if not a guest
         if(!$isGuest)
         {
             $db = connectDB();//creates DB Connection
             
                //editing the resume
             if(isset ( $_SESSION['Edit_Number'] ))
             {
                 $EditNumber =  $_SESSION['Edit_Number'];
                 //updates the resume
                 $create = "UPDATE ps5_Resume_Detail SET `Name`=?, `AddressNumber`=?, `PhoneNumber`=? WHERE ResumeNumber=?";
                 $stmt = $db->prepare ( $create);//prepare the query
                 
                 $stmt->bindValue(1,$Name);
                 $stmt->bindValue(2,$AddressNum);
                 $stmt->bindValue(3,$PhoneNum);
                 $stmt->bindValue(4,$EditNumber);
                 $stmt->execute();
                 unset($stmt); 
                 
                 //get the position number
                 $PositionNumber = getPositionNumber($EditNumber,$db);//this is the position number associated with the resume
                 //set the date
                 getNumber($Name,$ID,$db,$Date);
                 
                 //update into position Detail
                 $create = "UPDATE ps5_Position_Detail SET `Name`=?, `Description`=? WHERE PositionNumber=?";
                 $stmt = $db->prepare ( $create);//prepare the query
                 //do insert  
                 $stmt->bindValue(1,$positionTitle);
                 $stmt->bindValue(2,$PositionD);
                 $stmt->bindValue(3,$PositionNumber);
                 $stmt->execute();
                 unset($stmt); 
                 
                 
                 
                 //filling temp resume object
                 $TempResume->Name = $Name;
                 $TempResume->add_address($addressArray[$AddressNum]);
                 $TempResume->add_phone($phoneArray[$PhoneNum]);
                 //create the position obj
                 $tempPos = new Position();
                 $tempPos->PositionNumber = $PositionNumber;
                 $tempPos->Name = $positionTitle;
                 $tempPos->Description = $PositionD;
                 //fill position into the temp resume
                 $TempResume->add_position($tempPos);
                 $TempResume->DateCreated = $Date;
                 $TempResume->ResumeNumber = $EditNumber;
                 
                 //THEN SAVE to THE SEESSION
                 $userObj->resumeArray[$EditNumber] = $TempResume;
                 
                 
                 // Redirect to display resume page
                 header("Location: ResumeArchive.php");
                 
             }//end if not guest
             
             
        //else just adding new resume
        else
        {
           
                $create = "INSERT INTO ps5_Resume_Detail (`ID`, `Name`, `AddressNumber`, `PhoneNumber`) VALUES (?,?,?,?)";
                $stmt = $db->prepare ( $create);//prepare the query
                
                $stmt->bindValue(1,$ID);
                $stmt->bindValue(2,$Name);
                $stmt->bindValue(3,$AddressNum);
                $stmt->bindValue(4,$PhoneNum);
                $stmt->execute();
                unset($stmt); 
                
                //Get the ResumeNumber for the entry just made
                $ResumeNum = getNumber($Name,$ID,$db,$Date);
                
                //insert into position
                $create = "INSERT INTO ps5_Position (`ResumeNumber`) VALUES (?)";
                $stmt = $db->prepare ( $create);//prepare the query
                //do insert  
                $stmt->bindValue(1,$ResumeNum);
                $stmt->execute();
                unset($stmt); 
                
                $PositionNumber = getPositionNumber($ResumeNum,$db);//this is the position number associated with the resume
                unset($stmt); 
                
                //insert into position Detail
                $create = "INSERT INTO ps5_Position_Detail (`PositionNumber`, `Name`,`Description`) VALUES (?,?,?)";
                $stmt = $db->prepare ( $create);//prepare the query
                //do insert  
                $stmt->bindValue(1,$PositionNumber);
                $stmt->bindValue(2,$positionTitle);
                $stmt->bindValue(3,$PositionD);
                $stmt->execute();
                unset($stmt); 
                
                //add to the session
                
                //filling temp resume object
                $TempResume->Name = $Name;
                $TempResume->add_address($addressArray[$AddressNum]);
                $TempResume->add_phone($phoneArray[$PhoneNum]);
                //create the position obj
                $tempPos = new Position();
                $tempPos->PositionNumber = $PositionNumber;
                $tempPos->Name = $positionTitle;
                $tempPos->Description = $PositionD;
                //fill position into the temp resume
                $TempResume->add_position($tempPos);
                $TempResume->DateCreated = $Date;
                $TempResume->ResumeNumber = $ResumeNum;
                
                
                //THEN SAVE to THE SEESSION
                $userObj->resumeArray[$ResumeNum] = $TempResume;
                
                
                // Redirect to display resume page
                header("Location: ResumeArchive.php");
                
            }//else new resume
        }//end checking guest
         
            //if the user is a guest
            else
            {
                //filling temp resume object
                $TempResume->Name = $Name;
                $TempResume->add_address($addressArray[$AddressNum]);
                $TempResume->add_phone($phoneArray[$PhoneNum]);
                //create the position obj
                $tempPos = new Position();
                $tempPos->PositionNumber = 0;
                $tempPos->Name = $positionTitle;
                $tempPos->Description = $PositionD;
                //fill position into the temp resume
                $TempResume->add_position($tempPos);
                $TempResume->DateCreated = $Date;
                $TempResume->ResumeNumber = 0;
                
              
                    //get address OBJ
                    $address = $TempResume->address;
                    $selectedAddress = $address->Name; //sets the dropdown to the currently selected address
                    
                    //gets the phone obj    
                    $phone = $TempResume->phone;
                    $selectedPhone = $phone->Name;//sets the dropdown to the current phone number associated with the resume
                    
               
                //THEN SAVE to THE SEESSION
                $userObj->resumeArray["guest"] = $TempResume;
                //sets the resume number to this variable so it can be pulled by the viewer
                $_SESSION['View_Resume'] = "guest";//sends the resume to be viewed
                
                $button="
           
            <input type='button' id='GuestView' value='View Resume'/>";
                
            }//else the user was a guest
        
         
     }//end check no error
     
 }//end if submit
 
 
 //adds the resume back into the session and deletes the temp
 //---The Array Key
 //---The Resume Object
 //---The Session Array of Resumes
 function updateToSession($resNum,$resObj,&$Array)
 {
     //adds or overWrites in session
     $Array[$resNum]=$resObj;
     
     //send user back to the resume chooser
     header("Location: ResumeArchive.php");
     
 }//end updateToSession function
 
 //queries and then sends back the resume number
 function getNumber($Name,$ID,$db,&$Date)
 {
     $q = "SELECT ResumeNumber,DateCreated FROM ps5_Resume_Detail Where ID=? and Name=?";
     
     
     $stmt = $db->prepare ( $q);//prepare the query

     $stmt->bindValue(1,$ID);
     $stmt->bindValue(2,$Name);
     $stmt->execute();
     $stmt->setFetchMode( PDO::FETCH_ASSOC);
     $result =($stmt->fetch());
     
     $Date = $result[DateCreated];//returns the date
     $go = $result[ResumeNumber];
     return $go;//if exists grab address number   
     
 }
 
 //takes the resume number and returns the position number it coresponds to
 function getPositionNumber($resNum,$db)
 {
     //get position number
     $create = "SELECT PositionNumber FROM ps5_Position Where ResumeNumber=?";
     $stmt = $db->prepare ( $create);//prepare the query
     //do insert  
     $stmt->bindValue(1,$resNum);
     $stmt->execute();
     $stmt->setFetchMode( PDO::FETCH_ASSOC);
     $result =($stmt->fetch());   
      $go = $result[PositionNumber];
      return $go;//this is what we will use to add into position detail
     
 }
//____________________________________________________________
//**********************END Upon Submit****************************

//*******************************VALIDATION LOGIC************************************************
//_______________________________________________________________________________________________
 
 //checks the values of the submitted objects
 //$shown is the value entered into the text box
 //$error is the return error that will be displayed if needed
 //$ErrorClass if error returns class that turns boxes colors
 //$toBChecked is the value that is in need of validation
 //all of these variables are references of the original calling variables so no return is needed
 function checkValue(&$shown, &$error,&$errClass,$toBChecked)
 {
     
     $shown = '';
     $error = '';

         //check if the name contains special characters
     if (preg_match('/[\^�$%&*()}{@~?><>,|=_+�-]/',$toBChecked)  && strlen($toBChecked) > 0)
         {
             
             $error = "Cannot Include Special Characters";
             $errClass ="boxError"; 
         }
         elseif(!strlen($toBChecked) > 0)
         {
             $error = "Cannot Be Blank";
             $errClass ="boxError";  
         }
         else
         {   //sanitize and feed back up the pipe to display in the form and save on the server
             $shown = filter_var(trim($toBChecked), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);    
         }

         
     
     
 }//end function

 
//___________________________________________________________________________________________________
//*******************************End VALIDATION LOGIC************************************************


 
 //******Drop Down Logic******************************************************************
 //_______________________________________________________________________________________
 

 //creates the options for the dropdown menu and then sets it equal to the state selected
 $addressOptions = createAddressOptions($addressArray,$selectedAddress,$addErr);
 $phoneOptions = createPhoneOptions($phoneArray,$selectedPhone,$phoErr);


//returns all the addresses in drop down format
 function createAddressOptions ($array, $selected,&$error) 
 {
     $result = '';
     
        //if no addresses
     if(count($array) < 1)
     {
         
         $error = "No Addresses on File Yet!";
     }
     
     //puts the default value in the drop down
     $name = "Select an Address";
     $result = $result . "<option value='default'>$name:</option>\n";
     
     //iterates through the array and adds additional items
     foreach ($array as $key => $value) 
     {      
         $name = $value->Name;
         $id = $value->Address1;
         $selection = ($selected == $name) ? "selected='selected'" : "";
         $result = $result . "<option value='$key' $selection>$name:&nbsp;$id</option>\n";
     }
     return $result;
 }//end of createStateOptions

 
 
//returns all the Phone Numbers in drop down format
 function createPhoneOptions ($array, $selected,&$error)
 {
     $result = '';
     
        //if no phones
     if(count($array) < 1)
     {
         
         $error = "No Phone Numbers on File Yet!";
        
     }
     
     //puts the default value in the drop down
     $name = "Select an Phone";
     $result = $result . "<option value='default'>$name:</option>\n";
     
    //iterates through the array and adds additional items
     foreach ($array as $key => $value) 
     {
         $name = $value->Name;
         $id = $value->Actual;
         $selection = ($selected == $name) ? "selected='selected'" : "";
         $result = $result . "<option value='$key' $selection>$name:&nbsp;$id</option>\n";
     }
     return $result;
 }//end of createStateOptions

 //_______________________________________________________________________________________
 //***BUILD THE HTML******************
//________________________________________________________


//for adding the style sheets - this can change based on the path
$style = "
        <link rel='stylesheet' type='text/css' href='resumeMain.css' />
        <link rel='stylesheet' type='text/css' href='styles.css' />";



//This is the main content for this page which will be added to the resused helper code
$additional ="
      <form method='post' id='edit'>
      <h2 class=error >$logErr</h2>
      </br>
<table class='ViewResumes'>
<h2 id='confirmed'>$message</h2>

 </br>
<table class='Resumes' >
<tr>
    <td> <h3>Resume Name:</h3> </td>
    <td><input type='text' class='$errClass' $buttonDis id='resumeName' name='Resume_Name' required value='$Name'/>$nameErr</td>
     <td>
        </td>
         <td>
        </td>
  </tr>
 <tr>
    <td> Position Name Applying For: </td>
    <td><input type='text' class='$errClass' $buttonDis id='address1' name='Position_Name' required value='$positionTitle'/>$posNErr</td>
     <td>
        </td>
  </tr>
 
  <tr>
         <td> Description of Position:</td>
        <td colspan='2'>
        <textarea spellcheck='true' class='$errClass' $buttonDis id='positionArea' maxlength='700' name='Position_Description' required 
        placeholder='ENTER POSITION DESCRIPTION' rows='4' cols='50' >$PositionD</textarea>$posDErr
        </td>
        
        <td>
        </td>
         <td>
        </td>
  </tr>
  <tr>
    <td> Address To Be Used: </td>
    <td><select class='$errClass' name='Select_Address' > $addressOptions </select>$addErr </td>
     <td>
        </td>
  </tr>
  <tr>
    <td> Phone To Be Used: </td>
    <td><select class='$errClass' name='Select_Phone' > $phoneOptions </select>$phoErr </td>
     <td>
        </td>
  </tr>
</table>

<p>
$button
</p>

    <p>
    
   $backButton
    </p>
    
    <!--Hidden button for submit from jquery--!>
     <input type='submit' id='editButton' hidden  name='Submit_Resume' value='Submit_Resume'/>
      <input type='submit' id='ResumeView' hidden  name='Resume_View' 'value='ResumeView'/>
    
    
</form>
";



//builds the page content
$content =  build_html_page_header($additional,$style,"","","",true);

//Dispays all html content
echo
"
 $content
 ";

?>