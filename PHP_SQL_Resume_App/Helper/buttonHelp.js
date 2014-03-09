/**
 * Javascript Resume Button Helper
 * 
 * Developed by: Rick Villucci
 * 
 * University of Utah Assignment
 * Class:CS4540
 * Spring 2014
 * 
 * Date Developed: Feb 2014
 * 
 * 
 */



//Start using Jquery find function
$(function() {
    var resume =  $("#count").attr("value");
    var editing = $("#editing").attr("value"); 
    var hide = $("#hiding").attr("value");

    //when pressed adds buttons on resumechooser page
    $('input[type=radio]').click(function ()
    {
        //shows buttons only if there is more than zero resumes
        $(".toHide").removeAttr("disabled");

    });
    


    //-----NEW VALIDATION----------------------------------------

    //checks login parameters
    $('input[id=newUserButton]').click(function () {
        var proceed = true;
        //check boxes aren't blank
        if ($('#newUserName').val().length < 1 )
        {
            document.getElementById("username").innerHTML = "Name cannot be Blank";
            proceed = false;
        }
        //check for blank
        if ($('#newUserUserName').val().length < 1) {
            document.getElementById("userusername").innerHTML = " UserName cannot be Blank";
            proceed = false;
        }
        //check for blank
        if ($('#newUserPassword').val().length < 1) {
            document.getElementById("password").innerHTML = " Passwords cannot be Blank";
            document.getElementById("password1").innerHTML = " Passwords cannot be Blank";
            proceed = false;
        }
        //check if the password is atleast 8 characters
        if ($('#newUserPassword').val().length < 8 ) {
            document.getElementById("password").innerHTML = " Passwords Must be atleast 8 characters";
            proceed = false;
        }
        if(proceed) {
            $('#newUser #newUserSubmit').click();
            
        }
        
       
    });
   
        //checks the passwords in new user both match else displays message for the new user page
         $("#newUserPassword1").keyup(function () {
           

            if ($('#newUserPassword').val() != $('#newUserPassword1').val()) {
                document.getElementById("password1").innerHTML ="Password Mismatch";
            }
            else
            {   //if the passwords match and are at least 8 characters then valid
                if ($('#newUserPassword1').val().length > 7) {
                    document.getElementById("password1").innerHTML = "Valid";
                    $("#newUserButton").removeAttr("disabled");
                }
            }
         });
    //checks the password for the new user page
         $("#newUserPassword").keyup(function () {

             if ($('#newUserPassword').val().length < 8) {
                 document.getElementById("password").innerHTML = "At least 8 Characters Needed";
             }
             else {
                 document.getElementById("password").innerHTML = "Valid";
               
             }
         });

    //checks the password for the login page
         $("#logPassword").keyup(function () {

             if ($('#logPassword').val().length < 8) {
                 document.getElementById("loginpassword").innerHTML = "At least 8 Characters Needed";
             }
             else {
                 document.getElementById("loginpassword").innerHTML = "Valid";
                 $("#loginUserButton").removeAttr("disabled");
             }
         });

    //checks login parameters
         $('input[id=loginUserButton]').click(function () {
             var proceed = true;
             //check boxes aren't blank
            
             //check for blank
             if ( $('#logName').val().length < 1) {
                 document.getElementById("loginusername").innerHTML = " UserName cannot be Blank";
                 proceed = false;
             }
             //check for blank
             if ( $('#logPassword').val().length < 1) {
                 document.getElementById("loginpassword").innerHTML = " Passwords cannot be Blank";
                
                 proceed = false;
             }
             //check if the password is atleast 8 characters
             if ( $('#logPassword').val().length < 8) {
                 document.getElementById("loginpassword").innerHTML = " Passwords Must be atleast 8 characters";
                 proceed = false;
             }
             if (proceed) {
                 $('#Login #loginUserSubmit').click();

             }


         });
  

    //-----END USER VALIDATION----------------------------------------



        //aids the guest user in seeing their resume in a new page
    $('#resumeView').click(function (event) {
        event.preventDefault();
        $('#ResumeChooser #ResumeView').click();
        window.open("ResumeView.php", "popupWindow", "width=1024,height=768,scrollbars=yes");
    });

    //when pressed adds address fields on addressedit page
    $('input[id=newBtn]').click(function () {
        //showsaddress fields only if there is more than zero resumes
        $(".hide").removeClass("hide");


       

    });
    //when a selection from the drop down is selected makes
    //view edit button active
    $("#addressChooser").change(function () {
        $("#editBtn").removeAttr("hidden");
        $("#delete").removeAttr("disabled");
       

    });
        //warning message triggered
    $('input[id=cancelPhn]').click(function () {
        warningCancelEdit();//cancels the edit if approved

    });

    //when pressed adds address fields on addressedit page
    $('input[id=editBtn]').click(function () {
        //showsaddress fields only if there is more than zero resumes
        $(".hide").removeClass("hide");
      //  $(".hide").removeAttr("required");
        $('#edit #editBtn').click();//submits resume (form name,button submit type)
        
    });

    //when pressed adds address fields on addressedit page
    $('input[id=delete]').click(function () {
        //send warning of possible deletion of asset
        warningDeleteAssets();

    });

    //when pressed adds address fields on addressedit page
    $('input[id=logout]').click(function () {
        //send warning of possible deletion of asset
        warningLogOut();

    });

    //when pressed adds address fields on addressedit page
    $('input[id=logmein]').click(function () {
        //sends me back to the login page
        $('#LogMeIn #LogoutSubmit').click();
       // var url = "../index.php";
        //$(location).attr('href', url);

    });

    //when pressed uses the web app as a guest
    $('input[id=guest]').click(function () {
        $('#GuestForm #GuestSubmit').click();

    });

    
    //when pressed adds address fields on addressedit page
    $('input[id=guestAdmin]').click(function () {
        $('#GuestForm #GuestSubmit').click();
        window.open("AddressEdit.php", "popupWindow", "width=1024,height=768,scrollbars=yes");

    });
    

    //when pressed adds address fields on addressedit page
    $('input[id=GuestView]').click(function () {
        window.open("ResumeView.php", "popupWindow", "width=1024,height=768,scrollbars=yes");

    });

    
    //if not hiding then form is shown
    if (!hide)
    {
        $(".hide").removeClass("hide");
    }


   

    //if editing then show message on submit
    if (editing)
    {
        $("#addressSubmit").removeAttr("disabled");

        ////submit address if dates are good
        $('input[id=addressSubmit]').click(function () {
            if ($('#EndYear').val() < $('#StartYear').val()) {
                document.getElementById("endyearmessage").innerHTML = "End Year Must Be Greater Than Start Year";
            }
            else {
                warningOver();//run the message function
            }
           

        });
        $('input[id=cancelBtn]').click(function () {
            warningCancelEdit();//cancels the edit if approved

        });
    }//end if editing
    else
    {
        //sets the value of the new/cancel button
        $newCancelValue = "Cancel Edit";
        $newCancelID = "cancelBtn";


        //REALTIME checks the dates on the job page
        $("#EndYear").keyup(function () {
            //if both the boxes contain 4 numbers
            if ($('#EndYear').val().length == 4 && $('#StartYear').val().length == 4) {
                //check that end is greater than start
                if ($('#EndYear').val() < $('#StartYear').val()) {
                    document.getElementById("endyearmessage").innerHTML = "End Year Must Be Greater Than Start Year";
                }
                else {
                    document.getElementById("endyearmessage").innerHTML = "Valid";
                    $("#addressSubmit").removeAttr("disabled");

                }

            }
            
            
        });

        //submit address if dates are good
        $('input[id=addressSubmit]').click(function () {
            if ($('#EndYear').val() < $('#StartYear').val()) {
                document.getElementById("endyearmessage").innerHTML = "End Year Must Be Greater Than Start Year";
            }
            else {
                $('#edit #submitButton').click();
            }

        });

    }//end if not editing

 
    
});


