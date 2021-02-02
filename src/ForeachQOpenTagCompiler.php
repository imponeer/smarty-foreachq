<?php

namespace Imponeer\Smarty\Extensions\ForeachQ;

use Imponeer\Contracts\Smarty\Extension\SmartyCompilerInterface;
use Smarty_Internal_SmartyTemplateCompiler;
use SmartyCompilerException;

/**
 * Defines {foreachq} smarty tag
 *
 * @package Imponeer\Smarty\Extensions\ForeachQ
 */
class ForeachQOpenTagCompiler implements SmartyCompilerInterface
{

    /**
     * @inheritDoc
     */
    public function execute($args, Smarty_Internal_SmartyTemplateCompiler &$compiler)
    {

        $this->validateAttributesBeforeParsing($args, $compiler);

        $args['item'] = $this->getItemValue($args['item']);
        $args['key'] = isset($args['key']) ? $compiler->_dequote($args['key']) : null;

        $this->validateAttributesAfterParsing($args, $compiler);

        if (isset($args['name'])) {
            return $this->renderOutputWithName($args['name'], $args['from'], $args['key'], $args['item']);
        }

        return $this->renderOutputWithoutName($args['from'], $args['key'], $args['item']);
    }

    /**
     * Validates item argument
     *
     * @param array $args Arguments data
     * @param Smarty_Internal_SmartyTemplateCompiler $compiler Current smarty compiler instance
     *
     * @throws SmartyCompilerException
     */
    protected function validateAttributesAfterParsing(array $args, Smarty_Internal_SmartyTemplateCompiler $compiler) {
        if (!$this->isVariableName($args['item'])) {
            $compiler->trigger_template_error(
                "foreachq 'item' argument must have a variable name",
                null,
                true
            );
        }
        if (is_string($args['key']) && !$this->isVariableName($args['key'])) {
            $compiler->trigger_template_error(
                "foreachq 'key' argument must have a variable name",
                null,
                true
            );
        }
    }

    /**
     * Checks if argument can be variable name
     *
     * @param string $arg Argument name
     *
     * @return bool
     */
    private function isVariableName(string $arg): bool
    {
        return (bool)preg_match('/^\w+$/', $arg);
    }

    /**
     * Gets item argument value
     *
     * @param string $item Item arg
     * @return string
     */
    protected function getItemValue(string $item): string {
        return (string)eval(
            sprintf('return %s;' , $item )
        );
    }

    /**
     * Renders generated output string if name is used
     *
     * @param string $name Name of foreach cycle
     * @param string $from From variable string
     * @param string|null $key Foreach name
     * @param string $item Variable name for bind
     *
     * @return string
     */
    protected function renderOutputWithName(string $name, string $from, ?string $key, string $item): string {
        return sprintf(
            '$this->_foreach[%1$s] = [
                \'total\' => count(%2$s),
                \'iteration\' => 0
            ];
            if ($this->_foreach[%1$s][\'total\'] > 0) {
                %3$s
                    $this->_foreach[%1$s][\'iteration\']++;
            ',
            $name,
            $from,
            $this->renderForEachLine($from, $key, $item)
        );
    }

    /**
     * Renders generated output string if no name is used
     *
     * @param string $from From variable string
     * @param string|null $key Foreach name
     * @param string $item Variable name for bind
     *
     * @return string
     */
    protected function renderOutputWithoutName(string $from, ?string $key, string $item): string {
        return sprintf(
            'if (!empty(%1$s)) {
                %2$s
            ',
            $from,
            $this->renderForEachLine($from, $key, $item)
        );
    }

    /**
     * Renders foreach line
     *
     * @param string $from From variable string
     * @param string|null $key Foreach name
     * @param string $item Variable name for bind
     *
     * @return string
     */
    private function renderForEachLine(string $from, ?string $key, string $item) {
        if ($key) {
            return sprintf('foreach ((array)%s as $this->_tpl_vars[\'%s\'] => $this->_tpl_vars[\'%s\']) {', $from, $key, $item);
        }

        return sprintf('foreach ((array)%s as $this->_tpl_vars[\'%s\']) {', $from, $item);
    }

    /**
     * Checks if all attributes for tag are OK
     *
     * @param array $attr Attributes array
     * @param Smarty_Internal_SmartyTemplateCompiler $compiler Smarty compiler instance
     *
     * @throws SmartyCompilerException
     */
    protected function validateAttributesBeforeParsing(array $attr, Smarty_Internal_SmartyTemplateCompiler $compiler) {
        foreach (['from', 'item'] as $name) {
            if (!empty($attr[$name])) {
                continue;
            }

            $compiler->trigger_template_error(
                sprintf("foreachq is missing '%s' attribute", $name),
                null,
                true
            );
            return;
        }
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'foreachq';
    }
}