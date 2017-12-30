<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\DescriptionAwareInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;

/**
 * Class Description
 *
 * @package ChriCo\Fields\View
 */
class Description implements RenderableElementInterface {

	use AttributeFormatterTrait;

	/**
	 * @param ElementInterface|DescriptionAwareInterface $element
	 *
	 * @throws InvalidClassException
	 *
	 * @return string
	 */
	public function render( ElementInterface $element ): string {

		if ( ! $element instanceof DescriptionAwareInterface ) {
			throw new InvalidClassException(
				sprintf(
					'The given element "%s" does not implement "%s"',
					$element->get_name(),
					DescriptionAwareInterface::class
				)
			);
		}

		$description = $element->get_description();
		if ( $description === '' ) {

			return '';
		}

		return sprintf(
			'<p %s>%s</p>',
			$this->get_attributes_as_string(
				[
					'class' => 'form-row__description',
				]
			),
			$description
		);
	}
}
