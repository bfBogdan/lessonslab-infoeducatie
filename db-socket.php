<?php

// database credentials

$servername = "localhost";
$username = "u207455012_qgT";
$password = "t:M!9b6|h31H";
$dbname = "u207455012_lessonlab";

$conexiune_db;

function ConectareDB()
{	
	global $conexiune_db;
	global $username;
	global $password;
	global $servername;
	global $dbname;
	
	if(!$conexiune_db)
	{
		$conexiune_db=new mysqli($servername,$username,$password,$dbname);
	}	

	if($conexiune_db->connect_error){
		die("Eroare conectare la baza de date: "+$conexiune_db->connect_error);
	}
}

function DeconectareDB()
{
	global $conexiune_db;
	
	if($conexiune_db)
	{
		mysqli_close($conexiune_db);
	}	
}

function QueryDB($query)
{
	global $conexiune_db;
	
	ConectareDB();
	
	$rezultate = $conexiune_db->query($query);
	
	//DeconectareDB();
	
	return $rezultate;
}

function InsertID()
{
	global $conexiune_db;
	
	ConectareDB();
	
	return $conexiune_db->insert_id;
}

?>