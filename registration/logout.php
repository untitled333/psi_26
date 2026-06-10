<?php
session_start();   
session_unset();   
session_destroy(); 

header("Location: /lapka-nadiyi/index.php");
exit();
?>