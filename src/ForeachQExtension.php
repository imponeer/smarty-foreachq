<?php

namespace Imponeer\Smarty\Extensions\ForeachQ;

use Imponeer\Smarty\Extensions\ForeachQ\CompilerTag\ForeachQCloseCompilerTag;
use Imponeer\Smarty\Extensions\ForeachQ\CompilerTag\ForeachQCompilerTag;
use Smarty\Compile\CompilerInterface;
use Smarty\Extension\Base;

class ForeachQExtension extends Base
{

    public function getTagCompiler(string $tag): ?CompilerInterface
    {
        return match ($tag) {
            'foreachq' => new ForeachQCompilerTag(),
            'foreachqclose' => new ForeachQCloseCompilerTag(),
            default => null,
        };
    }



}