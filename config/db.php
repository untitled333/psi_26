<?php
$host = 'localhost';
$user = 'root';      
$password = '';     
$db_name = 'ln'; 

$db = new mysqli($host, $user, $password, $db_name);

if ($db->connect_error) {
    die("Blad: " . $db->connect_error);
}

$db->set_charset("utf8mb4");
?>