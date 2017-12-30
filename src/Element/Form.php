<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

use ChriCo\Fields\ErrorAwareInterface;
use Inpsyde\Filter\FilterInterface;
use Inpsyde\Validator\ErrorLoggerAwareValidatorInterface;
use Inpsyde\Validator\ValidatorInterface;

/**
 * Class Form
 *
 * @package ChriCo\Fields\Element
 */
class Form extends CollectionElement implements FormInterface {

	/**
	 * @var array
	 */
	protected $attributes = [
		'action' => 'POST',
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

	/**
	 * @param string       $key
	 * @param string|array $value
	 */
	public function set_attribute( string $key, $value ) {

		if ( $key !== 'value' || ! is_array( $value ) ) {
			parent::set_attribute( $key, $value );

			return;
		}

		$this->bind_data( $value );
	}

	/**
	 * @param array $input_data
	 */
	public function bind_data( array $input_data = [] ) {

		$this->has_validated = FALSE;

		/** @var ElementInterface $element */
		foreach ( $this->get_elements() as $name => $element ) {

			if ( ! $element->is_disabled() ) {

				$value = $input_data[ $name ] ?? '';

				$this->raw_data[ $name ] = $value;
				$value                   = $this->filter( $name, $value );
				$this->data[ $name ]     = $value;
				$element->set_value( $value );

			}
		}
	}

	/**
	 * Filter a value to a given name.
	 *
	 * @param string $name
	 * @param mixed  $value
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

	/**
	 * @return array
	 */
	public function get_data(): array {

		return $this->data;
	}

	/**
	 * @param array $input_data
	 */
	public function set_data( array $input_data = [] ) {

		/** @var ElementInterface $element */
		foreach ( $this->get_elements() as $name => $element ) {

			$value = $input_data[ $name ] ?? '';
			$value = $this->filter( $name, $value );

			$element->set_value( $value );
		}
	}

	/**
	 * @return bool
	 */
	public function is_valid(): bool {

		if ( $this->has_validated ) {
			return $this->is_valid;
		}

		$this->is_valid = TRUE;

		/** @var ElementInterface $element */
		foreach ( $this->elements as $name => $element ) {

			// only validate elements which are not disabled.
			if ( ! $element->is_disabled() ) {
				if ( ! $this->validate( $name, $element->get_value() ) ) {
					$this->is_valid = FALSE;
					break;
				}
			}
		}

		$this->has_validated = TRUE;

		return $this->is_valid;
	}

	/**
	 * Internal function to validate data based on the $name.
	 *
	 * @param string $name
	 * @param mixed  $value
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

	/**
	 * @param string          $name
	 * @param FilterInterface $filter
	 */
	public function add_filter( string $name, FilterInterface $filter ) {

		if ( ! isset( $this->filters[ $name ] ) ) {
			$this->filters[ $name ] = [];
		}

		$this->filters[ $name ][] = $filter;
	}

	/**
	 * @param string             $name
	 * @param ValidatorInterface $validator
	 */
	public function add_validator( string $name, ValidatorInterface $validator ) {

		if ( ! isset( $this->validators[ $name ] ) ) {
			$this->validators[ $name ] = [];
		}

		$this->validators[ $name ][] = $validator;
	}

}
