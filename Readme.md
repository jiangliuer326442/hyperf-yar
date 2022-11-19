基于 **bromine-mai/cor-yar** 实现的 hyperf 框架可用的 yar 服务端/客户端工具

`composer require mustafa3264/cor-yar`

## 使用教程

### config/autoload/server.php
替换 callbacks Event::ON_REQUEST 为 **\Mustafa\CorYar\YarServer::class**
``` php
    'servers' => [
        [
            'name' => 'http',
            'type' => Server::SERVER_HTTP,
            'host' => '0.0.0.0',
            'port' => 9501,
            'sock_type' => SWOOLE_SOCK_TCP,
            'callbacks' => [
                Event::ON_REQUEST => [\Mustafa\CorYar\YarServer::class, 'onRequest'],
            ],
        ],
    ],
```

### 服务端使用
加在类方法中
model为代理yar请求实现的model
```php
use Mustafa\CorYar\Annotation\YarServer;

#[YarServer(model: \App\Model\Services\Interfaces\CalculatorServiceInterface::class)]
public function t3()
{
}
```

### 客户端使用
加在类属性中，属性url为 yar服务端地址
```php
use Mustafa\CorYar\Annotation\YarClient;

#[YarClient(url: 'http://127.0.0.1:9501/calculate/t3')]
public $test_client;
```

## 示例
```php
<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;
use Mustafa\CorYar\Annotation\YarClient;
use Mustafa\CorYar\Annotation\YarServer;

class CalculateController extends AbstractController
{

    #[YarClient(url: 'http://127.0.0.1:9501/calculate/t3')]
    public $test_client;

    public function t1()
    {
        return $this->test_client->add(25, 20);
    }

    #[YarServer(model: \App\Model\Services\Interfaces\CalculatorServiceInterface::class)]
    public function t3()
    {
    }
}
```

```php
    Router::addGroup('/calculate', static function () {
        Router::post('/myyarserver', 'App\Controller\CalculateController@myyarserver');
        Router::get('/myyarclient', 'App\Controller\CalculateController@myyarclient');
    });
```

## 联系作者

#### 我的邮箱
- fanghailiang2016@gmail.com
- hailiang_fang@163.com

#### 微信

**fanghailiang2023**