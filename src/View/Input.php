<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;

class Input extends BaseInput {

	public function render( ElementInterface $element ): string {

		$attributes = $element->get_attributes();
		$attributes = $this->prepare_attributes( $attributes, $element );

		return sprintf(
			'<input %s />',
			$this->get_attributes_as_string( $attributes )
		);
	}
}