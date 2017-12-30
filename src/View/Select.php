<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCO\Fields\ChoiceList\ChoiceListInterface;
use ChriCo\Fields\Element\ChoiceElementInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;

/**
 * @package ChriCo\Fields\View
 */
class Select implements RenderableElementInterface {

	use AttributeFormatterTrait;

	/**
	 * @param ElementInterface|ChoiceElementInterface $element
	 *
	 * @throws InvalidClassException
	 *
	 * @return string
	 */
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

		$attributes = $element->get_attributes();
		unset( $attributes[ 'type' ], $attributes[ 'value' ] );

		return sprintf(
			'<select %s>%s</select>',
			$this->get_attributes_as_string( $attributes ),
			$this->render_choices( $element->get_choices(), $element->get_value() )
		);
	}

	/**
	 * @param ChoiceListInterface $list
	 * @param mixed               $current_value
	 *
	 * @return string $html
	 */
	protected function render_choices( ChoiceListInterface $list, $current_value ): string {

		if ( ! is_array( $current_value ) ) {
			$current_value = [ $current_value ];
		}

		$selected = $list->get_choices_for_value( $current_value );
		$html     = [];

		foreach ( $list->get_choices() as $key => $name ) {

			$html[] = sprintf(
				'<option %s>%s</option>',
				$this->get_attributes_as_string(
					[
						'value'    => $key,
						'selected' => isset( $selected[ $key ] ),
					]
				),
				$this->esc_html( $name )
			);
		}

		return implode( '', $html );
	}
}
