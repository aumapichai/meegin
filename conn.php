<?php
 
//MySQLi Procedural
    $conn = mysqli_connect("localhost","root","","meegin");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $conn -> set_charset('utf8');
?>