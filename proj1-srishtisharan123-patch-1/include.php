<?php
function doDB()
{
    global $mysqli;
    $mysqli=mysqli_connect("localhost","root","Srishti@123","testDB");
    if(mysqli_connect_errno()){
      printf("connection falied: %s\n",mysqli_connect_error());
      exit();  
    }
}
?>