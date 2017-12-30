<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;

/**
 * @package ChriCo\Fields\View
 */
class Progress implements RenderableElementInterface {

	use AttributeFormatterTrait;

	/**
	 * @param ElementInterface $element
	 *
	 * @return string
	 */
	public function render( ElementInterface $element ): string {

		$attributes = $element->get_attributes();

		return sprintf(
			'<progress %s>%s</progress>',
			$this->get_attributes_as_string( $attributes ),
			$this->esc_html( $element->get_value() )
		);
	}
}
