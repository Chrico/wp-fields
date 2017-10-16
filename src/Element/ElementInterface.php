<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

interface ElementInterface {

	/**
	 * Proxy to get the "id" in field attributes.
	 *
	 * @return string
	 */
	public function get_id(): string;

	/**
	 * Proxy to get the "type" in field attributes.
	 *
	 * @return string
	 */
	public function get_type(): string;

	/**
	 * Proxy to get the name in field attributes.
	 *
	 * @return string
	 */
	public function get_name(): string;

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
	public function get_attributes(): array;

	/**
	 * @param array $attributes
	 */
	public function set_attributes( array $attributes = [] );

	/**
	 * @param string          $key
	 * @param int|string|bool $value
	 */
	public function set_attribute( string $key, $value );

	/**
	 * @param string $key
	 *
	 * @return int|string|bool $value
	 */
	public function get_attribute( string $key );

	/**
	 * Get all field options for this element.
	 *
	 * @return array
	 */
	public function get_options(): array;

	/**
	 * Set specific options which can be used in e.G. JavaScript.
	 *
	 * @param array $options
	 */
	public function set_options( array $options = [] );

	/**
	 * @param string     $key
	 * @param int|string $value
	 */
	public function set_option( string $key, $value );

	/**
	 * @param string $key
	 *
	 * @return int|string $value
	 */
	public function get_option( string $key );
}