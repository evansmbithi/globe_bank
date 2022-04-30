<?php require_once('../../../private/initialize.php'); ?>

<?php require_login(); ?>

<?php
if(!isset($_GET['id'])){
  redirect_to(url_for('/staff/pages/index.php'));
}

$id = $_GET['id'];

if(is_post_request()){
  $page = [];
  $page['id'] = $id;
  $page['subject_id'] = $_POST['subject'] ?? '';
  $page['menu_name'] = $_POST['menu_name'] ?? '';
  $page['position'] = $_POST['position'] ?? '';
  $page['visible'] = $_POST['visible'] ?? '';
  $page['content'] = $_POST['content'] ?? '';

  $update = update_page($page);
  if($update === true){
    // Store Status message in sessions
    $_SESSION['message'] = 'The page was updated successfully!';
    redirect_to(url_for('/staff/pages/show.php?id=' . h(u($id))));
  }else{
    $errors = $update;
  }

}else{
  $page = get_page_by_id($id);
  
}
  // page count
  $page_set = find_all_pages();
  $page_count = mysqli_num_rows($page_set);
  mysqli_free_result($page_set);

?>

<?php $page_title = 'Edit Page'; ?>

<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
  <a href="<?php echo url_for('/staff/pages');?>">&laquo; Back to List</a>

  <div class="subject new">
    <h1>Edit Page: <?php echo h($page['menu_name']) ?></h1>

    <?php echo display_errors($errors); ?>

    <form action="<?php url_for('/staff/pages/edit.php?id=' . h(u($id))) ?>" method="post">
    <dl>
        <dt>Subject</dt>
        <dd>
          <select name="subject">
            <?php                  
            $subject_set = find_all_subjects();
            // $subject_count = mysqli_num_rows($subject_set);
            //   for ($i=1; $i<=$subject_count; $i++){
            //     echo "<option value=\"{$i}\"";
            //     if($i == $page['subject_id']){
            //       echo "selected";
            //     }
            //     echo ">";
            //     $subject = mysqli_fetch_assoc($subject_set);                          
            //     echo h($subject['menu_name']) . "</option>";
            //   }
            while($subject = mysqli_fetch_assoc($subject_set)){
              echo "<option value=\"" . h($subject['id']) . "\"";
              if($page['subject_id'] == $subject['id']){
                echo " selected";
              }
              echo ">";
              echo h($subject['menu_name']) . "</option>";
            }   

             ?>            
          </select>
        </dd>
      </dl>
      <dl>
        <dt>Menu Name</dt>
        <dd><input type="text" name="menu_name" value="<?php echo h($page['menu_name']);
        ?>"></dd>
      </dl>

      <dl>
        <dt>Position</dt>
        <dd><select name="position">
          <?php
          // should loop through the page count
          
          for($i=1; $i<=$page_count; $i++){
            echo "<option value=\"{$i}\"";
            if($page['position'] == $i){
              echo " selected";
            }
            echo ">{$i}";
            echo "</option>";
          }
          ?>
        </select></dd>
      </dl>

      <dl>
        <dt>Visible</dt>
        <dd>
          <input type="hidden" name="visible" value="0">
          <input type="checkbox" name="visible" value="1" <?php if($page['visible'] == "1"){ echo "checked"; } ?>>
      </dd>
      </dl>

      <dl>
        <dt>Content</dt>
        <dd>
          <textarea name="content" cols="60" rows="10"><?php echo h($page['content']); ?></textarea>
        </dd>
      </dl>

      <div id="operations">
        <input type="submit" value="Edit Page">
      </div>
    </form>
  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>