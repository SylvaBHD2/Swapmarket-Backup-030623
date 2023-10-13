<?php

  // $hostname = "localhost";
  // $username = "root";
  // $dbname = "swapmarket_b";
  // $password = "";
  
  
  // $host_name = 'db5013222910.hosting-data.io';
  // $database = 'dbs11093044';
  // $user_name = 'dbu1740503';
  // $password = '6^Hs43Fpm9xB5%';

  $hostname = 'db5013222910.hosting-data.io';
  $dbname = 'dbs11093044';
  $username = 'dbu1740503';
  $password = '6^Hs43Fpm9xB5%';

  $conn = mysqli_connect($hostname, $username, $password, $dbname);
  // echo "Connected to database";
  if(!$conn){
    echo "Database connection error".mysqli_connect_error();
  }
  
?>