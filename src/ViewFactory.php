<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields;

use ChriCo\Fields\View\RenderableElementInterface;

/**
 * @package ChriCo\Fields
 */
class ViewFactory extends AbstractFactory {

	/**
	 * Find the correct Field-Class by type.
	 *
	 * @param mixed $type
	 *
	 * @throws Exception\UnknownTypeException
	 *
	 * @return RenderableElementInterface $class
	 */
	public function create( $type ): RenderableElementInterface {

		$type   = (string) $type;
		$search = strtolower( $type );

		if ( isset( $this->type_to_view[ $search ] ) ) {
			$class = $this->type_to_view[ $search ];

			return new $class();
		} elseif ( class_exists( $type ) ) {
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
