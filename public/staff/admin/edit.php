<?php require_once('../../../private/initialize.php'); ?>

<?php require_login(); ?>

<?php
if(isset($_GET['id'])){
  $id = $_GET['id'];

  $admin = find_admin_by_id($id);

}else{
  redirect_to(url_for('/staff/admin/index.php'));
}

if(is_post_request()){
  $admin = [];
  $admin['id'] = $id;
  $admin['first_name'] = $_POST['first_name'];
  $admin['last_name'] = $_POST['last_name'];
  $admin['email'] = $_POST['email'];
  $admin['username'] = $_POST['username'];
  $admin['password'] = $_POST['password'];
  $admin['pass_confirm'] = $_POST['pass_confirm'] ?? '';

  $update = update_admin($admin);
  if($update === true){
    $_SESSION['message'] = "Admin details updated successfully";
    redirect_to(url_for('/staff/admin/index.php'));
  }else{
    $errors = $update;
  }
}
?>

<?php $page_title='Edit Admin'; ?>
<?php include(SHARED_PATH . '/staff_header.php');?>

<div id="content">

<a class="back-link" href="<?php echo url_for('/staff/admin/index.php'); ?>">&laquo; Back to List</a>

  <div class="Edit Admin">
  <h1><?php echo $page_title; ?></h1>

  <?php echo display_errors($errors) ?>

    <form action="<?php echo url_for('/staff/admin/edit.php?id='. h(u($id))) ?>" method="post">
      <dl>
        <dt>First Name</dt>
        <dd><input type="text" name="first_name" value="<?php echo $admin['first_name'] ?>"></dd>
      </dl>

      <dl>
        <dt>Last Name</dt>
        <dd><input type="text" name="last_name" value="<?php echo $admin['last_name'] ?>"></dd>
      </dl>

      <dl>
        <dt>Email</dt>
        <dd><input type="text" name="email" value="<?php echo $admin['email'] ?>"></dd>
      </dl>

      <dl>
        <dt>Username</dt>
        <dd><input type="text" name="username" value="<?php echo $admin['username'] ?>"></dd>
      </dl>

      <dl>
        <dt>Password</dt>
        <dd><input type="password" name="password"></dd>
      </dl>

      <dl>
        <dt>Confirm Password</dt>
        <dd><input type="password" name="pass_confirm"></dd>
      </dl>

      <div id="operations">
      <input type="submit" value="Edit user">
      </div>
    </form>

  </div>
</div>

<?php include(SHARED_PATH . '/staff_footer.php') ?>