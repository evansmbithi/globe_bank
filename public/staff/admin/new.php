<?php require_once('../../../private/initialize.php'); ?>

<?php require_login(); ?>

<?php
if(is_post_request()){
  $admin = [];
  $admin['first_name'] = $_POST['first_name'];
  $admin['last_name'] = $_POST['last_name'];
  $admin['email'] = $_POST['email'];
  $admin['username'] = $_POST['username'];
  $admin['password'] = $_POST['password'];
  $admin['pass_confirm'] = $_POST['pass_confirm'] ?? '';

  $insert = insert_admin($admin);
  if($insert === true){
    $insert_id = mysqli_insert_id($db);
    
    // send status message    
    $_SESSION['message'] = "New Admin created successfully";
    redirect_to(url_for('/staff/admin/show.php?id=' . h(u($insert_id))));    
  }else{
    $errors = $insert;
  }
}else{
  // display blank form
}
?>

<?php $page_title='Create New Admin'; ?>
<?php include(SHARED_PATH . '/staff_header.php');?>

<div id="content">

<a class="back-link" href="<?php echo url_for('/staff/admin/index.php'); ?>">&laquo; Back to List</a>

  <div class="admin new">
  <h1><?php echo $page_title; ?></h1>

  <?php echo display_errors($errors); ?>

    <form action="<?php echo url_for('/staff/admin/new.php') ?>" method="post">
      <dl>
        <dt>First Name</dt>
        <dd><input type="text" name="first_name" id=""></dd>
      </dl>

      <dl>
        <dt>Last Name</dt>
        <dd><input type="text" name="last_name" id=""></dd>
      </dl>

      <dl>
        <dt>Email</dt>
        <dd><input type="text" name="email" id=""></dd>
      </dl>

      <dl>
        <dt>Username</dt>
        <dd><input type="text" name="username" id=""></dd>
      </dl>

      <dl>
        <dt>Password</dt>
        <dd><input type="password" name="password" id=""></dd>
      </dl>

      <dl>
        <dt>Confirm Password</dt>
        <dd><input type="password" name="pass_confirm" id=""></dd>
      </dl>

      <p>Passwords should be atleast 12 characters and include atleast one uppercase letter, lowercase letter, number and a symbol.</p>

      <div id="operations">
      <input type="submit" value="Add admin">
      </div>
    </form>

  </div>
</div>

<?php include(SHARED_PATH . '/staff_footer.php') ?>