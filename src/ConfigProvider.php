<?php

declare(strict_types=1);

namespace Mustafa\CorYar;

use \Mustafa\CorYar\Aspect;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'listeners' => [
            ],
            'commands' => [

            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'aspects' => [
                Aspect\YarClientAspect::class,
                Aspect\YarServerAspect::class,
                Aspect\HproseClientAspect::class,
                Aspect\HproseServerAspect::class,
            ],
            'publish' => [

            ],
        ];
    }
}
