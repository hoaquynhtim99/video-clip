<?php

/**
 * @Project VIDEO CLIPS AJAX 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 01, 2014, 04:33:14 AM
 */

if (!defined('NV_SYSTEM'))
    die('Stop!!!');

define('NV_IS_MOD_VIDEOCLIPS', true);

/**
 * nv_settopics()
 * 
 * @param mixed $id
 * @param mixed $list
 * @param mixed $name
 * @return
 */
function nv_settopics($id, $list, $name)
{
    global $module_name;

    $name = $list[$id]['title'] . " &raquo; " . $name;
    $parentid = $list[$id]['parentid'];
    if ($parentid)
        $name = nv_settopics($parentid, $list, $name);
    return $name;
}

/**
 * nv_list_topics()
 * 
 * @return
 */
function nv_list_topics()
{
    global $db, $module_data, $module_name, $module_info;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE status=1 ORDER BY parentid,weight ASC";
    $result = $db->query($sql);

    $list = array();
    while ($row = $result->fetch()) {
        $list[$row['id']] = array(
            'id' => (int)$row['id'],
            'title' => $row['title'],
            'alias' => $row['alias'],
            'description' => $row['description'],
            'parentid' => (int)$row['parentid'],
            'img' => $row['img'],
            'subcats' => array(),
            'keywords' => $row['keywords']);
    }

    $list2 = array();

    if (!empty($list)) {
        foreach ($list as $row) {
            if (!$row['parentid'] or isset($list[$row['parentid']])) {
                $list2[$row['id']] = $list[$row['id']];
                $list2[$row['id']]['name'] = $list[$row['id']]['title'];

                if ($row['parentid']) {
                    $list2[$row['parentid']]['subcats'][] = $row['id'];
                    $list2[$row['id']]['name'] = nv_settopics($row['parentid'], $list, $list2[$row['id']]['name']);
                }
            }
        }
    }

    return $list2;
}

/**
 * nv_extKeywords()
 * 
 * @param mixed $keywords
 * @return
 */
function nv_extKeywords($keywords)
{
    if (empty($keywords))
        return "";
    $keywords = explode(",", $keywords);
    $keywords = array_map("trim", $keywords);
    $keywords = array_unique($keywords);
    $keywords = implode(",", $keywords);
    return $keywords;
}

// Cau hinh module
$configMods = array();
$configMods['otherClipsNum'] = 16; //So video-clip hien thi tren trang chu hoac trang The loai
$configMods['playerAutostart'] = 0; //Co tu dong phat video hay khong
$configMods['playerSkin'] = ""; //Skin cua player
$configMods['aspectratio'] = "16:9"; // Ti le phat video
$configMods['titleLength'] = 20; // So ky tu cua tieu de

if (file_exists(NV_ROOTDIR . "/" . NV_DATADIR . "/config_module-" . $module_data . ".php")) {
    require (NV_ROOTDIR . "/" . NV_DATADIR . "/config_module-" . $module_data . ".php");
}

if (!empty($configMods['playerSkin'])) {
    $configMods['playerSkin'] = ",skin:\"" . NV_BASE_SITEURL . "images/jwplayer/skin/" . $configMods['playerSkin'] . ".zip\"";
}

$configMods['aspectratioPadding'] = $configMods['aspectratio'] == "16:9" ? "56" : ($configMods['aspectratio'] == "4:3" ? "75" : "42");

// Tieu de, meta tag
$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
if (isset($module_info['description'])) {
    $description = $module_info['description'];
} else {
    $description = 'no';
}

// Cac bien he thong
$array_mod_title = array();
$topicList = nv_list_topics();
$topicList2 = array();
$topicID = 0;
$VideoData = array();
$isDetail = false;

foreach ($topicList as $key => $_topicList)
    $topicList2[$_topicList['alias']] = $key;

if (isset($array_op[0]) and ($array_op0 = strtolower($array_op[0])) != $array_op[0]) {
    $_tempUrl = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $array_op0;
    if (isset($array_op[1]))
        $_tempUrl .= "/" . strtolower($array_op[1]);
    $_tempUrl = nv_url_rewrite($_tempUrl, 1);
    header('Location: ' . $_tempUrl, true, 301);
    exit;
}

