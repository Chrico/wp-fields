<?php

namespace ChriCo\Fields\Element;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\ChoiceList\ChoiceListInterface;

class ChoiceElement extends Element implements ChoiceElementInterface {

	/**
	 * @var ChoiceListInterface
	 */
	protected $list;

	/**
	 * {@inheritdoc}
	 */
	public function set_choices( ChoiceListInterface $list ) {

		$this->list = $list;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_choices() {

		if ( $this->list === NULL ) {
			$this->list = new ArrayChoiceList();
		}

		return $this->list;
	}
}