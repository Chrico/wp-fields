<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCO\Fields\ChoiceList\ChoiceListInterface;
use ChriCo\Fields\Element\ChoiceElementInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;

/**
 * @package ChriCo\Fields\View
 */
class Select implements RenderableElementInterface
{

    use AttributeFormatterTrait;

    /**
     * @param ElementInterface|ChoiceElementInterface $element
     *
     * @throws InvalidClassException
     *
     * @return string
     */
    public function render(ElementInterface $element): string
    {
        if (! $element instanceof ChoiceElementInterface) {
            throw new InvalidClassException(
                sprintf(
                    'The given element "%s" has to implement "%s"',
                    $element->name(),
                    ChoiceElementInterface::class
                )
            );
        }

        $attributes = $element->attributes();
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
        if (! is_array($currentValue)) {
            $currentValue = [$currentValue];
        }

        $selected = $list->choicesForValue($currentValue);
        $html = [];

        foreach ($list->choices() as $key => $name) {
            $html[] = sprintf(
                '<option %s>%s</option>',
                $this->attributesToString(
                    [
                        'value' => $key,
                        'selected' => isset($selected[$key]),
                    ]
                ),
                $this->escapeHtml($name)
            );
        }

        return implode('', $html);
    }
}
