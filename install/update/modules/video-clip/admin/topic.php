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

/**
 * nv_FixWeightTopic()
 * 
 * @param integer $parentid
 * @return
 */
function nv_FixWeightTopic($parentid = 0)
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE parentid=" . $parentid . " ORDER BY weight ASC";
    $result = $db->query($sql);

    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        $db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_topic SET weight=" . $weight . " WHERE id=" . $row['id']);
    }
}

/**
 * nv_del_topic()
 * 
 * @param mixed $tid
 * @return
 */
function nv_del_topic($tid)
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip WHERE tid=" . $tid;
    $result = $db->query($sql);

    $in = array();
    while ($row = $result->fetch()) {
        $in[] = $row['id'];
    }
    $in = implode(",", $in);

    if (!empty($in)) {
        $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_hit WHERE cid IN (" . $in . ")";
        $db->query($sql);

        $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip WHERE id IN (" . $in . ")";
        $db->query($sql);
    }

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE parentid=" . $tid;
    $result = $db->query($sql);

    while (list($id) = $result->fetch(3)) {
        nv_del_topic($id);
    }

    $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE id=" . $tid;
    $db->query($sql);
}

$array = array();

// Them, sua the loai
if ($nv_Request->isset_request('add', 'get') or $nv_Request->isset_request('edit', 'get')) {
    $tid = $nv_Request->get_int('tid', 'get', 0);

    if (empty($tid)) {
        $page_title = $lang_module['addtopic_titlebox'];
        $form_action = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;add=1";
    } else {
        $page_title = $lang_module['edittopic_titlebox'];
        $form_action = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;edit=1&amp;tid=" . $tid;

        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE id=" . $tid;
        $result = $db->query($sql);
        $numcat = $result->rowCount();

        if ($numcat != 1) {
            Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
            exit();
        }

        $row = $result->fetch();
    }

    if ($nv_Request->isset_request('submit', 'post')) {
        $array['parentid'] = $nv_Request->get_int('parentid', 'post', 0);
        $array['title'] = $nv_Request->get_title('title', 'post', '', 1);
        $array['description'] = $nv_Request->get_title('description', 'post', '');
        $array['keywords'] = $nv_Request->get_title('keywords', 'post', '', 1);

        $ajaxRespon->reset()->setError();

        if (empty($array['title'])) {
            $ajaxRespon->setInput('title')->setMessage($lang_module['error1'])->respon();
        }

        if (!empty($array['parentid'])) {
            $sql = "SELECT COUNT(*) AS count FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE id=" . $array['parentid'];
            $result = $db->query($sql);
            $count = $result->fetchColumn();

            if (!$count) {
                $ajaxRespon->setInput('parentid')->setMessage($lang_module['error2'])->respon();
            }
        }

        if ($tid) {
            $alias = nv_myAlias(strtolower(change_alias($array['title'])), 1, $tid);

            $sql = "SELECT COUNT(*) AS count FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE id!=" . $tid . " AND alias=" . $db->quote($alias) . " AND parentid=" . $array['parentid'];
            $result = $db->query($sql);
            $count = $result->fetchColumn();

            if ($count) {
                $ajaxRespon->setInput('title')->setMessage($lang_module['error3'])->respon();
            }

            if ($array['parentid'] != $row['parentid']) {
                $sql = "SELECT MAX(weight) AS new_weight FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE parentid=" . $array['parentid'];
                $result = $db->query($sql);
                $new_weight = $result->fetchColumn();
                $new_weight = (int)$new_weight;
                ++$new_weight;
            } else {
                $new_weight = $row['weight'];
            }
        } else {
            $alias = nv_myAlias(strtolower(change_alias($array['title'])));

            $sql = "SELECT MAX(weight) AS new_weight FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE parentid=" . $array['parentid'];
            $result = $db->query($sql);
            $new_weight = $result->fetchColumn();
            $new_weight = (int)$new_weight;
            ++$new_weight;
        }

        $array['img'] = "";
        $homeimg = $nv_Request->get_title('img', 'post');
        if (!empty($homeimg)) {
            $homeimg = preg_replace("/^" . nv_preg_quote(NV_BASE_SITEURL) . "(.+)$/", "$1", $homeimg);
            if (preg_match("/^([a-z0-9\/\.\-\_]+)\.(jpg|png|gif)$/i", $homeimg)) {
                $image = NV_ROOTDIR . "/" . $homeimg;
                $image = nv_is_image($image);
                if (!empty($image))
                    $array['img'] = $homeimg;
            }
        }

        if (empty($array['keywords'])) {
            $array['keywords'] = nv_get_keywords($array['description']);
        } else {
            $array['keywords'] = explode(",", $array['keywords']);
            $array['keywords'] = array_map("trim", $array['keywords']);
            $array['keywords'] = array_unique($array['keywords']);
            $array['keywords'] = implode(",", $array['keywords']);
        }

        if ($tid) {
            $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_topic SET 
                parentid=" . $array['parentid'] . ", 
                title=" . $db->quote($array['title']) . ", 
                alias=" . $db->quote($alias) . ", 
                description=" . $db->quote($array['description']) . ", 
                keywords=" . $db->quote($array['keywords']) . ", 
                img=" . $db->quote($array['img']) . ", 
                weight=" . $new_weight . " 
            WHERE id=" . $tid;

            $result = $db->query($sql);

            if (!$result) {
                $ajaxRespon->setMessage($lang_module['error4'])->respon();
            } else {
                if ($array['parentid'] != $row['parentid']) {
                    nv_FixWeightTopic($row['parentid']);
                }

                nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['edittopic_titlebox'], "ID " . $tid, $admin_info['userid']);
            }
        } else {
            $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_topic VALUES (
                NULL, 
                " . $array['parentid'] . ", 
                " . $db->quote($array['title']) . ", 
                " . $db->quote($alias) . ", 
                " . $db->quote($array['description']) . ", 
                " . $new_weight . ", 
                " . $db->quote($array['img']) . ", 
                1, 
                " . $db->quote($array['keywords']) . "
            )";

            $tid = $db->insert_id($sql);

            if (!$tid) {
                $ajaxRespon->setMessage($lang_module['error4'])->respon();
            }

            nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['addtopic_titlebox'], "ID " . $tid, $admin_info['userid']);
        }

        $nv_Cache->delMod($module_name);

        $redirect = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op;
        $ajaxRespon->setSuccess()->setMessage($lang_module['successfullySaved'])->setRedirect($redirect)->respon();
    } elseif (!$tid) {
        $array['parentid'] = 0;
        $array['title'] = "";
        $array['description'] = "";
        $array['keywords'] = "";
        $array['img'] = "";
    } else {
        $array['parentid'] = (int)$row['parentid'];
        $array['title'] = $row['title'];
        $array['description'] = $row['description'];
        $array['keywords'] = $row['keywords'];
        $array['img'] = $row['img'];
    }

    if (!empty($array['img']))
        $array['img'] = NV_BASE_SITEURL . $array['img'];

    $listTopics = array(array(
            'id' => 0,
            'name' => $lang_module['is_maintopic'],
            'selected' => ""));
    $listTopics = $listTopics + nv_listTopics($array['parentid'], $tid);

    $xtpl = new XTemplate("topic_add.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
    $xtpl->assign('FORM_ACTION', $form_action);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_name);
    $xtpl->assign('UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name);
    $xtpl->assign('DATA', $array);

    foreach ($listTopics as $cat) {
        $xtpl->assign('LISTCATS', $cat);
        $xtpl->parse('main.parentid');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Xoa chu de
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX'))
        die('Wrong URL');

    $tid = $nv_Request->get_int('tid', 'post', 0);

    if (empty($tid)) {
        die('NO');
    }

    $sql = "SELECT COUNT(*) AS count, parentid FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE id=" . $tid;
    $result = $db->query($sql);
    list($count, $parentid) = $result->fetch(3);

    if ($count != 1) {
        die('NO');
    }

    nv_del_topic($tid);
    nv_FixWeightTopic($parentid);

    $nv_Cache->delMod($module_name);

    die('OK');
}

// Chinh thu tu chu de
if ($nv_Request->isset_request('changeweight', 'post')) {
    if (!defined('NV_IS_AJAX'))
        die('Wrong URL');

    $tid = $nv_Request->get_int('tid', 'post', 0);
    $new = $nv_Request->get_int('new', 'post', 0);

    if (empty($tid))
        die('NO');

    $query = "SELECT parentid FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE id=" . $tid;
    $result = $db->query($query);
    $numrows = $result->rowCount();
    if ($numrows != 1)
        die('NO');
    $parentid = $result->fetchColumn();

    $query = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE id!=" . $tid . " AND parentid=" . $parentid . " ORDER BY weight ASC";
    $result = $db->query($query);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new)
            ++$weight;
        $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_topic SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query($sql);
    }
    $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_topic SET weight=" . $new . " WHERE id=" . $tid;
    $db->query($sql);

    $nv_Cache->delMod($module_name);

    die('OK');
}

