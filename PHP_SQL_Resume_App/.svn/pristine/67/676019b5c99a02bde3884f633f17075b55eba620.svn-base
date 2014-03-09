<?php
/**
 *
 * Author: Rick Villucci
 * Date: Spring 2014
 * 
 * This is the default login page. This page takes the login cridentials and 
 * matches them with the database. Upon match this page loads all the previous
 * resume information into the user session for edit and display
 * 
 */
require("Helper/authentication.php");//contains helper methods for security
require ("Hidden/databasePW.php");
require("Helper/Server.php");//server info
require("Objects/User.php");//user class for storing the user data
require("Objects/Address.php");//allows access to internal obj
require("Objects/Position.php");//allows access to internal obj
require("Objects/Job.php");//allows access to internal obj
require("Objects/Phone.php");//allows access to internal obj
require("Objects/History.php");//allows access to internal obj
require("Objects/Resume.php");//allows access to internal obj

require("Helper/html_Helper.php");//for building the various HTML and setting up sessions


$poo = "";//point of origin
$hasPoo = false;

$cancelButton = "
        <input type='button' id='cancelLogin' hidden value='Cancel Login' onclick=location.href='$poo' />";//this is the cancel button which clears the form

//if the session is set but there is no refering server then
//continue with login
//else store the referring server and continue with 
//existing
if(checkPoo() != false)
{
    $poo = checkPoo();
    $hasPoo = true;
    $cancelButton = "
        <input type='button' value='Cancel Login' onclick=location.href='View/$poo' />";//if comming to login as a previous guest this will return to point of origin
}




//Variables used to login with

$userName = "";
$RealName = "";
$password = "";





try
{
    //****HTTPS SSL*****NONFUNCTIONAL
    
    // Redirect to HTTPS for secure login
	//redirectToHTTPS();
   
    $db = connectDB();//connects to the server
	

// ***LOGIN***----------------------------------------------------------------------------    
             
    
            //if submitting for login
	    if (isset ( $_REQUEST ['Login'] ))
	    {

		    $userName = $_REQUEST ['name'];
		    $password = $_REQUEST ['password'];
		    $q = "SELECT ID, RealName, isAdmin, Password, Salt FROM ps5_User WHERE UserName=?" ;
            
		    $stmt = $db->prepare ( $q);//prepare the query
            

            //sanitize
            $stmt->bindValue(1,$userName);
            $stmt->execute();
            
            $row = $stmt->fetch();
            
          
          
			$hashedPassword = $row['Password'];
            $salted = $row['Salt'];
            $compare = computeHash($password, $salted);//adds salt to see if it matches
                // Validate the password if valid continue else throw error
			if ($compare == $hashedPassword)
            {
              
                session_regenerate_id(true);//changes the session ID of the user for security 
              
               $userObj = new User();//creates the object for the user
               
              //load the parameters
               $userObj->ID = $row['ID'];
               $userObj->RealName = $row['RealName'];
               
               if($row['isAdmin'] = 1)
               {
                   $userObj->isAdmin = true;
               }
               else
               {
                   $userObj->isAdmin = false;
               }
               
               
            
               $_SESSION['User_Obj'] = $userObj;//loads the object into session
             
              
              //kills the new user variable if they just joined
              if(isset( $_SESSION['New_User']))
              {
                unset( $_SESSION['New_User']);
              }
           }//end successfull query
          
           
           
                //if the User was set then Load resumes and 
                //continue to the resumeArchive page
		    if ( isset($userObj) && $userObj != false)
		    {
                    //finishes loading the items into the User
			    loadUserDetail( $db, $userObj);
			
               
                
                if($hasPoo)
                {
                    // Redirect to display resume page
                    header("Location: View/$poo");
                }
                else
                {
                    // Redirect to display resume page
                    header("Location: View/ResumeArchive.php");
                    
                }
                

			   exit; // don't do anything else!
		    }
		    else 
		    {
                $logErr = "Login Failed Try Again";   
                $errClass ="boxError";
			
		    }
	    }//end if login

   

} //end try

//if there is an exception communicating with the DB
catch ( PDOException $ex )
{
    $logErr = "COMMUNICATION ERROR - Try again later :)";
	//echo "<p>PDO Exception</p>";
	//echo "$ex";
}

//****END LOGIN----------------------------------------------------------------



//***BUILD THE HTML******************
//________________________________________________________

//ifNewUser
if(isset($_SESSION['New_User']))
{
    $type = $_SESSION['New_User'];
    $RealName = $_SESSION['User_Obj']->getName();
}
else{
    $type = false;
}

//for adding the style sheets - this can change based on the path
$style = "
        <link rel='stylesheet' type='text/css' href='View/resumeMain.css' />
        <link rel='stylesheet' type='text/css' href='View/styles.css' />";

//additional links

//This is the main content for this page which will be added to the resused helper code
  $additional ="
  
      <form id='Login' method='post'>
      <div class=error >$logErr</div>
      </br>
<table class='Log'>
  <tr>
    <td> UserName </td>
    <td><input class='$errClass' type='text'  id='logName' name='name'  value='$userName'></td>
     <td><div id='loginusername'></div></td>
  </tr>
  <tr>
    <td>Password</td>
    <td><input class='$errClass' type='password' id='logPassword'  name='password'/></td>
    <td><div id='loginpassword'></div></td>
  </tr>
</table>

    <p>
    <input type='button' id='loginUserButton' disabled  value='Login'/>
    $cancelButton
    </p>
   
    <!--Hidden button for submit from jquery--!>
     <input type='submit' id='loginUserSubmit' hidden name='Login' value='Login'/>
    
</form>

<form id='GuestForm' method='post'>
    <p>
    <input type='button' id='newUser' value='Create User' onclick=location.href='View/NewUser.php' />
    </p>
    <p>
    <input type='button' id='guest' name='Guest' value='Continue as Guest'/>
    </p>
    
     <!--Hidden button for submit from jquery--!>
     <input type='submit' id='GuestSubmit' hidden  name='Guest' 'value='Guest'/>
     
   
    </form>

default (Admin: name:admin PW:administrator)(User: name:user PW:testuser)
  ";

  
  //builds the page content
  $content =  build_html_page_header($additional,$style,true,$type,$RealName,"",false);

//Dispays all html content
 echo
 "
 $content
 ";

?>