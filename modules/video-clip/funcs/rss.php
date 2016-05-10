<?php

/**
 * @Project VIDEO CLIPS AJAX 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 01, 2014, 04:33:14 AM
 */

if (!defined('NV_IS_MOD_VIDEOCLIPS'))
    die('Stop!!!');

$channel = array();
$items = array();

$channel['title'] = $module_info['custom_title'];
$channel['link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$channel['atomlink'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=rss";
$channel['description'] = !empty($module_info['description']) ? $module_info['description'] : $global_config['site_description'];

$sql = "SELECT id, addtime, title, alias, hometext, img FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip WHERE status=1 ORDER BY addtime DESC LIMIT 30";

if ($module_info['rss']) {
    $result = $db->query($sql);
    while (list($id, $publtime, $title, $alias, $hometext, $homeimgfile) = $result->fetch(3)) {
        if (!empty($homeimgfile)) {
            $homeimgfile = substr($homeimgfile, strlen(NV_UPLOADS_DIR));
            if (file_exists(NV_ROOTDIR . '/' . NV_ASSETS_DIR . $homeimgfile)) {
                $homeimgfile = NV_BASE_SITEURL . NV_ASSETS_DIR . $homeimgfile;
            } elseif (file_exists(NV_UPLOADS_REAL_DIR . $homeimgfile)) {
                $homeimgfile = NV_BASE_SITEURL . NV_UPLOADS_DIR . $homeimgfile;
            } else {
                $homeimgfile = '';
            }
        }
        if (empty($homeimgfile)) {
            $homeimgfile = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/video.png";
        }

        $rimages = (!empty($homeimgfile)) ? "<img src=\"" . NV_MY_DOMAIN . $homeimgfile . "\" width=\"100\" border=\"0\" align=\"left\">" : "";
        $items[] = array(
            'title' => $title,
            'link' => NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $alias . $global_config['rewrite_exturl'],
            'guid' => $module_name . '_' . $id,
            'description' => $rimages . $hometext,
            'pubdate' => $publtime);
    }
}

nv_rss_generate($channel, $items);
die();
