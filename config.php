<?php
$host =  "localhost";
$username = "root";
$password = "";
$dbname = "project_db";

$conn = new mysqli($host, $username, $password, $dbname);

if  ($conn->connect_error) {
    die("". $conn->connect_error);
}
    else  {

        // echo "Successfull Connection";
        // $conn->close();
    }




?>