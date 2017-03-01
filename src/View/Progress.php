<?php
namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;

class Progress implements RenderableElementInterface {

	use AttributeFormatterTrait;

	public function render( ElementInterface $element ) {

		$attributes = $element->get_attributes();

		return sprintf(
			'<progress %s>%s</progress>',
			$this->get_attributes_as_string( $attributes ),
			$this->esc_html( $element->get_value() )
		);
	}
}