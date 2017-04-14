<?php declare( strict_types=1 );

namespace ChriCo\Fields\Element;

use ChriCo\Fields\Exception\ElementNotFoundException;

interface CollectionElementInterface {

	/**
	 * @param ElementInterface $element
	 */
	public function add_element( ElementInterface $element );

	/**
	 * @param ElementInterface[] $elements
	 */
	public function add_elements( array $elements = [] );

	/**
	 * @return ElementInterface[]
	 */
	public function get_elements(): array;

	/**
	 * @param string $name                The name of the element.
	 *
	 * @throws ElementNotFoundException If element is not found in Collection.
	 *
	 * @return ElementInterface $element
	 */
	public function get_element( string $name ): ElementInterface;

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function has_element( string $name ) : bool;
}