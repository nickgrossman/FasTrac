<?php

define('CODE_VERSION', 0.4);


# User
if ($_SERVER['PHP_AUTH_USER']) {
  define('CURRENT_USER', $_SERVER['PHP_AUTH_USER']);
} else {
  define('CURRENT_USER', 'anonymous');
}

// Make the connnection and then select the database.
$dbc = @mysql_connect (DB_HOST, DB_USER, DB_PASSWORD) OR die ('Could not connect to MySQL: ' . mysql_error() );
mysql_select_db (DB_NAME) OR die ('Could not select the database: ' . mysql_error() );


#
# Database Version
#
if((mysql_query("SELECT 1 FROM options LIMIT 0"))) {
  $q = mysql_query('SELECT option_value FROM options WHERE option_name = "dsb_version" LIMIT 1');
  $r = mysql_fetch_row($q);
  define('DB_VERSION', $r[0]);
} else {
  define('DB_VERSION', 0);
}


if (CODE_VERSION > DB_VERSION || DB_VERSION == '') {
  check_database();
} else {
  die('no check');
}


function check_database() {

# Initial table setup

/* Entries table */
if(!(mysql_query("SELECT 1 FROM entries LIMIT 0"))) {
  mysql_query("
  CREATE TABLE `entries` (
  `entry_id` int(11) NOT NULL auto_increment,
  `startdate` date default NULL,
  `project_id` int(11) default NULL,
  `person_id` int(11) default NULL,
  PRIMARY KEY  (`entry_id`)
  ) ENGINE=MyISAM AUTO_INCREMENT=191 DEFAULT CHARSET=latin1
  ");
}

/* People table */
if(!(mysql_query("SELECT 1 FROM people LIMIT 0"))) {
  mysql_query("
  CREATE TABLE `people` (
  `person_id` int(11) NOT NULL auto_increment,
  `person_name` varchar(100) default NULL,
  `person_role` varchar(11) default NULL,
  PRIMARY KEY  (`person_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1
  ");
}

/* Projects table */
if(!(mysql_query("SELECT 1 FROM projects LIMIT 0"))) {
  mysql_query("
  CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL auto_increment,
  `project_name` varchar(100) default NULL,
  `sort_order` int(11) default NULL,
  `parked` varchar(20) default NULL,
  PRIMARY KEY  (`project_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1
  ");
}

/* Changes table */
if(!(mysql_query("SELECT 1 FROM changes LIMIT 0"))) {
  mysql_query("
  CREATE TABLE `changes` (
  `change_id` int(11) NOT NULL auto_increment,
  `timestamp` datetime default NULL,
  `instigator` varchar(100) default NULL,
  `verb` varchar(100) default NULL,
  `person_id` int(11) default NULL,
  `preposition` varchar(100) default NULL,
  `project_id` int(11) default NULL,
  `week` varchar(100) default NULL,
  PRIMARY KEY  (`change_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1
  ");
}

/* Options table */
if(!(mysql_query("SELECT 1 FROM options LIMIT 0"))) {
  mysql_query("
  CREATE TABLE `options` (
  `option_id` int(11) NOT NULL auto_increment,
  `option_name` varchar(100) default NULL,
  `option_value` text default NULL,
  PRIMARY KEY  (`option_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1
  ");
}

  #
  # add "project_url" field to projects table */
  #
  $fields = mysql_list_fields(DB_NAME, 'projects');
  $columns = mysql_num_fields($fields);
  for ($i = 0; $i < $columns; $i++) {$field_array[] = mysql_field_name($fields, $i);}
  
  if (!in_array('project_url', $field_array)) {
    $result = mysql_query('ALTER TABLE projects ADD project_url text');
  }
  # end project_url
  
  
  #
  # add "person_long_name" field to people table */
  #
  $fields = mysql_list_fields(DB_NAME, 'people');
  $columns = mysql_num_fields($fields);
  for ($i = 0; $i < $columns; $i++) {$field_array[] = mysql_field_name($fields, $i);}
  
  if (!in_array('person_long_name', $field_array)) {
    $result = mysql_query('ALTER TABLE people ADD person_long_name VARCHAR(200)');
  }
  # end project_url  
  
  #
  # Versions after 0.3
  # 
  
  
  /* Assuming all went well, bump DB # */
  $q = mysql_query('SELECT option_value FROM options WHERE option_name = "db_version"');
  $r = mysql_fetch_row($q);
  if (!$r) {
    mysql_query('INSERT INTO options (option_name, option_value) VALUES ("db_version", '. CODE_VERSION . ')');
  } else {
    mysql_query('UPDATE options SET option_value = "'. CODE_VERSION . '" where option_name = "db_version"');
  }
}



?>