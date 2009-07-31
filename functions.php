<?php

function display_items() {
  $items = array(
    'story editing: missing some vertices on polygons when first loading page',
    'Geo-searching should place marker',
    "OL interaction: don't know to double-click to end drawing a line or polygon",
    "Loosen restrictions on almanc boundaries",
    "flash message for story or almanac delete",
    "Reorder story create workflow",
    "Clarify licensing on story-add"
  );
  shuffle($items);
  ?>
  <ul>
  <?php foreach ($items as $key => $item) : ?>
    <li id="ticket-10<?php echo $key; ?>" ft:ticket-id="10<?php echo $key; ?>">
      <input type="checkbox" />
      <a href=""><?php echo $item; ?></a>
    </li>
  <?php endforeach; ?>
  </ul>
  <?php
}

function update_ticket() {
  echo $_POST['ticket_id'];
}


?>