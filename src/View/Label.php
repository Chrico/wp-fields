<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ChoiceElementInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\Element\LabelAwareInterface;

/**
 * @package ChriCo\Fields\View
 */
class Label implements RenderableElementInterface
{
    use AttributeFormatterTrait;
    use AssertElementInstanceOfTrait;

    /**
     * @param ElementInterface|LabelAwareInterface $element
     *
     * @return string
     * @throws InvalidClassException
     *
     */
    public function render(ElementInterface $element): string
    {
        $this->assertElementIsInstanceOf($element, LabelAwareInterface::class);

        if ($element->label() === '') {
            return '';
        }

        $labelAttributes = $element->labelAttributes();
        $attributes = $element->attributesForView();
        if (!isset($labelAttributes['for'])) {
            $labelAttributes['for'] = $attributes['id'];
        }
        $labelAttributes = $this->buildCssClasses($labelAttributes, 'label', $element);

        return sprintf(
            '<label %s>%s</label>',
            $this->attributesToString($labelAttributes),
            $element->label()
        );
    }
}
