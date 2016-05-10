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

$pgnum = $nv_Request->get_int("page", "get", 1); // Trang
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
if ($topicID) {
    $base_url .= "&amp;" . NV_OP_VARIABLE . "=" . $topicList[$topicID]['alias'];
}

$sqlTopic = "";
if ($topicID) {
    if (empty($topicList[$topicID]['subcats'])) {
        $sqlTopic = " AND a.tid=" . $topicID;
    } else {
        $sqlTopic = $topicList[$topicID]['subcats'];
        $sqlTopic[] = $topicID;
        $sqlTopic = " AND a.tid IN(" . implode(",", $sqlTopic) . ")";
    }
}

$sql = "SELECT SQL_CALC_FOUND_ROWS a.*,b.view FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip a, 
" . NV_PREFIXLANG . "_" . $module_data . "_hit b 
WHERE a.id=b.cid AND a.status=1" . $sqlTopic . " 
ORDER BY a.id DESC 
LIMIT " . (($pgnum - 1) * $configMods['otherClipsNum']) . "," . $configMods['otherClipsNum'];

$xtpl = new XTemplate("main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('MODULECONFIG', $configMods);

// Xuat video
$result = $db->query($sql);
$res = $db->query("SELECT FOUND_ROWS()");
$all_page = $res->fetchColumn();
$all_page = intval($all_page);
if ($all_page) {
    $i = 1;
    while ($row = $result->fetch()) {
        if (!empty($row['img'])) {
            $row['img'] = substr($row['img'], strlen(NV_UPLOADS_DIR));
            if (file_exists(NV_ROOTDIR . '/' . NV_ASSETS_DIR . $row['img'])) {
                $row['img'] = NV_BASE_SITEURL . NV_ASSETS_DIR . $row['img'];
            } elseif (file_exists(NV_UPLOADS_REAL_DIR . $row['img'])) {
                $row['img'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . $row['img'];
            } else {
                $row['img'] = '';
            }
        }
        if (empty($row['img'])) {
            $row['img'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/video.png";
        }
        
        $row['href'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $row['alias'] . $global_config['rewrite_exturl'];
        $row['topicTitle'] = $topicList[$row['tid']]['title'];
        $row['topicLink'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $topicList[$row['tid']]['alias'];

        $xtpl->assign('OTHERCLIPSCONTENT', $row);

        if ($i++ % 3 == 0) {
            $xtpl->parse('main.otherClipsContent.clear');
        }

        $xtpl->parse('main.otherClipsContent');
    }

    $generate_page = nv_generate_page($base_url, $all_page, $configMods['otherClipsNum'], $pgnum, true, true, 'nv_urldecode_ajax', 'VideoPageData');

    if (!empty($generate_page)) {
        $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.nv_generate_page');
    }
}

// Xuat chu de
if (!empty($topicList)) {
    foreach ($topicList as $topic) {
        if ($topic['parentid'] == 0) {
            $xtpl->assign('OTHERTOPIC', array(
                'href' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $topic['alias'],
                'title' => $topic['title'],
                'current' => $topicID == $topic['id'] ? " current" : "",
                ));

            // Xuat cap 2
            if (!empty($topic['subcats'])) {
                foreach ($topicList as $subtopic) {
                    if (in_array($subtopic['id'], $topic['subcats'])) {
                        $xtpl->assign('OTHERTOPICSUB', array(
                            'href' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $subtopic['alias'],
                            'title' => $subtopic['title'],
                            'current' => $topicID == $subtopic['id'] ? " current" : "",
                            ));

                        $xtpl->parse('main.topicList.loop.sub.loop');
                    }
                }

                $xtpl->parse('main.topicList.loop.sub');
            }

            $xtpl->parse('main.topicList.loop');
        }
    }
    $xtpl->parse('main.topicList');
}

// Chi tiet video
if (!empty($VideoData)) {
    if (!nv_user_in_groups($VideoData['groups_view'])) {
        $xtpl->parse('main.clipForbidden');
    } else {
        $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "modules/" . $module_file . "/js/jquery.autoresize.js\"></script>\n";
        $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "modules/" . $module_file . "/js/jwplayer.js\"></script>\n";

        $xtpl->assign('DETAILCONTENT', $VideoData);

        if (defined('NV_IS_MODADMIN')) {
            $xtpl->parse('main.clipDetail.isAdmin');
        }
        if (!empty($VideoData['bodytext'])) {
            $xtpl->parse('main.clipDetail.bodytext');
        }
        $xtpl->parse('main.clipDetail');
    }
}

$xtpl->parse('main');
$contents = $xtpl->text("main");

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
