<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

use ChriCo\Fields\ErrorAwareInterface;
use Inpsyde\Filter\FilterInterface;
use Inpsyde\Validator\ErrorLoggerAwareValidatorInterface;
use Inpsyde\Validator\ValidatorInterface;

class Form extends CollectionElement implements FormInterface {

	protected $attributes = [
		'action' => 'POST'
	];

	/**
	 * @var FilterInterface[][]
	 */
	protected $filters = [];

	/**
	 * @var ValidatorInterface|ErrorLoggerAwareValidatorInterface[][]
	 */
	protected $validators = [];

	/**
	 * @var @bool
	 */
	private $is_valid = TRUE;

	/**
	 * @var bool
	 */
	private $has_validated = FALSE;

	/**
	 * Contains the raw data assigned by Form::bind_data
	 *
	 * @var array
	 */
	private $raw_data = [];

	/**
	 * Contains the filtered data.
	 *
	 * @var array
	 */
	private $data = [];

	public function set_attribute( string $key, $value ) {

		if ( $key !== 'value' || ! is_array( $value ) ) {
			parent::set_attribute( $key, $value );

			return;
		}

		$this->bind_data( $value );
	}

	public function get_data(): array {

		return $this->data;
	}

	public function set_data( array $input_data = [] ) {

		foreach ( $input_data as $name => $value ) {
			if ( ! $this->has_element( $name ) ) {
				continue;
			}
			$element = $this->get_element( $name );
			if ( $element->is_disabled() ) {
				continue;
			}
			$element->set_value( $value );
		}
	}

	public function bind_data( array $input_data = [] ) {

		$this->has_validated = FALSE;

		foreach ( $input_data as $name => $value ) {

			if ( ! $this->has_element( $name ) ) {
				continue;
			}

			$element = $this->get_element( $name );

			if ( $element->is_disabled() ) {
				continue;
			}

			$this->raw_data[ $name ] = $value;
			$value                   = $this->filter( $name, $value );
			$this->data[ $name ]     = $value;
			$element->set_value( $value );
		}
	}

	/**
	 * Filter a value to a given name.
	 *
	 * @param string $name
	 * @param        $value
	 *
	 * @return mixed $value
	 */
	private function filter( string $name, $value ) {

		if ( ! isset( $this->filters[ $name ] ) ) {
			return $value;
		}

		return array_reduce(
			$this->filters[ $name ],
			function ( $value, FilterInterface $filter ) {

				return $filter->filter( $value );
			},
			$value
		);
	}

	public function is_valid(): bool {

		if ( $this->has_validated ) {
			return $this->is_valid;
		}

		$this->is_valid = TRUE;

		foreach ( $this->data as $name => $data ) {

			// only validate data where an element exists
			// ... or ...
			// the element is not disabled.
			if ( ! $this->has_element( $name )
				|| $this->get_element( $name )
					->is_disabled() ) {
				continue;
			}

			if ( ! $this->validate( $name, $data ) ) {
				$this->is_valid = FALSE;
				break;
			}
		}

		$this->has_validated = TRUE;

		return $this->is_valid;
	}

	/**
	 * Internal function to validate data based on the $name.
	 *
	 * @param string $name
	 * @param        $value
	 *
	 * @return bool $is_valid
	 */
	private function validate( string $name, $value ): bool {

		if ( ! isset( $this->validators[ $name ] ) ) {
			return TRUE;
		}

		$errors = [];

		$is_valid = TRUE;
		foreach ( $this->validators[ $name ] as $validator ) {
			if ( ! $validator->is_valid( $value ) ) {
				$errors   = array_merge( $errors, $validator->get_error_messages() );
				$is_valid = FALSE;
			}
		}

		$element = $this->get_element( $name );
		if ( $element instanceof ErrorAwareInterface ) {
			$element->set_errors( $errors );
		}

		return $is_valid;
	}

	public function add_filter( string $name, FilterInterface $filter ) {

		if ( ! isset( $this->filters[ $name ] ) ) {
			$this->filters[ $name ] = [];
		}

		$this->filters[ $name ][] = $filter;
	}

	public function add_validator( string $name, ValidatorInterface $validator ) {

		if ( ! isset( $this->validators[ $name ] ) ) {
			$this->validators[ $name ] = [];
		}

		$this->validators[ $name ][] = $validator;
	}

}