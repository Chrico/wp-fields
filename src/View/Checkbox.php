<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ChoiceElementInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;

class Checkbox extends BaseInput {

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
			$is_multi_choice = TRUE;
		}

		foreach ( $choices as $key => $name ) {
			$context = $is_multi_choice ? $key : 'default';

			$element_attr = $this->prepare_attributes( $attributes, $element, $context );

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

	public function prepare_attributes( array $attributes, ElementInterface $element, string $context = 'default' ) {
		$attributes = parent::prepare_attributes( $attributes, $element, $context );

		if ( 'default' !== $context ) {
			$attributes[ 'id' ]   .= '_' . $context;
			$attributes[ 'name' ] .= '[]';
		}

		return $attributes;
	}
}
