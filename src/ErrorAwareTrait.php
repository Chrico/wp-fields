<?php declare( strict_types=1 );

namespace ChriCo\Fields;

trait ErrorAwareTrait {

	/**
	 * @var array
	 */
	protected $errors = [];

	/**
	 * Returns a list of error messages.
	 *
	 * @return array
	 */
	public function get_errors(): array {

		return $this->errors;
	}

	/**
	 * Set a list of error messages when validation fails.
	 *
	 * @param array
	 */
	public function set_errors( array $errors = [] ) {

		$this->errors = $errors;
	}

}