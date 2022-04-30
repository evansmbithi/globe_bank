<?php require_once('../../../private/initialize.php'); ?>

<?php require_login(); ?>

<?php
if(isset($_GET['id'])){
  $id = $_GET['id'];

  $admin = find_admin_by_id($id);
}else{
  redirect_to(url_for('/staff/admin/index.php'));
}
?>

<?php $page_title='Show Admin'; ?>
<?php include(SHARED_PATH . '/staff_header.php');?>

<div id="content">

<a class="back-link" href="<?php echo url_for('/staff/admin/index.php'); ?>">&laquo; Back to List</a>

  <div class="Show Admin"> 
  <h1><?php echo $page_title; ?></h1>

    <form action="" method="post">
      <dl>
        <dt>First Name</dt>
        <dd><?php echo $admin['first_name']; ?></dd>
      </dl>

      <dl>
        <dt>Last Name</dt>
        <dd><?php echo $admin['last_name']; ?></dd>
      </dl>

      <dl>
        <dt>Email</dt>
        <dd><?php echo $admin['email']; ?></dd>
      </dl>

      <dl>
        <dt>Username</dt>
        <dd><?php echo $admin['username']; ?></dd>
      </dl>

      <dl>
        <!-- <dt>Password</dt> -->
        <dd></dd>
      </dl>

    </form>

  </div>
</div>

<?php include(SHARED_PATH . '/staff_footer.php') ?>