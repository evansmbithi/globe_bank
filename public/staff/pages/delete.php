<?php require_once('../../../private/initialize.php'); ?>

<?php require_login(); ?>

<?php

if(!isset($_GET['id'])){
  redirect_to(url_for('/staff/pages/index.php'));
}

$id = $_GET['id'];

if(is_post_request()){
  $page_delete = delete_page($id);
  // Store Status message in sessions
  $_SESSION['message'] = 'The page was deleted successfully!'; 
  redirect_to(url_for('/staff/pages/index.php'));

}else{
  $page = get_page_by_id($id);
  

}
?>

<?php $page_title = "Delete Page"; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
  <a href="<?php echo url_for('/staff/pages');?>">&laquo; Back to List</a>

  <h1>Delete Page</h1>
  <p>Are you sure you want to delete page <?php echo h($page['menu_name']); ?></p>

  <form action="<?php url_for('/staff/pages/delete.php') ?>" method="post">
  <div id="operations">
  <input type="submit" value="Delete Page">
  </div>
  
</form>


</div>

<?php include(SHARED_PATH . '/staff_footer.php');