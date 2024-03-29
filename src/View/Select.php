<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\ChoiceList\ChoiceListInterface;
use ChriCo\Fields\Element\ChoiceElementInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;

/**
 * @package ChriCo\Fields\View
 */
class Select implements RenderableElementInterface
{
    use AttributeFormatterTrait;
    use AssertElementInstanceOfTrait;

    /**
     * @param ElementInterface|ChoiceElementInterface $element
     *
     * @return string
     * @throws InvalidClassException
     *
     */
    public function render(ElementInterface $element): string
    {
        $this->assertElementIsInstanceOf($element, ChoiceElementInterface::class);

        $attributes = $element->attributesForView();
        $attributes = $this->buildCssClasses($attributes, 'element', $element);
        unset($attributes['type'], $attributes['value']);

        return sprintf(
            '<select %s>%s</select>',
            $this->attributesToString($attributes),
            $this->renderChoices($element->choices(), $element->value())
        );
    }

    /**
     * @param ChoiceListInterface $list
     * @param mixed $currentValue
     *
     * @return string $html
     */
    protected function renderChoices(ChoiceListInterface $list, $currentValue): string
    {
        if (!is_array($currentValue)) {
            $currentValue = [$currentValue];
        }

        $selected = $list->choicesForValue($currentValue);
        $html = [];

        foreach ($list->choices() as $key => $choice) {
            $html[] = sprintf(
                '<option %s>%s</option>',
                $this->attributesToString(
                    [
                        'value' => $key,
                        'selected' => isset($selected[$key]),
                        'disabled' => $choice['disabled'],
                    ]
                ),
                $this->escapeHtml($choice['label'])
            );
        }

        return implode('', $html);
    }
}
