<?php
include('functions.php');

// Call a function to retrieve data
$schoolData = getSchoolInfo(1); 

// Display the retrieved data
print_r($schoolData);
?>
