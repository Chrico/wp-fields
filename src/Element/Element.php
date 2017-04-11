<?php declare( strict_types=1 );

namespace ChriCo\Fields\Element;

use ChriCo\Fields\DescriptionAwareInterface;
use ChriCo\Fields\DescriptionAwareTrait;
use ChriCo\Fields\ErrorAwareInterface;
use ChriCo\Fields\ErrorAwareTrait;
use ChriCo\Fields\LabelAwareInterface;
use ChriCo\Fields\LabelAwareTrait;

class Element implements ElementInterface, LabelAwareInterface, ErrorAwareInterface, DescriptionAwareInterface {

	use LabelAwareTrait;
	use ErrorAwareTrait;
	use DescriptionAwareTrait;

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
	public function __construct( $name ) {

		$this->set_attribute( 'name', (string) $name );
	}

	public function get_id(): string {

		$id = (string) $this->get_attribute( 'id' );
		if ( $id === '' ) {
			$id = (string) $this->get_name();
		}

		return $id;
	}

	public function get_type(): string {

		return (string) $this->get_attribute( 'type' );
	}

	public function get_name(): string {

		return (string) $this->get_attribute( 'name' );
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

	public function set_attribute( $key, $value ) {

		$this->attributes[ $key ] = $value;
	}

	public function get_attribute( $key ) {

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

	public function set_option( $key, $value ) {

		$this->options[ $key ] = $value;
	}

	public function get_option( $key ) {

		if ( ! isset( $this->options[ $key ] ) ) {
			return '';
		}

		return $this->options[ $key ];
	}

}