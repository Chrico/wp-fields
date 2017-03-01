<?php
namespace ChriCo\Fields\Element;

use ChriCo\Fields\ChoiceList\ChoiceListInterface;

interface ChoiceElementInterface {

	/**
	 * @param ChoiceListInterface $list
	 */
	public function set_choices( ChoiceListInterface $list );

	/**
	 * @return ChoiceListInterface $list
	 */
	public function get_choices();
}