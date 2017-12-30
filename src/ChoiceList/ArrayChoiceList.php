<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\ChoiceList;

/**
 * Class ArrayChoiceList
 *
 * @package ChriCo\Fields\ChoiceList
 */
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
	public function get_values(): array {

		return array_map( 'strval', array_keys( $this->get_choices() ) );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_choices(): array {

		return $this->choices;
	}

	/**
	 * @param array $values
	 *
	 * @return array
	 */
	public function get_choices_for_value( array $values = [] ): array {

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
