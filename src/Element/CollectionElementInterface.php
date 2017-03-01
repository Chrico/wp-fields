<?php

namespace ChriCo\Fields\Element;

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
	public function get_elements();
}