<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ChoiceElementInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;

class Checkbox implements RenderableElementInterface {

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

		if ( count( $choices ) < 1 ) {
			return '';
		}

		$is_multi_choice = FALSE;
		if ( count( $choices ) > 1 ) {
			$attributes[ 'name' ] = $element->get_name() . '[]';
			$is_multi_choice      = TRUE;
		}

		foreach ( $choices as $key => $name ) {
			$element_attr = $attributes;

			$element_attr[ 'id' ] = $is_multi_choice
				? $element->get_id() . '_' . $key
				: $element->get_id();

			$element_attr[ 'value' ] = $key;

			$label = sprintf(
				'<label for="%s">%s</label>',
				$this->esc_attr( $element_attr[ 'id' ] ),
				$this->esc_html( $name )
			);

			if ( isset( $selected[ $key ] ) ) {
				$element_attr[ 'checked' ] = 'checked';
			}

			$html[] = sprintf(
				'<p><input %s /> %s</p>',
				$this->get_attributes_as_string( $element_attr ),
				$label
			);
		}

		return implode( '', $html );
	}
}
