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
     * Pre-assign data (values) to all Elements when the Form is not submitted.
     * This method is a shorthand to Form::withValue($data) or Form::withAttribute('value', $data);
     *
     * @param array $data
     *
     * @deprecated ElementInterface::setValue()
     */
    public function withData(array $data = []);

    /**
     * Returns the assigned data.
     *
     * @return array
     *
     * @deprecated ElementInterface::value()
     */
    public function data(): array;

    /**
     * @return bool
     */
    public function isValid(): bool;
}
