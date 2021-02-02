<?php

namespace Imponeer\Smarty\Extensions\ForeachQ;

use Imponeer\Contracts\Smarty\Extension\SmartyCompilerInterface;
use Smarty_Internal_SmartyTemplateCompiler;

/**
 * Defines {endforeach} smarty tag
 *
 * @package Imponeer\Smarty\Extensions\ForeachQ
 */
class ForeachQCloseTagCompiler implements SmartyCompilerInterface
{

    /**
     * @inheritDoc
     */
    public function execute($args, Smarty_Internal_SmartyTemplateCompiler &$compiler)
    {
        return '}}';
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'foreachqclose';
    }
}