<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields;

use ChriCo\Fields\Element\ChoiceElement;
use ChriCo\Fields\Element\CollectionElement;
use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Element\Form;

/**
 * Class AbstractFactory
 *
 * @package ChriCo\Fields
 */
abstract class AbstractFactory
{

    /**
     * Map of types to the related View-class.
     *
     * @var View\RenderableElementInterface[]
     */
    protected $typeToView = [
        'form' => View\Form::class,

        'row' => View\FormRow::class,
        'label' => View\Label::class,
        'description' => View\Description::class,
        'errors' => View\Errors::class,

        'radio' => View\Radio::class,
        'select' => View\Select::class,
        'checkbox' => View\Checkbox::class,

        'collection' => View\Collection::class,

        'col' => View\Input::class,
        'color' => View\Input::class,
        'date' => View\Input::class,
        'datetime' => View\Input::class,
        'datetime-local' => View\Input::class,
        'email' => View\Input::class,
        'file' => View\Input::class,
        'hidden' => View\Input::class,
        'image' => View\Input::class,
        'month' => View\Input::class,
        'number' => View\Input::class,
        'password' => View\Input::class,
        'range' => View\Input::class,
        'search' => View\Input::class,
        'submit' => View\Input::class,
        'tel' => View\Input::class,
        'text' => View\Input::class,
        'time' => View\Input::class,
        'url' => View\Input::class,
        'week' => View\Input::class,

        'textarea' => View\Textarea::class,
        'progress' => View\Progress::class,
    ];

    /**
     * Map of types to related Element-class.
     *
     * @var array
     */
    protected $typeToElement = [
        'form' => Form::class,

        'checkbox' => ChoiceElement::class,
        'select' => ChoiceElement::class,
        'radio' => ChoiceElement::class,

        'collection' => CollectionElement::class,

        'col' => Element::class,
        'color' => Element::class,
        'date' => Element::class,
        'datetime' => Element::class,
        'datetime-local' => Element::class,
        'email' => Element::class,
        'file' => Element::class,
        'hidden' => Element::class,
        'image' => Element::class,
        'month' => Element::class,
        'number' => Element::class,
        'password' => Element::class,
        'progress' => Element::class,
        'range' => Element::class,
        'search' => Element::class,
        'submit' => Element::class,
        'tel' => Element::class,
        'text' => Element::class,
        'textarea' => Element::class,
        'time' => Element::class,
        'url' => Element::class,
        'week' => Element::class,
    ];
}
