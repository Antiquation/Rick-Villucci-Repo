<?php




function connectDB()
{
    global $db_password;
    
    // Connect to the data base.
	$db = new PDO("mysql:host=atr.eng.utah.edu; dbname=cs4540_nephis; charset=utf8",'cs4540_nephis', $db_password);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    return $db;
}




?>