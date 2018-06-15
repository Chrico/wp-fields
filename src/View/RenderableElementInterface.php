<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;

/**
 * Interface ElementRendererInterface
 *
 * @package ChriCo\Fields\View
 */
interface RenderableElementInterface
{

    /**
     * @param ElementInterface $element
     *
     * @return string $html
     */
    public function render(ElementInterface $element): string;
}
