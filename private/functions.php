<?php

//root URL function
function url_for($script_path) {
  // add the leading '/' if not present
  if($script_path[0] != '/') {
    $script_path = "/" . $script_path;
  }
  return WWW_ROOT . $script_path;
}

//encode URL parameters (spaces and special characters apart from _ and numbers)
function u($string){
  return urlencode($string);
}
function raw_u($string){
  return rawurlencode($string);
}

// prevent XSS by escaping HTML characters
function h($string){
  return htmlspecialchars($string);
}

function error_404() {
  header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
  exit();

}

function error_500() {
  header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
  exit();
}

function redirect_to($location) {
  header("Location: " . $location);
  exit;
}

// Detect form submission; whether a POST or GET

function is_post_request() {
  return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function is_get_request() {
  return $_SERVER['REQUEST_METHOD'] == 'GET';
}

function display_errors($errors=array()) {
  $output = '';
  if(!empty($errors)) {
    $output .= "<div class=\"errors\">";
    $output .= "Please fix the following errors:";
    $output .= "<ul>";
    foreach($errors as $error) {
      $output .= "<li>" . h($error) . "</li>";
    }
    $output .= "</ul>";
    $output .= "</div>";
  }
  return $output;
}

function get_and_clear_session_message(){
  if(isset($_SESSION['message']) && $_SESSION['message'] != ''){
    $msg = $_SESSION['message'];
    unset($_SESSION['message']);
    return $msg;
  }
}

function session_admin($id){
  $admin = find_admin_by_id($id);
  $_SESSION['admin'] = $admin['username'];

  $user = $_SESSION['admin'];
  unset($_SESSION['admin']);
  return $user;
}

function display_session_message(){
  $msg = get_and_clear_session_message();
  if(!is_blank($msg)){
    return '<div id="status">' . h($msg) . '</div>';
  }

}


?>
