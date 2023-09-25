<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\DescriptionAwareInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Element\ErrorAwareInterface;
use ChriCo\Fields\Element\LabelAwareInterface;
use ChriCo\Fields\ViewFactory;

/**
 * Class FormRow
 *
 * @package ChriCo\Fields\View
 */
class FormRow implements RenderableElementInterface
{
    use AttributeFormatterTrait;

    protected ViewFactory $factory;

    /**
     * FormRow constructor.
     *
     * @param ViewFactory|NULL $factory
     */
    public function __construct(ViewFactory $factory = null)
    {
        $this->factory = $factory === null
            ? new ViewFactory()
            : $factory;
    }

    /**
     * Renders a single FieldRow with label, field and errors.
     *
     * @param ElementInterface $element
     *
     * @return string
     * @throws \ChriCo\Fields\Exception\UnknownTypeException
     */
    public function render(ElementInterface $element): string
    {
        $field = $this->factory->create($element->type())
            ->render($element);

        $errors = $element instanceof ErrorAwareInterface
            ? $this->factory->create(Errors::class)
                ->render($element)
            : '';

        $description = $element instanceof DescriptionAwareInterface
            ? $this->factory->create(Description::class)
                ->render($element)
            : '';

        $label = $element instanceof LabelAwareInterface
        && !in_array(
            $element->type(),
            ['submit', 'button', 'reset'],
            true
        )
            ? $this->factory->create(Label::class)
                ->render($element)
            : '';

        $html = ($label !== '')
            ? sprintf(
                '%1$s %2$s %3$s %4$s',
                $label,
                $field,
                $description,
                $errors
            )
            : sprintf(
                '%1$s %2$s %3$s',
                $field,
                $description,
                $errors
            );

        $rowAttributes = $this->buildCssClasses([], 'row', $element);;
        if ($errors !== '') {
            $rowAttributes['class'] .= ' form-row--has-errors';
        }
        if ($element->isDisabled()) {
            $rowAttributes['class'] .= 'form-row--is-disabled';
        }
        if ($element->type() === 'hidden') {
            $rowAttributes['class'] .= ' hidden';
        }

        return sprintf(
            '<div %s>%s</div>',
            $this->attributesToString($rowAttributes),
            $html
        );
    }
}
