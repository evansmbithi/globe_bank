<?php
require_once('../../private/initialize.php');

/*
 * admin@gmail.com
 * admin
 * @Mystirica2022
 */

$errors = [];
$username = '';
$password = '';

if(is_post_request()) {

  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  // simple validations
  // ensure the user does not leave the form blank
  if(is_blank($username)){
    $errors[] = "Username cannot be blank";
  }

  if(is_blank($password)){
    $errors[] = "Password cannot be blank";
  }

  // if there were no errors
  if(empty($errors)){
    $admin = find_admin_by_username($username);
    // error message
    // Not specifying the error makes hacking harder
    $login_error = "Login was unsuccessful";

    if($admin){
      // compare the current password to the hashed_password
      if(password_verify($password,$admin['hashed_password'])){
        // password match
        log_in_admin($admin);
        redirect_to(url_for('/staff/index.php'));
      }else{
        // password mismatch
        $errors[] = $login_error;
      }

    }else{
      $errors[] = $login_error;
    }
  }

}

?>

<?php $page_title = 'Log in'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
  <h1>Log in</h1>

  <?php echo display_errors($errors); ?>

  <form action="login.php" method="post">
    Username:<br />
    <input type="text" name="username" value="<?php echo h($username); ?>" /><br />
    Password:<br />
    <input type="password" name="password" value="" /><br />
    <input type="submit" name="submit" value="Submit"  />
  </form>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
