<?php

/**
 * @Project VIDEO CLIPS AJAX 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 01, 2014, 04:33:14 AM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_top_videos' ) )
{
	function nv_top_videos( $block_config )
	{
		global $module_info, $module_name, $db, $my_head, $site_mods;
		
		$module = $block_config['module'];
        $data = $site_mods[$module]['module_data'];
        $file = $site_mods[$module]['module_file'];

		$path_file_ini = NV_ROOTDIR . "/modules/" . $file . "/blocks/global.top_videos.ini";
		$xml = simplexml_load_file( $path_file_ini );
		
		$xmllanguage = $xml->xpath( 'language' );
		$language = ( array )$xmllanguage[0];
		$lang_block = ( array )$language[NV_LANG_INTERFACE];
		
		if( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $file . "/block_top_video.tpl" ) ) $block_theme = $module_info['template'];
		else $block_theme = "default";

		if( $module != $module_name )
		{
			$my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "themes/" . $block_theme . "/css/" . $file . ".css\" />\n";
		}
		
		$xtpl = new XTemplate( "block_top_video.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $file );
		$xtpl->assign( 'LANG', $lang_block );

		$sql = "SELECT SQL_CALC_FOUND_ROWS a.*,b.view, ( b.view / ( " . NV_CURRENTTIME . " - a.addtime ) ) AS per FROM " . NV_PREFIXLANG . "_" . $data . "_clip a, " . NV_PREFIXLANG . "_" . $data . "_hit b WHERE a.id=b.cid AND a.status=1 ORDER BY per DESC LIMIT 0,5";
		$result = $db->query( $sql );

		while( $row = $result->fetch() )
		{
			if( ! empty( $row['img'] ) )
			{
				$imageinfo = nv_ImageInfo( NV_ROOTDIR . '/' . $row['img'], 120, true, NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module );
				$row['img'] = $imageinfo['src'];
			}
			else
			{
				$row['img'] = NV_BASE_SITEURL . "themes/default/images/" . $file . "/video.png";
			}
			$row['href'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $row['alias'];
			$row['sortTitle'] = nv_clean60( $row['title'], 20 );
			$row['addtime'] = nv_date( "d/m/Y", $row['addtime'] );
			
			$xtpl->assign( 'ROW', $row );
			$xtpl->parse( 'main.loop' );
		}

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
    $content = nv_top_videos( $block_config );
}