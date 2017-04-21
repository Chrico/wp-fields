<?php declare( strict_types=1 );

namespace ChriCo\Fields\Element;

use Inpsyde\Filter\FilterInterface;
use Inpsyde\Validator\ValidatorInterface;

interface FormInterface {

	/**
	 * @param array $input_data
	 */
	public function bind_data( array $input_data = [] );

	/**
	 * Set data without re-validating and filtering it.
	 */
	public function set_data();

	/**
	 * Returns the assigned data.
	 *
	 * @return array
	 */
	public function get_data(): array;

	/**
	 * @return bool
	 */
	public function is_valid(): bool;

	/**
	 * Add a filter by passing an $name which matches to an Element.
	 *
	 * @param string          $name
	 * @param FilterInterface $filter
	 *
	 * @return mixed
	 */
	public function add_filter( string $name, FilterInterface $filter );

	/**
	 * Add a validator by passing an $name which matches to an Element.
	 *
	 * @param string             $name
	 * @param ValidatorInterface $validator
	 */
	public function add_validator( string $name, ValidatorInterface $validator );

}