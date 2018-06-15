<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

use ChriCo\Fields\DescriptionAwareInterface;
use ChriCo\Fields\DescriptionAwareTrait;
use ChriCo\Fields\ErrorAwareInterface;
use ChriCo\Fields\ErrorAwareTrait;
use ChriCo\Fields\Exception\ElementNotFoundException;
use ChriCo\Fields\LabelAwareInterface;
use ChriCo\Fields\LabelAwareTrait;

/**
 * Class CollectionElement
 *
 * @package ChriCo\Fields\Element
 */
class CollectionElement extends Element implements
    ElementInterface,
    CollectionElementInterface,
    DescriptionAwareInterface,
    LabelAwareInterface,
    ErrorAwareInterface
{

    use ErrorAwareTrait;
    use DescriptionAwareTrait;
    use LabelAwareTrait;

    /**
     * Default attribute type "collection" to assign it to the right view.
     *
     * @var array
     */
    protected $attributes = [
        'type' => 'collection',
    ];

    /**
     * @var ElementInterface[]
     */
    protected $elements = [];

    /**
     * Contains all errors including the element itself and all children.
     *
     * @var array
     */
    private $allErrors = [];

    /**
     * @param ElementInterface[] $elements
     *
     * @return CollectionElement
     */
    public function withElement(ElementInterface ...$elements): CollectionElement
    {
        array_walk(
            $elements,
            function (ElementInterface $element) {
                $this->elements[$element->name()] = $element;
            }
        );

        return $this;
    }

    /**
     * @param string $name
     *
     * @throws ElementNotFoundException
     *
     * @return ElementInterface
     */
    public function element(string $name): ElementInterface
    {
        if (! isset($this->elements[$name])) {
            throw new ElementNotFoundException(
                sprintf('The element with name <code>%s</code> does not exists', $name)
            );
        }

        return $this->elements[$name];
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function elementExists(string $name): bool
    {
        return isset($this->elements[$name]);
    }

    /**
     * If the key is "value" and the $value an array, we assign all values to the children.
     *
     * @param string $key
     * @param bool|int|string $value
     *
     * @return CollectionElement
     */
    public function withAttribute(string $key, $value): ElementInterface
    {
        if ($key === 'value' && is_array($value)) {
            foreach ($this->elements as $name => $element) {
                $this->elements[$name]->withValue($value[$name] ?? '');
            }

            return $this;
        }

        parent::withAttribute($key, $value);

        return $this;
    }

    /**
     * Returns a list of values for each element inside the collection.
     *
     * @param string $key
     *
     * @return array
     */
    public function attribute(string $key)
    {
        if ($key !== 'value') {
            return parent::attribute($key);
        }

        return array_map(
            function (ElementInterface $element) {
                return $element->value();
            },
            $this->elements()
        );
    }

    /**
     * @return array
     */
    public function elements(): array
    {
        return $this->elements;
    }

    /**
     * Delegate errors down to the children.
     *
     * @param array $errors
     *
     * @return CollectionElement
     */
    public function withErrors(array $errors = []): CollectionElement
    {
        $this->allErrors = $errors;

        array_walk(
            $this->elements,
            function (ElementInterface $element) use ($errors) {
                $name = $element->name();
                if (isset($errors[$name]) && $element instanceof ErrorAwareInterface) {
                    $element->withErrors($errors);
                    unset($errors[$name]);
                }
            }
        );

        // assign errors without matches to the collection itself.
        $this->errors = $errors;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return count($this->allErrors) > 0;
    }
}
