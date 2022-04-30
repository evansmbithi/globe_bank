<?php

require_once('db_credentials.php');

function db_connect(){
  $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  confirm_db_connect();
  return $connection;
}

function db_disconnect($connection){
  if(isset($connection)){
    mysqli_close($connection);
  }
}

// data sanitize function
// escape special characters before submitting data to db
function db_escape($db,$string){
  return mysqli_real_escape_string($db,$string);
}

function confirm_db_connect(){
  if(mysqli_connect_errno()){
    $msg = "Database connection failed: ";
    $msg .= mysqli_connect_error();
    $msg .= " (" . mysqli_connect_errno() . ")";
    exit($msg);
  }
}

function confirm_result_set($result_set){
  if(!$result_set){
    exit("Database query failed.");
  }
}

?>