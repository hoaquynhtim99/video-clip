<?php

/**
 * @Project VIDEO CLIPS AJAX 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2013 PHAN TAN DUNG. All rights reserved
 * @Createdate Dec 08, 2013, 09:57:59 PM
 */

if ( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

$sql = "SELECT SQL_CALC_FOUND_ROWS `title`,`alias`,`hometext` 
FROM `" . NV_PREFIXLANG . "_" . $m_values['module_data'] . "_clip` 
WHERE `status`=1 AND (" . nv_like_logic( 'title', $dbkeyword, $logic ) . " 
OR " . nv_like_logic( 'hometext', $dbkeyword, $logic ) . " 
OR " . nv_like_logic( 'bodytext', $dbkeyword, $logic ) . ") 
LIMIT " . $pages . "," . $limit;

$tmp_re = $db->sql_query( $sql );

$result = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $result );

if ( $all_page )
{
	$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=';

	while ( list( $tilterow, $alias, $content ) = $db->sql_fetchrow( $tmp_re ) )
	{
		$url = $link . $alias;

		$result_array[] = array( //
			'link' => $url, //
			'title' => BoldKeywordInStr( $tilterow, $key, $logic ), //
			'content' => BoldKeywordInStr( $content, $key, $logic ) //
				);
	}
}

?>