<?php
namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ChoiceElementInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;

class Radio implements RenderableElementInterface {

	use AttributeFormatterTrait;

	public function render( ElementInterface $element ) {

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
			$attributes[ 'id' ]    = $element->get_name() . '_' . $key;
			$attributes[ 'value' ] = $key;

			$label = sprintf(
				'<label for="%s">%s</label>',
				$this->esc_attr( $attributes[ 'id' ] ),
				$this->esc_html( $name )
			);

			$html[] = sprintf(
				'<input %s %s /> %s',
				$this->get_attributes_as_string( $attributes ),
				isset( $selected[ $key ] ) ? 'checked="checked"' : '',
				$label
			);
		}

		return implode( ' ', $html );
	}
}
