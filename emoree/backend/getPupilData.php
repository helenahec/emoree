<?php
// Include the database connection and functions file
include_once 'db_connect.php';
include_once 'functions.php';

// Get the class ID from the GET request
$pupilID = $_GET['pupilID'];

// Fetch class data based on the class ID using the getClassInfo function
$pupilData = getPupilInfo($pupilID);

// Output the data as JSON
header('Content-Type: application/json');
echo json_encode($pupilData);
?>
