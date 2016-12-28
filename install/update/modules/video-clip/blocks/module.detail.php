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

global $array_op, $module_name, $module_data, $nv_Request, $VideoData, $db, $lang_module, $user_info, $client_info, $topicList, $module_info, $module_file, $configMods, $my_head, $isDetail;

// Neu chua co video nao
if (empty($VideoData))
    return "";

$topic = $topicList[$VideoData['tid']];

$comments = array();
$cpgnum = 0;
$commNext = false;
if ($VideoData['comm']) {
    if ($nv_Request->isset_request('cpgnum', 'post'))
        $cpgnum = $nv_Request->get_int('cpgnum', 'post', 0);

    $sql = "SELECT a.*, b.username, b.email, b.first_name, b.last_name, b.gender, b.photo, b.view_mail, b.md5username 
    FROM " . NV_PREFIXLANG . "_" . $module_data . "_comm a, " . NV_USERS_GLOBALTABLE . " b 
    WHERE a.cid=" . $VideoData['id'] . " AND a.status=1 AND a.userid=b.userid 
    ORDER BY a.id DESC LIMIT " . $cpgnum . ", " . ($configMods['commNum'] + 1);
    $result = $db->query($sql);
    $i = 0;
    while ($row = $result->fetch()) {
        $row['full_name'] = trim($row['first_name'] . ' ' . $row['last_name']);

        if ($i <= $configMods['commNum'] - 1) {
            $comments[$i] = $row;
            $comments[$i]['full_name'] = !empty($row['full_name']) ? $row['full_name'] : $row['username'];
            $comments[$i]['userView'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=memberlist/" . change_alias($row['username']) . "-" . $row['md5username'];
            $comments[$i]['email'] = $row['view_mail'] ? $row['email'] : "";
            $comments[$i]['photo'] = NV_BASE_SITEURL . (!empty($row['photo']) ? $row['photo'] : "themes/default/images/users/no_avatar.jpg");
            $comments[$i]['posttime'] = nv_ucfirst(nv_strtolower(nv_date("l, j F Y, H:i", $row['posttime'])));
        } else {
            $commNext = true;
            break;
        }
        ++$cpgnum;
        ++$i;
    }
}

$xtpl = new XTemplate("detail.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('MODULECONFIG', $configMods);
$xtpl->assign('MODULEURL', nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $VideoData['alias'], 1));

$xtpl->assign('DETAILCONTENT', $VideoData);
if (defined('NV_IS_MODADMIN'))
    $xtpl->parse('main.isAdmin');
if (!empty($VideoData['bodytext']))
    $xtpl->parse('main.bodytext');

if ($isDetail) {
    $xtpl->parse('main.scrollPlayer');
}

$xtpl->parse('main');
$content = $xtpl->text("main");

if ($nv_Request->isset_request('aj', 'post') and ($aj = $nv_Request->get_title('aj', 'post', '', 0) == 1)) {
    echo $content;
    exit;
}

$content = "<div id=\"videoDetail\">" . $content . "</div>\n";

$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "modules/" . $module_file . "/js/jquery.autoresize.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "modules/" . $module_file . "/js/jwplayer.js\"></script>\n";
