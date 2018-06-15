<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\ChoiceList;

/**
 * Interface ChoiceListInterface
 *
 * @package ChriCo\Fields\ChoiceList
 */
interface ChoiceListInterface
{

    /**
     * Returns the list of choices.
     *
     * @return array
     */
    public function choices(): array;

    /**
     * Returns a unique list of all values for the choices.
     *
     * @return string[]
     */
    public function values(): array;

    /**
     * Returns the selected choices for the given values.
     *
     * @param array $values
     *
     * @return array
     */
    public function choicesForValue(array $values = []): array;
}
