<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Element\LabelAwareInterface;

/**
 * Class Input
 *
 * @package ChriCo\Fields\View
 */
class Button implements RenderableElementInterface
{
    use AttributeFormatterTrait;
    use AssertElementInstanceOfTrait;

    /**
     * @param ElementInterface|LabelAwareInterface $element
     *
     * @return string
     */
    public function render(ElementInterface $element): string
    {
        // Every button should have a label (text).
        $this->assertElementIsInstanceOf($element, LabelAwareInterface::class);

        $attributes = $element->attributesForView();
        $attributes = $this->buildCssClasses($attributes, 'element', $element);

        return sprintf(
            '<button %1$s>%2$s</button>',
            $this->attributesToString($attributes),
            $element->label(),
        );
    }
}
