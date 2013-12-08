<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if ( ! defined( 'NV_IS_MOD_VIDEOCLIPS' ) ) die( 'Stop!!!' );

global $array_op, $module_name, $module_data, $nv_Request, $VideoData, $db, $lang_module, $user_info, $client_info, $topicList, $module_info, $module_file, $configMods, $my_head, $isDetail;

if( ! function_exists( 'listComm' ) )
{
	function listComm()
	{
		global $xtpl, $cpgnum, $comments, $commNext;

		if ( empty( $comments ) ) return "";

		foreach ( $comments as $comment )
		{
			$xtpl->assign( 'USER', $comment );

			if ( ! $comment['ischecked'] )
			{
				$xtpl->parse( 'listComm.listComm2.unchecked' );
			}
			if ( defined( "NV_IS_MODADMIN" ) )
			{
				$xtpl->parse( 'listComm.listComm2.delcomm' );
			}
			$xtpl->parse( 'listComm.listComm2' );
		}

		if ( $commNext )
		{
			$xtpl->assign( 'NEXTID', $cpgnum );
			$xtpl->parse( 'listComm.ifNext' );
		}
		if ( defined( "NV_IS_MODADMIN" ) )
		{
			$xtpl->parse( 'listComm.ifDelComm' );
		}
		$xtpl->parse( 'listComm' );
		return $xtpl->text( "listComm" );
	}
}

if( ! function_exists( 'commentReload' ) )
{
	function commentReload()
	{
		global $xtpl, $comments, $VideoData, $lang_module;

		if ( ! $VideoData['comm'] ) return "";

		if ( defined( "NV_IS_USER" ) )
		{
			$xtpl->parse( 'commentList.commentForm' );
		}
		else
		{
			$pleasLogin = sprintf( $lang_module['pleaseLogin'], NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=users", NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=users/register" );
			$pleasLogin = nv_url_rewrite( $pleasLogin, 1 );
			$xtpl->assign( 'PLEASELOGIN', $pleasLogin );
			$xtpl->parse( 'commentList.ifNotGuest' );
		}

		$xtpl->assign( 'LISTCOMM', listComm() );

		$xtpl->parse( 'commentList' );
		return $xtpl->text( "commentList" );
	}
}

//Kiem tra quyen truy cap
if ( ! ( $allow = nv_set_allow( $VideoData['who_view'], $VideoData['groups_view'] ) ) )
{
	if ( $nv_Request->isset_request( 'aj', 'post' ) ) die( "access forbidden" );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_site_theme( $lang_module['accessForbidden'] );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	die();
}

//Comment broken
if ( $nv_Request->isset_request( 'mbroken', 'post' ) )
{
	$mbroken = filter_text_input( 'mbroken', 'post', '', 1 );
	$sessionName = "mbroken";
	$session = isset( $_SESSION[$module_data . '_' . $sessionName] ) ? $_SESSION[$module_data . '_' . $sessionName] : "";
	$session = intval( $session );
	if ( $session > NV_CURRENTTIME - 30 ) die( "ERROR" );
	$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_comm` SET `broken`=`broken`+1 WHERE `id`=" . $mbroken . " AND `ischecked`=0";
	$db->sql_query( $query );
	$_SESSION[$module_data . '_' . $sessionName] = NV_CURRENTTIME;
	die( "OK" );
}

//Delete Comment
if ( defined( "NV_IS_MODADMIN" ) and $nv_Request->isset_request( 'delcomm', 'post' ) )
{
	$delcomm = $nv_Request->get_int( 'delcomm', 'post', 0 );
	$sql = "SELECT `cid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comm` WHERE `id`=" . $delcomm;
	$result = $db->sql_query( $sql );
	list( $cid ) = $db->sql_fetchrow( $result );

	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comm` WHERE `id`=" . $delcomm;
	$db->sql_query( $sql );

	$sql = "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comm` WHERE `cid`=" . $cid;
	$result = $db->sql_query( $sql );
	list( $count ) = $db->sql_fetchrow( $result );

	$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_hit` SET `comment`=" . $count . " WHERE `cid`=" . $cid;
	$db->sql_query( $query );
	die( "OK|" . $count . "" );
}

//AJAX comment
if ( $nv_Request->isset_request( 'savecomm', 'post' ) )
{
	if ( ! defined( "NV_IS_USER" ) ) die( "ERROR|" . $lang_module['error3'] );
	if ( ! $VideoData['comm'] ) die( "ERROR|" . $lang_module['error4'] );

	$sql = "SELECT MAX(`posttime`) as `ptime` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comm` WHERE `userid`=" . $user_info['userid'];
	$result = $db->sql_query( $sql );
	list( $ptime ) = $db->sql_fetchrow( $result );
	$ptime = intval( $ptime );
	if ( $ptime > NV_CURRENTTIME - 60 ) die( "ERROR|" . $lang_module['error2'] );

	$content = filter_text_input( 'savecomm', 'post', '', 1, 500 );
	if ( empty( $content ) ) die( "ERROR|" . $lang_module['error1'] );

	$isChecked = defined( "NV_IS_MODADMIN" ) ? 1 : 0;

	$content = nv_nl2br( $content );
	$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_comm` VALUES 
    (NULL , " . $VideoData['id'] . ", " . $db->dbescape( $content ) . ", " . NV_CURRENTTIME . ", 
    " . $user_info['userid'] . ", " . $db->dbescape( $client_info['ip'] ) . ", 1, 0, " . $isChecked . ");";
	$db->sql_query( $sql );

	$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_hit` SET `comment`=comment+1 WHERE `cid`=" . $VideoData['id'];
	$db->sql_query( $query );

	die( "OK" );
}

