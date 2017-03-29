<?php declare( strict_types=1 );

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

	public function add_element( ElementInterface $element ) {

		$name = $element->get_name();

		// adding the name of the collection to the id.
		$id = $element->get_id();
		$id = $id === ''
			? $this->get_name() . '_' . $name
			: $this->get_name() . '_' . $id;

		$element->set_attribute( 'id', $id );

		// adding the CollectionElement name to the Element name as prefix.
		$element->set_attribute( 'name', $this->get_name() . '[' . $name . ']' );

		$this->elements[ $id ] = $element;
	}

	public function add_elements( array $elements = [] ) {

		array_walk( $elements, [ $this, 'add_element' ] );
	}

	public function get_elements(): array {

		return $this->elements;
	}
}