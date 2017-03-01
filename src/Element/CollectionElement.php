<?php

namespace ChriCo\Fields\Element;

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

	/**
	 * {@inheritdoc}
	 */
	public function add_element( ElementInterface $element ) {

		$name = $element->get_name();

		// setting the name to element.
		$element->set_attribute( 'name', $this->get_name() . '[' . $name . ']' );

		// adding the name of the collection to the id.
		$id = $element->get_id();
		$id = $id === ''
			? $this->get_name() . '_' . $name
			: $this->get_name() . '_' . $id;

		$element->set_attribute( 'id', $id );

		$this->elements[ $id ] = $element;
	}

	/**
	 * {@inheritdoc}
	 */
	public function add_elements( array $elements = [] ) {

		array_walk( $elements, [ $this, 'add_element' ] );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_elements() {

		return $this->elements;
	}
}