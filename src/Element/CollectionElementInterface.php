<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

use ChriCo\Fields\Exception\ElementNotFoundException;

/**
 * Interface CollectionElementInterface
 *
 * @package ChriCo\Fields\Element
 */
interface CollectionElementInterface
{

    /**
     * @param ElementInterface[] $element
     */
    public function withElement(ElementInterface ...$element);

    /**
     * @return ElementInterface[]
     */
    public function elements(): array;

    /**
     * @param string $name The name of the element.
     *
     * @throws ElementNotFoundException If element is not found in Collection.
     *
     * @return ElementInterface $element
     */
    public function element(string $name): ElementInterface;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function elementExists(string $name): bool;
}
