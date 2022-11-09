<?php

declare(strict_types=1);
/**
 * This file is part of Mustafa Project.
 *
 * @link     https://blog.csdn.net/fanghailiang2016
 * @contact  fanghailiang2016@gmail.com
 */
namespace Mustafa\CorYar\Aspect;

use Mustafa\CorYar\Annotation\HproseServer;
use Hyperf\Context\Context;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;

#[Aspect(annotations: [HproseServer::class])]
class HproseServerAspect extends AbstractAspect
{
    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        foreach (AnnotationCollector::getMethodsByAnnotation(HproseServer::class) as $_annotation) {
            if ($_annotation['class'] == $proceedingJoinPoint->className && $_annotation['method'] == $proceedingJoinPoint->methodName) {
                $model = make($_annotation['annotation']->model);
                $stream = new \Hprose\BytesIO(file_get_contents("php://input"));
                $reader = new \Hprose\Reader($stream);
                $tag = $stream->getc();
                if ($tag !== \Hprose\Tags::TagCall) {
                    throw new \Exception('Unexpected tag');
                }

                $server = new \Hprose\Http\Server();
                $server->addFunction([$model, $reader->readString()]);
                $server->start();

            }
        }
    }
}
