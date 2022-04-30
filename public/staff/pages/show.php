<?php require_once('../../../private/initialize.php'); ?>

<?php require_login(); ?>

<?php
// $id = isset($_GET['id']) ? $_GET['id'] : '1';
//if the id is not set, the default id will be '1'
if(!isset($_GET['id'])){
  redirect_to(url_for('/staff/pages/index.php'));
}

$id = $_GET['id'] ?? '1';

// Find page by id no need for subject count
$page = get_page_by_id($id);
$page_set = find_all_pages();
$page_count = mysqli_num_rows($page_set);
mysqli_free_result($page_set);

?>

<?php 
    // Find subject by id ($page['subject_id'])
    $subject = get_subject_by_id($page['subject_id']); 
?>

<?php $page_title='Show Pages'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('staff/subjects/show.php?id=' . h(u($subject['id']))) ?>">&laquo; Back to subject page</a>

  <div class="page show">
  
    <h1>Page: <?php echo h($page['menu_name']) ?></h1>

    <div class="actions">
    <a class="action" target="_blank" href="<?php echo url_for('index.php?id='. h(u($page['id']))) . '&preview=true'?>">Preview</a>
    </div>

    <dl>
      <dt>Subject</dt>
      
      <dd>
        <?php echo h($subject['menu_name']); ?>
      </dd>
    </dl>  
    <dl>
        <dt>Menu Name</dt>
        <dd><?php echo h($page['menu_name']) ?></dd>
      </dl>
      <dl>
        <dt>Position</dt>
        <dd><?php echo h($page['position']) ?></dd>
      </dl>
      <dl>
        <dt>Visible</dt>
        <dd><?php echo $page['visible'] === '1' ? 'True' : 'False' ?></dd>
      </dl>

      <dl>
        <dt>Content</dt>
        <dd><?php echo h($page['content']) ?></dd>
      </dl>

  </div>
    

  

</div>

<?php include(SHARED_PATH . '/staff_footer.php');