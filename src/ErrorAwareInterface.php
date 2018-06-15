<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields;

interface ErrorAwareInterface
{

    /**
     * Returns a list of error messages.
     *
     * @return array
     */
    public function errors(): array;

    /**
     * Set a list of error messages when validation fails.
     *
     * @param array $errors
     */
    public function withErrors(array $errors = []);

    /**
     * @return bool
     */
    public function hasErrors(): bool;
}
