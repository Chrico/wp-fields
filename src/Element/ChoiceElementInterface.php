<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

use ChriCo\Fields\ChoiceList\ChoiceListInterface;

/**
 * Interface ChoiceElementInterface
 *
 * @package ChriCo\Fields\Element
 */
interface ChoiceElementInterface
{

    /**
     * @param ChoiceListInterface $list
     */
    public function withChoices(ChoiceListInterface $list);

    /**
     * @return ChoiceListInterface $list
     */
    public function choices(): ChoiceListInterface;
}
