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

}