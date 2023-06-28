<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

use ChriCo\Fields\Exception\ElementNotFoundException;
use ChriCo\Fields\Exception\LogicException;

/**
 * Class Form
 *
 * @package ChriCo\Fields\Element
 */
class Form extends CollectionElement implements FormInterface
{
    protected array $attributes = [
        'method' => 'POST',
    ];

    protected bool $isValid = true;

    protected bool $isSubmitted = false;

    /**
     * Contains the raw data assigned by Form::bind_data
     */
    protected array $rawData = [];

    /**
     * Contains the filtered data.
     */
    protected array $data = [];

    /**
     * @param string $key
     * @param string|array $value
     *
     * @return ElementInterface
     * @throws LogicException
     *
     */
    public function withAttribute(string $key, $value): ElementInterface
    {
        if ($key === 'value' && is_array($value)) {
            $this->withData($value);
        }

        parent::withAttribute($key, $value);

        return $this;
    }

    /**
     * @param array $data
     *
     * @return FormInterface
     * @throws LogicException
     *
     */
    public function withData(array $data = []): FormInterface
    {
        if ($this->isSubmitted) {
            throw new LogicException('You cannot change data of a submitted form.');
        }

        foreach ($this->elements() as $name => $element) {
            $value = $data[$name] ?? '';
            $this->rawData[$name] = $value;

            $element->withValue($value);
            $this->data[$name] = $element->value();
        }

        return $this;
    }

    /**
     * @param array $inputData
     *
     * @throws ElementNotFoundException
     */
    public function submit(array $inputData = [])
    {
        $this->isSubmitted = true;
        $this->isValid = true;

        foreach ($this->elements() as $name => $element) {
            // only validate elements which are not disabled.
            if ($element->isDisabled()) {
                continue;
            }

            $value = $inputData[$name] ?? '';
            $this->rawData[$name] = $value;

            $element->withValue($value);
            $this->data[$name] = $element->value();

            if (!$element->validate()) {
                $this->isValid = false;
            }
        }
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @return bool
     * @throws LogicException
     *
     */
    public function isValid(): bool
    {
        if (!$this->isSubmitted) {
            throw new LogicException(
                'You need to call Form::submit() first, before checking Form::isValid().'
            );
        }

        // Either on "submit" we have erroneous Elements, or the
        // Form itself has errors assigned through Form::withErrors().
        return $this->hasErrors() || $this->isValid;
    }

    /**
     * @return bool
     */
    public function isSubmitted(): bool
    {
        return $this->isSubmitted;
    }
}
