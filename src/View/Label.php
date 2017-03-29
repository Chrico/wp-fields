<?php declare( strict_types=1 );

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\LabelAwareInterface;

class Label implements RenderableElementInterface {

	use AttributeFormatterTrait;

	public function render( ElementInterface $element ): string {

		if ( ! $element instanceof LabelAwareInterface ) {
			throw new InvalidClassException(
				sprintf(
					'The given element "%s" does not implement "%s"',
					$element->get_name(),
					LabelAwareInterface::class
				)
			);
		}

		$attributes = $element->get_label_attributes();
		if ( ! isset ( $attributes[ 'for' ] ) ) {
			$attributes[ 'for' ] = $element->get_id();
		}

		return sprintf(
			'<label %s>%s</label>',
			$this->get_attributes_as_string( $attributes ),
			$element->get_label()
		);
	}
}