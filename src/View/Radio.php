<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ChoiceElementInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;

class Radio implements RenderableElementInterface {

	use AttributeFormatterTrait;

	public function render( ElementInterface $element ): string {

		if ( ! $element instanceof ChoiceElementInterface ) {
			throw new InvalidClassException(
				sprintf(
					'The given element "%s" has to implement "%s"',
					$element->get_name(),
					ChoiceElementInterface::class
				)
			);
		}
		$list       = $element->get_choices();
		$attributes = $element->get_attributes();
		$choices    = $list->get_choices();
		$selected   = $list->get_choices_for_value( (array) $element->get_value() );

		$html = [];

		foreach ( $choices as $key => $name ) {

			$element_attr              = $attributes;
			$element_attr[ 'id' ]      .= '_' . $key;
			$element_attr[ 'value' ]   = $key;
			$element_attr[ 'checked' ] = isset( $selected[ $key ] );

			$label = sprintf(
				'<label for="%s">%s</label>',
				$this->esc_attr( $element_attr[ 'id' ] ),
				$this->esc_html( $name )
			);

			$html[] = sprintf(
				'<p><input %s /> %s</p>',
				$this->get_attributes_as_string( $element_attr ),
				$label
			);
		}

		return implode( ' ', $html );
	}
}
