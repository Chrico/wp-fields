<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

use ChriCo\Fields\DescriptionAwareInterface;
use ChriCo\Fields\DescriptionAwareTrait;
use ChriCo\Fields\ErrorAwareInterface;
use ChriCo\Fields\ErrorAwareTrait;
use ChriCo\Fields\Exception\ElementNotFoundException;
use ChriCo\Fields\LabelAwareInterface;
use ChriCo\Fields\LabelAwareTrait;

class CollectionElement extends BaseElement implements CollectionElementInterface, DescriptionAwareInterface, LabelAwareInterface, ErrorAwareInterface {

	use DescriptionAwareTrait;
	use ErrorAwareTrait;
	use LabelAwareTrait;

	/**
	 * Contains all errors including the element itself and all children.
	 *
	 * @var array
	 */
	private $all_errors = [];

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

		$this->elements[ $element->get_name() ] = $element;
	}

	public function add_elements( array $elements = [] ) {

		array_walk( $elements, [ $this, 'add_element' ] );
	}

	public function get_elements(): array {

		return $this->elements;
	}

	public function get_element( string $name ): ElementInterface {

		if ( ! isset( $this->elements[ $name ] ) ) {

			throw new ElementNotFoundException(
				sprintf( 'The element with name <code>%s</code> does not exists', $name )
			);
		}

		return $this->elements[ $name ];
	}

	public function has_element( string $name ): bool {

		return isset( $this->elements[ $name ] );
	}

	/**
	 * If the key is "value" and the $value an array, we assign all values to the children.
	 *
	 * {@inheritdoc}
	 */
	public function set_attribute( string $key, $value ) {

		if ( $key === 'value' && is_array( $value ) ) {
			foreach ( $this->elements as $name => $element ) {
				$this->elements[ $name ]->set_value( $value[ $name ] ?? '' );
			}
		} else {
			parent::set_attribute( $key, $value );
		}
	}

	/**
	 * Returns a list of values for each element inside the collection.
	 *
	 * @param string $key
	 *
	 * @return array
	 */
	public function get_attribute( string $key ) {

		if ( $key !== 'value' ) {
			return parent::get_attribute( $key );
		}

		return array_map(
			function ( ElementInterface $element ) {

				return $element->get_value();
			},
			$this->get_elements()
		);
	}

	/**
	 * Delegate errors down to the children.
	 *
	 * {@inheritdoc}
	 */
	public function set_errors( array $errors = [] ) {

		$this->all_errors = $errors;

		array_walk(
			$this->elements,
			function ( ElementInterface $element ) use ( $errors ) {

				$name = $element->get_name();
				if ( isset( $errors[ $name ] ) && $element instanceof ErrorAwareInterface ) {
					$element->set_errors( $errors );
					unset( $errors[ $name ] );
				}
			}
		);

		// assign errors without matches to the collection itself.
		$this->errors = $errors;
	}

	/**
	 * {@inheritdoc}
	 */
	public function has_errors(): bool {

		return count( $this->all_errors ) > 0;
	}
}