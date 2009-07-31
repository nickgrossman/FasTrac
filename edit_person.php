<?php 
require_once('header.php'); 
$person = get_person($person_id);
?>

<p><a href="<?php echo HOME; ?>"> < back to The Board</a></p>

<?php if ($_REQUEST['updated'] == 'true') : ?>
<p class="flash-message">Person updated</p>
<?php endif; ?>


<form action="<?php echo HOME; ?>" method="post">
  <input type="hidden" name="action" value="edit_person" />
  <input type="hidden" name="person_id" value="<?php echo $person_id; ?>" />
  <p>Person #<?php echo $person_id; ?></p>
  <p>Short Name: <input type="text" name="person_name" value="<?php echo $person['person_name'];?>" /></p>
  <p>Long Name: <input type="text" name="person_long_name" value="<?php echo $person['person_long_name'];?>" /></p>
  <p>Role: 
  <select name="person_role">
    <option value="">-- Choose --</option>
    <option value="dev" <?php if ($person['person_role'] == 'dev'): ?>selected="true"<?php endif;?>>Dev</option>
    <option value="dzn" <?php if ($person['person_role'] == 'dzn'): ?>selected="true"<?php endif;?>>Dzn</option>
  </select>
  </p>  
  
  <p><input type="submit" value="Update" /></p>
</form>


<?php 
require_once('footer.php'); 
?>