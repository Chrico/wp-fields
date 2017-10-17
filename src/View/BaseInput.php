<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;

abstract class BaseInput implements RenderableElementInterface {

	use AttributeFormatterTrait;

	/**
	 * Prepare attributes for output on the input.
	 *
	 * @param array            $attributes
	 * @param ElementInterface $element
	 * @param string           $context
	 *
	 * @return array $prepared_attributes
	 */
	public function prepare_attributes( array $attributes, ElementInterface $element, string $context = 'default' ) {
		if ( ! isset ( $attributes[ 'id' ] ) ) {
			$attributes[ 'id' ] = $element->get_id();
		}

		return $attributes;
	}
}
