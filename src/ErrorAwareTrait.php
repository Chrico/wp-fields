<?php
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
	public function get_errors() {

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