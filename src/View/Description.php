<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\DescriptionAwareInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;

/**
 * Class Description
 *
 * @package ChriCo\Fields\View
 */
class Description implements RenderableElementInterface
{

    use AttributeFormatterTrait;

    /**
     * @param DescriptionAwareInterface|ElementInterface $element
     *
     * @throws InvalidClassException
     *
     * @return string
     */
    public function render($element): string
    {
        if (! $element instanceof ElementInterface) {
            throw new InvalidClassException(
                sprintf(
                    'The given element does not implement "%s"',
                    ElementInterface::class
                )
            );
        }
        if (! $element instanceof DescriptionAwareInterface) {
            throw new InvalidClassException(
                sprintf(
                    'The given element "%s" does not implement "%s"',
                    $element->name(),
                    DescriptionAwareInterface::class
                )
            );
        }

        $description = $element->description();
        if ($description === '') {
            return '';
        }

        return sprintf(
            '<p %s>%s</p>',
            $this->attributesToString(
                [
                    'class' => 'form-row__description',
                ]
            ),
            $description
        );
    }
}
