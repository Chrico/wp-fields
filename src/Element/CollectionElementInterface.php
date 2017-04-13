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
	 * @param string $id                The ID of the element.
	 *
	 * @throws ElementNotFoundException If element is not found in Collection.
	 *
	 * @return ElementInterface $element
	 */
	public function get_element( string $id ): ElementInterface;

	/**
	 * @param string $id
	 *
	 * @return bool
	 */
	public function has_element( string $id ) : bool;
}