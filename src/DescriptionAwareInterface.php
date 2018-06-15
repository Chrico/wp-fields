<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields;

interface DescriptionAwareInterface
{

    /**
     * @return string $description
     */
    public function description(): string;

    /**
     * @param string $description
     */
    public function withDescription(string $description);
}
