<?php

declare(strict_types=1);

namespace Mustafa\CorYar;

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
            ],
            'publish' => [

            ],
        ];
    }
}
