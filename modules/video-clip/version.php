<?php

/**
 * @Project VIDEO CLIPS AJAX 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 01, 2014, 04:33:14 AM
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

$module_version = array(
    "name" => "Videoclips",
    "modfuncs" => "main",
    "submenu" => "main",
    "is_sysmod" => 0,
    "virtual" => 1,
    "version" => "4.0.28",
    "date" => "Tue, 10 May 2016 11:36:55 GMT",
    "author" => "PHAN TAN DUNG (phantandung92@gmail.com)",
    "uploads_dir" => array($module_name),
    "note" => "Module playback of video-clips",
    "uploads_dir" => array(
        $module_name,
        $module_name . "/icons",
        $module_name . "/images",
        $module_name . "/video"),
    "files_dir" => array($module_name)
);
