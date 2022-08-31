<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\ChoiceList\ChoiceListInterface;

/**
 * Class ChoiceElement
 *
 * @package ChriCo\Fields\Element
 */
class ChoiceElement extends Element implements ChoiceElementInterface
{
    protected ?ChoiceListInterface $list = null;

    /**
     * @param ChoiceListInterface $list
     *
     * @return ChoiceElement
     */
    public function withChoices(ChoiceListInterface $list): ChoiceElement
    {
        $this->list = $list;

        return $this;
    }

    /**
     * @return ChoiceListInterface
     */
    public function choices(): ChoiceListInterface
    {
        if ($this->list === null) {
            $this->list = new ArrayChoiceList();
        }

        return $this->list;
    }
}
