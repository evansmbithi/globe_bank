<?php
// subjects
function find_all_subjects($options = []){
  global $db;

  $visible = $options['visible'] ?? false;

  $sql = "SELECT * FROM subjects ";
  if($visible){
    $sql .= "WHERE visible = true ";
  }
  $sql .= "ORDER BY position ASC";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  // mysqli_free_result($result);
  return $result;
 }

function get_subject_by_id($id, $options = []){
  global $db;

  $visible = $options['visible'] ?? false;
  
  $sql = "SELECT * FROM subjects ";
  $sql .= "WHERE id='" . db_escape($db,$id) . "'";
  if ($visible){
    $sql .= " AND visible = true";
  }
  // echo $sql;
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);

  $subject = mysqli_fetch_assoc($result);
  mysqli_free_result($result);
  return $subject; //returns an assoc array
 }

function validate_subject($subject) {
  // this function takes in an assoc array for subject
  // the $errors array takes in all errors and returns them all at once at the end of the function
  $errors = [];
  
  // menu_name
  if(is_blank($subject['menu_name'])) {
    $errors[] = "Name cannot be blank.";
  }elseif(!has_length($subject['menu_name'], ['min' => 2, 'max' => 255])) {
    // if menu_name does not have characters between 2 - 255, return an error
    $errors[] = "Name must be between 2 and 255 characters.";
  }

  // position
  // Make sure we are working with an integer
  // typecasting to int
  $postion_int = (int) $subject['position'];
  if($postion_int <= 0) {
    $errors[] = "Position must be greater than zero.";
  }
  if($postion_int > 999) {
    $errors[] = "Position must be less than 999.";
  }

  // visible
  // Make sure we are working with a string
  $visible_str = (string) $subject['visible'];
  if(!has_inclusion_of($visible_str, ["0","1"])) {
    $errors[] = "Visible must be true or false.";
  }

  return $errors;
 }
