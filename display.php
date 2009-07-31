<?php 
require_once('header.php'); 
require_once('display.inc.php'); 
?>

<div id="sourcelist">
  <h2>People</h2>
  <ul>
    <li><a href="" ft:key="owner" ft:value="Nick">Nick</a></li>
    <li><a href="" ft:key="owner" ft:value="Jeff">Jeff</a></li>
    <li><a href="" ft:key="owner" ft:value="Sonali">Sonali</a></li>
  </ul>
  
  <h2>Milestones</h2>
  <ul>
    <li><a href="" ft:key="milestone" ft:value="0.1 Yahtzee">0.1 Yahtzee</a></li>
    <li><a href="" ft:key="milestone" ft:value="0.2 Booyah">0.2 Booyah</a></li>
    <li><a href="" ft:key="milestone" ft:value="0.3 Lobsterman">0.3 Lobsterman</a></li>
  </ul>
  
  <h2>Components</h2>
  <ul>
    <li><a href="" ft:key="component" ft:value="Groups">Groups</a></li>
    <li><a href="" ft:key="component" ft:value="Wiki">Wiki</a></li>
    <li><a href="" ft:key="component" ft:value="Sysadmin">Sysadmin</a></li>
  </ul>    
</div>

<div id="content">
  <div id="groupby" style="display: none">
    Group by: 
    <select>
      <option value="milestone">Milestone</option>        
      <option value="milestone">Component</option>
    </select>
  </div>
  <div id="tickets">
    <?php /* display_items(); */?>
  </div>
  
</div>
<?php require_once('footer.php'); ?>
