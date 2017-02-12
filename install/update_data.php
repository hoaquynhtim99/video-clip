<?php

/**
 * @Project VIDEO CLIPS AJAX 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 01, 2014, 04:33:14 AM
 */

if (!defined('NV_IS_UPDATE'))
    die('Stop!!!');

$nv_update_config = array();

// Kieu nang cap 1: Update; 2: Upgrade
$nv_update_config['type'] = 1;

// ID goi cap nhat
$nv_update_config['packageID'] = 'NVUDVIDEOCLIP4101';

// Cap nhat cho module nao, de trong neu la cap nhat NukeViet, ten thu muc module neu la cap nhat module
$nv_update_config['formodule'] = 'video-clip';

// Thong tin phien ban, tac gia, ho tro
$nv_update_config['release_date'] = 1486936962;
$nv_update_config['author'] = 'PHAN TAN DUNG (phantandung92@gmail.com)';
$nv_update_config['support_website'] = 'https://github.com/hoaquynhtim99/video-clip/tree/4.1.01';
$nv_update_config['to_version'] = '4.1.01';
$nv_update_config['allow_old_version'] = array('4.0.29');

// 0:Nang cap bang tay, 1:Nang cap tu dong, 2:Nang cap nua tu dong
$nv_update_config['update_auto_type'] = 1;

$nv_update_config['lang'] = array();
$nv_update_config['lang']['vi'] = array();

// Tiếng Việt
$nv_update_config['lang']['vi']['nv_up_p1'] = 'Cập nhật chức năng hiển thị cover';
$nv_update_config['lang']['vi']['nv_up_p2'] = 'Xóa thư mục thừa';
$nv_update_config['lang']['vi']['nv_up_finish'] = 'Đánh dấu phiên bản mới';

$nv_update_config['tasklist'] = array();
$nv_update_config['tasklist'][] = array(
    'r' => '4.1.01',
    'rq' => 1,
    'l' => 'nv_up_p1',
    'f' => 'nv_up_p1'
);
$nv_update_config['tasklist'][] = array(
    'r' => '4.1.01',
    'rq' => 1,
    'l' => 'nv_up_p2',
    'f' => 'nv_up_p2'
);
$nv_update_config['tasklist'][] = array(
    'r' => '4.1.01',
    'rq' => 1,
    'l' => 'nv_up_finish',
    'f' => 'nv_up_finish'
);

// Danh sach cac function
/*
Chuan hoa tra ve:
array(
'status' =>
'complete' => 
'next' =>
'link' =>
'lang' =>
'message' =>
);
status: Trang thai tien trinh dang chay
- 0: That bai
- 1: Thanh cong
complete: Trang thai hoan thanh tat ca tien trinh
- 0: Chua hoan thanh tien trinh nay
- 1: Da hoan thanh tien trinh nay
next:
- 0: Tiep tuc ham nay voi "link"
- 1: Chuyen sang ham tiep theo
link:
- NO
- Url to next loading
lang:
- ALL: Tat ca ngon ngu
- NO: Khong co ngon ngu loi
- LangKey: Ngon ngu bi loi vi,en,fr ...
message:
- Any message
Duoc ho tro boi bien $nv_update_baseurl de load lai nhieu lan mot function
Kieu cap nhat module duoc ho tro boi bien $old_module_version
*/

$array_modlang_update = array();

// Lay danh sach ngon ngu
$result = $db->query("SELECT lang FROM " . $db_config['prefix'] . "_setup_language WHERE setup=1");
while (list($_tmp) = $result->fetch(PDO::FETCH_NUM)) {
    $array_modlang_update[$_tmp] = array("lang" => $_tmp, "mod" => array());

    // Get all module
    $result1 = $db->query("SELECT title, module_data FROM " . $db_config['prefix'] . "_" . $_tmp . "_modules WHERE module_file=" . $db->quote($nv_update_config['formodule']));
    while (list($_modt, $_modd) = $result1->fetch(PDO::FETCH_NUM)) {
        $array_modlang_update[$_tmp]['mod'][] = array("module_title" => $_modt, "module_data" => $_modd);
    }
}

/**
 * nv_up_p1()
 *
 * @return
 *
 */
function nv_up_p1()
{
    global $nv_update_baseurl, $db, $db_config, $nv_Cache, $array_modlang_update;

    $return = array(
        'status' => 1,
        'complete' => 1,
        'next' => 1,
        'link' => 'NO',
        'lang' => 'NO',
        'message' => ''
    );

    foreach ($array_modlang_update as $lang => $array_mod) {
        foreach ($array_mod['mod'] as $module_info) {
            $table_prefix = $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'];
            try {
                $db->query("ALTER TABLE " . $table_prefix . "_clip ADD showcover tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Hiển thị/Ẩn ảnh mình họa làm ảnh bìa video' AFTER comm;");
            } catch (PDOException $e) {
                trigger_error($e->getMessage());
            }
        }
    }

    return $return;
}

/**
 * nv_up_p2()
 * 
 * @return
 */
function nv_up_p2()
{
    global $nv_update_baseurl, $db, $db_config, $nv_Cache, $array_modlang_update;

    $return = array(
        'status' => 1,
        'complete' => 1,
        'next' => 1,
        'link' => 'NO',
        'lang' => 'NO',
        'message' => ''
    );

    @nv_deletefile(NV_ROOTDIR . '/modules/video-clip/js', true);

    return $return;
}

/**
 * nv_up_finish()
 *
 * @return
 *
 */
function nv_up_finish()
{
    global $nv_update_baseurl, $db, $db_config, $nv_Cache, $nv_update_config;

    $return = array(
        'status' => 1,
        'complete' => 1,
        'next' => 1,
        'link' => 'NO',
        'lang' => 'NO',
        'message' => ''
    );

    try {
        $num = $db->query("SELECT COUNT(*) FROM " . $db_config['prefix'] . "_setup_extensions WHERE basename='" . $nv_update_config['formodule'] . "' AND type='module'")->fetchColumn();
        $version = $nv_update_config['to_version'] . " " . $nv_update_config['release_date'];
        
        if (!$num) {
            $db->query("INSERT INTO " . $db_config['prefix'] . "_setup_extensions (
                id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note
            ) VALUES (
                79, 'module', 'video-clip', 0, 1, 'video-clip', 'video_clip', '" . $nv_update_config['to_version'] . " " . $nv_update_config['release_date'] . "', " . NV_CURRENTTIME . ", 'PHAN TAN DUNG (phantandung92@gmail.com)', 
                'Module playback of video-clips'
            )");
        } else {
            $db->query("UPDATE " . $db_config['prefix'] . "_setup_extensions SET 
                id=79, 
                version='" . $version . "', 
                author='PHAN TAN DUNG (phantandung92@gmail.com)' 
            WHERE basename='" . $nv_update_config['formodule'] . "' AND type='module'");
        }
    } catch (PDOException $e) {
        trigger_error($e->getMessage());
    }

    $nv_Cache->delAll();
    return $return;
}
