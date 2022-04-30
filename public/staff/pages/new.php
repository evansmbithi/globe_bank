<?php require_once('../../../private/initialize.php'); ?>

<?php require_login(); ?>

<?php
if(is_post_request()){
  $page = [];
  $page['subject_id'] = $_POST['subject'] ?? '';
  $page['menu_name'] = $_POST['menu-name'] ?? '';
  $page['position'] = $_POST['position'] ?? '';
  $page['visible'] = $_POST['visible'] ?? '';
  $page['content'] = $_POST['content'] ?? '';

  $page_insert = insert_page($page);

  if($page_insert === true){
    // Store Status message in sessions
  $_SESSION['message'] = 'The page was created successfully!';

    // get the page id for new page
  $page_id = mysqli_insert_id($db);
  redirect_to(url_for('/staff/pages/show.php?id=' . $page_id));
  }else{
    $errors = $page_insert;
    // var_dump($errors);
  }

  
}else{
  // redirect_to(url_for('/staff/pages/new.php'));
  $page = [];
  $page['subject_id'] = '';
  $page['menu_name'] = '';
  $page['position'] = '';
  $page['visible'] = '';
  $page['content'] = '';
}
?>

<?php
// Page count + 1 for the new page

$page_set = find_all_pages();
// total number of rows
$page_count = mysqli_num_rows($page_set) + 1;
mysqli_free_result($page_set);
// $page_set = find_all_pages();
// $page_count = mysqli_num_rows($page_set);


?>

<?php $page_title = 'Create Page'; ?>

<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
  <a href="<?php echo url_for('/staff/pages');?>">&laquo; Back to List</a>

  <div class="subject new">
    <h1>Create Page</h1>

    <?php echo display_errors($errors); ?>

    <form action="<?php echo url_for('/staff/pages/new.php') ?>" method="post">
      <dl>
        <dt>Subject</dt>
        <dd>
          <select name="subject">
            <?php 
            // find all subjects
            // while loop to loop through the subject_set
            $subject_set = find_all_subjects();

            while($subject = mysqli_fetch_assoc($subject_set)){
              echo "<option value=\"" . h($subject['id']) . "\"";
              if($page['subject_id'] == $subject['id']){
                echo "selected";
              }
                echo ">" . h($subject['menu_name']) . "</option>";
            }
             ?>            
          </select>
        </dd>
      </dl>
      <dl>
        <dt>Menu Name</dt>
        <dd><input type="text" name="menu-name" value=""></dd>
      </dl>

      <dl>
        <dt>Position</dt>
        <dd><select name="position">
          <?php
          // use for loop to loop through the page_count
          // the new page ought to have a new position
          // the page_count becomes the last element in the loop
          // position is set to the last element
          
        /*
          $s = find_all_subjects();          
          for($i=1; $i<=$subject_count; $i++){
            $result = mysqli_fetch_assoc($s);
            echo "<option value=\"";
            echo $result['position'];
            echo "\">";
            echo $result['position'];
            echo "</option>";
          }
          */
        
          for($i=1; $i<=$page_count; $i++){
            echo "<option value=\"{$i}\"";
            if($page_count == $i){
              echo "selected";
            }          
            echo ">{$i}</option>";
          }
          ?>          
        </select></dd>
      </dl>

      <dl>
        <dt>Visible</dt>
        <dd>
          <input type="hidden" name="visible" value="0">
          <input type="checkbox" name="visible" value="1">
      </dd>
      </dl>

      <dl>
        <dt>Content</dt>
        <dd>
          <textarea name="content" cols="60" rows="10"></textarea>
        </dd>
      </dl>

      <div id="operations">
        <input type="submit" value="Create Page">
      </div>
    </form>
  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>