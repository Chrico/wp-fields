<?php

namespace ChriCo\Fields;

use ChriCo\Fields\View\RenderableElementInterface;

class ViewFactory extends AbstractFactory {

	/**
	 * Find the correct Field-Class by type.
	 *
	 * @param       $type
	 *
	 * @throws Exception\UnknownTypeException
	 *
	 * @return View\RenderableElementInterface $class
	 */
	public function create( $type ) {

		$type   = (string) $type;
		$search = strtolower( $type );

		if ( isset( $this->type_to_view[ $search ] ) ) {
			$class = $this->type_to_view[ $search ];

			return new $class();
		} else if ( class_exists( $type ) ) {
			$class = new $type();
			if ( $class instanceof RenderableElementInterface ) {

				return $class;
			}
		}

		throw new Exception\UnknownTypeException(
			sprintf(
				'The given type "%s" is not an instance of "%s".',
				$type,
				RenderableElementInterface::class
			)
		);
	}
}