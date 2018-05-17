<?php

function testRun() {
// Try and connect to the database
$connection = mysqli_connect('localhost','root','root','databas6');


// If connection was not successful, handle the error
if($connection ===false){
// Handle error - notify administrator, log to a file, show an error screen, etc.
}

$query = "INSERT INTO cron_result_test(textval, logged_date) VALUES ('TestRun', NOW());";
$result = mysqli_query($connection, $query);

if ($result === false){
// Handle failure - log the error, notify administrator, etc.
} else {
// We successfully inserted a row into the database
}
}


?>