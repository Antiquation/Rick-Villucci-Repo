<?php
/**
 *
 * Author: Rick Villucci
 * Date: Spring 2014
 * 
 * This page is where the user will go to add multiple addresses they can use on the resume
 * ***THIS PAGE DETECTS IF NEW USER AND WILL CHANGE TO DIRECT THEM THROUGH INITIAL SETUP OF THEIR ACCOUNT
 * 
 */
require_once("../Objects/User.php");//user class for storing the user data
require("../Objects/Phone.php");//allows access to internal address object creation
require ("../Hidden/databasePW.php");
require("../Helper/Server.php");//server info
require_once("../Helper/html_Helper.php");//for building the various HTML


//SessionHandler
$userObj = $_SESSION['User_Obj'];

//This changes the boxes color upon error
//and is also used to stop logic if error exists
$inputClass = "";//This changes to warn the user that they will be overWriting an existing address

$PhoneArray =& $userObj->phoneArray;//loads the sessions addresses for display / edit
$ID =  $userObj->getID();
//server for communicating with the DB

$status = false;//this sets the entire system up for editing
$hiding = true;//controls form view

//this is the object which will be used for the duration of address edit / addition within this page
$TempPhone = new Phone();

//error variable declaration
$numErr = "";
$nameErr = "";
$phErr = "";


//***DISPLAY PREVIOUS ADDRESS LOGIC***
//________________________________________________________________________________

//if the cancel editing button is pressed - resets everything
if (isset ( $_REQUEST ['Cancel_Edit'] ))
{
    unset($TempPhone);
    $TempPhone = new Phone();
    $hiding = true;//controls form view
    
}//end cancel edit logic



$Name =& $TempPhone->Name;
$selectedPhone = $Name;
$PhoneNumber =&$TempPhone->PhoneNumber;
$Actual =& $TempPhone->Actual;
/**
 * TODO Load address objects into a table with buttons for edit / delete---------------------------------------------------
 * 1.when load check if the form is blank and warn that data will be lost
 * 2.load local variables with session 
 * 3.If deleting warn the data will be lost
 * 4.upon delete remove from session and DB
 * 5.upon edit update session and database
 **/

//________________________________________________________________________________
//***END DISPLAY PREVIOUS ADDRESS LOGIC***


//***SAVE FORM LOGIC***
//________________________________________________________________________________



//Validate Logic
//if submitting for Address Save or overwrite
if (isset ( $_REQUEST ['Submit_Phone'] ))
{   
    //Removes any DB error logic
    $logErr = "";
    
    //validates the name field
    checkNameValue($Name,$nameErr,$errClass);

    //validate the address1 field
    checkActualPhoneNumber($Actual,$numErr,$errClass);

    //****DATABASE COMMUNICATION AND VALIDATION
    try
    {
        //if there is an error reload without query DB
        if($errClass =="")
        {  
            //if authenticated save to DB
            if(!$isGuest)
            {
                
                $db = connectDB();//creates DB Connection
                
                
                //insert into Address
                $create = "INSERT INTO ps5_Phone ( `ID`,`Name`,`Actual`) VALUES (?,?,?)";
                $stmt = $db->prepare ( $create);//prepare the query
                //do insert  
                $stmt->bindValue(1,$ID);
                $stmt->bindValue(2,$Name);
                $stmt->bindValue(3,$Actual);
                $stmt->execute();
                unset($stmt); 
                
                $Num;//the new number associated with the actual number for the new actual number
                getPhoneNumber($Name,$ID,$db,$Num);
                
                //THEN SAVE to THE SEESSION
                $userObj->phoneArray[$Num] = $TempPhone; //replaces the item at that location in the session with the new item
                $message ="New Phone: $Name Saved";//message to the user
            }
            else
            {
                //user is guest - just save to the session
                
                //THEN SAVE to THE SEESSION
                $userObj->phoneArray[] = $TempPhone; //replaces the item at that location in the session with the new item
                $message ="New Phone: $Name Saved";//message to the user
                
            }
            
       
            
        }//if no error
        else
        {
            $hiding = false;//controls form view   
        }
        
    }//end try to save

    //if there is an exception communicating with the DB
    catch ( PDOException $ex )
    {
        $logErr = "COMMUNICATION ERROR - Try again later :)";
        // echo "<p>PDO Exception</p>";
	    //echo "$ex";
    }


}//end if set

