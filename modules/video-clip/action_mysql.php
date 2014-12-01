<?php

/**
 * @Project VIDEO CLIPS AJAX 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 01, 2014, 04:33:14 AM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_clip";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_hit";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_topic";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comm";

$sql_create_module = $sql_drop_module;
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_clip (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  tid mediumint(8) unsigned NOT NULL DEFAULT '0',
  title varchar(255) NOT NULL DEFAULT '',
  alias varchar(255) NOT NULL DEFAULT '',
  hometext mediumtext NOT NULL,
  bodytext mediumtext NOT NULL,
  keywords mediumtext NOT NULL,
  img varchar(255) NOT NULL,
  internalpath varchar(255) NOT NULL,
  externalpath mediumtext NOT NULL,
  groups_view varchar(255) NOT NULL,
  comm tinyint(1) unsigned NOT NULL DEFAULT '1',
  status tinyint(1) unsigned NOT NULL DEFAULT '0',
  addtime int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY alias (alias),
  KEY tid (tid)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_hit (
  cid mediumint(8) unsigned NOT NULL DEFAULT '0',
  view mediumint(8) unsigned NOT NULL DEFAULT '0',
  likehit mediumint(8) unsigned NOT NULL DEFAULT '0',
  unlikehit mediumint(8) unsigned NOT NULL DEFAULT '0',
  comment mediumint(8) unsigned NOT NULL DEFAULT '0',
  broken tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (cid),
  KEY view (view),
  KEY likehit (likehit),
  KEY unlikehit (unlikehit)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_topic (
  id mediumint(8) unsigned NOT NULL auto_increment,
  parentid mediumint(8) unsigned NOT NULL,
  title varchar(255) NOT NULL,
  alias varchar(255) NOT NULL,
  description mediumtext NOT NULL,
  weight smallint(4) unsigned NOT NULL default '0',
  img varchar(255) NOT NULL,
  status tinyint(1) unsigned NOT NULL default '0',
  keywords mediumtext NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY alias (alias)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comm (
  id mediumint(8) unsigned NOT NULL auto_increment,
  cid mediumint(8) unsigned NOT NULL default '0',
  content mediumtext NOT NULL,
  posttime int(11) unsigned NOT NULL default '0',
  userid mediumint(8) unsigned NOT NULL default '0',
  ip varchar(15) NOT NULL,
  status tinyint(1) unsigned NOT NULL default '0',
  broken mediumint(8) unsigned NOT NULL default '0',
  ischecked tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY posttime (userid,posttime),
  KEY cid (cid)
) ENGINE=MyISAM";