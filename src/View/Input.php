<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;

class Input implements RenderableElementInterface {

	use AttributeFormatterTrait;

	public function render( ElementInterface $element ): string {

		$attributes = $element->get_attributes();
		if ( ! isset ( $attributes[ 'id' ] ) ) {
			$attributes[ 'id' ] = $element->get_id();
		}

		return sprintf(
			'<input %s />',
			$this->get_attributes_as_string( $attributes )
		);
	}
}