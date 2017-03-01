<?php
namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;

class Input implements RenderableElementInterface {

	use AttributeFormatterTrait;

	public function render( ElementInterface $element ) {

		$attributes = $element->get_attributes();

		return sprintf(
			'<input %s />',
			$this->get_attributes_as_string( $attributes )
		);
	}
}