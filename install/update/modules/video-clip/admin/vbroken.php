<?php

/**
 * @Project VIDEO CLIPS AJAX 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 01, 2014, 04:33:14 AM
 */

if (!defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

$topicList = nv_listTopics(0);

if (empty($topicList)) {
    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=topic&add");
    exit();
}

if ($nv_Request->isset_request('remove', 'post')) {
    $remove = $nv_Request->get_int('remove', 'post', 0);
    $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_hit SET broken=0 WHERE cid=" . $remove;
    $db->query($query);
    die('OK');
}

$page_title = $lang_module['vbroken'];
$contents = "";

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('MODULE_URL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE);
$xtpl->assign('NV_ADMIN_THEME', $global_config['module_theme']);
$xtpl->assign('MODULE_FILE', $module_file);

$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op;
$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 20;

$sql = "SELECT SQL_CALC_FOUND_ROWS a.* FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip a, 
" . NV_PREFIXLANG . "_" . $module_data . "_hit b 
WHERE a.id=b.cid AND b.broken=1 ORDER BY a.addtime DESC LIMIT " . (($page - 1) * $per_page) . "," . $per_page;
$result = $db->query($sql);
$res = $db->query("SELECT FOUND_ROWS()");
$all_page = $res->fetchColumn();

if ($all_page) {
    $a = 0;
    while ($row = $result->fetch()) {
        $row['adddate'] = date("d/m/Y H:i", $row['addtime']);
        $row['topicUrl'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=topic&edit=1&tid=" . $row['tid'];
        $row['topicname'] = isset($topicList[$row['tid']]) ? $topicList[$row['tid']]['title'] : "";
        $row['icon'] = $row['status'] ? "enabled" : "disabled";
        $row['status'] = $row['status'] ? $lang_module['status1'] : $lang_module['status0'];
        $xtpl->assign('DATA', $row);
        $xtpl->parse('main.loop');
        $a++;
    }

    $generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    } elseif ($page > 1) {
        Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
        exit();
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
