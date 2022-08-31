<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

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
}
