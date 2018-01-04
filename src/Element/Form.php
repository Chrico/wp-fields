<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

use ChriCo\Fields\ErrorAwareInterface;
use ChriCo\Fields\Exception\LogicException;
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
	protected $is_valid = TRUE;

	/**
	 * @var bool
	 */
	protected $validated = FALSE;

	/**
	 * @var bool
	 */
	protected $is_submitted = FALSE;

	/**
	 * Contains the raw data assigned by Form::bind_data
	 *
	 * @var array
	 */
	protected $raw_data = [];

	/**
	 * Contains the filtered data.
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * @param string       $key
	 * @param string|array $value
	 */
	public function set_attribute( string $key, $value ) {

		if ( $key === 'value' && is_array( $value ) ) {

			$this->set_data( $value );
		}

		parent::set_attribute( $key, $value );
	}

	/**
	 * @param array $input_data
	 */
	public function submit( array $input_data = [] ) {

		$this->validated    = FALSE;
		$this->is_submitted = TRUE;
		$this->is_valid     = TRUE;

		/** @var ElementInterface $element */
		foreach ( $this->get_elements() as $name => $element ) {

			if ( ! $element->is_disabled() ) {

				$value                   = $input_data[ $name ] ?? '';
				$this->raw_data[ $name ] = $value;
				$value                   = $this->filter( $name, $value );
				$this->data[ $name ]     = $value;
				$element->set_value( $value );

				// only validate elements which are not disabled.
				if ( ! $element->is_disabled() ) {
					if ( ! $this->validate( $name, $element->get_value() ) ) {
						$this->is_valid = FALSE;
					}
				}
			}
		}
	}

	/**
	 * @param array $input_data
	 *
	 * @deprecated see Form::submit( $input_data ). This method will be removed in future
	 */
	public function bind_data( array $input_data = [] ) {

		$this->submit( $input_data );
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
	 * @throws LogicException
	 *
	 * @param array $input_data
	 */
	public function set_data( array $input_data = [] ) {

		if ( $this->is_submitted ) {
			throw new LogicException( 'You cannot change data of a submitted form.' );
		}

		/** @var ElementInterface $element */
		foreach ( $this->get_elements() as $name => $element ) {

			$value                   = $input_data[ $name ] ?? '';
			$this->raw_data[ $name ] = $value;
			$value                   = $this->filter( $name, $value );
			$this->data[ $name ]     = $value;
			$element->set_value( $value );
		}
	}

	/**
	 * @throws LogicException
	 *
	 * @return bool
	 */
	public function is_valid(): bool {

		if ( ! $this->is_submitted ) {
			throw new LogicException(
				'Cannot check if a not submitted form is valid. Call Form::is_submitted() before Form::is_valid().'
			);
		}

		return $this->is_valid;
	}

	/**
	 * @return bool
	 */
	public function is_submitted(): bool {

		return $this->is_submitted;
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
