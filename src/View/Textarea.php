<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;

class Textarea extends BaseInput {

	public function render( ElementInterface $element ): string {

		$attributes = $element->get_attributes();
		$attributes = $this->prepare_attributes( $attributes, $element );

		return sprintf(
			'<textarea %s>%s</textarea>',
			$this->get_attributes_as_string( $attributes ),
			$this->esc_html( $element->get_value() )
		);
	}

	public function prepare_attributes( array $attributes, ElementInterface $element, string $context = 'default' ) {
		$attributes = parent::prepare_attributes( $attributes, $element, $context );

		unset( $attributes[ 'type' ], $attributes[ 'value' ] );

		return $attributes;
	}
}