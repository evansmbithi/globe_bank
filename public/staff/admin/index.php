<?php require_once('../../../private/initialize.php'); ?>

<?php require_login(); ?>

<?php
  // $admins = [
  //   ['id' => '1', 'first_name' => 'evans', 'last_name' => 'mbithi', 'email' => 'evholmbithy@gmail.com', 'username' => 'mbithy', 'hashed_password' => '']
  // ];

  $admins = find_all_admins();
?>

<?php $page_title = 'Admins'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
  <div class="subjects listing">
    <h1><?php echo $page_title; ?></h1>

    <div class="actions">
      <a class="action" href="<?php echo url_for('/staff/admin/new.php'); ?>">Create New Admin</a>
    </div>

  	<table class="list">
  	  <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
  	    <th>Email</th>
        <th>Username</th>
        <!-- <th>Password</th> -->
  	    <th>&nbsp;</th>
  	    <th>&nbsp;</th>
        <th>&nbsp;</th>
  	  </tr>

      <?php while($admin = mysqli_fetch_assoc($admins)) { ?>
        <tr>
          <td><?php echo $admin['id']; ?></td>
          <td><?php echo $admin['first_name']; ?></td>
          <td><?php echo $admin['last_name']; ?></td>
    	    <td><?php echo $admin['email']; ?></td>
          <td><?php echo $admin['username']; ?></td>
          <!-- <td><?php echo $admin['hashed_password']; ?></td> -->
          <td><a class="action" href="<?php echo url_for('/staff/admin/show.php?id='. h(u($admin['id']))) ?>">View</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/admin/edit.php?id='. h(u($admin['id']))) ?>">Edit</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/admin/delete.php?id='. h(u($admin['id']))) ?>">Delete</a></td>
    	  </tr>
      <?php } ?>
      <?php mysqli_free_result($admins) ?>
  	</table>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
