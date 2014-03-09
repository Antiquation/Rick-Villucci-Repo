<?php
/**
 *
 * Author: Rick Villucci
 * Date: Spring 2014
 * 
 * This page is where the user will go to view and edit their resumes
 * ***THIS PAGE DETECTS IF NEW USER AND WILL CHANGE TO DIRECT THEM THROUGH INITIAL SETUP OF THEIR ACCOUNT
 * 
 */
require ("../Hidden/databasePW.php");
require("../Helper/Server.php");//server info
require("../Objects/User.php");//user class for storing the user data
require("../Objects/Resume.php");//allows access to internal address object creation
require_once("../Helper/html_Helper.php");//for building the various HTML

//check if authorized to view page
if($isGuest)
{
    // Redirect to display resume page
    header("Location: badRole.php");
}

//SessionHandler
$userObj = $_SESSION['User_Obj'];

$resumeArray =& $userObj->resumeArray;//loads the sessions resumeArray for display / edit
$count = count($resumeArray);
$ID =  $userObj->getID();
$message = "Welcome $RealName you have $count Resume(s)...";;//this is a notifier for the user
$buttonDis = ""; //disables the buttons if there is no assets to create a resume with


//disables the create resume button if there is no assets to create it with
if(count($userObj->addressArray) <1 || count($userObj->phoneArray) <1 || count( $userObj->jobArray) <1)
{
    $logErr = "Cannot Create Resume Without Creating Address, Phone Number, and Job";
    $buttonDis = "disabled";
}

//***VARIOUS FORM SUBMIT LOGIC***
//________________________________________________________________________________

    //if editing a resume
 if (isset ( $_REQUEST ['Resume_Edit'] ) )
 {
     //passes the number of the resume to edit
     $_SESSION['Edit_Number'] = $_REQUEST ['Resume_Selected'];
     header("Location: ResumeEdit.php");
     
 }
 
 //if editing a resume
 if ( isset($_REQUEST ['Resume_Delete']))
 {
     $TBDeleted = $_REQUEST ['Resume_Selected'];
     
      //****DATABASE COMMUNICATION
    try
    {
        $db = connectDB();//creates DB Connection
                
                   //deleted the resume from the database
        $deleteMe = "DELETE FROM ps5_Resume_Detail WHERE ResumeNumber = ?";
                    
                    $stmt = $db->prepare ( $deleteMe);//prepare the query
                    
                    $stmt->bindValue(1,$TBDeleted);
                    $stmt->execute();
                    
                    unset($stmt); 
                        
                    unset($resumeArray[$TBDeleted]);//delete the resume from the session
                    $message = "Resume Deleted";
                   
            }
    //if there is an exception communicating with the DB
    catch ( PDOException $ex )
    {
        $logErr = "COMMUNICATION ERROR - Try again later :)";
	   // echo "<p>PDO Exception</p>";
	   // echo "$ex";
    }
     
 }//end isset resume delete
 
 
 


 
 
 //if create a new resume
 if (isset ( $_REQUEST ['Resume_New'] ) )
 {
     unset( $_SESSION['Edit_Number']);//makes sure that we are not pulling from an existing resume
     header("Location: ResumeEdit.php");
     
     
 }

//________________________________________________________________________________
//***END VARIOUS FORM SUBMIT LOGIC***


//creates the radio buttons for the resumes from a list of resumes
function createResumeOptions ($res) {
	$result= "";
    $Num = 0;
     //iterate and build the html radio buttons to represent the resumes
	foreach ($res as $value) 
    {
        $Num ++;
        $name = $value->Name;
        $ResumeNum = $value->ResumeNumber;
        $date = $value->DateCreated;
        
		$result = $result . "
                <tr>
                    <td>Resume #$Num</td>
                </tr>
                <tr>
                    <td> <label for='$name'>$name</label>
                    <input type='radio' id = '$name' name='Resume_Selected' value='$ResumeNum'><br></td>
                    <td> ---------  Created:$date </td>
                </tr>\n";
	}
	return $result;
}//end of createResumeOptions



//***BUILD THE HTML******************
//________________________________________________________

//builds the resume options
$resumeDisplay = createResumeOptions($resumeArray);

//for adding the style sheets - this can change based on the path
$style = "
        <link rel='stylesheet' type='text/css' href='resumeMain.css' />
        <link rel='stylesheet' type='text/css' href='styles.css' />";



//This is the main content for this page which will be added to the resused helper code
$additional ="
    <div id='count' style='display: none;' value='$count'></div>
      <form method='post' id='ResumeChooser'>
      <div class=error >$logErr</div>
      </br>
<table class='ViewResumes'>
<h2 id='confirmed'>$message</h2>
$resumeDisplay
<tr>
    <td>
<p>
<input type='submit' $buttonDis name='Resume_New' value='New Resume'/>
<input type='submit' class ='toHide' disabled  name='Resume_Edit' value='Edit Resume'/>
<input type='button' $buttonDis id='resumeView' class ='toHide' disabled  name='Resume_View' value='View Resume'/>
</p>
    </td>
</tr>
<tr>
</tr>
<tr>
</tr>
<tr>
    <td>
        <p>
        <input type='submit' class ='toHide' disabled  name='Resume_Delete' onclick='warningDelete()'value='Delete Resume'/>
        </p>
    </td>
</tr>

</table>

            <!--Hidden button for submit from jquery--!>
            <input type='submit' id='Delete_Resume' hidden  name='Resume_Delete' 'value='Delete Resume'/>
            <input type='submit' id='ResumeView' hidden  name='Resume_View' 'value='ResumeView'/>
  
    
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