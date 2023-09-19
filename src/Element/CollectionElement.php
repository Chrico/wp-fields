<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

use ChriCo\Fields\Exception\ElementNotFoundException;

/**
 * Class CollectionElement
 *
 * @package ChriCo\Fields\Element
 */
class CollectionElement extends Element implements CollectionElementInterface
{
    /**
     * Default attribute type "collection" to assign it to the right view.
     */
    protected array $attributes = [
        'type' => 'collection',
    ];

    /**
     * @var ElementInterface[]
     */
    protected array $elements = [];

    /**
     * Contains all errors including the element itself and all children.
     *
     * @var array
     */
    private array $allErrors = [];

    /**
     * {@inheritDoc}
     */
    public function withElement(ElementInterface ...$elements): static
    {
        $this->assertNotSubmitted(__METHOD__);

        array_walk(
            $elements,
            function (ElementInterface $element): void {
                $this->elements[$element->name()] = $element;
                $element->withParent($this);
            }
        );

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function element(string $name): ElementInterface
    {
        if (!isset($this->elements[$name])) {
            throw new ElementNotFoundException(
                sprintf('The element with name <code>%s</code> does not exists', $name)
            );
        }

        return $this->elements[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function elementExists(string $name): bool
    {
        return isset($this->elements[$name]);
    }

    /**
     * If the key is "value" and the $value an array, we assign all values to the children.
     *
     * {@inheritDoc}
     */
    public function withAttribute(string $key, $value): static
    {
        $this->assertNotSubmitted(__METHOD__);

        if ($key === 'value' && is_array($value)) {
            $assignedValues = [];
            foreach ($value as $elementName => $elementValue) {
                if (!$this->elementExists($elementName)) {
                    continue;
                }
                $this->element($elementName)->withValue($elementValue);
                $assignedValues[$elementName] = $elementValue;
            }

            $this->attributes['value'] = $assignedValues;

            return $this;
        }

        parent::withAttribute($key, $value);

        return $this;
    }

    /**
     * Returns a list of values for each element inside the collection.
     *
     * {@inheritDoc}
     */
    public function attribute(string $key)
    {
        if ($key !== 'value') {
            return parent::attribute($key);
        }

        return array_map(
            static fn(ElementInterface $element) => $element->value(),
            $this->elements()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function elements(): array
    {
        return $this->elements;
    }

    /**
     * Delegate errors down to the children.
     *
     * {@inheritDoc}
     */
    public function withErrors(array $errors = []): static
    {
        $this->allErrors = $errors;

        foreach ($this->elements as $name => $element) {
            if (isset($errors[$name]) && $element instanceof ErrorAwareInterface) {
                $element->withErrors((array) $errors[$name]);
                unset($errors[$name]);
            }
        }

        // assign errors without matches to the collection itself.
        $this->errors = $errors;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function hasErrors(): bool
    {
        if (count($this->allErrors) > 0) {
            return true;
        }
        foreach ($this->elements() as $element) {
            if ($element instanceof ErrorAwareInterface && $element->hasErrors()) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function validate(): bool
    {
        $isValid = parent::validate();

        foreach ($this->elements() as $element) {
            if (!$element->validate()) {
                $isValid = false;
            }
        }

        return $isValid;
    }
}
