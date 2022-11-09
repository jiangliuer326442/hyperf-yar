<?php

declare(strict_types=1);
/**
 * This file is part of Mustafa Project.
 *
 * @link     https://blog.csdn.net/fanghailiang2016
 * @contact  fanghailiang2016@gmail.com
 */
namespace Mustafa\CorYar\Aspect;

use Mustafa\CorYar\Annotation\YarServer;
use BromineMai\CorYar\Server\SwooleServer as Yar_Server;
use Hyperf\Context\Context;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;

#[Aspect(annotations: [YarServer::class])]
class YarServerAspect extends AbstractAspect
{
    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        foreach (AnnotationCollector::getMethodsByAnnotation(YarServer::class) as $_annotation) {
            if ($_annotation['class'] == $proceedingJoinPoint->className && $_annotation['method'] == $proceedingJoinPoint->methodName) {
                $model = make($_annotation['annotation']->model);
                $server = new Yar_Server($model);
                $server->setIoHandler(Context::get('myrequest'), Context::get('myresponse')); // 设置IO句柄
                $server->handle();
            }
        }
    }
}