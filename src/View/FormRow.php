<?php declare( strict_types=1 );

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\ErrorAwareInterface;
use ChriCo\Fields\ViewFactory;
use ChriCo\Fields\LabelAwareInterface;

class FormRow implements RenderableElementInterface {

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

		$errors = '';
		if ( $element instanceof ErrorAwareInterface ) {
			$errors = $this->factory->create( Errors::class )
				->render( $element );
		}

		$html = '<tr class="form-row">';
		if ( $element instanceof LabelAwareInterface && $element->get_label() !== NULL ) {
			$label = $this->factory->create( Label::class );
			$html  .= '<th>' . $label->render( $element ) . '</th>';
			$html  .= '<td>' . $field . $errors . '</td>';

		} else {
			$html .= '<td colspan="2">' . $field . $errors . '</td>';
		}
		$html .= '</tr>';

		return $html;
	}
}