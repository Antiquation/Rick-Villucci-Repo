<?php

//DB server login information
class Server
{
    
    private $server_name  = "anitquation1.db.10880661.hostedresource.com";
    private $db_name      = "anitquation1";
    private $db_user_name = "anitquation1";
private $db_password  = "Pgrjk74755!";

function connectDB()
{
    // Connect to the data base.
	$db = new PDO("mysql:host=$this->server_name; dbname=$this->db_name; charset=utf8", $this->db_user_name, $this->db_password);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    return $db;
}



}//end Class
?>