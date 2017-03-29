<?php declare( strict_types=1 );

namespace ChriCo\Fields\Element;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\ChoiceList\ChoiceListInterface;

class ChoiceElement extends Element implements ChoiceElementInterface {

	/**
	 * @var ChoiceListInterface
	 */
	protected $list;

	public function set_choices( ChoiceListInterface $list ) {

		$this->list = $list;
	}

	public function get_choices(): ChoiceListInterface {

		if ( $this->list === NULL ) {
			$this->list = new ArrayChoiceList();
		}

		return $this->list;
	}
}