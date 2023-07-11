<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

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
    protected array $errors = [];

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
     * @return static
     */
    public function withErrors(array $errors = []): static
    {
        $this->errors = array_merge($this->errors, $errors);

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
