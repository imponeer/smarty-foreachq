<?php

use Imponeer\Smarty\Extensions\ForeachQ\ForeachQExtension;
use PHPUnit\Framework\TestCase;
use Smarty\Smarty;
use org\bovigo\vfs\vfsStream;

class ForeachQExtensionTest extends TestCase
{

    private Smarty $smarty;

    /**
     * @noinspection MethodVisibilityInspection
     */
    #[Override]
    protected function setUp(): void
    {
        $vfsRoot = vfsStream::setup('smarty_test');

        vfsStream::create([
            'templates' => [],
            'templates_c' => [],
            'cache' => [],
            'configs' => []
        ], $vfsRoot);

        $this->smarty = new Smarty();
        $this->smarty->setCaching(Smarty::CACHING_OFF);
        $this->smarty->setCacheLifetime(1);
        $this->smarty->clearAllCache();

        $this->smarty->setTemplateDir(vfsStream::url('smarty_test/templates'));
        $this->smarty->setCompileDir(vfsStream::url('smarty_test/templates_c'));
        $this->smarty->setCacheDir(vfsStream::url('smarty_test/cache'));
        $this->smarty->setConfigDir(vfsStream::url('smarty_test/configs'));

        $this->smarty->addExtension(new ForeachQExtension());

        parent::setUp();
    }

    /**
     * @throws \Smarty\Exception
     */
    public function testForeachQInMemory(): void
    {
        $this->smarty->assign('el', null);
        $this->smarty->assign('data', [1, 2, 3]);
        $this->assertEquals(
            '123',
            $this->smarty->fetch('string:{foreachq from=$data item=el}{$el}{/foreachq}')
        );
    }

    /**
     * @throws \Smarty\Exception
     */
    public function testForeachQWithTemplateFile(): void
    {
        $templateContent = '{foreachq from=$items item=item}{$item.name}: {$item.value}{if !$item@last}, {/if}{/foreachq}';
        file_put_contents(vfsStream::url('smarty_test/templates/test_template.tpl'), $templateContent);

        $this->smarty->assign('items', [
            ['name' => 'first', 'value' => 'A'],
            ['name' => 'second', 'value' => 'B'],
            ['name' => 'third', 'value' => 'C']
        ]);

        $result = $this->smarty->fetch('test_template.tpl');
        $this->assertEquals('first: A, second: B, third: C', $result);
    }

    /**
     * @throws \Smarty\Exception
     */
    public function testForeachQWithNestedArrays(): void
    {
        $templateContent = '{foreachq from=$categories item=category}' .
                          'Category: {$category.name}' .
                          '{foreachq from=$category.items item=item} - {$item}{/foreachq}' .
                          '{if !$category@last}|{/if}' .
                          '{/foreachq}';

        file_put_contents(vfsStream::url('smarty_test/templates/nested_template.tpl'), $templateContent);

        $this->smarty->assign('categories', [
            [
                'name' => 'Fruits',
                'items' => ['Apple', 'Banana']
            ],
            [
                'name' => 'Vegetables',
                'items' => ['Carrot', 'Broccoli']
            ]
        ]);

        $result = $this->smarty->fetch('nested_template.tpl');
        $expected = 'Category: Fruits - Apple - Banana|Category: Vegetables - Carrot - Broccoli';
        $this->assertEquals($expected, $result);
    }

}