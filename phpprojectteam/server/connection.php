<?php 


  
  $db_hostname = 'kc-sce-appdb01';
  $db_database = "jktkc3";
  $db_username = "jktkc3";
  $db_password = "fHUkTTy0qRwI";
  

 $connection = mysqli_connect($db_hostname, $db_username,$db_password,$db_database);
 
 if (!$connection)
    die("Unable to connect to MySQL: " . mysqli_connect_errno());


?>
