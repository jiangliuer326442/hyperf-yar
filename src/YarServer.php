<?php

namespace Mustafa\CorYar;

use Hyperf\Context\Context;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\HttpServer\MiddlewareManager;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\HttpServer\Server as HyperfServer;
use Hyperf\Utils\Coordinator\Constants;
use Hyperf\Utils\Coordinator\CoordinatorManager;
use Mustafa\CorYar\Annotation\YarServer as Annotation_YarServer;
use Mustafa\CorYar\HttpServer\Throwable;

class YarServer extends HyperfServer
{
    public function onRequest($request, $response): void
    {
        $yar_handlerd = false;
        $yar_classes = AnnotationCollector::getMethodsByAnnotation(Annotation_YarServer::class);
        foreach (array_merge($yar_classes) as $_annotation) {
            $path_delimiter_arr = explode('/', $request->server['request_uri']);
            $path_delimiter_num = count($path_delimiter_arr);
            $path_class = '';
            foreach ($path_delimiter_arr as $_index => $_path_delimiter_row) {
                if ($_index < $path_delimiter_num - 2) {
                    $path_class .= ucfirst($_path_delimiter_row).'\\';
                } elseif ($_index === $path_delimiter_num - 2) {
                    $path_class .= ucfirst($_path_delimiter_row).'Controller';
                }
            }
            $method = $path_delimiter_arr[$path_delimiter_num - 1];
            if (strstr($_annotation['class'], $path_class) && $method == $_annotation['method']) {
                $yar_handlerd = true;
            }
        }
        try {
            CoordinatorManager::until(Constants::WORKER_START)->yield();

            [$psr7Request, $psr7Response] = $this->initRequestAndResponse($request, $response);

            $psr7Request = $this->coreMiddleware->dispatch($psr7Request);
            /** @var Dispatched $dispatched */
            $dispatched = $psr7Request->getAttribute(Dispatched::class);
            $middlewares = $this->middlewares;
            if ($dispatched->isFound()) {
                $registeredMiddlewares = MiddlewareManager::get(
                    $this->serverName,
                    $dispatched->handler->route,
                    $psr7Request->getMethod()
                );
                $middlewares = array_merge($middlewares, $registeredMiddlewares);
            }

            $psr7Response = $this->dispatcher->dispatch($psr7Request, $middlewares, $this->coreMiddleware);
        } catch (Throwable $throwable) {
            // Delegate the exception to exception handler.
            $psr7Response = $this->exceptionHandlerDispatcher->dispatch($throwable, $this->exceptionHandlers);
        } finally {
            // Send the Response to client.
            if (!isset($psr7Response)) {
                return;
            }
            if ($yar_handlerd) {
                return;
            }
            if (isset($psr7Request) && $psr7Request->getMethod() === 'HEAD') {
                $this->responseEmitter->emit($psr7Response, $response, false);
            } else {
                $this->responseEmitter->emit($psr7Response, $response, true);
            }
        }
    }

    protected function initRequestAndResponse($request, $response): array
    {
        Context::set('myrequest', $request);
        Context::set('myresponse', $response);

        return parent::initRequestAndResponse($request, $response);
    }
}