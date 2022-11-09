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
use BromineMai\CorYar\Client\Client as Yar_Client;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Retry\Annotation\Retry;
use Hyperf\Utils\ApplicationContext;

#[Aspect(classes: ['App\\Controller\\*Controller'], annotations: [HproseClient::class])]
class HproseClientAspect extends AbstractAspect
{
    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        foreach (AnnotationCollector::getPropertiesByAnnotation(HproseClient::class) as $_annotation) {
            if ($_annotation['class'] == $proceedingJoinPoint->className) {
                $proceedingJoinPoint->getInstance()->{$_annotation['property']} = new Yar_Client($_annotation['annotation']->url);
            }
        }
        return $proceedingJoinPoint->process();
    }
}
