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

// Goi cau hinh module
if (file_exists(NV_ROOTDIR . "/" . NV_DATADIR . "/config_module-" . $module_data . ".php")) {
    require (NV_ROOTDIR . "/" . NV_DATADIR . "/config_module-" . $module_data . ".php");
}

// Tao cau truc thu muc upload
$imageFolder = NV_UPLOADS_DIR . '/' . $module_name . '/images';
$imageFolderCurrent = NV_UPLOADS_DIR . '/' . $module_name . '/images';

if (isset($configMods['folderStructureEnable']) and !empty($configMods['folderStructureEnable'])) {
    $imageFolderName = date("Y_m");

    if (!is_dir(NV_ROOTDIR . '/' . $imageFolderCurrent . '/' . $imageFolderName)) {
        $check = nv_mkdir(NV_ROOTDIR . '/' . $imageFolder, $imageFolderName);

        if ($check[0] == 1) {
            $imageFolderCurrent .= '/' . $imageFolderName;
            $db->query("INSERT INTO " . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . $imageFolderCurrent . "', 0)");
        }
    } else {
        $imageFolderCurrent .= '/' . $imageFolderName;
    }
}

$topicList = nv_listTopics(0);

if (empty($topicList)) {
    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=topic&add");
    exit();
}

$page_title = $lang_module['main'];
$contents = "";

$sql = "SELECT COUNT(*) as count FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip";
$result = $db->query($sql);
$count = $result->fetch();

