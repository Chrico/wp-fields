<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;

class Textarea implements RenderableElementInterface {

	use AttributeFormatterTrait;

	public function render( ElementInterface $element ): string {

		$attributes = $element->get_attributes();
		unset( $attributes[ 'value' ] );

		return sprintf(
			'<textarea %s>%s</textarea>',
			$this->get_attributes_as_string( $attributes ),
			$this->esc_html( $element->get_value() )
		);
	}
}