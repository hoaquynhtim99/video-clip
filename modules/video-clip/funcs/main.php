<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if ( ! defined( 'NV_IS_MOD_VIDEOCLIPS' ) ) die( 'Stop!!!' );

$pgnum = $nv_Request->get_int( "page", "get", 0 ); // Trang
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;ajax=1";
if( $topicID )
{
	$base_url .= "&amp;" . NV_OP_VARIABLE . "=" . $topicList[$topicID]['alias'];
}

$sql = "SELECT SQL_CALC_FOUND_ROWS a.*,b.view FROM `" . NV_PREFIXLANG . "_" . $module_data . "_clip` a, `" . NV_PREFIXLANG . "_" . $module_data . "_hit` b WHERE a.id=b.cid AND a.status=1" . ( $topicID ? " AND a.tid=" . $topicID : "" ) . " ORDER BY a.id DESC LIMIT " . $pgnum . "," . $configMods['otherClipsNum'];

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'MODULECONFIG', $configMods );

// Chu de
if ( ! empty( $topicList ) )
{
	$xtpl->assign( 'OTHERTOPIC', array(
		'href' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;ajax=1",
		'title' => $lang_module['all'],
		'current' => $topicID ? "" : " current",
	));
	$xtpl->parse( 'main.topicList.loop' );

	foreach ( $topicList as $topic )
	{
		if( $topic['parentid'] == 0 )
		{
			$xtpl->assign( 'OTHERTOPIC', array(
				'href' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $topic['alias'] . "&amp;ajax=1",
				'title' => $topic['title'],
				'current' => $topicID == $topic['id'] ? " current" : "",
			));
			
			$xtpl->parse( 'main.topicList.loop' );
		}
	}
	$xtpl->parse( 'main.topicList' );
}

$result = $db->sql_query( $sql );
$res = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $res );
$all_page = intval( $all_page );
if ( $all_page )
{
	while ( $row = $db->sql_fetch_assoc( $result ) )
	{
		if ( ! empty( $row['img'] ) )
		{
			$imageinfo = nv_ImageInfo( NV_ROOTDIR . '/' . $row['img'], 120, true, NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name );
			$row['img'] = $imageinfo['src'];
		}
		else
		{
			$row['img'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/video.png";
		}
		$row['href'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $row['alias'];
		$row['sortTitle'] = nv_clean60( $row['title'], 20 );
		$xtpl->assign( 'OTHERCLIPSCONTENT', $row );
		$xtpl->parse( 'main.otherClips.otherClipsContent' );
	}

	$generate_page = nv_generate_page( $base_url, $all_page, $configMods['otherClipsNum'], $pgnum, true, true, 'nv_urldecode_ajax', 'VideoPageData' );
	if ( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.otherClips.nv_generate_page' );
	}

	$xtpl->parse( 'main.otherClips' );
}

if( $nv_Request->isset_request( "ajax", "get" ) )
{
	$contents = $xtpl->text( "main.otherClips" );
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo $contents;
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( "main" );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>