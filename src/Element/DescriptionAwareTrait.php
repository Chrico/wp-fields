<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

trait DescriptionAwareTrait
{

    /**
     * @var string
     */
    protected string $description = '';

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return static
     */
    public function withDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
