<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\ChoiceList\ChoiceListInterface;
use ChriCo\Fields\DescriptionAwareInterface;
use ChriCo\Fields\DescriptionAwareTrait;
use ChriCo\Fields\ErrorAwareInterface;
use ChriCo\Fields\ErrorAwareTrait;
use ChriCo\Fields\LabelAwareInterface;
use ChriCo\Fields\LabelAwareTrait;

/**
 * Class ChoiceElement
 *
 * @package ChriCo\Fields\Element
 */
class ChoiceElement extends Element implements
    ElementInterface,
    ChoiceElementInterface,
    ErrorAwareInterface,
    DescriptionAwareInterface,
    LabelAwareInterface
{

    use ErrorAwareTrait;
    use DescriptionAwareTrait;
    use LabelAwareTrait;

    /**
     * @var ChoiceListInterface
     */
    protected $list;

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
