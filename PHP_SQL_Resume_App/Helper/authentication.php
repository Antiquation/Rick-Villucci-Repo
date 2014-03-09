<?php
/**
 *
 * Authors: H. James de St. Germain
 * and : Rick Villucci
 * Date: Spring 2014
 * 
 * This helper code was used with permission for this project
 * 
 */

// Reports if https is in use
function usingHTTPS () {
	return isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != "off");
}


// Redirects to HTTPS
function redirectToHTTPS()
{
	if(!usingHTTPS())
	{
		$redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $_SERVER['HTTPS'] = "on";
		header("Location:$redirect");
		exit();
	}
}



// Generates random salt for blowfish
function makeSalt () {
	$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
	return '$2a$12$' . $salt;
}

// Compute a hash using blowfish using the salt. 
function computeHash ($password, $salt) {
	return crypt($password, $salt);
}


//checks if the point of origin has been set
function checkPoo()
{
    if(isset($_SESSION['POO']))
    {
        $poo = $_SESSION['POO'];//sets the page of origin if it exists
        
        return $poo;
    } 
    else
    {
        return false;
    }
}
?>