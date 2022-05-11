<?php require_once('../../../private/initialize.php'); ?>
<!-- we can't use a constant here because the initialize.php file contains all the constants -->

<?php require_login(); ?>

<?php
  // $subjects = [
  //   ['id' => '1', 'position' => '1', 'visible' => '1', 'menu_name' => 'About Globe Bank'],
  //   ['id' => '2', 'position' => '2', 'visible' => '1', 'menu_name' => 'Consumer'],
  //   ['id' => '3', 'position' => '3', 'visible' => '1', 'menu_name' => 'Small Business'],
  //   ['id' => '4', 'position' => '4', 'visible' => '1', 'menu_name' => 'Commercial'],
  //   // ['id' => '5', 'position' => '5', 'visible' => '0', 'menu_name' => 'New Info']
  // ];
?>

<?php
$subject_set = find_all_subjects();
?>

<?php $page_title = 'Subjects'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>
<!-- the $page_title variable is available inside the staff_header content -->

<div id="content">
  <div class="subjects listing">
    <h1>Subjects</h1>

    <div class="actions">
      <a class="action" href="<?php echo url_for('/staff/subjects/new.php'); ?>">Create New Subject</a>
    </div>

  	<table class="list">
  	  <tr>
        <th>ID</th>
        <th>Position</th>
        <th>Visible</th>
  	    <th>Name</th>
        <th>Pages</th>
  	    <th>&nbsp;</th>
  	    <th>&nbsp;</th>
        <th>&nbsp;</th>
  	  </tr>

  <?php 
      // foreach($subjects as $subject) {
      while($subject = mysqli_fetch_assoc($subject_set)) {
  ?>
  <?php $page_count = count_pages_by_subject_id($subject['id']); ?>
        <tr>
          <td><?php echo h($subject['id']) ?></td>
          <td><?php echo h($subject['position']) ?></td>
          <td><?php echo $subject['visible'] == 1 ? 'true' : 'false'; ?></td>
    	    <td><?php echo h($subject['menu_name']) ?></td>
          <td><?php echo $page_count; ?></td>
          <td><a class="action" href="<?php echo url_for('/staff/subjects/show.php?id=' . h(u($subject['id']))) ?>">View</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/subjects/edit.php?id='. h(u($subject['id']))); ?>">Edit</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/subjects/delete.php?id=' . h(u($subject['id']))) ?>">Delete</a></td>
    	  </tr>
      <?php } ?>
  	</table>

    <?php mysqli_free_result($subject_set); ?>

  </div>

</div>
    
<?php include(SHARED_PATH . '/staff_footer.php'); ?>  