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

		$this->elements[ $element->get_id() ] = $element;
	}

	public function add_elements( array $elements = [] ) {

		array_walk( $elements, [ $this, 'add_element' ] );
	}

	public function get_elements(): array {

		return $this->elements;
	}
}