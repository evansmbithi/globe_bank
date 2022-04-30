<?php 
require_once('../../../private/initialize.php');

// $pages = [
//   ['id' => '1', 'position' => '1', 'visible' => '1', 'menu_name' => 'About Globe Bank'],
//   ['id' => '2', 'position' => '2', 'visible' => '1', 'menu_name' => 'Consumer'],
//   ['id' => '3', 'position' => '3', 'visible' => '1', 'menu_name' => 'Small Business'],
//   ['id' => '4', 'position' => '4', 'visible' => '1', 'menu_name' => 'Commercial'],
// ];

?>

<?php require_login(); ?>

<?php
// content moved to subject/show.php
  redirect_to(url_for('/staff/index.php'));
?>

