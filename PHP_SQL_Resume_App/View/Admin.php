<?php
/**
 *
 * Author: Rick Villucci
 * Date: Spring 2014
 * 
 * This is the administration page where the admins can go to change the Users.
 * It allows for the deletion and alteration of each user via ajax
 * 
 */
require ("../Hidden/databasePW.php");
require("../Helper/Server.php");//server info
require("../Objects/User.php");//user class for storing the user data
require("../Objects/Resume.php");//allows access to internal address object creation
require_once("../Helper/html_Helper.php");//for building the various HTML


//check if authorized to view page
if(!$isAdmin)
{
    // Redirect to display resume page
    header("Location: badRole.php");
}
else
 {
   $isGuest = false;
}

//sets the user obj
$userObj = $_SESSION['User_Obj'];

$UserArray = array();
$message = "Administrator - Edit User";

//*** Get the users first time***
try
{
    
    $db = connectDB();//creates DB Connection
    $stmt = $db->prepare ( "
SELECT ID,UserName, RealName, isAdmin FROM ps5_User ");

    if($stmt->execute())
    {
        
        //set the fetch mode to create a Job class 
        $stmt->setFetchMode( PDO::FETCH_ASSOC);
        while($row = $stmt->fetch())
        {
            //create a temp array
            $Temp = new User();//creates the object for the user
            
            //load the parameters
            $Temp->ID = $row['ID'];
            $Temp->RealName = $row['RealName'];
            $Temp->UserName = $row['UserName'];
            
            if($row['isAdmin'] == "1")
            {
                $Temp->isAdmin = true;
            }
            else
            {
                $Temp->isAdmin = false;
            }
            
            //adds the user to the user array
            $UserArray[$row['ID']] = $Temp;
            
        }//end while loop
        
    }//end execute
    
    //build the table
    $Fill = buildUserTable($UserArray);
    
    unset($stmt);//kills the query statement
}
//if there is an exception communicating with the DB
catch ( PDOException $ex )
{
    $logErr = "COMMUNICATION ERROR - Try again later :)";
	//echo "<p>PDO Exception</p>";
	//echo "$ex";
}

/**
 * Build the table for the user
 * takes an array and builds the table for modifying the users
 * --$Array = an array of users
 * */
function buildUserTable($array)
{
    //start with the table headers
    $result = "
    <tr>
  <th>User Number</th>
  <th>User Name</th>
  <th>Real Name</th>		
  <th>Admin?</th>
  <th>Edit Me</th>
  </tr>
";

    //iterates through the array and adds additional items
    //key is the user ID value is a user object
    foreach ($array as $key => $value) 
    {      
        //checked values
        
        $admin = "";
        $client = "";
        
        $ID = $value->ID;
        $userN = $value->UserName;
        $userRN = $value->RealName;
        
            //determine if admins
        if($value->isAdmin)
        { 
        $userAdmin = "true";
        $admin = "checked";
       
        }
        else
        {
            $userAdmin = "false";
            $client = "checked";
        }
        
        //the user adjust form embedded in the table
        $interface = "  <form id='form_".$ID."'>
                            <table id='useredit'>
                              <tr>
                                  <th>Delete User??</th>
                                  <th colspan='2'>Change Role</th>		
                                  <th>Change Real Name</th>
                                  <th>Submit</th>
                            </tr>
                                <tr>
                                 <td><input type='checkbox'   name='delete' value='true' >
                                  <td><input type='radio'   name='admin' value='1' $admin>Admin</td>
                                  <td><input type='radio'   name='admin' value='0' $client>Client</td>
                                  <td><input type='text' name='nameEdit'  placeholder='ENTER NEW NAME' value=''>
                                   <input type='hidden' name='ID'    value=".$ID.">
                                  </td>
                                  <td><input type='button' onclick='modify_User(".$ID.")' value='Update User'/></td>
                                </tr>
                            </table>
                        </form>
    
    
                    ";
        
        $result = $result . "<tr  id='updateRow_".$ID."'>
                              <td>$key</td>
                              <td>$userN</td>
                              <td  id='updateRN_".$ID."'>$userRN</td>		
                              <td  id='updateAdmin_".$ID."'>$userAdmin</td>
                              <td>$interface</td>
                              </tr>";
    }//end foreach
    
    
    
    
    return $result;
    
}//end build user table


//*****HTML STUFF---------------------------------------------------------------------------------------------------
//for adding the style sheets - this can change based on the path
$style = "
        <link rel='stylesheet' type='text/css' href='resumeMain.css' />
        <link rel='stylesheet' type='text/css' href='styles.css' />";


//This is the main content for this page which will be added to the resused helper code
$additional ="

<table id='AdminPage' style='margin: 0 auto; border-collapse: collapse;'> 
    
        <h1 id='confirmed'>$message $logErr</h1>
        </br>
      $Fill
    
</table>

<form id='GuestForm' method='post'>
  
    <p>
    MUST ALOW POPUPS
    <input type='button' id='guestAdmin' name='Guest' value='View Site As Guest'/>
    </p>
    
     <!--Hidden button for submit from jquery--!>
     <input type='submit' id='GuestSubmit' hidden  name='Guest' 'value='Guest'/>
     
   
    </form>
";



//additional links





//makes the nav
$menu = makeNav(basename($_SERVER['PHP_SELF']),false,$userObj);

//builds the page content
$content =  build_html_page_header($additional,$style,"","",$menu,true);

//Dispays all html content
echo
"
 $content
 ";

//<div id="debug"></div>

?>