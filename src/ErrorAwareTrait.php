<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields;

/**
 * Trait ErrorAwareTrait
 *
 * @package ChriCo\Fields
 */
trait ErrorAwareTrait {

	/**
	 * @var array
	 */
	protected $errors = [];

	/**
	 * @return array
	 */
	public function get_errors(): array {

		return array_unique( $this->errors );
	}

	/**
	 * @param array $errors
	 */
	public function set_errors( array $errors = [] ) {

		$this->errors = $errors;
	}

	/**
	 * @return bool
	 */
	public function has_errors(): bool {

		return count( $this->errors ) > 0;
	}

}
