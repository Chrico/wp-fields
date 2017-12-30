<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

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
class ChoiceElement extends BaseElement implements ChoiceElementInterface, ErrorAwareInterface, DescriptionAwareInterface, LabelAwareInterface {

	use DescriptionAwareTrait;
	use ErrorAwareTrait;
	use LabelAwareTrait;

	/**
	 * @var ChoiceListInterface
	 */
	protected $list;

	/**
	 * @param ChoiceListInterface $list
	 */
	public function set_choices( ChoiceListInterface $list ) {

		$this->list = $list;
	}

	/**
	 * @return ChoiceListInterface
	 */
	public function get_choices(): ChoiceListInterface {

		if ( $this->list === NULL ) {
			$this->list = new ArrayChoiceList();
		}

		return $this->list;
	}
}
