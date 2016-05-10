<?php

/**
 * @Project VIDEO CLIPS AJAX 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 01, 2014, 04:33:14 AM
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN'))
    die('Stop!!!');

//$submenu['main'] = $lang_module['main'];
//$submenu['topic'] = $lang_module['topic'];
//$submenu['vbroken'] = $lang_module['vbroken'];
//$submenu['cbroken'] = $lang_module['cbroken'];
//$submenu['config'] = $lang_module['config'];
$allow_func = array(
    'main',
    'topic',
    'vbroken',
     /*'cbroken',*/
    'config');

define('NV_IS_FILE_ADMIN', true);

/**
 * nv_settopics()
 * 
 * @param mixed $list2
 * @param mixed $id
 * @param mixed $list
 * @param integer $m
 * @param integer $num
 * @return
 */
function nv_settopics($list2, $id, $list, $m = 0, $num = 0)
{
    ++$num;
    $defis = "";
    for ($i = 0; $i < $num; ++$i) {
        $defis .= "--";
    }

    if (isset($list[$id])) {
        foreach ($list[$id] as $value) {
            if ($value['id'] != $m) {
                $list2[$value['id']] = $value;
                $list2[$value['id']]['name'] = "|" . $defis . "&gt; " . $list2[$value['id']]['name'];
                if (isset($list[$value['id']])) {
                    $list2 = nv_settopics($list2, $value['id'], $list, $m, $num);
                }
            }
        }
    }
    return $list2;
}

/**
 * nv_listTopics()
 * 
 * @param mixed $parentid
 * @param integer $m
 * @return
 */
function nv_listTopics($parentid, $m = 0)
{
    global $db, $module_data;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic ORDER BY parentid,weight ASC";
    $result = $db->query($sql);
    $list = array();

    while ($row = $result->fetch()) {
        $list[$row['parentid']][] = array( //
            'id' => (int)$row['id'], //
            'parentid' => (int)$row['parentid'], //
            'title' => $row['title'], //
            'alias' => $row['alias'], //
            'description' => $row['description'], //
            'weight' => (int)$row['weight'], //
            'status' => $row['weight'], //
            'name' => $row['title'], //
            'selected' => $parentid == $row['id'] ? " selected=\"selected\"" : "" //
                );
    }

    if (empty($list))
        return $list;

    $list2 = array();
    foreach ($list[0] as $value) {
        if ($value['id'] != $m) {
            $list2[$value['id']] = $value;
            if (isset($list[$value['id']])) {
                $list2 = nv_settopics($list2, $value['id'], $list, $m);
            }
        }
    }

    return $list2;
}

/**
 * nv_myAlias()
 * 
 * @param mixed $alias
 * @param integer $mode
 * @param integer $id
 * @param integer $_id
 * @return
 */
function nv_myAlias($alias, $mode = 0, $id = 0, $_id = 1)
{
    global $db, $module_data;

    if ($mode == 1) //Edit Topic
        {
        $where1 = "";
        $where2 = " id!=" . $id . " AND";
    } elseif ($mode == 2) //Edit Video
    {
        $where1 = " id!=" . $id . " AND";
        $where2 = "";
    } else {
        $where1 = $where2 = "";
    }

    if ((list($count) = $db->query("SELECT COUNT(*) AS count FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip WHERE" . $where1 . " alias=" . $db->quote($alias))->fetch(3)) and $count != 0) {
        if (preg_match("/^(.*)\-(\d+)$/", $alias, $matches)) {
            $alias = $matches[1];
            $_id = $matches[2] + 1;
        }
        $alias = nv_myAlias($alias . "-" . $_id, $mode, $id, ++$_id);
    } elseif ((list($count2) = $db->query("SELECT COUNT(*) AS count FROM " . NV_PREFIXLANG . "_" . $module_data . "_topic WHERE" . $where2 . " alias=" . $db->quote($alias))->fetch(3)) and $count2 != 0) {
        if (preg_match("/^(.*)\-(\d+)$/", $alias, $matches)) {
            $alias = $matches[1];
            $_id = $matches[2] + 1;
        }
        $alias = nv_myAlias($alias . "-" . $_id, $mode, $id, ++$_id);
    }

    return $alias;
}

/**
 * ajaxRespon
 * 
 * @package VIDEO CLIPS AJAX 4.x
 * @author PHAN TAN DUNG (phantandung92@gmail.com)
 * @copyright (C) 2015 PHAN TAN DUNG. All rights reserved
 * @version 1.0
 * @access public
 */
class ajaxRespon
{
    private $jsonDefault = array(
        'status' => 'error',
        'message' => '',
        'input' => '',
        'redirect' => '');

    private $json = array();

    /**
     * ajaxRespon::__construct()
     * 
     * @return
     */
    public function __construct()
    {
        $this->json = $this->jsonDefault;
    }

    /**
     * ajaxRespon::setMessage()
     * 
     * @param mixed $message
     * @return
     */
    public function setMessage($message)
    {
        $this->json['message'] = $message;
        return $this;
    }

    /**
     * ajaxRespon::setInput()
     * 
     * @param mixed $input
     * @return
     */
    public function setInput($input)
    {
        $this->json['input'] = $input;
        return $this;
    }

    /**
     * ajaxRespon::setRedirect()
     * 
     * @param mixed $redirect
     * @return
     */
    public function setRedirect($redirect)
    {
        $this->json['redirect'] = $redirect;
        return $this;
    }

    /**
     * ajaxRespon::setSuccess()
     * 
     * @return
     */
    public function setSuccess()
    {
        $this->json['status'] = 'ok';
        return $this;
    }

    /**
     * ajaxRespon::setError()
     * 
     * @return
     */
    public function setError()
    {
        $this->json['status'] = 'error';
        return $this;
    }

    /**
     * ajaxRespon::reset()
     * 
     * @return
     */
    public function reset()
    {
        $this->json = $this->jsonDefault;
        return $this;
    }

    /**
     * ajaxRespon::respon()
     * 
     * @return
     */
    public function respon()
    {
        die(json_encode($this->json));
    }
}

$ajaxRespon = new ajaxRespon();
