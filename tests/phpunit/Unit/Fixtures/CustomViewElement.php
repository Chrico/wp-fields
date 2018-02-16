<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\Fixtures;

use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\View\RenderableElementInterface;

class CustomViewElement implements RenderableElementInterface
{

    /**
     * @param ElementInterface $element
     *
     * @return string $html
     */
    public function render(ElementInterface $element): string
    {

        return '';
    }
}