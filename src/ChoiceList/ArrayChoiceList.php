<?php

namespace ChriCo\Fields\ChoiceList;

class ArrayChoiceList implements ChoiceListInterface {

	/**
	 * @var array
	 */
	protected $choices;

	/**
	 * ArrayChoiceList constructor.
	 *
	 * @param array $choices
	 */
	public function __construct( array $choices = [] ) {

		$this->choices = $choices;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_choices() {

		return $this->choices;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_values() {

		return array_map( 'strval', array_keys( $this->get_choices() ) );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_choices_for_value( array $values = [] ) {

		$choices  = $this->get_choices();
		$selected = [];
		foreach ( $values as $value ) {
			if ( array_key_exists( $value, $this->choices ) ) {
				$selected[ $value ] = $choices[ $value ];
			}
		}

		return $selected;
	}

}