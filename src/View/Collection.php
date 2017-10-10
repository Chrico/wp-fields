<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\ViewFactory;

class Collection implements RenderableElementInterface {

	use AttributeFormatterTrait;

	/**
	 * @var ViewFactory
	 */
	protected $factory;

	public function __construct( ViewFactory $factory = NULL ) {

		$this->factory = $factory === NULL ? new ViewFactory() : $factory;
	}

	/**
	 * {@inheritdoc}
	 */
	public function render( ElementInterface $element ): string {

		if ( ! $element instanceof Element\CollectionElement ) {
			throw new InvalidClassException(
				sprintf(
					'The given element "%s" has to implement "%s"',
					$element->get_name(),
					Element\CollectionElement::class
				)
			);
		}

		$row = $this->factory->create( FormRow::class );

		$html = array_reduce(
			$element->get_elements(),
			function ( $html, ElementInterface $next ) use ( $element, $row ) {

				// adding the CollectionElement name to the Element name and ID as prefix.
				$next->set_attribute( 'id', $element->get_name() . '_' . $next->get_id() );
				$next->set_attribute( 'name', $element->get_name() . '[' . $next->get_name() . ']' );

				$html .= $row->render( $next );

				return $html;
			},
			''
		);

		$errors = $this->factory->create( Errors::class )
			->render( $element );
		$class  = $errors !== '' ? 'form-table--has-errors' : '';

		return sprintf( '%1$s<table class="form-table %2$s">%3$s</table>', $errors, $class, $html );
	}
}