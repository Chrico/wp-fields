<?php
namespace ChriCo\Fields\Element;

interface ElementInterface {

	/**
	 * Proxy to get the "id" in field attributes.
	 *
	 * @return string
	 */
	public function get_id();

	/**
	 * Proxy to get the "type" in field attributes.
	 *
	 * @return string
	 */
	public function get_type();

	/**
	 * Proxy to get the name in field attributes.
	 *
	 * @return string
	 */
	public function get_name();

	/**
	 * Proxy to get the value in field attributes.
	 *
	 * @return mixed
	 */
	public function get_value();

	/**
	 * Proxy to set the value in field attributes.
	 *
	 * @param string $value
	 */
	public function set_value( $value );

	/**
	 * Get all field attributes for this element.
	 *
	 * @return array
	 */
	public function get_attributes();

	/**
	 * @param array $attributes
	 */
	public function set_attributes( array $attributes = [] );

	/**
	 * @param string     $key
	 * @param int|string $value
	 */
	public function set_attribute( $key, $value );

	/**
	 * @param string $key
	 *
	 * @return int|string $value
	 */
	public function get_attribute( $key );
}