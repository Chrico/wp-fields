<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Element\FormInterface;
use ChriCo\Fields\ErrorAwareInterface;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\Exception\UnknownTypeException;
use ChriCo\Fields\ViewFactory;

/**
 * Class Form
 *
 * @package ChriCo\Fields\View
 */
class Form implements RenderableElementInterface
{

    use AttributeFormatterTrait;

    /**
     * @var ViewFactory
     */
    protected $factory;

    /**
     * Form constructor.
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
     * @param FormInterface|ElementInterface $element
     *
     * @throws InvalidClassException|UnknownTypeException
     *
     * @return string
     */
    public function render($element): string
    {
        if (! $element instanceof ElementInterface) {
            throw new InvalidClassException(
                sprintf(
                    'The given element has to implement "%s"',
                    ElementInterface::class
                )
            );
        }
        if (! $element instanceof FormInterface) {
            throw new InvalidClassException(
                sprintf(
                    'The given element "%s" has to implement "%s"',
                    $element->name(),
                    FormInterface::class
                )
            );
        }

        $row = $this->factory->create(FormRow::class);

        $html = array_reduce(
            // TODO Need to make sure $element implements CollectionElementInterface
            $element->elements(),
            function ($html, ElementInterface $next) use ($row) {
                return $html .= $row->render($next);
            },
            ''
        );

        $errors = $element instanceof ErrorAwareInterface
            ? $this->factory->create(Errors::class)
                ->render($element)
            : '';

        $class = $errors !== ''
            ? 'form-table--has-errors'
            : '';

        $html = sprintf('%1$s <table class="form-table %2$s">%3$s</table>', $errors, $class, $html);

        return sprintf(
            '<form %s>%s</form>',
            $this->attributesToString($element->attributes()),
            $html
        );
    }
}
