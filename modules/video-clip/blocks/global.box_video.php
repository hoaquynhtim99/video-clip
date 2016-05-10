<?php

/**
 * @Project VIDEO CLIPS AJAX 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 01, 2014, 04:33:14 AM
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

if (!nv_function_exists('nv_box_video')) {
    /**
     * nv_block_config_box_video()
     * 
     * @param mixed $module
     * @param mixed $data_block
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_box_video($module, $data_block, $lang_block)
    {
        $html = "";

        $html .= "<tr>";
        $html .= "<td>" . $lang_block['num_videos'] . "</td>";
        $html .= "<td><input type=\"text\" name=\"config_num_videos\" style=\"width:100px\" value=\"" . $data_block['num_videos'] . "\"/></td>";
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td>" . $lang_block['num_topics'] . "</td>";
        $html .= "<td><input type=\"text\" name=\"config_num_topics\" style=\"width:100px\" value=\"" . $data_block['num_topics'] . "\"/></td>";
        $html .= "</tr>";

        return $html;
    }

    /**
     * nv_block_config_box_video_submit()
     * 
     * @param mixed $module
     * @param mixed $lang_block
     * @return
     */
    function nv_block_config_box_video_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['num_videos'] = $nv_Request->get_int('config_num_videos', 'post', 6);
        $return['config']['num_topics'] = $nv_Request->get_int('config_num_topics', 'post', 5);
        return $return;
    }

    /**
     * nv_box_video()
     * 
     * @param mixed $block_config
     * @return
     */
    function nv_box_video($block_config)
    {
        global $module_info, $module_name, $db, $my_head, $site_mods, $global_config, $nv_Cache;

        $module = $block_config['module'];
        $data = $site_mods[$module]['module_data'];
        $file = $site_mods[$module]['module_file'];

        if (file_exists(NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $file . "/block.box_video.tpl")) {
            $block_theme = $module_info['template'];
        } else {
            $block_theme = "default";
        }

        if ($module != $module_name) {
            $my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "themes/" . $block_theme . "/css/" . $file . ".css\" />\n";
        }

        $xtpl = new XTemplate("block.box_video.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $file);

        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $data . "_clip WHERE status=1 ORDER BY id DESC LIMIT 0," . $block_config['num_videos'];
        $list = $nv_Cache->db($sql, '', $module);

        $i = 1;
        foreach ($list as $row) {
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
                $row['img'] = NV_BASE_SITEURL . "themes/" . $block_theme . "/images/" . $file . "/video.png";
            }

            $row['href'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $row['alias'] . $global_config['rewrite_exturl'];

            $xtpl->assign('ROW', $row);

            if ($i++ == 1) {
                $xtpl->parse('main.first');
            } else {
                $xtpl->parse('main.loop');
            }
        }

        // Xuat ten module va URL module
        $xtpl->assign('MODULE_TITLE', $site_mods[$module]['custom_title']);
        $xtpl->assign('MODULE_LINK', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module);

        // Xuat topic
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $data . "_topic WHERE parentid=0 ORDER BY weight ASC";
        $list = $nv_Cache->db($sql, '', $module);

        $i = 1;
        foreach ($list as $cat) {
            if ($i++ == $block_config['num_videos'])
                break;
            $cat['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $cat['alias'];

            $xtpl->assign('CAT', $cat);
            $xtpl->parse('main.cat');
        }

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_box_video($block_config);
}
