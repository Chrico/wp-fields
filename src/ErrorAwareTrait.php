<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields;

/**
 * Trait ErrorAwareTrait
 *
 * @package ChriCo\Fields
 */
trait ErrorAwareTrait
{

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @return array
     */
    public function errors(): array
    {
        return array_unique($this->errors);
    }

    /**
     * @param array $errors
     *
     * @return self
     */
    public function withErrors(array $errors = [])
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }
}
