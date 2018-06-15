<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields;

trait DescriptionAwareTrait
{

    /**
     * @var string
     */
    protected $description = '';

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
     * @return self
     */
    public function withDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }
}