// Kich hoat - dinh chi
if ($nv_Request->isset_request('changestatus', 'post')) {
    if (!defined('NV_IS_AJAX'))
        die('Wrong URL');

    $tid = $nv_Request->get_int('tid', 'post', 0);

    if (empty($tid))
        die('NO');

    $query = "SELECT status FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE id=" . $tid;
    $result = $db->query($query);
    $numrows = $result->rowCount();
    if ($numrows != 1)
        die('NO');

    $status = $result->fetchColumn();
    $status = $status ? 0 : 1;

    $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_topic SET status=" . $status . " WHERE id=" . $tid;
    $db->query($sql);

    $nv_Cache->delMod($module_name);

    die('OK');
}

// Danh sach chu de
$page_title = $lang_module['topic_management'];

$pid = $nv_Request->get_int('pid', 'get', 0);

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE parentid=" . $pid . " ORDER BY weight ASC";
$result = $db->query($sql);
$num = $result->rowCount();

if (!$num) {
    if ($pid) {
        Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
    } else {
        Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&add=1");
    }
    exit();
}

if ($pid) {
    $sql2 = "SELECT title,parentid FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE id=" . $pid;
    $result2 = $db->query($sql2);
    list($parentid, $parentid2) = $result2->fetch(3);
    $caption = sprintf($lang_module['listSubTopic'], $parentid);
    $parentid = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;pid=" . $parentid2 . "\">" . $parentid . "</a>";
} else {
    $caption = $lang_module['listMainTopic'];
    $parentid = $lang_module['is_maintopic'];
}

