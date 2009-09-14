<?php 

#
# Load up files
#
require_once('functions.php'); 


#
# Route URLS
#
if ($_GET['action'] == 'display_items') {
  $filter = $_GET['key'];
  $groupby = $_GET['groupby'];
  display_items($filter, $groupby);
} elseif ($_POST['action'] == 'update_ticket') {
  update_ticket();
} else {
  require_once('display.php');
}













?>