if (!empty($array_op[0])) {
    // Chi tiet video
    if (!isset($topicList2[$array_op[0]])) {
        $ClipSQL = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip a, " . NV_PREFIXLANG . "_" . $module_data . "_hit b WHERE a.alias=" . $db->quote($array_op[0]) . " AND a.status=1 AND a.id=b.cid LIMIT 1";

        $resultVideo = $db->query($ClipSQL);
        $num = $resultVideo->rowCount();
        if (!$num) {
            $headerStatus = substr(php_sapi_name(), 0, 3) == 'cgi' ? "Status:" : $_SERVER['SERVER_PROTOCOL'];
            header($headerStatus . " 404 Not Found");
            nv_info_die($lang_global['error_404_title'], $lang_global['site_info'], $lang_global['error_404_title']);
            die();
        }

        $VideoData = $resultVideo->fetch();
        unset($ClipSQL, $resultVideo, $num);

        $topicID = $VideoData['tid'];

        $array_mod_title[] = array(
            'catid' => 0,
            'title' => $topicList[$topicID]['title'],
            'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $topicList[$topicID]['alias']);
        $array_mod_title[] = array(
            'catid' => 0,
            'title' => $VideoData['title'],
            'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $VideoData['alias']);

        $page_title = $VideoData['title'] . " " . NV_TITLEBAR_DEFIS . " " . $page_title;

        if (!empty($VideoData['keywords'])) {
            $key_words = nv_extKeywords($VideoData['keywords'] . (!empty($key_words) ? "," . $key_words : ""));
        }

        $description = !empty($VideoData['hometext']) ? $VideoData['hometext'] : $VideoData['title'] . " " . NV_TITLEBAR_DEFIS . " " . $module_info['custom_title'];
        $isDetail = true;
    } else {
        $topicID = $topicList2[$array_op[0]];

        $array_mod_title[] = array(
            'catid' => 0,
            'title' => $topicList[$topicID]['title'],
            'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $array_op[0]);

        $topic = $topicList[$topicList2[$array_op[0]]];

        $page_title = $topic['title'] . " " . NV_TITLEBAR_DEFIS . " " . $page_title;
        if (!empty($topic['keywords']))
            $key_words = nv_extKeywords($topic['keywords'] . (!empty($key_words) ? "," . $key_words : ""));
        if (!empty($topic['description']))
            $description = $topic['description'];

        unset($topic);

        // Lay clip
        $ClipSQL = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip a, " . NV_PREFIXLANG . "_" . $module_data . "_hit b WHERE a.status=1 AND a.id=b.cid AND a.tid=" . $topicID . " ORDER BY a.id DESC LIMIT 1";
        $resultVideo = $db->query($ClipSQL);
        $VideoData = $resultVideo->fetch();
        unset($ClipSQL, $resultVideo);
    }
}

// Lay mot video moi nhat
if (empty($VideoData)) {
    $ClipSQL = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip a, " . NV_PREFIXLANG . "_" . $module_data . "_hit b WHERE a.status=1 AND a.id=b.cid ORDER BY a.id DESC LIMIT 1";
    $resultVideo = $db->query($ClipSQL);
    $VideoData = $resultVideo->fetch();
    unset($ClipSQL, $resultVideo);
}

// Tang viewHits
if (!empty($VideoData)) {
    $listRes = isset($_SESSION[$module_data . '_ViewList']) ? $_SESSION[$module_data . '_ViewList'] : "";
    $listRes = !empty($listRes) ? explode(",", $listRes) : array();

    if (empty($listRes) or !in_array($VideoData['id'], $listRes)) {
        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_hit SET view=view+1 WHERE cid=" . $VideoData['id'];
        $db->query($query);
        array_unshift($listRes, $VideoData['id']);
        $_SESSION[$module_data . '_ViewList'] = implode(",", $listRes);
        ++$VideoData['view'];
    }

    $VideoData['filepath'] = !empty($VideoData['internalpath']) ? NV_BASE_SITEURL . $VideoData['internalpath'] : $VideoData['externalpath'];
    $VideoData['url'] = nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $VideoData['alias'] . $global_config['rewrite_exturl'], 1);
    $VideoData['editUrl'] = nv_url_rewrite(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&op=main&edit&id=" . $VideoData['id'] . "&redirect=1", 1);
}

// Open Graph
if ($isDetail === true) {
    $base_url_rewrite = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $VideoData['alias'] . $global_config['rewrite_exturl'], true);
    if ($_SERVER['REQUEST_URI'] == $base_url_rewrite) {
        $canonicalUrl = NV_MAIN_DOMAIN . $base_url_rewrite;
    } elseif (NV_MAIN_DOMAIN . $_SERVER['REQUEST_URI'] != $base_url_rewrite) {
        Header('Location: ' . $base_url_rewrite);
        die();
    } else {
        $canonicalUrl = $base_url_rewrite;
    }

    $ogImage = NV_MY_DOMAIN . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/video.png";
    if (!empty($VideoData['img'])) {
        $ogImage = NV_MY_DOMAIN . NV_BASE_SITEURL . $VideoData['img'];
    }

    $my_head .= "<meta property=\"og:title\" content=\"" . $VideoData['title'] . "\" />" . NV_EOL;
    $my_head .= "<meta property=\"og:type\" content=\"video.movie\" />" . NV_EOL;
    $my_head .= "<meta property=\"og:url\" content=\"" . $client_info['selfurl'] . "\" />" . NV_EOL;
    $my_head .= "<meta property=\"og:image\" content=\"" . $ogImage . "\" />" . NV_EOL;
    $my_head .= "<meta property=\"og:description\" content=\"" . $VideoData['hometext'] . "\" />" . NV_EOL;

    unset($ogImage);

    // Kiem tra quyen truy cap
    if (!nv_user_in_groups($VideoData['groups_view'])) {
        if ($nv_Request->isset_request('aj', 'post'))
            die("access forbidden");

        include NV_ROOTDIR . '/includes/header.php';
        echo nv_site_theme($lang_module['accessForbidden']);
        include NV_ROOTDIR . '/includes/footer.php';
        die();
    }

    // Nut like, unlike, broken
    if ($nv_Request->isset_request('aj', 'post')) {
        $aj = $nv_Request->get_title('aj', 'post', '', 1);

        if (in_array($aj, array(
            'likehit',
            'unlikehit',
            'broken'))) {
            $sessionName = $aj == "broken" ? "broken" : "like";
            $listLike = isset($_SESSION[$module_data . '_' . $sessionName]) ? $_SESSION[$module_data . '_' . $sessionName] : "";
            $listLike = !empty($listLike) ? explode(",", $listLike) : array();

            if (empty($listLike) or !in_array($VideoData['id'], $listLike)) {
                $set = $aj == "broken" ? "" . $aj . "=1" : "" . $aj . "=" . ($VideoData[$aj] + 1);
                $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_hit SET " . $set . " WHERE cid=" . $VideoData['id'];
                $db->query($query);
                array_unshift($listLike, $VideoData['id']);
                $_SESSION[$module_data . '_' . $sessionName] = implode(",", $listLike);
                ++$VideoData[$aj];
            }

            die($aj . "_" . $VideoData[$aj]);
        }
    }
}