//if editing a resume
if ( isset($_REQUEST ['Delete_Phone']))
{
    $TBDeleted = $_REQUEST ['Select_Phone'];
    
    //****DATABASE COMMUNICATION
    try
    {
          //if authenticated save to DB
        if(!$isGuest)
        {
            $db = connectDB();//creates DB Connection
            
            //deleted the resume from the database
            $deleteMe = "DELETE FROM ps5_Phone WHERE PhoneNumber = ? and ID = ?";
            
            $stmt = $db->prepare ( $deleteMe);//prepare the query
            
            $stmt->bindValue(1,$TBDeleted);
            $stmt->bindValue(2,$ID);
            $stmt->execute();
            
            unset($stmt); 
            
            unset($PhoneArray[$TBDeleted]);//delete the resume from the session
            $message ="Phone: $Name Deleted";//message to the user
        }
        else
        {
            unset($PhoneArray[$TBDeleted]);//delete the resume from the session
            $message ="Phone: $Name Deleted";//message to the user
        }
        
    }//end try
    //if there is an exception communicating with the DB
    catch ( PDOException $ex )
    {
        $logErr = "COMMUNICATION ERROR - Try again later :)";
        //echo "<p>PDO Exception</p>";
        // echo "$ex";
    }
    
}//end isset resume delete


//gets the number associated with the actual phone number
function getPhoneNumber($Name,$ID,$db,&$Num)
{
    $q = "SELECT PhoneNumber FROM ps5_Phone WHERE Name=? and ID=?";
    
    
    $stmt = $db->prepare ( $q);//prepare the query

    $stmt->bindValue(1,$Name);
    $stmt->bindValue(2,$ID);
    $stmt->execute();
    $stmt->setFetchMode( PDO::FETCH_ASSOC);
    $result =($stmt->fetch());
    
    $Num = $result['PhoneNumber'];
    
    
    
}

//________________________________________________________________________________
//***END SAVE FORM LOGIC***


//***MISC FORM LOGIC***
//________________________________________________________________________________

//checks the values of the submitted objects
//$shown is the value entered into the text box
//$error is the return error that will be displayed if needed
//$isSubmission tells the function to go ahead and run validation
//all of these variables are references of the original calling variables so no return is needed
function checkNameValue(&$shown, &$error,&$errClass)
{
    

    $shown = '';
    $error = '';

    
    
    
    //if check name was selected
    if(isset($_REQUEST['phoneName']))
    {

        //check if the name contains special characters
        if (!preg_match('/[a-zA-Z]/i', $_REQUEST['phoneName'])  && strlen($_REQUEST['phoneName']) > 0)
        {
            
            $error = "Phone Name Cannot Include Special Characters or Numbers";
            $errClass ="boxError"; 
        }
        elseif(!strlen($_REQUEST['phoneName']) > 0)
        {
            $error = "Phone Name Cannot Be Blank";
            $errClass ="boxError";  
        }
        else
        {   //sanitize and feed back up the pipe to display in the form and save on the server
            $shown = filter_var(trim($_REQUEST['phoneName']), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);    
        }

        
    }
    
}//end function



//Formats and returns the phone number box
//SPECIAL THANKS TO Webnet @ 
//http://stackoverflow.com/questions/2675370/creating-phone-number-format-standard-php
//FOR THIS FORMATTING CODE FOUNDATION WHICH I BUILD UPON FOR THIS PROGRAM
function checkActualPhoneNumber(&$phone, &$error,&$errClass)
{
    
    //trin the string
    $number = trim(preg_replace('#[^0-9]#s', '', $_REQUEST['phoneNumber']));


    //if the number entered is greater than 11 characters
    //or if it is less than 7 characters
    if( strlen ($number) < 10)
    {
        $error = "Incorrect Phone Number - Did you forget the Area Code?";
        $errClass ="boxError"; 
    }
    elseif(strlen($number) > 10) 
    {
        $error = "To Many Numbers";
        $errClass ="boxError"; 
    }

    //if used special characters throw error
    elseif(preg_match('/[\'^�$%&*}{@~?><>,|=_+�]/', $_REQUEST['phoneNumber']) )
    {

        $error = "Cannot Include Special Characters";
        $errClass ="boxError";
    }

    else
    {
        $length = strlen($number);
         if($length == 10) {
            $regex = '/([0-9]{3})([0-9]{3})([0-9]{4})/';
            $replace = '($1) $2-$3';
        } elseif($length == 11) {
            $regex = '/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/';
            $replace = '$1 ($2) $3-$4';
        }

        //return the formated string
        $number = preg_replace($regex, $replace, $number);
            
           $phone =  filter_var(trim($number), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);  

      
    }//end else
    

}//end function

