<?php

declare(strict_types=1);
/**
 * This file is part of Mustafa Project.
 *
 * @link     https://blog.csdn.net/fanghailiang2016
 * @contact  fanghailiang2016@gmail.com
 */
namespace Mustafa\CorYar\Aspect;

use Mustafa\CorYar\Annotation\HproseClient;
use Hprose\Swoole\Http\Client as Hprose_Client;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;

class HproseClientAspect
{

    public $classes = [
        'App\\Controller\\*Controller'
    ];

    public $annotations = [
        HproseClient::class,
    ];

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        foreach (AnnotationCollector::getPropertiesByAnnotation(HproseClient::class) as $_annotation) {
            if ($_annotation['class'] == $proceedingJoinPoint->className) {
                $proceedingJoinPoint->getInstance()->{$_annotation['property']} = new Hprose_Client($_annotation['annotation']->url);
            }
        }
        return $proceedingJoinPoint->process();
    }
}