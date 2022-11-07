
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