function insert_subject($subject){
  global $db;

  $errors = validate_subject($subject);
  if(!empty($errors)){
    return $errors;
  }

  shift_subject_positions(0, $subject['position']);

  $sql =  "INSERT INTO subjects";
  $sql .= "(menu_name, position, visible) ";
  $sql .= "VALUES (";
  $sql .= "'" . db_escape($db,$subject['menu_name']) . "',";
  $sql .= "'" . db_escape($db,$subject['position']) . "',";
  $sql .= "'" . db_escape($db,$subject['visible']) . "'";
  $sql .= ")";
  // echo $sql;
  $result = mysqli_query($db, $sql);
  // for INSERT statement, $result is true/false
  if($result){
    return true;

  }else{
    // INSERT failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
 }

function update_subject($subject){
  global $db;

  // perform validation on the subject
  // if the $subject does not meet any validation, it passes an error into an array
  // if the are any errors, don't execute the update; Return $errors.
  // if no update is executed, it will return false

  $errors = validate_subject($subject);
  if(!empty($errors)){
    return $errors;
  }

  $old_subject = get_subject_by_id($subject['id']);
  $old_position = $old_subject['position'];
  shift_subject_positions($old_position, $subject['position'], $subject['id']);


  $sql = "UPDATE subjects SET ";
  $sql .= "menu_name='" . db_escape($db,$subject['menu_name']) . "',";
  $sql .= "position='" . db_escape($db,$subject['position']) . "',";
  $sql .= "visible='" . db_escape($db,$subject['visible']) . "' ";
  //set the id as an assoc array to minimize on passing too many values to the function
  $sql .= "WHERE id='" . db_escape($db,$subject['id']) . "' ";
  $sql .= "LIMIT 1";

  $result = mysqli_query($db, $sql);
  if($result){
    // redirect_to(url_for('/staff/subjects/show.php?id=' . $subject['id']));
    return true;

  }else{
    mysqli_error($db);
    db_disconnect($db);
    exit;
  }
 }

function delete_subject($id){
  global $db;

    $old_subject = get_subject_by_id($id);
    $old_position = $old_subject['position'];
    shift_subject_positions($old_position, 0, $id);


  $sql = "DELETE FROM subjects ";
  $sql .= "WHERE id='" . db_escape($db,$id) . "' ";
  $sql .= "LIMIT 1";
  $delete_query = mysqli_query($db,$sql);
  confirm_result_set($delete_query);

  if($delete_query){
    // echo "Deleted Successfully";
    return true;

  }else{
    mysqli_error($db);
    db_disconnect($db);
    exit;
  }
 }
function shift_subject_positions($start_pos, $end_pos, $current_id=0) {
  global $db;

  // fail safe: if the starting position and the ending position are equal, do nothing
  if($start_pos == $end_pos) { return; }

  $sql = "UPDATE subjects ";
  //  if the starting position is equal to zero, it's a new item and we need to add 1 to all items greater than the ending position
  if($start_pos == 0) {
    // new item, +1 to items greater than $end_pos
    $sql .= "SET position = position + 1 ";
    $sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
    // when the ending position is equal to 0, there's a possibility we are deleting an item; subtract 1 from items greater than the starting position.
  } elseif($end_pos == 0) {
    // delete item, -1 from items greater than $start_pos
    $sql .= "SET position = position - 1 ";
    $sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
    // from higher up the list to further down
  } elseif($start_pos < $end_pos) {
    // move later, -1 from items between (including $end_pos)
    $sql .= "SET position = position - 1 ";
    $sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
    $sql .= "AND position <= '" . db_escape($db, $end_pos) . "' ";
  } elseif($start_pos > $end_pos) {
    // move earlier, +1 to items between (including $end_pos)
    $sql .= "SET position = position + 1 ";
    $sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
    $sql .= "AND position < '" . db_escape($db, $start_pos) . "' ";
  }
  // Exclude the current_id in the SQL WHERE clause
  // because for this instance the id could be 0
  $sql .= "AND id != '" . db_escape($db, $current_id) . "' ";

  $result = mysqli_query($db, $sql);
  // For UPDATE statements, $result is true/false
  if($result) {
    return true;
  } else {
    // UPDATE failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
 }


// Pages
function find_all_pages(){
  global $db;
  
  $sql = "SELECT * FROM pages ";
  $sql .= "ORDER BY subject_id ASC, position ASC";
  $result = mysqli_query($db, $sql);
  confirm_result_set($result);
  // mysqli_free_result($result);
  return $result;
 }

function get_page_by_id($id, $options = []){
  global $db;

  $visible = $options['visible'] ?? false;

  $sql = "SELECT * FROM pages ";
  $sql .= "WHERE id='" . db_escape($db,$id) . "'";
  if ($visible){
    $sql .= " AND visible = true";
  }
  $sql .= " LIMIT 1";
  $result_set = mysqli_query($db,$sql);
  confirm_result_set($result_set);

  $page = mysqli_fetch_assoc($result_set);
  mysqli_free_result($result_set);
  return $page;
 }

function validate_page($page) {
  // this function takes in an assoc array for subject
  // the $errors array takes in all errors and returns them all at once at the end of the function
    $errors = [];
    
    // subject_id
    if(is_blank($page['subject_id'])){
      $errors[] = "Subject cannot be blank";
    }
    
    // menu_name
    if(is_blank($page['menu_name'])) {
      $errors[] = "Name cannot be blank.";
    }elseif(!has_length($page['menu_name'], ['min' => 2, 'max' => 255])) {
      // if menu_name does not have characters between 2 - 255, return an error
      $errors[] = "Name must be between 2 and 255 characters.";
    }
    // elseif(!has_unique_page_menu_name($page['menu_name'])){
    //   $errors[] = "Name already exists";
    // }

    // unique menu_name
    // ensure that the record we're seeing is not the same record we're updating
    // in that case, pass in the current_id. So that it looks for all menu_names other than the current one
    // current_id defaults to zero if no id is present
    $current_id = $page['id'] ?? '0';
    if(!has_unique_page_menu_name($page['menu_name'], $current_id)){
      $errors[] = "Menu name must be unique";
    }
  
    // position
    // Make sure we are working with an integer
    // typecasting to int
    $postion_int = (int) $page['position'];
    if($postion_int <= 0) {
      $errors[] = "Position must be greater than zero.";
    }
    if($postion_int > 999) {
      $errors[] = "Position must be less than 999.";
    }
  
    // visible
    // Make sure we are working with a string
    $visible_str = (string) $page['visible'];
    if(!has_inclusion_of($visible_str, ["0","1"])) {
      $errors[] = "Visible must be true or false.";
    }

    // content
    if(is_blank($page['content'])){
      $errors[] = "Content cannot be blank";
    }
  
    return $errors;

 }


function insert_page($page){
  global $db;

  $errors = validate_page($page);
  if(!empty($errors)){
    return $errors;
  }

  shift_page_positions(0, $page['position'],$page['subject_id']);

  $sql = "INSERT INTO pages (";
  $sql .= "subject_id,menu_name,position,visible,content";
  $sql .= ") VALUES (";
  $sql .= "'" . db_escape($db,$page['subject_id']) . "',";
  $sql .= "'" . db_escape($db,$page['menu_name']) . "',";
  $sql .= "'" . db_escape($db,$page['position']) . "',";
  $sql .= "'" . db_escape($db,$page['visible']) . "',";
  $sql .= "'" . db_escape($db,$page['content']) . "')";

  $query = mysqli_query($db,$sql);

  if($query){
    return true;

  }else{
    // INSERT failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit();
  }
 }

function update_page($page){
  global $db;

  $errors = validate_page($page);
  if(!empty($errors)){
    return $errors;
  }

  $old_page = get_page_by_id($page['id']);
  $old_position = $old_page['position'];
  shift_page_positions($old_position, $page['position'],$page['subject_id'],$page['id']);

  $sql = "UPDATE pages SET ";
  $sql .= "subject_id='" . db_escape($db,$page['subject_id']) . "',";
  $sql .= "menu_name='" . db_escape($db,$page['menu_name']) . "',";
  $sql .= "position='" . db_escape($db,$page['position']) . "',";
  $sql .= "visible='" . db_escape($db,$page['visible']) . "' ";
  $sql .= "WHERE id='" . db_escape($db,$page['id']) . "' ";
  $sql .= "LIMIT 1";
  $page_set = mysqli_query($db,$sql);
  confirm_result_set($page_set);

  if($page_set){
    return true;

  }else{
    // INSERT failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit();
  }
 }

function delete_page($id){
  global $db;

  $old_page = get_page_by_id($id);
  $old_position = $old_page['position'];
  shift_page_positions($old_position, 0,$old_page['subject_id'],$id);
  
  $sql = "DELETE FROM pages ";
  $sql .= "WHERE id='" . db_escape($db,$id) . "' ";
  $sql .= "LIMIT 1";
  $delete_set = mysqli_query($db,$sql);
  confirm_result_set($delete_set);

  if($delete_set){
    return true;

  }else{
    // INSERT failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit();
  }
 }

function get_pages_by_subject_id($subject_id, $options = []){
  global $db;

  $visible = $options['visible'] ?? false;

  $sql = "SELECT * FROM pages ";
  $sql .= "WHERE subject_id='" . db_escape($db,$subject_id) . "' ";
  if($visible){
    $sql .= "AND visible = true ";
  }
  $sql .= "ORDER BY position ASC";
  $result_set = mysqli_query($db,$sql);
  confirm_result_set($result_set);

  return $result_set;
 }

 function count_pages_by_subject_id($subject_id, $options = []){
  global $db;

  $visible = $options['visible'] ?? false;

  $sql = "SELECT COUNT(id) FROM pages ";
  $sql .= "WHERE subject_id='" . db_escape($db,$subject_id) . "' ";
  if($visible){
    $sql .= "AND visible = true ";
  }
  $sql .= "ORDER BY position ASC";
  $result_set = mysqli_query($db,$sql);
  confirm_result_set($result_set);
  $row = mysqli_fetch_row($result_set);
  mysqli_free_result($result_set);
  $count = $row[0];

  return $count;
 }

 function shift_page_positions($start_pos, $end_pos, $subject_id, $current_id=0) {
  global $db;

  // fail safe: if the starting position and the ending position are equal, do nothing
  if($start_pos == $end_pos) { return; }

  $sql = "UPDATE pages ";
  //  if the starting position is equal to zero, it's a new item and we need to add 1 to all items greater than the ending position
  if($start_pos == 0) {
    // new item, +1 to items greater than $end_pos
    $sql .= "SET position = position + 1 ";
    $sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
    // when the ending position is equal to 0, there's a possibility we are deleting an item; subtract 1 from items greater than the starting position.
  } elseif($end_pos == 0) {
    // delete item, -1 from items greater than $start_pos
    $sql .= "SET position = position - 1 ";
    $sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
    // from higher up the list to further down
  } elseif($start_pos < $end_pos) {
    // move later, -1 from items between (including $end_pos)
    $sql .= "SET position = position - 1 ";
    $sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
    $sql .= "AND position <= '" . db_escape($db, $end_pos) . "' ";
  } elseif($start_pos > $end_pos) {
    // move earlier, +1 to items between (including $end_pos)
    $sql .= "SET position = position + 1 ";
    $sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
    $sql .= "AND position < '" . db_escape($db, $start_pos) . "' ";
  }
  // Exclude the current_id in the SQL WHERE clause
  // because for this instance the id could be 0
  // the second WHERE clause equates the current subject_id to that in the database
  //  we are only dealing with the ones on this subject; not the pages
  $sql .= "AND id != '" . db_escape($db, $current_id) . "' ";
  $sql .= "AND subject_id = '" . db_escape($db, $subject_id) . "'";

  $result = mysqli_query($db, $sql);
  // For UPDATE statements, $result is true/false
  if($result) {
    return true;
  } else {
    // UPDATE failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
 }

// Admins
function find_all_admins(){
  global $db;

  $sql = "SELECT * FROM admin ";
  $sql .= "ORDER BY id ASC";
  $query = mysqli_query($db,$sql);
  confirm_result_set($query);

  return $query;
 }
function find_admin_by_id($id){
  global $db;

  $sql = "SELECT * FROM admin ";
  $sql .= "WHERE id='" . db_escape($db,$id) . "' ";
  $sql .= "LIMIT 1";

  $query = mysqli_query($db,$sql);
  confirm_result_set($query);

  $admin = mysqli_fetch_assoc($query);
  mysqli_free_result($query);

  return $admin;
 }

function find_admin_by_username($username){
  global $db;

  $sql = "SELECT * FROM admin ";
  $sql .= "WHERE username='" . db_escape($db,$username) . "' ";
  $sql .= "LIMIT 1";

  $query = mysqli_query($db,$sql);
  confirm_result_set($query);

  $admin = mysqli_fetch_assoc($query);
  mysqli_free_result($query);

  return $admin;
 }

function validate_admins($admin,$options=[]){
  // if password was set, assign it to $password_required else default to true
  // if nothing was set then password_required is going to default to true
  // This determines whether password is required; if passed in false, then password is not required
  $password_required = $options['password_required'] ?? true;

  $errors = [];

  if(is_blank($admin['first_name'])){
    $errors[] = "First name cannot be blank";
  }elseif(!has_length($admin['first_name'],['min'=> 2, 'max' => 255])){
    $errors[] = "First name can only have characters between 2 and 255";
  }

  if(is_blank($admin['last_name'])){
    $errors[] = "Last name cannot be blank";
  }elseif(!has_length($admin['last_name'],['min'=> 2, 'max' => 255])){
    $errors[] = "Last name can only have characters between 2 and 255";
  }

  if(is_blank($admin['email'])){
    $errors[] = "Email cannot be blank";
  }elseif(!has_valid_email_format($admin['email'])){
    $errors[] = "Use a valid email address";
  }

  if(is_blank($admin['username'])){
    $errors[] = "Username cannot be blank";
  }

  $current_id = $admin['id'] ?? '0';
  if(!has_unique_username($admin['username'],$current_id)){
    $errors[] = "Username already exists";
  }

  // if password_required = true, perform validation
  if($password_required){
    if(is_blank($admin['password'])){
      $errors[] = "A password is required";
    }elseif(!has_length($admin['password'], ['min' => '12'])){
      $errors[] = "Password should be atleast 12 characters long";
    }elseif(!preg_match('/[A-Z]/', $admin['password'])){
      $errors[] = "Password should have atleast one uppercase character";
    }elseif(!preg_match('/[a-z]/', $admin['password'])){
      $errors[] = "Password should have atleast one lowercase character";
    }elseif(!preg_match('/[^A-Za-z0-9\s]/', $admin['password'])){
      $errors[] = "Password should have atleast one symbol";
    }
  
    if(is_blank($admin['pass_confirm'])) {
      $errors[] = "Confirm password cannot be blank";
    }elseif($admin['pass_confirm'] !== $admin['password']){
      $errors[] = "Passwords do not match";
    }
  }

  return $errors;
 }

function insert_admin($admin){
  global $db;
  $errors = validate_admins($admin);

  if(!empty($errors)){
    return $errors;
  }

  $hashed_password = password_hash($admin['password'], PASSWORD_BCRYPT);

  // mysql insert
  $sql = "INSERT INTO admin (";
  $sql .= "first_name,last_name,email,username,hashed_password";
  $sql .= ") VALUES (";
  $sql .= "'" . db_escape($db,$admin['first_name']) . "',";
  $sql .= "'" . db_escape($db,$admin['last_name']) . "',";
  $sql .= "'" . db_escape($db,$admin['email']) . "',";
  $sql .= "'" . db_escape($db,$admin['username']) . "',";
  $sql .= "'" . db_escape($db,$hashed_password) . "'";
  $sql .= ")";

  $query = mysqli_query($db, $sql);

  if($query){
    return true;
  }else{
    // INSERT failed
    echo mysqli_error($db);
    db_disconnect($db);
    exit();
  }
 }

function update_admin($admin){
  global $db;

  // if the password was set, then its required.
  // if it's not blank then it's set
  $password_set = !is_blank($admin['password']);

  // if password is set, check for validation. Otherwise don't validate
  // pass in an option called 'password_required' and set it equal to whatever value of $password_set is
  // option array to give it conditional behavior
  $errors = validate_admins($admin,['password_required' => $password_set]);

  if(!empty($errors)){
    return $errors;
  }

  $hashed_password = password_hash($admin['password'], PASSWORD_BCRYPT);

  $sql = "UPDATE admin SET ";
  $sql .= "first_name='" . db_escape($db,$admin['first_name']) . "',";
  $sql .= "last_name='" . db_escape($db,$admin['last_name']) . "',";
  $sql .= "email='" . db_escape($db,$admin['email']) . "',";
  if($password_set){
    // if password is not set, this statement will not be queried
    // making username the last item to be queried
    // password is set before username to avoid errors caused by the comma
    // update can be in any order
    $sql .= "hashed_password='" . db_escape($db,$hashed_password) . "',";
  }
  $sql .= "username='" . db_escape($db,$admin['username']) . "' ";
  $sql .= "LIMIT 1";

  // echo $sql;
  $query = mysqli_query($db,$sql);
  confirm_result_set($query);


  if($query){
    return true;
  }else{
    echo mysqli_error($db);
    db_disconnect($db);
    exit();
  }
 }

function delete_admin($id){
  global $db;
  
  $sql = "DELETE FROM admin ";
  $sql .= "WHERE id='" . db_escape($db,$id) . "' ";
  $sql .= "LIMIT 1";
  $query = mysqli_query($db,$sql);
  confirm_result_set($query);

  if($query){
    return true;
  }else{
    echo mysqli_error($db);
    db_disconnect($db);
    exit;
  }
 }

?>