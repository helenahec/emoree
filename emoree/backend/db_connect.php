<?php

// Database configuration
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "emoree";

// Connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) 

    {
        
        die("Connection failed: " . $conn->connect_error);

    }

// Close the database connection when done
// $conn->close();
 
?>
