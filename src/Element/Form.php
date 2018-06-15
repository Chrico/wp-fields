<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

use ChriCo\Fields\DescriptionAwareInterface;
use ChriCo\Fields\ErrorAwareInterface;
use ChriCo\Fields\Exception\ElementNotFoundException;
use ChriCo\Fields\Exception\LogicException;
use ChriCo\Fields\LabelAwareInterface;
use Inpsyde\Filter\FilterInterface;
use Inpsyde\Validator\ErrorLoggerAwareValidatorInterface;
use Inpsyde\Validator\ValidatorInterface;

/**
 * Class Form
 *
 * @package ChriCo\Fields\Element
 */
class Form extends CollectionElement implements
    FormInterface,
    CollectionElementInterface,
    DescriptionAwareInterface,
    LabelAwareInterface,
    ErrorAwareInterface
{

    /**
     * @var array
     */
    protected $attributes = [
        'action' => 'POST',
    ];

    /**
     * @var FilterInterface[][]
     */
    protected $filters = [];

    /**
     * @var ValidatorInterface|ErrorLoggerAwareValidatorInterface[][]
     */
    protected $validators = [];

    /**
     * @var @bool
     */
    protected $isValid = true;

    /**
     * @var bool
     */
    protected $validated = false;

    /**
     * @var bool
     */
    protected $isSubmitted = false;

    /**
     * Contains the raw data assigned by Form::bind_data
     *
     * @var array
     */
    protected $rawData = [];

    /**
     * Contains the filtered data.
     *
     * @var array
     */
    protected $data = [];

    /**
     * @param string $key
     * @param string|array $value
     *
     * @throws LogicException
     *
     * @return Form
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
     * @throws LogicException
     *
     * @param array $data
     *
     * @return Form
     */
    public function withData(array $data = []): Form
    {
        if ($this->isSubmitted) {
            throw new LogicException('You cannot change data of a submitted form.');
        }

        /** @var ElementInterface $element */
        foreach ($this->elements() as $name => $element) {
            $value = $data[$name] ?? '';
            $this->rawData[$name] = $value;
            $value = $this->filter($name, $value);
            $this->data[$name] = $value;
            $element->withValue($value);
        }

        return $this;
    }

    /**
     * Filter a value to a given name.
     *
     * @param string $name
     * @param mixed $value
     *
     * @return mixed $value
     */
    private function filter(string $name, $value)
    {
        if (! isset($this->filters[$name])) {
            return $value;
        }

        return array_reduce(
            $this->filters[$name],
            function ($value, FilterInterface $filter) {
                return $filter->filter($value);
            },
            $value
        );
    }

    /**
     * @param array $inputData
     *
     * @throws ElementNotFoundException
     */
    public function submit(array $inputData = [])
    {
        $this->validated = false;
        $this->isSubmitted = true;
        $this->isValid = true;

        /** @var ElementInterface $element */
        foreach ($this->elements() as $name => $element) {
            if ($element->isDisabled()) {
                continue;
            }
            $value = $inputData[$name] ?? '';
            $this->rawData[$name] = $value;
            $value = $this->filter($name, $value);
            $this->data[$name] = $value;
            $element->withValue($value);

            // only validate elements which are not disabled.
            if (! $this->validate($name, $element->value())) {
                $this->isValid = false;
            }
        }
    }

    /**
     * Internal function to validate data based on the $name.
     *
     * @param string $name
     * @param mixed $value
     *
     * @throws ElementNotFoundException
     *
     * @return bool $is_valid
     */
    private function validate(string $name, $value): bool
    {
        if (! isset($this->validators[$name])) {
            return true;
        }

        $errors = [];

        $isValid = true;
        foreach ($this->validators[$name] as $validator) {
            if (! $validator->is_valid($value)) {
                $errors = array_merge($errors, $validator->get_error_messages());
                $isValid = false;
            }
        }

        $element = $this->element($name);
        if ($element instanceof ErrorAwareInterface) {
            $element->withErrors($errors);
        }

        return $isValid;
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @throws LogicException
     *
     * @return bool
     */
    public function isValid(): bool
    {
        if (! $this->isSubmitted) {
            throw new LogicException(
                'Cannot check if a not submitted form is valid. Call Form::is_submitted() before Form::is_valid().'
            );
        }

        return $this->isValid;
    }

    /**
     * @return bool
     */
    public function isSubmitted(): bool
    {
        return $this->isSubmitted;
    }

    /**
     * @param string $name
     * @param FilterInterface $filter
     *
     * @return Form
     */
    public function withFilter(string $name, FilterInterface $filter): Form
    {
        if (! isset($this->filters[$name])) {
            $this->filters[$name] = [];
        }

        $this->filters[$name][] = $filter;

        return $this;
    }

    /**
     * @param string $name
     * @param ValidatorInterface $validator
     *
     * @return Form
     */
    public function withValidator(string $name, ValidatorInterface $validator): Form
    {
        if (! isset($this->validators[$name])) {
            $this->validators[$name] = [];
        }

        $this->validators[$name][] = $validator;

        return $this;
    }
}
