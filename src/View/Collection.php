<?php
namespace ChriCo\Fields\View;

use \ChriCo\Fields\Element;
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
	public function render( ElementInterface $element ) {

		if ( ! $element instanceof Element\CollectionElement ) {
			throw new InvalidClassException(
				sprintf(
					'The given element "%s" has to implement "%s"',
					$element->get_name(),
					Element\CollectionElement::class
				)
			);
		}

		$row  = $this->factory->create( FormRow::class );
		$html = '';
		foreach ( $element->get_elements() as $element ) {
			$html .= $row->render( $element );
		}

		return $html;
	}
}