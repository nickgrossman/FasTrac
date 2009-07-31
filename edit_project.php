<?php 
require_once('header.php'); 
$project = get_project($project_id);
?>

<p><a href="<?php echo HOME; ?>"> < back to The Board</a></p>

<?php if ($_REQUEST['updated'] == 'true') : ?>
<p class="flash-message">Project updated</p>
<?php endif; ?>


<form action="<?php echo HOME; ?>" method="post">
  <input type="hidden" name="action" value="edit_project" />
  <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
  <p>Project #<?php echo $project_id; ?></p>
  <p>Name: <input type="text" name="project_name" value="<?php echo $project['project_name'];?>" /></p>
  <p>URL: <input type="text" name="project_url" value="<?php echo $project['project_url'];?>" /></p>
  <select name="parked">
    <option>-- Status --</option>
    <option value="false" <?php if ($project['parked'] != 'true'): ?>selected="true"<?php endif;?>>On the board</option>
    <option value="true" <?php if ($project['parked'] == 'true'): ?>selected="true"<?php endif;?>>Off the board</option>
  </select>
  <p><input type="submit" value="Update" /></p>
</form>


<?php 
require_once('footer.php'); 
?>