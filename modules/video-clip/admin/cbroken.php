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

if ($nv_Request->isset_request('ischecked', 'post')) {
    $ischecked = $nv_Request->get_int('ischecked', 'post', 0);
    $content = $nv_Request->get_title('content', 'post', '', 1);
    if (empty($content))
        die("ERROR");
    $content = nv_nl2br($content);

    $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_comm SET 
    content=" . $db->quote($content) . ", 
    ischecked=1, 
    broken=0 
    WHERE id=" . $ischecked;
    $db->query($query);
    die('OK');
}

if ($nv_Request->isset_request('delcomm', 'post')) {
    $delcomm = $nv_Request->get_int('delcomm', 'post', 0);
    $sql = "SELECT cid FROM " . NV_PREFIXLANG . "_" . $module_data . "_comm WHERE id=" . $delcomm;
    $result = $db->query($sql);
    $cid = $result->fetchColumn();

    $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_comm WHERE id=" . $delcomm;
    $db->query($sql);

    $sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_comm WHERE cid=" . $cid;
    $result = $db->query($sql);
    $count = $result->fetchColumn();

    $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_hit SET comment=" . $count . " WHERE cid=" . $cid;
    $db->query($query);
    die('OK');
}

$page_title = $lang_module['cbroken'];

$xtpl = new XTemplate("cbroken.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODURL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);

$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op;
$page = $nv_Request->get_int('page', 'get', 0);
$per_page = 10;

$sql = "SELECT SQL_CALC_FOUND_ROWS a.*, b.username, b.full_name, c.title 
    FROM " . NV_PREFIXLANG . "_" . $module_data . "_comm a, 
    " . NV_USERS_GLOBALTABLE . " b, 
    " . NV_PREFIXLANG . "_" . $module_data . "_clip c 
    WHERE a.broken!=0 AND a.ischecked=0 AND a.userid=b.userid AND a.cid=c.id 
    ORDER BY a.broken DESC LIMIT " . $page . ", " . $per_page;
$result = $db->query($sql);

$res = $db->query("SELECT FOUND_ROWS()");
$all_page = $res->fetchColumn();

if ($all_page) {
    while ($row = $result->fetch()) {
        if (empty($row['full_name']))
            $row['full_name'] = $row['username'];
        $row['content'] = nv_br2nl($row['content']);
        $row['userUrl'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=edit&userid=" . $row['userid'];
        $row['clipUrl'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main&edit&id=" . $row['cid'];
        $row['pubDate'] = nv_ucfirst(nv_strtolower(nv_date("d/m/Y, H:i", $row['posttime'])));
        $xtpl->assign('DATA', $row);
        $xtpl->parse('main.loop');
    }
}

$generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);
if (!empty($generate_page)) {
    $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
} elseif ($page) {
    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
    exit();
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
