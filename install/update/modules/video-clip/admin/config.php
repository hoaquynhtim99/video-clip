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

$page_title = $lang_module['config'];

$skins = array();

$array_config = array();
if ($nv_Request->isset_request('submit', 'post')) {
    $array_config['otherClipsNum'] = $nv_Request->get_int('otherClipsNum', 'post', 0);
    $array_config['playerAutostart'] = $nv_Request->get_int('playerAutostart', 'post', 0);
    $array_config['playerSkin'] = $nv_Request->get_title('playerSkin', 'post', '', 1);
    $array_config['aspectratio'] = $nv_Request->get_title('aspectratio', 'post', '', 0);
    $array_config['folderStructureEnable'] = $nv_Request->get_int('folderStructureEnable', 'post', 0) ? 1 : 0;
    $array_config['titleLength'] = $nv_Request->get_int('titleLength', 'post', 0);

    if (!in_array($array_config['playerSkin'] . ".zip", $skins))
        $array_config['playerSkin'] = "";

    $content_config = "<?php\n\n";
    $content_config .= NV_FILEHEAD . "\n\n";
    $content_config .= "if(!defined('NV_MAINFILE'))\n    die('Stop!!!');\n\n";
    $content_config .= "\$configMods['otherClipsNum'] = " . $array_config['otherClipsNum'] . ";\n";
    $content_config .= "\$configMods['playerAutostart'] = " . $array_config['playerAutostart'] . ";\n";
    $content_config .= "\$configMods['playerSkin'] = \"" . nv_htmlspecialchars($array_config['playerSkin']) . "\";\n";
    $content_config .= "\$configMods['aspectratio'] = \"" . $array_config['aspectratio'] . "\";\n";
    $content_config .= "\$configMods['folderStructureEnable'] = " . $array_config['folderStructureEnable'] . ";\n";
    $content_config .= "\$configMods['titleLength'] = " . $array_config['titleLength'] . ";\n";

    file_put_contents(NV_ROOTDIR . "/" . NV_DATADIR . "/config_module-" . $module_data . ".php", $content_config, LOCK_EX);

    $ajaxRespon->reset()->setSuccess()->setMessage($lang_module['successfullySaved'])->respon();
}

$configMods = array();
$configMods['otherClipsNum'] = 15; //So video-clip hien thi tren trang chu hoac trang The loai
$configMods['playerAutostart'] = 0; //Co tu dong phat video hay khong
$configMods['playerSkin'] = ""; //Skin cua player
$configMods['aspectratio'] = "16:9"; //Skin cua player
$configMods['folderStructureEnable'] = 1; // Bat cau truc thu muc upload hay khong
$configMods['titleLength'] = 20; //

if (file_exists(NV_ROOTDIR . "/" . NV_DATADIR . "/config_module-" . $module_data . ".php")) {
    require (NV_ROOTDIR . "/" . NV_DATADIR . "/config_module-" . $module_data . ".php");
}

$configMods['playerAutostart'] = $configMods['playerAutostart'] ? " checked=\"checked\"" : "";
$configMods['folderStructureEnable'] = $configMods['folderStructureEnable'] ? " checked=\"checked\"" : "";

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op);
$xtpl->assign('CONFIGMODULE', $configMods);

for ($i = 0; $i <= 60; ++$i) {
    $sel = $i == $configMods['otherClipsNum'] ? " selected=\"selected\"" : "";
    $xtpl->assign('NUMS', array('value' => $i, 'select' => $sel));
    $xtpl->parse('main.otherClipsNum');
}

foreach ($skins as $skin) {
    $skin = substr($skin, 0, -4);
    $sel = $skin == $configMods['playerSkin'] ? " selected=\"selected\"" : "";
    $xtpl->assign('SKIN', array('value' => $skin, 'select' => $sel));
    $xtpl->parse('main.playerSkin');
}

$array_aspectratio = array(
    '4:3',
    '16:9',
    '24:10');

foreach ($array_aspectratio as $aspectratio) {
    $sel = $aspectratio == $configMods['aspectratio'] ? " selected=\"selected\"" : "";
    $xtpl->assign('ASPECTRATIO', array('value' => $aspectratio, 'select' => $sel));
    $xtpl->parse('main.aspectratio');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