$list = array();
$a = 0;

while ($row = $result->fetch()) {
    $numsub = $db->query("SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE parentid=" . $row['id'])->rowCount();
    if ($numsub) {
        $numsub = " (<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;pid=" . $row['id'] . "\">" . $numsub . " " . $lang_module['is_subtopic'] . "</a>)";
    } else {
        $numsub = "";
    }

    $weight = array();
    for ($i = 1; $i <= $num; ++$i) {
        $weight[$i]['title'] = $i;
        $weight[$i]['pos'] = $i;
        $weight[$i]['selected'] = ($i == $row['weight']) ? " selected=\"selected\"" : "";
    }

    $class = ($a % 2) ? " class=\"second\"" : "";

    $list[$row['id']] = array( //
        'id' => (int)$row['id'], //
        'title' => $row['title'], //
        'titlelink' => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;tid=" . $row['id'], //
        'numsub' => $numsub, //
        'parentid' => $parentid, //
        'weight' => $weight, //
        'status' => $row['status'] ? " checked=\"checked\"" : "", //
        'class' => $class //
            );

    ++$a;
}

$xtpl = new XTemplate("topic_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('ADD_NEW_TOPIC', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;add=1");
$xtpl->assign('TABLE_CAPTION', $caption);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('LANG', $lang_module);

foreach ($list as $row) {
    $xtpl->assign('ROW', $row);

    foreach ($row['weight'] as $weight) {
        $xtpl->assign('WEIGHT', $weight);
        $xtpl->parse('main.row.weight');
    }

    $xtpl->assign('EDIT_URL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;edit=1&amp;tid=" . $row['id']);
    $xtpl->parse('main.row');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
