<?php require_once('../../../private/initialize.php'); ?>

<?php require_login(); ?>

<?php
// $id = isset($_GET['id']) ? $_GET['id'] : '1';
//if the id is not set, the default id will be '1'
$id = $_GET['id'] ?? '1';

$subject = get_subject_by_id($id);

// nested resource
// loop through pages with a common subject
$page_set = get_pages_by_subject_id($id);

?>

<?php $page_title='Show Subjects'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/subjects/index.php'); ?>">&laquo; Back to List</a>

  <div class="subject new">
    <h1>Subject: <?php echo h($subject['menu_name']) ?></h1>

      <dl>
        <dt>Menu Name</dt>
        <dd><?php echo h($subject['menu_name']) ?></dd>
      </dl>
      <dl>
        <dt>Position</dt>
        <dd><?php echo h($subject['position']) ?></dd>
      </dl>
      <dl>
        <dt>Visible</dt>
        <dd><?php echo $subject['visible'] === '1' ? 'True' : 'False' ?></dd>
      </dl>

  </div>

  <hr />

  <div class="pages listing">
  <h2>Pages</h2>

  <?php
  // // $session = 
  //   if(isset($_GET['session'])){
  //     echo $_SESSION['page_deleted'];
  //   }else{
  //     unset($_SESSION['page_deleted']);
  //   }
  ?>

<div class="actions">
  <a class="action" href="<?php echo url_for('/staff/pages/new.php?subject_id=' . h(u($subject['id']))); ?>">Create New Page</a>
</div>

<div>
  <table class="list">
    <tr>
    <th>ID</th>
    <!-- <th>Subject ID</th> -->
    <th>Position</th>
    <th>Visible</th>
    <th>Name</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>   
    </tr>
    
    
    <?php 
      // foreach ($pages as $page) {
      while ($page = mysqli_fetch_assoc($page_set)) {
        $subject = get_subject_by_id($page['subject_id']);
      
    ?>
    <tr>
    <td><?php echo h($page['id']) ?></td>
    <!-- 
      <td><?php //echo h($subject['menu_name']) ?></td> 
    -->
    <td><?php echo h($page['position']) ?></td>
    <td><?php echo $page['visible'] == 1 ? 'true' : 'false'; ?></td>
    <td><?php echo h($page['menu_name']) ?></td>
    <td><a class="action" href="<?php echo url_for('/staff/pages/show.php?id=' . h(u($page['id']))) ?>">View</a></td>
    <td><a class="action" href="<?php echo url_for('/staff/pages/edit.php?id=' . h(u($page['id']))) ?>">Edit</a></td>
    <td><a class="action" href="<?php echo url_for('/staff/pages/delete.php?id=' . h(u($page['id']))) ?>">Delete</a></td>
    </tr>
    <?php } ?>
    
    
  </table>
</div>
</div>

</div>


<?php include(SHARED_PATH . '/staff_footer.php'); ?>

