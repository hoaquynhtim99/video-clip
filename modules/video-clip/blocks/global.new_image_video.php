<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate Jul 06, 2011, 06:31:13 AM
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_new_videos1' ) )
{
	function nv_new_videos1( $block_config )
	{
		global $module_info, $module_name, $db, $my_head, $site_mods;
		
		$module = $block_config['module'];
        $data = $site_mods[$module]['module_data'];
        $file = $site_mods[$module]['module_file'];
		
		if( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $file . "/block_new_image_video.tpl" ) ) $block_theme = $module_info['template'];
		else $block_theme = "default";

		if( $module != $module_name )
		{
			$my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "themes/" . $block_theme . "/css/" . $file . ".css\" />\n";
		}
		
		$xtpl = new XTemplate( "block_new_image_video.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $file );

		$sql = "SELECT SQL_CALC_FOUND_ROWS a.*,b.view FROM `" . NV_PREFIXLANG . "_" . $data . "_clip` a, `" . NV_PREFIXLANG . "_" . $data . "_hit` b WHERE a.id=b.cid AND a.status=1 ORDER BY a.id DESC LIMIT 0,5";
		$result = $db->sql_query( $sql );

		$i = 1;
		while( $row = $db->sql_fetchrow( $result ) )
		{
			if ( ! empty( $row['img'] ) )
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
			
			$row['last'] = ( $i == 3 or $i == 5 ) ? " last" : ""; 
			
			$xtpl->assign( 'ROW', $row );
			
			if( $i ++ == 1 )
			{
				$xtpl->parse( 'main.first' );
			}
			else
			{
				if( $i == 4 )
				{
					$xtpl->parse( 'main.loop.break' );
				}
				
				$xtpl->parse( 'main.loop' );
			}
		}

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if ( defined( 'NV_SYSTEM' ) )
{
    $content = nv_new_videos1( $block_config );
}

?>