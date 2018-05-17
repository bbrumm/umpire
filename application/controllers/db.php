<?php


function dbConnect() {
    static $connection;
    if(!isset($connection)){
        $connection = mysqli_connect('localhost','root','root','databas6');
    }
    
    // If connection was not successful, handle the error
    if($connection ===false){
        // Handle error - notify administrator, log to a file, show an error screen, etc.
        return mysqli_connect_error();
    }
    return $connection;
}

function dbQuery($query) {
    $connection = dbConnect();
    $result = mysqli_query($connection,$query);
    return $result;
}

function dbSelect($query){
    $rows = array();
    $result = dbQuery($query);
    // If query failed, return `false`
    if($result ===false){
        return false;
    }
    // If query was successful, retrieve all the rows into an array
    while($row = mysqli_fetch_assoc($result)){
        $rows[]= $row;
    }
    return $rows;
}

?>