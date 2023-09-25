<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

use ChriCo\Fields\Exception\LogicException;

/**
 * Class Element
 *
 * @package ChriCo\Fields\Element
 */
class Element implements
    ElementInterface,
    LabelAwareInterface,
    ErrorAwareInterface,
    DescriptionAwareInterface
{
    use ErrorAwareTrait;
    use DescriptionAwareTrait;
    use LabelAwareTrait;

    protected array $attributes = [];

    protected array $options = [];

    /**
     * @var callable|null
     */
    protected $validator = null;

    /**
     * @var callable|null
     */
    protected $filter = null;

    protected ?CollectionElement $parent = null;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->withAttribute('name', $name);
        $this->withAttribute('id', $name);
    }

    /**
     * {@inheritDoc}
     */
    public function withAttribute(string $key, $value): static
    {
        $this->assertNotSubmitted(__METHOD__);
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function attribute(string $key)
    {
        return $this->attributes()[$key] ?? '';
    }

    /**
     * An Element itself can be disabled but also can be disabled through the parent.
     *
     * {@inheritDoc}
     */
    public function isDisabled(): bool
    {
        $disabled = $this->parent()?->isDisabled() ?? $this->attribute('disabled');

        return is_bool($disabled) && $disabled;
    }

    /**
     * The Element itself cannot be submitted. It is always submitted through
     * the parent which is Form::isSubmitted().
     *
     * {@inheritDoc}
     */
    public function isSubmitted(): bool
    {
        return $this->parent()?->isSubmitted() ?? false;
    }

    /**
     * {@inheritDoc}
     */
    public function id(): string
    {
        return (string) $this->attribute('id');
    }

    /**
     * {@inheritDoc}
     */
    public function name(): string
    {
        return (string) $this->attribute('name');
    }

    /**
     * {@inheritDoc}
     */
    public function type(): string
    {
        return (string) $this->attribute('type');
    }

    /**
     * {@inheritDoc}
     */
    public function value()
    {
        $value = $this->attribute('value');

        return $this->filter($value);
    }

    /**
     * {@inheritDoc}
     */
    public function withValue($value): static
    {
        $this->assertNotSubmitted(__METHOD__);
        $this->withAttribute('value', $value);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function attributes(): array
    {
        return $this->attributes;
    }

    /**
     * {@inheritDoc}
     */
    public function attributesForView(): array
    {
        $attributes = $this->attributes;
        if ($this->parent() !== null) {
            $parentAttributes = $this->parent()->attributesForView();
            $id = $parentAttributes['id'];
            $name = $parentAttributes['name'];

            $attributes['disabled'] = $this->isDisabled();
            $attributes['id'] = $id . '_' . $attributes['id'];
            $attributes['name'] = $name . '[' . $attributes['name'] . ']';
        }

        return $attributes;
    }

    /**
     * {@inheritDoc}
     */
    public function withAttributes(array $attributes = []): static
    {
        $this->assertNotSubmitted(__METHOD__);
        foreach ($attributes as $key => $value) {
            $this->withAttribute($key, $value);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function options(): array
    {
        return $this->options;
    }

    /**
     * {@inheritDoc}
     */
    public function withOptions(array $options = []): static
    {
        $this->assertNotSubmitted(__METHOD__);
        foreach ($options as $key => $value) {
            $this->withOption($key, $value);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withOption(string $key, $value): static
    {
        $this->assertNotSubmitted(__METHOD__);
        $this->options[$key] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function option(string $key)
    {
        if (!isset($this->options[$key])) {
            return '';
        }

        return $this->options[$key];
    }

    /**
     * {@inheritDoc}
     */
    public function withFilter(callable $callable): static
    {
        $this->assertNotSubmitted(__METHOD__);
        $this->filter = $callable;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function filter($value)
    {
        if ($this->filter) {
            $value = ($this->filter)($value, $this);
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function withValidator(callable $callable): static
    {
        $this->assertNotSubmitted(__METHOD__);
        $this->validator = $callable;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function validate(): bool
    {
        $value = $this->value();

        $valid = true;
        if ($this->validator) {
            $error = ($this->validator)($value, $this);
            if (is_wp_error($error)) {
                $this->withErrors($error->get_error_messages());
                $valid = false;
            }
        }

        return $valid;
    }

    /**
     * {@inheritDoc}
     */
    public function withParent(CollectionElement $element): static
    {
        $this->parent = $element;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function parent(): ?CollectionElement
    {
        return $this->parent;
    }

    /**
     * Internal helper function for with*()-methods to ensure
     * that data is only set when the Element (and parent) is not yet submitted.
     *
     * @param string $caller
     *
     * @return void
     */
    protected function assertNotSubmitted(string $caller): void
    {
        if ($this->isSubmitted()) {
            throw new LogicException(sprintf('You cannot call %s after submission.', $caller));
        }
    }
}
