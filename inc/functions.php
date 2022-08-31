<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields;

use ChriCo\Fields\Element\ElementInterface;

/**
 * Helper to render an Element.
 *
 * @param ElementInterface $element
 *
 * @return string
 * @throws Exception\UnknownTypeException
 */
function renderElement(ElementInterface $element): string
{
    return ViewFactory::create($element->type())->render($element);
}

/**
 * Helper to create an Element.
 *
 * @param array $spec
 *
 * @return ElementInterface
 * @throws Exception\InvalidClassException
 * @throws Exception\MissingAttributeException
 * @throws Exception\UnknownTypeException
 */
function createElement(array $spec): ElementInterface
{
    return ElementFactory::create($spec);
}
