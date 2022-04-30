<?php require_once('../../../private/initialize.php'); ?>

<?php require_login(); ?>

<?php
if(isset($_GET['id'])){
  $id = $_GET['id'];

  $admin = find_admin_by_id($id);
  $current = session_admin($id);
}else{
  redirect_to(url_for('/staff/admin/index.php'));
}

if(is_post_request()){
  $delete = delete_admin($id);
  if($delete === true){
    $_SESSION['message'] = "Admin $current deleted successfully";
    redirect_to(url_for('/staff/admin/index.php'));
  }
}
?>

<?php $page_title='Delete Admin'; ?>
<?php include(SHARED_PATH . '/staff_header.php');?>

<div id="content">

<a class="back-link" href="<?php echo url_for('/staff/admin/index.php'); ?>">&laquo; Back to List</a>

  <div class="Delete Admin">
    <h1><?php echo $page_title; ?></h1>

    <p>Are you sure you want to delete admin "<?php echo $admin['username'] ?>"?</p>

    <form action="<?php echo url_for('/staff/admin/delete.php?id=' . h(u($id))) ?>" method="post">

    <div id="operations">
      <input type="submit" value="Delete Admin">
    </div>
    </form>

  </div>
</div>

<?php include(SHARED_PATH . '/staff_footer.php') ?>