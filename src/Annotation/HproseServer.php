<?php

declare(strict_types=1);
/**
 * This file is part of Mustafa Project.
 *
 * @link     https://blog.csdn.net/fanghailiang2016
 * @contact  fanghailiang2016@gmail.com
 */
namespace Mustafa\CorYar\Annotation;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;

#[Attribute(Attribute::TARGET_METHOD)]
class HproseServer extends AbstractAnnotation
{
    public $model;
}