if (empty($count['count']) and !$nv_Request->isset_request('add', 'get')) {
    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&add");
    die();
}

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('MODULE_URL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE);

$xtpl->assign('UPLOAD_IMG_PATH', $imageFolder);
$xtpl->assign('UPLOAD_IMG_CURRENT', $imageFolderCurrent);
$xtpl->assign('UPLOAD_FILE_PATH', NV_UPLOADS_DIR . '/' . $module_name . '/video');

$xtpl->assign('NV_ADMIN_THEME', $global_config['module_theme']);
$xtpl->assign('module', $module_file);

$groups_list = nv_groups_list();

if ($nv_Request->isset_request('add', 'get') or $nv_Request->isset_request('edit, id', 'get')) {
    if (defined('NV_EDITOR')) {
        require_once (NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php');
    }

    $post = array();
    $is_error = false;
    $info = "";

    if ($nv_Request->isset_request('edit, id', 'get')) {
        $post['id'] = $nv_Request->get_int('id', 'get', 0);

        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip WHERE id=" . $post['id'];
        $result = $db->query($sql);
        $num = $result->rowCount();
        if ($num != 1) {
            Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
            die();
        }

        $row = $result->fetch();
    }

    if ($nv_Request->isset_request('submit', 'post')) {
        // Reset lỗi
        $ajaxRespon->reset();

        $post['tid'] = $nv_Request->get_int('tid', 'post', 0);
        $post['title'] = $nv_Request->get_title('title', 'post', '', 1);
        $post['hometext'] = $nv_Request->get_title('hometext', 'post', '', 1);
        $post['bodytext'] = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);
        $post['keywords'] = $nv_Request->get_title('keywords', 'post', '', 1);
        $post['internalpath'] = $nv_Request->get_title('internalpath', 'post');
        $post['externalpath'] = $nv_Request->get_title('externalpath', 'post');
        $post['groups_view'] = $nv_Request->get_array('groups_view', 'post', array());
        $post['comm'] = $nv_Request->get_int('comm', 'post', 0);
        $post['redirect'] = $nv_Request->get_int('redirect', 'post', 0);

        $post['groups_view'] = !empty($post['groups_view']) ? implode(',', nv_groups_post(array_intersect($post['groups_view'], array_keys($groups_list)))) : '';

        if (!empty($post['internalpath'])) {
            $post['internalpath'] = preg_replace("/^" . nv_preg_quote(NV_BASE_SITEURL) . "(.+)$/", "$1", $post['internalpath']);
            if (!preg_match("/^([a-z0-9\/\.\-\_]+)\.([a-z0-9]+)$/i", $post['internalpath']) or !file_exists(NV_ROOTDIR . "/" . $post['internalpath']))
                $post['internalpath'] = "";
        }

        if (!empty($post['externalpath']) and !nv_is_url($post['externalpath']))
            $post['externalpath'] = "";

        if (!isset($topicList[$post['tid']]))
            $post['tid'] = 0;
        $post['hometext'] = nv_nl2br($post['hometext']);

        $where = isset($post['id']) ? " id!=" . $post['id'] . " AND" : "";

        if (empty($post['title'])) {
            $ajaxRespon->setInput('title')->setMessage($lang_module['error1'])->respon();
        }

        if (empty($post['hometext'])) {
            $ajaxRespon->setInput('hometext')->setMessage($lang_module['error7'])->respon();
        }

        if (empty($post['internalpath']) and empty($post['externalpath'])) {
            $ajaxRespon->setInput('internalpath')->setMessage($lang_module['error5'])->respon();
        }

        $post['img'] = "";
        $homeimg = $nv_Request->get_title('img', 'post');
        if (!empty($homeimg)) {
            $homeimg = preg_replace("/^" . nv_preg_quote(NV_BASE_SITEURL) . "(.+)$/", "$1", $homeimg);
            if (preg_match("/^([a-z0-9\/\.\-\_]+)\.(jpg|png|gif)$/i", $homeimg)) {
                $image = NV_ROOTDIR . "/" . $homeimg;
                $image = nv_is_image($image);
                if (!empty($image))
                    $post['img'] = $homeimg;
            }

            if (empty($post['img'])) {
                $ajaxRespon->setInput('img')->setMessage($lang_module['error6'])->respon();
            }
        }

        $test_content = strip_tags($post['bodytext']);
        $test_content = trim($test_content);
        $post['bodytext'] = !empty($test_content) ? nv_editor_nl2br($post['bodytext']) : "";

        if (empty($post['keywords'])) {
            $post['keywords'] = nv_get_keywords($post['hometext'] . " " . $post['bodytext']);
        } else {
            $post['keywords'] = explode(",", $post['keywords']);
            $post['keywords'] = array_map("trim", $post['keywords']);
            $post['keywords'] = array_unique($post['keywords']);
            $post['keywords'] = implode(",", $post['keywords']);
        }

        if (isset($post['id'])) {
            $alias = nv_myAlias(strtolower(change_alias($post['title'])), 2, $post['id']);

            $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_clip SET 
                tid=" . $post['tid'] . ", 
                alias=" . $db->quote($alias) . ", 
                title=" . $db->quote($post['title']) . ", 
                img=" . $db->quote($post['img']) . ", 
                hometext=" . $db->quote($post['hometext']) . ", 
                bodytext=" . $db->quote($post['bodytext']) . ", 
                keywords=" . $db->quote($post['keywords']) . ", 
                internalpath=" . $db->quote($post['internalpath']) . ",
                externalpath=" . $db->quote($post['externalpath']) . ", 
                groups_view=" . $db->quote($post['groups_view']) . ",
                 comm=" . $post['comm'] . " 
            WHERE id=" . $post['id'];

            $db->query($query);

            nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['editClip'], "Id: " . $post['id'], $admin_info['userid']);
        } else {
            $alias = nv_myAlias(strtolower(change_alias($post['title'])));

            $query = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_clip VALUES 
            (NULL, " . $post['tid'] . ", " . $db->quote($post['title']) . ", " . $db->quote($alias) . ", 
            " . $db->quote($post['hometext']) . ", " . $db->quote($post['bodytext']) . ", 
            " . $db->quote($post['keywords']) . ", " . $db->quote($post['img']) . ", 
            " . $db->quote($post['internalpath']) . ", " . $db->quote($post['externalpath']) . ", 
            " . $db->quote($post['groups_view']) . ", " . $post['comm'] . ", 
            1, " . NV_CURRENTTIME . ");";
            $_id = $db->insert_id($query);

            $query = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_hit VALUES (" . $_id . ", 0, 0, 0, 0, 0);";
            $db->query($query);

            nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['addClip'], "Id: " . $_id, $admin_info['userid']);
        }

        $nv_Cache->delMod($module_name);

        if ($post['redirect']) {
            $redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $alias;
        } else {
            $redirect = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
        }

        $ajaxRespon->setSuccess()->setMessage($lang_module['successfullySaved'])->setRedirect($redirect)->respon();
    } elseif (isset($post['id'])) {
        $post = $row;
        $post['hometext'] = nv_br2nl($post['hometext']);
        $post['bodytext'] = nv_editor_br2nl($post['bodytext']);
        $post['keywords'] = preg_replace("/\,[\s]*/", ", ", $post['keywords']);
        $post['groups_view'] = $row['groups_view'];
        $post['redirect'] = $nv_Request->get_int('redirect', 'get', 0);
    } else {
        $post['title'] = $post['hometext'] = $post['bodytext'] = $post['img'] = $post['keywords'] = $post['internalpath'] = $post['externalpath'] = "";
        $post['tid'] = $post['redirect'] = 0;
        $post['comm'] = 1;
        $post['groups_view'] = '6';
    }

    $post['groups_view'] = !empty($post['groups_view']) ? explode(',', $post['groups_view']) : array(6);

    if (!empty($post['bodytext']))
        $post['bodytext'] = nv_htmlspecialchars($post['bodytext']);
    if (!empty($post['img']))
        $post['img'] = NV_BASE_SITEURL . $post['img'];
    if (!empty($post['internalpath']))
        $post['internalpath'] = NV_BASE_SITEURL . $post['internalpath'];
    $post['comm'] = $post['comm'] ? "  checked=\"checked\"" : "";

    $xtpl->assign('ERROR_INFO', $info);

    if (isset($post['id'])) {
        $post['action'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&edit&id=" . $post['id'];
        $informationtitle = $lang_module['editClip'];
    } else {
        $post['action'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&add";
        $informationtitle = $lang_module['addClip'];
    }

    $xtpl->assign('INFO_TITLE', $informationtitle);
    $xtpl->assign('POST', $post);

    foreach ($topicList as $_tid => $_value) {
        $option = array(
            'value' => $_tid,
            'name' => $_value['name'],
            'selected' => $_tid == $post['tid'] ? " selected=\"selected\"" : "");
        $xtpl->assign('OPTION3', $option);
        $xtpl->parse('add.option3');
    }

    $groups_view = $post['groups_view'];
    $post['groups_view'] = array();
    foreach ($groups_list as $key => $title) {
        $post['groups_view'][] = array(
            'key' => $key,
            'title' => $title,
            'checked' => in_array($key, $groups_view) ? ' checked="checked"' : '');
    }

    foreach ($post['groups_view'] as $group) {
        $xtpl->assign('GROUPS_VIEW', $group);
        $xtpl->parse('add.groups_view');
    }

    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
        $_cont = nv_aleditor('bodytext', '100%', '300px', $post['bodytext']);
    } else {
        $_cont = "<textarea style=\"width:100%;height:300px\" name=\"bodytext\" id=\"bodytext\">" . $post['bodytext'] . "</textarea>";
    }
    $xtpl->assign('CONTENT', $_cont);

    $xtpl->parse('add');
    $contents = $xtpl->text('add');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

if ($nv_Request->isset_request('changeStatus', 'post')) {
    $id = $nv_Request->get_int('changeStatus', 'post', 0);
    $sql = "SELECT status FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip WHERE id=" . $id;
    $result = $db->query($sql);
    $status = $result->fetchColumn();

    $newStatus = $status ? 0 : 1;
    $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_clip SET status=" . $newStatus . " WHERE id=" . $id;
    $db->query($query);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['cstatus'], "Id: " . $id, $admin_info['userid']);

    $alt = $newStatus ? $lang_module['status1'] : $lang_module['status0'];
    $icon = $newStatus ? "enabled" : "disabled";

    die("<img style=\"vertical-align:middle;margin-right:10px\" alt=\"" . $alt . "\" title=\"" . $alt . "\" src=\"" . NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/images/" . $module_file . "/" . $icon . ".png\" width=\"12\" height=\"12\" />");
}
if ($nv_Request->isset_request('del', 'post')) {
    $id = $nv_Request->get_int('del', 'post', 0);
    $query = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_hit WHERE cid = " . $id;
    $db->query($query);
    $query = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip WHERE id = " . $id;
    $db->query($query);
    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['delClip'], "Id: " . $id, $admin_info['userid']);
    die('OK');
}

foreach ($topicList as $id => $name) {
    $option = array('id' => $id, 'name' => $name['name']);
    $xtpl->assign('OPTION4', $option);
    $xtpl->parse('main.psopt4');
}

$where = "";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;

if ($nv_Request->isset_request('tid', 'get')) {
    $top = $nv_Request->get_int('tid', 'get', 0);
    if (isset($topicList[$top])) {
        $where .= " WHERE tid=" . $top;
        $base_url .= "&tid=" . $top;
        $page_title = sprintf($lang_module['listClipByTid'], $topicList[$top]['title']);
    }
}

$sql = "SELECT COUNT(*) as ccount FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip" . $where;
$result = $db->query($sql);
$all_page = $result->fetch();
$all_page = $all_page['ccount'];

$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 50;

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip" . $where . " ORDER BY addtime DESC LIMIT " . (($page - 1) * $per_page) . "," . $per_page;
$result = $db->query($sql);

$a = 0;
while ($row = $result->fetch()) {
    $xtpl->assign('CLASS', $a % 2 ? " class=\"second\"" : "");

    $row['adddate'] = date("d-m-Y H:i", $row['addtime']);
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
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    exit();
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
