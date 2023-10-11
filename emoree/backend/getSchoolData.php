<?php
// Include the database connection and functions file
include_once 'db_connect.php';
include_once 'functions.php';

// Get the school ID from the GET request
$schoolID = $_GET['schoolID'];

// Fetch school data based on the school ID using the getSchoolInfo function
$schoolData = getSchoolInfo($schoolID);
// Output the data as JSON
header('Content-Type: application/json');
echo json_encode($schoolData);
?>
