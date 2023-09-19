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
     * {@inheritDoc}
     */
    public function withChoices(ChoiceListInterface $list): static
    {
        $this->assertNotSubmitted(__METHOD__);
        $this->list = $list;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function choices(): ChoiceListInterface
    {
        if ($this->list === null) {
            $this->list = new ArrayChoiceList();
        }

        return $this->list;
    }
}
