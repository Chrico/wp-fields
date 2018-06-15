<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

use Inpsyde\Filter\FilterInterface;
use Inpsyde\Validator\ValidatorInterface;

/**
 * Interface FormInterface
 *
 * @package ChriCo\Fields\Element
 */
interface FormInterface
{

    /**
     * Submits data to the form, filter and validates it.
     *
     * @param array $inputData
     */
    public function submit(array $inputData = []);

    /**
     * @return bool
     */
    public function isSubmitted(): bool;

    /**
     * Set data without re-validating and filtering it.
     *
     * @param array $data
     */
    public function withData(array $data = []);

    /**
     * Returns the assigned data.
     *
     * @return array
     */
    public function data(): array;

    /**
     * @return bool
     */
    public function isValid(): bool;

    /**
     * Add a filter by passing an $name which matches to an Element.
     *
     * @param string $name
     * @param FilterInterface $filter
     */
    public function withFilter(string $name, FilterInterface $filter);

    /**
     * Add a validator by passing an $name which matches to an Element.
     *
     * @param string $name
     * @param ValidatorInterface $validator
     *
     * @return self
     */
    public function withValidator(string $name, ValidatorInterface $validator);
}
