<?php

$host = 'localhost';
$user = 'mathpath';
$pass = 'c25d0310a';
$db   = 'mathpath';

$conn = new mysqli($host,$user,$pass,$db);

if($conn->connect_error){
	die("Error de conexión: ".$conn->connect_error);
}

$conn->set_charset("utf8");

?>