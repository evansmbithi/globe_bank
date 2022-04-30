<?php require_once('../../../private/initialize.php'); ?>

<?php require_login(); ?>

<?php

if(!isset($_GET['id'])){
  redirect_to(url_for('/staff/subjects/index.php'));
}

$id = $_GET['id'];

if(is_post_request()){
  $result = delete_subject($id);
  // Store Status message in sessions
  $_SESSION['message'] = 'The subject was deleted successfully!';
  redirect_to(url_for('/staff/subjects/index.php'));
}else{
  // only find the subject if it's not a POST request
  // either we delete the subject or we find the subject
  $subject = get_subject_by_id($id);
}
?>

<?php $page_title = 'Delete Subject'; ?>

<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
<a class="back-link" href="<?php echo url_for('/staff/subjects/index.php'); ?>">&laquo; Back to List</a>

<div class="subject delete">
  <h1>Delete subject</h1>
  <p>Are you sure you want to delete subject <?php echo h($subject['menu_name']); ?>?</p>
  <p class="item"></p>

  <form action="<?php echo url_for('/staff/subjects/delete.php?id=' . h(u($subject['id']))) ?>" method="post">
  <div id="operations">
    <input type="submit" value="Delete Subject">
  </div>
</form>

</div>


</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>