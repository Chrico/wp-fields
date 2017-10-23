<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

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

	public function get_id(): string {

		return (string) $this->get_attribute( 'id' );
	}

	public function get_name(): string {

		return (string) $this->get_attribute( 'name' );
	}

	public function get_type(): string {

		return (string) $this->get_attribute( 'type' );
	}

	public function get_value() {

		return $this->get_attribute( 'value' );
	}

	public function set_value( $value ) {

		$this->set_attribute( 'value', $value );
	}

	public function get_attributes(): array {

		return $this->attributes;
	}

	public function set_attributes( array $attributes = [] ) {

		$this->attributes = array_merge(
			$this->attributes,
			$attributes
		);
	}

	public function set_attribute( string $key, $value ) {

		$this->attributes[ $key ] = $value;
	}

	public function get_attribute( string $key ) {

		if ( ! isset( $this->attributes[ $key ] ) ) {
			return '';
		}

		return $this->attributes[ $key ];
	}

	public function get_options(): array {

		return $this->options;
	}

	public function set_options( array $options = [] ) {

		$this->options = array_merge(
			$this->options,
			$options
		);
	}

	public function set_option( string $key, $value ) {

		$this->options[ $key ] = $value;
	}

	public function get_option( string $key ) {

		if ( ! isset( $this->options[ $key ] ) ) {
			return '';
		}

		return $this->options[ $key ];
	}

	public function is_disabled(): bool {

		$disabled = $this->get_attribute( 'disabled' );

		return is_bool( $disabled ) && $disabled;
	}
}