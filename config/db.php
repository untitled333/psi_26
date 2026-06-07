<?php
$host = 'localhost';
$user = 'root';      
$password = '';     
$db_name = 'ln'; 

$db = new mysqli($host, $user, $password, $db_name);

if ($db->connect_error) {
    die("Помилка підключення до БД: " . $db->connect_error);
}

$db->set_charset("utf8mb4");
?>