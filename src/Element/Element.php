<?php
namespace ChriCo\Fields\Element;

use ChriCo\Fields\ErrorAwareInterface;
use ChriCo\Fields\ErrorAwareTrait;
use ChriCo\Fields\LabelAwareInterface;
use ChriCo\Fields\LabelAwareTrait;

class Element implements ElementInterface, LabelAwareInterface, ErrorAwareInterface {

	use LabelAwareTrait;
	use ErrorAwareTrait;

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

	/**
	 * {@inheritdoc}
	 */
	public function get_id() {

		$id = (string) $this->get_attribute( 'id' );
		if ( $id === '' ) {
			$id = (string) $this->get_name();
		}

		return $id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type() {

		return (string) $this->get_attribute( 'type' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_name() {

		return (string) $this->get_attribute( 'name' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_value() {

		return $this->get_attribute( 'value' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_value( $value ) {

		$this->set_attribute( 'value', $value );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_attributes() {

		return $this->attributes;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_attributes( array $attributes = [] ) {

		$this->attributes = array_merge(
			$this->attributes,
			$attributes
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_attribute( $key, $value ) {

		$this->attributes[ $key ] = $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_attribute( $key ) {

		if ( ! isset( $this->attributes[ $key ] ) ) {
			return '';
		}

		return $this->attributes[ $key ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_options() {

		return $this->options;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_options( array $options = [] ) {

		$this->options = array_merge(
			$this->options,
			$options
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_option( $key, $value ) {

		$this->options[ $key ] = $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_option( $key ) {

		if ( ! isset( $this->options[ $key ] ) ) {
			return '';
		}

		return $this->options[ $key ];
	}

}