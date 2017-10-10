<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\ViewFactory;

class Form implements RenderableElementInterface {

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

		if ( ! $element instanceof Element\Form ) {
			throw new InvalidClassException(
				sprintf(
					'The given element "%s" has to implement "%s"',
					$element->get_name(),
					Element\Form::class
				)
			);
		}

		$row = $this->factory->create( FormRow::class );

		$html = array_reduce(
			$element->get_elements(),
			function ( $html, ElementInterface $next ) use ( $element, $row ) {

				$html .= $row->render( $next );

				return $html;
			},
			''
		);

		$errors = $this->factory->create( Errors::class )
			->render( $element );
		$class  = $errors !== '' ? 'form-table--has-errors' : '';

		$html = sprintf( '%1$s <table class="form-table %2$s">%3$s</table>', $errors, $class, $html );

		return sprintf(
			'<form %s>%s</form>',
			$this->get_attributes_as_string( $element->get_attributes() ),
			$html
		);
	}
}