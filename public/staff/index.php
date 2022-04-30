<?php require_once('../../private/initialize.php'); ?>

<?php require_login(); ?>

<?php $page_title = 'Staff Menu'; ?>
<?php include(PRIVATE_PATH . '/shared/staff_header.php'); ?>
<!-- the $page_title variable is available inside the staff_header content -->

<div id="content">
  <div id="main-menu">
    <h2>Main Menu</h2>
    <ul>
      <li>
        <a href="<?php echo url_for('/staff/subjects/index.php'); ?>">Subjects</a>
      </li>

      <li>
        <a href="<?php echo url_for('/staff/admin/index.php'); ?>">Admin</a>
      </li>
    </ul>
  </div>

</div>
    
<?php include(PRIVATE_PATH . '/shared/staff_footer.php'); ?>   

  
