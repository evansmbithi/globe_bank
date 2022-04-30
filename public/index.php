<?php require_once('../private/initialize.php'); ?>

<?php

$preview = false;
if(isset($_GET['preview'])){
  $preview = $_GET['preview'] == 'true' && is_logged_in() ? true : false;
}

$visible = !$preview;

if(isset($_GET['id'])){
  // select pages
  $page_id = $_GET['id'];
  $page = get_page_by_id($page_id, ['visible' => $visible]);
  
  if(!$page){
    redirect_to(url_for('/index.php'));
  }

  $subject_id = $page['subject_id'];

  // check if subject is visible
  $subject = get_subject_by_id($subject_id, ['visible' => $visible]);
  if(!$subject){
    redirect_to(url_for('/index.php'));
  }

}elseif(isset($_GET['subject_id'])){
  $subject_id = $_GET['subject_id'];

  // check if subject is visible
  $subject = get_subject_by_id($subject_id, ['visible' => $visible]);
  if(!$subject){
    redirect_to(url_for('/index.php'));
  }

  // if a subject is clicked
  // get pages by subject_id and select the first page
  $page_set = get_pages_by_subject_id($subject_id, ['visible' => $visible]);
  $page = mysqli_fetch_assoc($page_set); //first page
  mysqli_free_result($page_set);
  if(!$page){
    redirect_to(url_for('/index.php'));
  }
  $page_id = $page['id'];

}
// elseif(isset($_GET['preview'])){
//   $page_id = $_GET['preview'];
//   $page = get_page_by_id($page_id);
//   // if(!$page){
//   //   redirect_to(url_for('/index.php'));
//   // }
// }

?>

<?php include(SHARED_PATH . '/public_header.php'); ?>

<div id="main">

<?php include(SHARED_PATH . '/public_navigation.php'); ?>

  <div id="page">

  <?php
  // if page_id is not set, it defaults to the static homepage
  if(isset($page)){
    // show page from db

    // echo nl2br(strip_tags($page['content']));
    // strip all HTML tags out and only allow the following
    $allowed_tags = '<div><img><h1><h2><p><br><strong><em><ul><li>';
    echo strip_tags($page['content'], $allowed_tags);
  }else{
    include(SHARED_PATH . '/static_homepage.php');
  } 
   
  ?>

  </div>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>
