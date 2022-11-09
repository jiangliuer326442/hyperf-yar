<?php

declare(strict_types=1);
/**
 * This file is part of Mustafa Project.
 *
 * @link     https://blog.csdn.net/fanghailiang2016
 * @contact  fanghailiang2016@gmail.com
 */
namespace Mustafa\CorYar\Aspect;

use Mustafa\CorYar\Annotation\YarClient;
use BromineMai\CorYar\Client\Client as Yar_Client;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;

class YarClientAspect extends AbstractAspect
{

    public array $classes = [
        'App\\Controller\\*Controller'
    ];

    public array $annotations = [
        YarClient::class,
    ];

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        foreach (AnnotationCollector::getPropertiesByAnnotation(YarClient::class) as $_annotation) {
            if ($_annotation['class'] == $proceedingJoinPoint->className) {
                $proceedingJoinPoint->getInstance()->{$_annotation['property']} = new Yar_Client($_annotation['annotation']->url);
            }
        }
        return $proceedingJoinPoint->process();
    }
}