//________________________________________________________________________________
//***MISC FORM LOGIC END***



//** DROPDOWN SELECTOR LOGIC**
//________________________________________________________________________________
//set the local phone variable to a reference of the session variable


//creates the options for the dropdown menu and then sets it equal to the phone number selected
$phoneOptions = createPhoneOptions($PhoneArray,$selectedPhone);


//returns all the addresses in drop down format
function createPhoneOptions ($array, $selected) 
{
    $result = '';
    
    //puts the default value in the drop down
    $name = "Select and Delete";
    $result = $result . "<option value='default' >$name:</option>\n";
    
    //iterates through the array and adds additional items
    foreach ($array as $key => $value) 
    {      
        $name = $value->Name;
        $id = $value->Actual;
        $selection = ($selected == $name) ? "selected='selected'" : "";
        $result = $result . "<option value='$key' $selection>$name:&nbsp;$id</option>\n";
    }
    return $result;
}//end of createAddressOptions



//***BUILD THE HTML******************
//________________________________________________________



//for adding the style sheets - this can change based on the path
$style = "
        <link rel='stylesheet' type='text/css' href='resumeMain.css' />
        <link rel='stylesheet' type='text/css' href='styles.css' />";

//This is the main content for this page which will be added to the resused helper code
$additional ="
    
<div id='editing' style='display: none;' value='$status'></div>
<div id='hiding' style='display: none;' value='$hiding'></div>
      <form method='post' id='edit'>
      <h2 id='confirmed'>$message $logErr</h2>
      </br>
<table class='Resumes'>
 <tr>
    <td> Select an address to edit: </td>
    <td><select id='addressChooser' name='Select_Phone' > $phoneOptions </select>$phErr</td>
    <td><input type='button' disabled id='delete' value='Delete Phone'/>
    <td><input type='button' id='newBtn'  name='New_Cancel' value='New Phone'/></td>
  </tr>
   <tr></tr>
  <tr></tr>
<tr>
    <td class ='hide'> Phone Name </td>
    <td><input type='text' class ='hide'  class='$errClass' id='phoneName' name='phoneName'  value='$Name'/>$nameErr</td>
  </tr>
 <tr>
    <td class ='hide'> Phone Number ie:9165551212</td>
    <td><input type='text' class ='hide'  class='$errClass' maxlength='10' size='10' id='phoneNumber' name='phoneNumber'  value='$PhoneNumber'/>$numErr</td>
  </tr>
  <tr>
    <td>
    <p>
    <input type='button' class ='hide' id='addressSubmit' value='Submit Phone'/>
    </p> 
    </td>
   <td>
   </td>
   <td>
    <p>
    <input type='button' class ='hide' id='cancelPhn' value='Cancel Edit'/>
    </p>
    </td>
  </tr>
</table>

     <!--Hidden button for submit from jquery--!>
     <input type='submit' id='submitButton' hidden  name='Submit_Phone' value='Submit_Phone'/>
     
     <!--Hidden button for submit from jquery--!>
     <input type='submit' id='deleteButton' hidden name='Delete_Phone' value='Delete_Phone'/>
     
      <!--Hidden button for submit from jquery--!>
     <input type='submit' id='cancelButton' hidden  name='Cancel_Edit' value='Cancel_Edit'/>
    
     
    
     
    
</form>
  ";



//makes the nav
$menu = makeNav(basename($_SERVER['PHP_SELF']),false,$userObj);


//builds the page content
$content =  build_html_page_header($additional,$style,"","",$menu,true);

//Dispays all html content
echo
"
 $content
 ";

?>