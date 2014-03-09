﻿//
// Send an AJAX request to the DB to change a students
// gender
//
function modify_User(ID)
{

    $.ajax(
	{
	    type: 'POST',
	    url: "../Helper/ModifyUser.php",
	    data: $("#form_" + ID).serialize(),
	    dataType: "json",  		      // The type of data that is getting returned.

	    beforeSend: function () {
	         alert('here: ' + $("#form_" + ID).serialize());
	    },

	    success: function (response) {
	        //check if the user was deleted 
	        //if so delete the row
	       
	        alert("Got it!!");
	        if (response.hasOwnProperty('delete'))
	        {
	            $("#updateRow_ " + ID).remove();
	        }
                //else update the table with the items from the post
	        else
	        {   //if a name is present in the json array
	            if (response.hasOwnProperty('name'))
	            {
	                $("#updateRN_" + ID).html(response.name);
	            }
	            //else just change the role
	            $("#updateAdmin_" + ID).html(response.role);
	           
	        }

	       
	    }

	});

    return false;
}