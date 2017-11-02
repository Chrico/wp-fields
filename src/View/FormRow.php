<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\DescriptionAwareInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\ErrorAwareInterface;
use ChriCo\Fields\LabelAwareInterface;
use ChriCo\Fields\ViewFactory;

class FormRow implements RenderableElementInterface {

	use AttributeFormatterTrait;

	/**
	 * @var ViewFactory
	 */
	protected $factory;

	public function __construct( ViewFactory $factory = NULL ) {

		$this->factory = $factory === NULL ? new ViewFactory() : $factory;
	}

	/**
	 * Renders a single FieldRow with label, field and errors.
	 *
	 * @param ElementInterface $element
	 *
	 * @return string
	 */
	public function render( ElementInterface $element ): string {

		$field = $this->factory->create( $element->get_type() )
			->render( $element );

		$errors = $element instanceof ErrorAwareInterface
			? $this->factory->create( Errors::class )
				->render( $element )
			: '';

		$description = $element instanceof DescriptionAwareInterface
			? $this->factory->create( Description::class )
				->render( $element )
			: '';

		$label = $element instanceof LabelAwareInterface
			? $this->factory->create( Label::class )
				->render( $element )
			: '';

		$html = ( $label !== '' )
			? sprintf(
				'<th>%1$s</th><td>%2$s %3$s %4$s</td>',
				$label,
				$field,
				$description,
				$errors
			)
			: sprintf(
				'<td colspan="2">%1$s %2$s %3$s</td>',
				$field,
				$description,
				$errors
			);

		$row_attributes = [ 'class' => 'form-row' . ( $errors === '' ? '' : ' form-row--has-errors' ) ];

		return '<tr ' . $this->get_attributes_as_string( $row_attributes ) . '>' . $html . '</tr>';
	}
}