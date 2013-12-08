<?php

/**
 * @Project VIDEO CLIPS AJAX 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 08, 2013, 09:57:59 PM
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
	"name" => "Videoclips",
	"modfuncs" => "main",
	"submenu" => "main",
	"is_sysmod" => 0,
	"virtual" => 1,
	"version" => "3.4.04",
	"date" => "Thu, 20 Sep 2012 04:05:46 GMT",
	"author" => "PHAN TAN DUNG (phantandung92@gmail.com)",
	"uploads_dir" => array( $module_name ),
	"note" => "Module playback of video-clips",
	"uploads_dir" => array(
		$module_name,
		$module_name . "/icons",
		$module_name . "/images",
		$module_name . "/video"
	),
	"files_dir" => array( $module_name ) 
);

?>