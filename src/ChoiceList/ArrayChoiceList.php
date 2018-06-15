<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\ChoiceList;

/**
 * Class ArrayChoiceList
 *
 * @package ChriCo\Fields\ChoiceList
 */
class ArrayChoiceList implements ChoiceListInterface
{

    /**
     * @var array
     */
    protected $choices;

    /**
     * ArrayChoiceList constructor.
     *
     * @param array $choices
     */
    public function __construct(array $choices = [])
    {
        $this->choices = $choices;
    }

    /**
     * {@inheritdoc}
     */
    public function values(): array
    {
        return array_map('strval', array_keys($this->choices()));
    }

    /**
     * {@inheritdoc}
     */
    public function choices(): array
    {
        return $this->choices;
    }

    /**
     * @param array $values
     *
     * @return array
     */
    public function choicesForValue(array $values = []): array
    {
        $choices = $this->choices();
        $selected = [];
        foreach ($values as $value) {
            if (array_key_exists($value, $this->choices)) {
                $selected[$value] = $choices[$value];
            }
        }

        return $selected;
    }
}
