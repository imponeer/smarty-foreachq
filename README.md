[![License](https://img.shields.io/github/license/imponeer/smarty-foreachq.svg)](LICENSE)
[![GitHub release](https://img.shields.io/github/release/imponeer/smarty-foreachq.svg)](https://github.com/imponeer/smarty-foreachq/releases) [![Maintainability](https://api.codeclimate.com/v1/badges/79f89e2fe21c0076c29a/maintainability)](https://codeclimate.com/github/imponeer/smarty-foreachq/maintainability) [![PHP](https://img.shields.io/packagist/php-v/imponeer/smarty-foreachq.svg)](http://php.net) 
[![Packagist](https://img.shields.io/packagist/dm/imponeer/smarty-foreachq.svg)](https://packagist.org/packages/imponeer/smarty-foreachq)

# Smarty ForeachQ

Rewritten (due that original use GPLv2+ license) [Smarty](https://smarty.net) foreach variant that was invented for use in [XOOPS](https://xoops.org), but nowadays used in some other PHP based CMS'es (like [ImpressCMS](https://impresscms.org)!).

See, [original version of this smarty plugin in Xoops](https://github.com/XOOPS/XoopsCore25/blob/v2.5.8/htdocs/class/smarty/xoops_plugins/compiler.foreachq.php) to see more accurate description why this plugin exists.

## Installation

To install and use this package, we recommend to use [Composer](https://getcomposer.org):

```bash
composer require imponeer/smarty-foreachq
```

Otherwise, you need to include manually files from `src/` directory. 

## Registering in Smarty

If you want to use these extensions from this package in your project you need register them with [`registerPlugin` function](https://www.smarty.net/docs/en/api.register.plugin.tpl) from [Smarty](https://www.smarty.net). For example:
```php
$smarty = new \Smarty();
$foreachqOpenPlugin = new \Imponeer\Smarty\Extensions\ForeachQ\ForeachQOpenTagCompiler();
$foreachqClosePlugin = new \Imponeer\Smarty\Extensions\ForeachQ\ForeachQCloseTagCompiler();
$smarty->registerPlugin('compiler', $foreachqOpenPlugin->getName(), [$foreachqOpenPlugin, 'execute']);
$smarty->registerPlugin('compiler', $foreachqClosePlugin->getName(), [$foreachqClosePlugin, 'execute']);
```

## Using from templates

Example how to use it:
```smarty
  {foreachq from=$data item=el}
    {$el}
  {/foreachq}
```
## How to contribute?

If you want to add some functionality or fix bugs, you can fork, change and create pull request. If you not sure how this works, try [interactive GitHub tutorial](https://try.github.io).

If you found any bug or have some questions, use [issues tab](https://github.com/imponeer/smarty-foreachq/issues) and write there your questions.