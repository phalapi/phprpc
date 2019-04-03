# PHPRPC扩展
PhalApi 2.x扩展类库，基于PHPRPC的扩展。

## 安装和配置
修改项目下的composer.json文件，并添加：  
```
    "phalapi/phprpc":"dev-master"
```
然后执行```composer update```。  


## 使用

为了使用PHPRPC协议而非HTTP协议，需要提供新的入口。可以编写新的./public/index_phprpc.php入口：

```php
<?php
/**
 * 统一访问入口 - PHPRPC专用
 */

require_once dirname(__FILE__) . '/init.php';

$server = new PhalApi\PHPRPC\Lite();
$server->response();
```

## 示例
这里以?s=App.Site.Index为例进行说明。

### (1)浏览器访问失败
在使用了phprpc协议后，我们再以浏览器（HTTP协议）访问调用新接口时，如：

```
http://localhost/phalapi/public/phprpc_index.php
```

会预期地出现以下失败信息：

```
phprpc_functions="YToxOntpOjA7czo4OiJyZXNwb25zZSI7fQ==";
```

### (2)通过phprpc协议调用

如果通过phprpc协议调用，我们则可以得到接口返回的 **源数据** 。  
假设请求的接口为：?s=App.Site.Index，则可以得到我们熟悉的：

```php
array(3) {
  ["ret"]=>
  int(200)
  ["data"]=>
  array(3) {
    ["title"]=>
    string(13) "Hello PhalApi"
    ["version"]=>
    string(5) "2.5.2"
    ["time"]=>
    int(1554284952)
  }
  ["msg"]=>
  string(0) ""
}

```

## phprpc协议调试
同样，为了方便进行phprpc协议下接口调用的调试，我们提供了一个脚本，如上面的对?s=App.Site.Index调用，使用脚本即为：

```bash
$ php ./vendor/phalapi/phprpc/bin/check.php http://localhost/phalapi/public/phprpc_index.php?s=App.Site.Index

array(3) {
  ["ret"]=>
  int(200)
  ["data"]=>
  array(3) {
    ["title"]=>
    string(13) "Hello PhalApi"
    ["version"]=>
    string(5) "2.5.2"
    ["time"]=>
    int(1554284952)
  }
  ["msg"]=>
  string(0) ""
}
```
## 对客户端的调整
虽然服务端不需要作出太多的改动，但对于客户端来说，仍然需要进行三方面的调整以进行phprpc协议的调用以及参数的传递和返回结果的获取。  
  
现分说如下。
### (1)调用方式的改变
首当其冲的就是调用方式的改变，但值得开心的是，phprpc对很多语言都有支持。具体可以查看phprpc官网。

### (2)POST参数传递方式的改变
其次对POST参数传递的改变。考虑到phprpc协议中对POST的数据有一定的复杂性，这里统一作了简化。
正如我们下面的代码所示：
```php

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
```
  
我们约定： **通过第一个参数用JSON格式来传递全部原来需要POST的数据** 。  
  
当POST的数据和GET的数据冲突时，以POST为准。  
  
所以，相应地，当需要传递POST参数时，客户需要这样调整（如PHP下）：
```php
$client->response(json_encode($params)))
```
  
如无此POST参数，则可以忽略不传。

### (3)返回结果格式的改变
最后，就是返回结果格式的改变。  
  
在phprpc协议下，因为可以更轻松地获取接口返回的源数据，所以这里也同样不再通过字符串流式的序列返回（如原来的JSON或XML），而是直接返回接口的 **源数据** 。如上面示例中所看到的结果一样。  
  
这点，需要特别注意。

## 参考
phprpc官网：http://www.phprpc.org/zh_CN/

