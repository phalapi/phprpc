<?php
/**
 * 统一访问入口 - PHPRPC专用
 */

require_once dirname(__FILE__) . '/init.php';

$server = new PhalApi\PHPRPC\Lite();
$server->response();

