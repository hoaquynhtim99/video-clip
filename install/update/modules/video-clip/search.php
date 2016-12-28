<?php

/**
 * @Project VIDEO CLIPS AJAX 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 01, 2014, 04:33:14 AM
 */

if (!defined('NV_IS_MOD_SEARCH'))
    die('Stop!!!');

$sql = "SELECT SQL_CALC_FOUND_ROWS title,alias,hometext 
FROM " . NV_PREFIXLANG . "_" . $m_values['module_data'] . "_clip 
WHERE status=1 AND (" . nv_like_logic('title', $dbkeyword, $logic) . " 
OR " . nv_like_logic('hometext', $dbkeyword, $logic) . " 
OR " . nv_like_logic('bodytext', $dbkeyword, $logic) . ") 
LIMIT " . (($page - 1) * $limit) . ", " . $limit;

$tmp_re = $db->query($sql);

$result = $db->query("SELECT FOUND_ROWS()");
$num_items = $result->fetchColumn();

if ($num_items) {
    $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=';

    while (list($tilterow, $alias, $des) = $tmp_re->fetch(3)) {
        $url = $link . $alias . $global_config['rewrite_exturl'];

        $result_array[] = array(
            'link' => $url,
            'title' => BoldKeywordInStr($tilterow, $key, $logic),
            'content' => BoldKeywordInStr($des, $key, $logic));
    }
}
