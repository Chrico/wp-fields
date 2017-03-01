<?php
namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\ErrorAwareInterface;
use ChriCo\Fields\Exception\InvalidClassException;

class Errors implements RenderableElementInterface {

	use AttributeFormatterTrait;

	const WRAPPER_MARKUP = '<div class="form-errors">%s</div>';
	const ERROR_MARKUP = '<p class="form-errors__entry">%s</p>';

	/**
	 * @var array
	 */
	private $options = [
		'wrapper' => self::WRAPPER_MARKUP,
		'error'   => self::ERROR_MARKUP,
	];

	/**
	 * Errors constructor.
	 *
	 * @param array $options
	 */
	public function __construct( array $options = [] ) {

		$this->options = array_merge(
			$this->options,
			$options
		);
	}

	public function render( ElementInterface $element ) {

		if ( ! $element instanceof ErrorAwareInterface ) {
			throw new InvalidClassException(
				sprintf(
					'The given element "%s" does not implement "%s"',
					$element->get_name(),
					ErrorAwareInterface::class
				)
			);
		}

		$errors = $element->get_errors();
		if ( count( $errors ) < 1 ) {
			return '';
		}

		$html = [];
		foreach ( $errors as $error ) {
			$html[] = sprintf(
				$this->options[ 'error' ],
				$this->esc_html( $error )
			);
		}

		return sprintf(
			$this->options[ 'wrapper' ],
			implode( ' ', $html )
		);
	}
}