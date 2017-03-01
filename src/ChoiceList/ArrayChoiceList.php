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

		return array_map( 'strval', array_keys( $this->choices ) );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_choices_for_value( array $values = [] ) {

		$choices = [];
		foreach ( $values as $value ) {
			if ( array_key_exists( $value, $this->choices ) ) {
				$choices[ $value ] = $this->choices[ $value ];
			}
		}

		return $choices;
	}

}