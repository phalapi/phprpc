<?php
namespace PhalApi\PHPRPC;

use PhalApi\PhalApi as KernalPhalApi;
use PhalApi\Request;

class PhalApi {

    protected $phalapi;

    public function __construct($phalapi = NULL) {
        if ($phalapi === NULL) {
            $phalapi = new KernalPhalApi();
        }

        $this->phalapi = $phalapi;
    }

    public function response($params = NULL) {
        $paramsArr = json_decode($params, TRUE);
        if ($paramsArr !== FALSE) {
            \PhalApi\DI()->request = new Request(array_merge($_GET, $paramsArr));
        } else {
			\PhalApi\DI()->request = new Request($_GET);
		}

        $rs = $this->phalapi->response();

        return $rs->getResult();
    }
}