//Nut like, unlike, broken
if ( $nv_Request->isset_request( 'aj', 'post' ) and in_array( ( $aj = filter_text_input( 'aj', 'post', '', 1 ) ), array(
	'like',
	'unlike',
	'broken' ) ) )
{
	$sessionName = $aj == "broken" ? "broken" : "like";
	$listLike = isset( $_SESSION[$module_data . '_' . $sessionName] ) ? $_SESSION[$module_data . '_' . $sessionName] : "";
	$listLike = ! empty( $listLike ) ? explode( ",", $listLike ) : array();
	if ( empty( $listLike ) or ! in_array( $VideoData['id'], $listLike ) )
	{
		$set = $aj == "broken" ? "`" . $aj . "`=1" : "`" . $aj . "`=" . ( $VideoData[$aj] + 1 );
		$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_hit` SET " . $set . " WHERE `cid`=" . $VideoData['id'];
		$db->sql_query( $query );
		array_unshift( $listLike, $VideoData['id'] );
		$_SESSION[$module_data . '_' . $sessionName] = implode( ",", $listLike );
		++$VideoData[$aj];
	}
	die( $aj . "_" . $VideoData[$aj] );
}

//Tang viewHits
$listRes = isset( $_SESSION[$module_data . '_ViewList'] ) ? $_SESSION[$module_data . '_ViewList'] : "";
$listRes = ! empty( $listRes ) ? explode( ",", $listRes ) : array();

if ( empty( $listRes ) or ! in_array( $VideoData['id'], $listRes ) )
{
	$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_hit` SET `view`=view+1 WHERE `cid`=" . $VideoData['id'];
	$db->sql_query( $query );
	array_unshift( $listRes, $VideoData['id'] );
	$_SESSION[$module_data . '_ViewList'] = implode( ",", $listRes );
	++$VideoData['view'];
}

$topic = $topicList[$VideoData['tid']];
$VideoData['filepath'] = urlencode( ! empty( $VideoData['internalpath'] ) ? NV_BASE_SITEURL . $VideoData['internalpath'] : $VideoData['externalpath'] );
$VideoData['url'] = nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $VideoData['alias'], 1 );
$VideoData['editUrl'] = nv_url_rewrite( NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&op=main&edit&id=" . $VideoData['id'] . "&redirect=1", 1 );

$comments = array();
$cpgnum = 0;
$commNext = false;
if ( $VideoData['comm'] )
{
	if ( $nv_Request->isset_request( 'cpgnum', 'post' ) ) $cpgnum = $nv_Request->get_int( 'cpgnum', 'post', 0 );

	$sql = "SELECT a.*, b.username, b.email, b.full_name, b.gender, b.photo, b.view_mail, b.md5username 
    FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comm` a, `" . NV_USERS_GLOBALTABLE . "` b 
    WHERE a.cid=" . $VideoData['id'] . " AND a.status=1 AND a.userid=b.userid 
    ORDER BY a.id DESC LIMIT " . $cpgnum . ", " . ( $configMods['commNum'] + 1 );
	$result = $db->sql_query( $sql );
	$i = 0;
	while ( $row = $db->sql_fetch_assoc( $result ) )
	{
		if ( $i <= $configMods['commNum'] - 1 )
		{
			$comments[$i] = $row;
			$comments[$i]['full_name'] = ! empty( $row['full_name'] ) ? $row['full_name'] : $row['username'];
			$comments[$i]['userView'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=memberlist/" . change_alias( $row['username'] ) . "-" . $row['md5username'];
			$comments[$i]['email'] = $row['view_mail'] ? $row['email'] : "";
			$comments[$i]['photo'] = NV_BASE_SITEURL . ( ! empty( $row['photo'] ) ? $row['photo'] : "themes/default/images/users/no_avatar.jpg" );
			$comments[$i]['posttime'] = nv_ucfirst( nv_strtolower( nv_date( "l, j F Y, H:i", $row['posttime'] ) ) );
		}
		else
		{
			$commNext = true;
			break;
		}
		++$cpgnum;
		++$i;
	}
}

$xtpl = new XTemplate( "detail.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'MODULECONFIG', $configMods );
$xtpl->assign( 'MODULEURL', nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $VideoData['alias'], 1 ) );

// if ( $nv_Request->isset_request( 'cpgnum', 'post' ) )
// {
	// echo listComm();
	// exit;
// }

// if ( $nv_Request->isset_request( 'commentReload', 'post' ) )
// {
	// echo commentReload();
	// exit;
// }

$xtpl->assign( 'DETAILCONTENT', $VideoData );
if ( defined( 'NV_IS_MODADMIN' ) ) $xtpl->parse( 'main.isAdmin' );
// if ( $VideoData['comm'] ) $xtpl->parse( 'main.ifComm' );
if ( ! empty( $VideoData['bodytext'] ) ) $xtpl->parse( 'main.bodytext' );

// if ( $VideoData['comm'] )
// {
	// $xtpl->assign( 'COMMENTSECTOR', commentReload() );
	// $xtpl->parse( 'main.commentSector' );
// }

if( $isDetail )
{
	$xtpl->parse( 'main.scrollPlayer' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( "main" );

if ( $nv_Request->isset_request( 'aj', 'post' ) and ( $aj = filter_text_input( 'aj', 'post', '', 0 ) == 1 ) )
{
	echo $content;
	exit;
}

$content = "<div id=\"videoDetail\">" . $content . "</div>\n";

$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "modules/" . $module_file . "/js/jquery.autoresize.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js\"></script>\n";
$my_head .= '<script type="text/javascript">
function explode(a,d,b){if(2>arguments.length||"undefined"==typeof a||"undefined"==typeof d)return null;if(""===a||!1===a||null===a)return!1;if("function"==typeof a||"object"==typeof a||"function"==typeof d||"object"==typeof d)return{"0":""};!0===a&&(a="1");var a=a+"",c=(d+"").split(a);if("undefined"===typeof b)return c;0===b&&(b=1);if(0<b)return b>=c.length?c:c.slice(0,b-1).concat([c.slice(b-1).join(a)]);if(-b>=c.length)return[];c.splice(c.length+b);return c};
function videoPlay(d,c){var a=$("#"+c).outerWidth(),b;' . $configMods['playerMaxWidth'] . '<a&&(a=' . $configMods['playerMaxWidth'] . ');b=a;a=Math.ceil(45*a/80)+4;$("#"+c).parent().css({width:b,height:a,margin:"0 auto"});swfobject.embedSWF("' . NV_BASE_SITEURL . 'modules/' . $module_file . '/js/player.swf",c,b,a,"10.0.0.0",!1,{file:d,backcolor:"0x000000",frontcolor:"0x666666",lightcolor:"0xFF6600",width:b,height:a,controlbar:"over",autostart:' . $configMods['playerAutostart'] . ',smoothing:1,autoscroll:1,stretching:"fill",volume:100,largecontrols:1' . $configMods['playerSkin'] . '},{bgcolor:"#000000",wmode:"window",allowFullScreen:"true",allowScriptAccess:"always"});return!1};
function videoPlayList(a,b){swfobject.embedSWF("' . NV_BASE_SITEURL . 'modules/' . $module_file . '/js/player.swf",b,"640","393","10.0.0.0",!1,{playlistfile:a,backcolor:"0x000000",frontcolor:"0x666666",lightcolor:"0xFF6600",width:"640",height:"392",controlbar:"over",autostart:' . $configMods['playerAutostart'] . ',smoothing:1,autoscroll:1,stretching:"fill",volume:100,largecontrols:1' . $configMods['playerSkin'] . '},{bgcolor:"#000000",wmode:"window",allowFullScreen:"true",allowScriptAccess:"always"});return!1};
function elementSupportsAttribute(a,b){var c=document.createElement(a);return b in c};
</script>' . "\n";

?>