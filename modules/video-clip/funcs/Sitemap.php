<?php

/**
 * @Project VIDEO CLIPS AJAX 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 01, 2014, 04:33:14 AM
 */

if( ! defined( 'NV_IS_MOD_VIDEOCLIPS' ) ) die( 'Stop!!!' );

$url = array();
$cacheFile = NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . NV_LANG_DATA . "_" . $module_name . "_Sitemap.cache";
$pa = NV_CURRENTTIME - 7200;

if( ( $cache = nv_get_cache( $cacheFile ) ) != false and filemtime( $cacheFile ) >= $pa )
{
    $url = unserialize( $cache );
}
else
{
    $sql = "SELECT alias,addtime FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip WHERE status=1";
    $result = $db->query( $sql );
    while ( list( $alias, $publtime ) = $result->fetch( 3 ) )
    {
        $url[] = array( //
            'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $alias, //
            'publtime' => $publtime //
            );
    }
    
    $cache = serialize($url);
    nv_set_cache( $cacheFile, $cache );
}

nv_xmlSitemap_generate( $url );
die();