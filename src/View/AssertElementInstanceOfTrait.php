<?php

declare(strict_types=1);

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;

trait AssertElementInstanceOfTrait
{
    protected function assertElementIsInstanceOf(ElementInterface $element, string $instanceOf): void
    {
        if (!$element instanceof $instanceOf) {
            throw new InvalidClassException(
                sprintf(
                    'The given element "%s" has to implement "%s"',
                    $element->name(),
                    $instanceOf
                )
            );
        }
    }
}