<?php declare( strict_types=1 );

namespace ChriCo\Fields\Element;

use ChriCo\Fields\ErrorAwareInterface;

class CollectionElement extends Element implements CollectionElementInterface {

	/**
	 * Default attribute type "collection" to assign it to the right view.
	 *
	 * @var array
	 */
	protected $attributes = [
		'type' => 'collection'
	];

	/**
	 * @var ElementInterface[]
	 */
	protected $elements = [];

	public function add_element( ElementInterface $element ) {

		$this->elements[ $element->get_id() ] = $element;
	}

	public function add_elements( array $elements = [] ) {

		array_walk( $elements, [ $this, 'add_element' ] );
	}

	public function get_elements(): array {

		return $this->elements;
	}

	/**
	 * If the key is "value" and the $value an array, we assign all values to the children.
	 *
	 * {@inheritdoc}
	 */
	public function set_attribute( $key, $value ) {

		if ( $key === 'value' && is_array( $value ) ) {
			foreach ( $value as $k => $v ) {
				if ( isset( $this->elements[ $k ] ) ) {
					$this->elements[ $k ]->set_value( $v );
				}
			}
		} else {
			parent::set_attribute( $key, $value );
		}
	}

	/**
	 * Delegate errors down to the children.
	 *
	 * {@inheritdoc}
	 */
	public function set_errors( array $errors = [] ) {

		foreach ( $this->elements as $element ) {
			$id = $element->get_id();
			if ( isset( $errors[ $id ] ) && $element instanceof ErrorAwareInterface ) {
				$element->set_errors( $errors );
				unset( $errors[ $id ] );
			}
		}
	}
}