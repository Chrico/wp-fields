<?php

namespace ChriCo\Fields\Tests\Unit\Fixtures;

use ChriCo\Fields\Element\ElementInterface;

class ElementWithoutLabelAwareInterface implements ElementInterface {

	/**
	 * Proxy to get the "id" in field attributes.
	 *
	 * @return string
	 */
	public function get_id(): string {

		return '';
	}

	/**
	 * Proxy to get the "type" in field attributes.
	 *
	 * @return string
	 */
	public function get_type(): string {

		return '';
	}

	/**
	 * Proxy to get the name in field attributes.
	 *
	 * @return string
	 */
	public function get_name(): string {

		return '';
	}

	/**
	 * Proxy to get the value in field attributes.
	 *
	 * @return mixed
	 */
	public function get_value() {

		return '';
	}

	/**
	 * Proxy to set the value in field attributes.
	 *
	 * @param string $value
	 */
	public function set_value( $value ) {
		// TODO: Implement set_value() method.
	}

	/**
	 * Get all field attributes for this element.
	 *
	 * @return array
	 */
	public function get_attributes(): array {

		return [];
	}

	/**
	 * @param array $attributes
	 */
	public function set_attributes( array $attributes = [] ) {
		// TODO: Implement set_attributes() method.
	}

	/**
	 * @param string     $key
	 * @param int|string $value
	 */
	public function set_attribute( $key, $value ) {
		// TODO: Implement set_attribute() method.
	}

	/**
	 * @param string $key
	 *
	 * @return int|string $value
	 */
	public function get_attribute( $key ) {

		return '';
	}

	/**
	 * Get all field options for this element.
	 *
	 * @return array
	 */
	public function get_options(): array {

		return [];
	}

	/**
	 * Set specific options which can be used in e.G. JavaScript.
	 *
	 * @param array $options
	 */
	public function set_options( array $options = [] ) {
		// TODO: Implement set_options() method.
	}

	/**
	 * @param string     $key
	 * @param int|string $value
	 */
	public function set_option( $key, $value ) {
		// TODO: Implement set_option() method.
	}

	/**
	 * @param string $key
	 *
	 * @return int|string $value
	 */
	public function get_option( $key ) {
		// TODO: Implement get_option() method.
	}
}