<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\ChoiceList;

/**
 * Class ArrayChoiceList
 *
 * @package ChriCo\Fields\ChoiceList
 *
 * @psalm-type SelectOption = array{
 *     label: string,
 *     value: string|int,
 *     disabled: boolean,
 * }
 */
class ArrayChoiceList implements ChoiceListInterface
{
    /**
     * @var array<string|int, SelectOption>
     */
    protected array $choices;

    /**
     * @param array $choices
     */
    public function __construct(array $choices = [])
    {
        $this->choices = $this->prepareChoices($choices);
    }

    public function values(): array
    {
        return array_map('strval', array_keys($this->choices()));
    }

    public function choices(): array
    {
        return $this->choices;
    }

    /**
     * Internal function which migrates choices from [ $value => $name ]
     * into the new [ $value => 'value' => $value, 'label' => $name, 'disabled' => false ]
     * structure to allow more configuration and flexibility in future.
     *
     * @param array $choices
     *
     * @return array
     */
    protected function prepareChoices(array $choices): array
    {
        $prepared = [];
        foreach ($choices as $value => $nameOrChoice) {
            if (is_string($nameOrChoice)) {
                $prepared[$value] = [
                    'value' => $value,
                    'label' => trim($nameOrChoice),
                    'disabled' => false,
                ];
            } elseif (is_array($nameOrChoice)) {
                $prepared[$value] = [
                    'value' => $value,
                    'label' => trim($nameOrChoice['label'] ?? ''),
                    'disabled' => (bool) ($nameOrChoice['disabled'] ?? false),
                ];
            }
        }

        return array_filter(
            $prepared,
            static fn(array $choice) => $choice['label'] !== ''
        );
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