//This is a warning message for the delete selection of the resume chooser
function warningDelete() {
    var x;
    var r = confirm("Delete Resume!!?");
    if (r == true) {
        $('#ResumeChooser #Delete_Resume').click();//submits resume (form name,button submit type)
     
        x = "Resume Deleted";
    }
    else
    {
        x = "Delete Canceled";
    }
    document.getElementById("confirmed").innerHTML = x;

}//Warning end

//This is a warning message for the delete selection of the Address Editor
function warningDeleteAssets() {
    var x;
    var r = confirm("DELETE RESUME ASSET?!!? ***NOTE:...If this asset is being used in a resume you will need to make a new selection");
    if (r == true) {
        $('#edit #deleteButton').click();//submits resume (form name,button submit type)

        x = "Deleted";
    }
    else {
        x = "Delete Canceled";
    }
    document.getElementById("confirmed").innerHTML = x;

}//Warning end

//if cancel editing send message
function warningCancelEdit() {
    var x;
    var r = confirm("Cancel Editing?!!?");
    if (r == true) {
        $('#edit #cancelButton').click();//submits resume (form name,button submit type)

        x = "Edit Canceled";
    }
   
    document.getElementById("confirmed").innerHTML = x;

}//Warning end


//This is a warning message for over writeing elements
function warningOver() {

    var r = confirm('OVER WRITE!!?');
    if (r == true) {
        $('#edit #editButton').click();


    }
}//end Warning

//This is a warning message for over writeing elements
    function warningLogOut() {

        var r = confirm('Log Out!!?... Any Unsaved Information... WILL BE LOST!!');
        if (r == true) {
            $('#Logout #LogoutSubmit').click();


        }

    }//Warning end


   
