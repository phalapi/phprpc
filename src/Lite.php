<?php
namespace PhalApi\PHPRPC;

/**
 * phprpc服务端
 *
 * - 基于phprpc 3.0的实现
 *
 * 示例
 *
 *  $server = new PHPRPC_Lite();
 *  $server->response();
 *
 * @link http://www.phprpc.org/zh_CN/
 * @author dogstar <chanzonghuang@gmail.com> 2019-04-03
 */

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'phprpc' . DIRECTORY_SEPARATOR . 'phprpc_server.php';

class Lite {

    protected $phalapiProxy;

    public function __construct($phalapi = NULL) {
        $this->phalapiProxy = new PHPRPC_PhalApi($phalapi);
    }

    public function response() {
        $server = new PHPRPC_Server();  

        $server->add(array('response'), $this->phalapiProxy);

        $server->start();
    }
}

