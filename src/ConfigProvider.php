<?php

namespace Mustafa\CorYar;

use Hyperf\Cache\Aspect\CacheableAspect;
use Hyperf\Cache\Aspect\CacheEvictAspect;
use Hyperf\Cache\Aspect\CachePutAspect;
use Hyperf\Cache\Aspect\FailCacheAspect;
use Hyperf\Cache\Cache;
use Hyperf\Cache\CacheListenerCollector;
use Hyperf\Cache\Listener\DeleteListener;
use Psr\SimpleCache\CacheInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'listeners' => [
            ],
            'annotations' => [
                'scan' => [
                    'collectors' => [

                    ],
                ],
            ],
            'aspects' => [

            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for cache.',
                    'source' => __DIR__ . '/../publish/cor_yar.php',
                    'destination' => BASE_PATH . '/config/autoload/cor_yar.php',
                ],
            ],
        ];
    }
}