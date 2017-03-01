<?php

namespace ChriCo\Fields\ChoiceList;

interface ChoiceListInterface {

	/**
	 * Returns the list of choices.
	 *
	 * @return array
	 */
	public function get_choices();

	/**
	 * Returns a unique list of all values for the choices.
	 *
	 * @return string[]
	 */
	public function get_values();

	/**
	 * Returns the selected choices for the given values.
	 *
	 * @param array $values
	 *
	 * @return array
	 */
	public function get_choices_for_value( array $values = [] );

}