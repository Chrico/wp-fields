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
     * Contains the raw data assigned by Form::submit()
     */
    protected array $rawData = [];

    /**
     * {@inheritDoc}
     */
    public function withData(array $data = []): static
    {
        $this->assertNotSubmitted(__METHOD__);
        $this->withAttribute('value', $data);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function submit(array $inputData = []): void
    {
        $this->assertNotSubmitted(__METHOD__);

        // By default, the form is valid.
        $this->isValid = true;

        // A disabled form should not change its data upon submission.
        if ($this->isDisabled()) {
            $this->isSubmitted = true;

            return;
        }

        // We also support as fallback that we send an array which contains
        // the Form::name() as entry-node.
        $inputData = $inputData[$this->name()] ?? $inputData;

        foreach ($this->elements() as $name => $element) {
            // only validate elements which are not disabled.
            if ($element->isDisabled()) {
                continue;
            }

            $value = $inputData[$name] ?? '';
            $this->rawData[$name] = $value;

            $element->withValue($value);
            if (!$element->validate()) {
                $this->isValid = false;
            }
        }

        // Submitted state is set on the end after all values are assigned.
        $this->isSubmitted = true;
    }

    /**
     * {@inheritDoc}
     */
    public function data(): array
    {
        return $this->value();
    }

    /**
     * {@inheritDoc}
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
        if ($this->hasErrors()) {
            return false;
        }

        return $this->isValid;
    }

    /**
     * {@inheritDoc}
     */
    public function isSubmitted(): bool
    {
        return $this->isSubmitted;
    }
}
