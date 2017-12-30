<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

/**
 * Class BaseElement
 *
 * @package ChriCo\Fields\Element
 */
abstract class BaseElement implements ElementInterface {

	/**
	 * @var array
	 */
	protected $attributes = [];

	/**
	 * @var array
	 */
	protected $options = [];

	/**
	 * Element constructor.
	 *
	 * @param string $name
	 */
	public function __construct( string $name ) {

		$this->set_attribute( 'name', $name );
		$this->set_attribute( 'id', $name );
	}

	/**
	 * @param string          $key
	 * @param bool|int|string $value
	 */
	public function set_attribute( string $key, $value ) {

		$this->attributes[ $key ] = $value;
	}

	/**
	 * @return string
	 */
	public function get_id(): string {

		return (string) $this->get_attribute( 'id' );
	}

	/**
	 * @param string $key
	 *
	 * @return bool|int|mixed|string
	 */
	public function get_attribute( string $key ) {

		if ( ! isset( $this->attributes[ $key ] ) ) {
			return '';
		}

		return $this->attributes[ $key ];
	}

	/**
	 * @return string
	 */
	public function get_name(): string {

		return (string) $this->get_attribute( 'name' );
	}

	/**
	 * @return string
	 */
	public function get_type(): string {

		return (string) $this->get_attribute( 'type' );
	}

	/**
	 * @return bool|int|mixed|string
	 */
	public function get_value() {

		return $this->get_attribute( 'value' );
	}

	/**
	 * @param string $value
	 */
	public function set_value( $value ) {

		$this->set_attribute( 'value', $value );
	}

	/**
	 * @return array
	 */
	public function get_attributes(): array {

		return $this->attributes;
	}

	/**
	 * @param array $attributes
	 */
	public function set_attributes( array $attributes = [] ) {

		$this->attributes = array_merge(
			$this->attributes,
			$attributes
		);
	}

	/**
	 * @return array
	 */
	public function get_options(): array {

		return $this->options;
	}

	/**
	 * @param array $options
	 */
	public function set_options( array $options = [] ) {

		$this->options = array_merge(
			$this->options,
			$options
		);
	}

	/**
	 * @param string     $key
	 * @param int|string $value
	 */
	public function set_option( string $key, $value ) {

		$this->options[ $key ] = $value;
	}

	/**
	 * @param string $key
	 *
	 * @return int|mixed|string
	 */
	public function get_option( string $key ) {

		if ( ! isset( $this->options[ $key ] ) ) {
			return '';
		}

		return $this->options[ $key ];
	}

	/**
	 * @return bool
	 */
	public function is_disabled(): bool {

		$disabled = $this->get_attribute( 'disabled' );

		return is_bool( $disabled ) && $disabled;
	}
}
