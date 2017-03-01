<?php
namespace ChriCo\Fields;

interface ErrorAwareInterface {

	/**
	 * Returns a list of error messages.
	 *
	 * @return array
	 */
	public function get_errors();

	/**
	 * Set a list of error messages when validation fails.
	 *
	 * @param array
	 */
	public function set_errors( array $errors = [] );
}