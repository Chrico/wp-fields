<?php declare( strict_types=1 );

namespace ChriCo\Fields;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\Element\ChoiceElementInterface;
use ChriCo\Fields\Element\CollectionElementInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\Exception\MissingAttributeException;
use ChriCo\Fields\Exception\UnknownTypeException;

class ElementFactory extends AbstractFactory {

	/**
	 * @param array $specs
	 *
	 * @return ElementInterface[] $elements
	 */
	public function create_multiple( array $specs = [] ): array {

		return array_map( [ $this, 'create' ], $specs );
	}

	/**
	 * @param array $spec
	 *
	 * @return ElementInterface|LabelAwareInterface|ChoiceElementInterface|CollectionElementInterface $element
	 *
	 */
	public function create( array $spec = [] ) {

		$this->ensure_required_attributes( $spec );

		$type = $spec[ 'attributes' ][ 'type' ];
		$name = $spec[ 'attributes' ][ 'name' ];

		if ( class_exists( $type ) ) {
			$element = new $type( $name );

			if ( ! $element instanceof ElementInterface ) {
				throw new InvalidClassException(
					sprintf(
						'The given type "%s" does not implement %s.',
						$type,
						ElementInterface::class
					)
				);
			}
		} else if ( isset( $this->type_to_element[ $type ] ) ) {
			$element = new $this->type_to_element[ $type ]( $name );
		} else {
			throw new UnknownTypeException(
				sprintf(
					'The given type "%s" will not create a valid class which implements "%s"',
					$type,
					ElementInterface::class
				)
			);
		}

		if ( $element instanceof ChoiceElementInterface ) {
			$element = $this->configure_choice_element( $element, $spec );
		}

		if ( $element instanceof LabelAwareInterface ) {
			$element = $this->configure_label( $element, $spec );
		}

		if ( $element instanceof ErrorAwareInterface ) {
			$element = $this->configure_errors( $element, $spec );
		}

		if ( $element instanceof CollectionElementInterface ) {
			$element = $this->configure_collection( $element, $spec );
		}

		$element = $this->configure_element( $element, $spec );

		return $element;
	}

	/**
	 * Internal function to ensure, that all required attributes are available.
	 *
	 * @param array $spec
	 *
	 * @throws MissingAttributeException
	 */
	protected function ensure_required_attributes( array $spec = [] ) {

		if ( ! isset( $spec[ 'attributes' ] ) ) {
			throw new MissingAttributeException(
				sprintf( 'The attribute "attributes" is not defined, but required.' )
			);
		}

		$required = [ 'type', 'name' ];
		foreach ( $required as $key ) {
			if ( ! isset( $spec[ 'attributes' ][ $key ] ) ) {
				throw new MissingAttributeException(
					sprintf( 'The attribute "%s" is not defined, but required.', $key )
				);
			}
		}
	}

	/**
	 * @param ChoiceElementInterface $element
	 * @param array                  $spec
	 *
	 * @return ChoiceElementInterface $element
	 */
	protected function configure_choice_element( ChoiceElementInterface $element, array $spec = [] ): ChoiceElementInterface {

		if ( ! isset( $spec[ 'choices' ] ) ) {

			return $element;
		}

		if ( is_array( $spec[ 'choices' ] ) ) {
			$element->set_choices( new ArrayChoiceList( $spec[ 'choices' ] ) );
		} else if ( is_callable( $spec[ 'choices' ] ) ) {
			$element->set_choices( new ChoiceList\CallbackChoiceList( $spec[ 'choices' ] ) );
		}

		return $element;
	}

	/**
	 * @param LabelAwareInterface $element
	 * @param array               $spec
	 *
	 * @return LabelAwareInterface $element
	 */
	protected function configure_label( LabelAwareInterface $element, array $spec = [] ): LabelAwareInterface {

		if ( isset( $spec[ 'label' ] ) ) {
			$element->set_label( $spec[ 'label' ] );
		}
		if ( isset( $spec[ 'label_attributes' ] ) && is_array( $spec[ 'label_attributes' ] ) ) {
			$element->set_label_attributes( $spec[ 'label_attributes' ] );
		}

		return $element;
	}

	/**
	 * @param CollectionElementInterface $element
	 * @param array                      $spec
	 *
	 * @return CollectionElementInterface $element
	 */
	protected function configure_collection( CollectionElementInterface $element, array $spec = [] ): CollectionElementInterface {

		if ( isset( $spec[ 'elements' ] ) && is_array( $spec[ 'elements' ] ) ) {
			$element->add_elements( $this->create_multiple( $spec[ 'elements' ] ) );
		}

		return $element;
	}

	/**
	 * @param ErrorAwareInterface $element
	 * @param array               $spec
	 *
	 * @return ErrorAwareInterface $element
	 */
	protected function configure_errors( ErrorAwareInterface $element, array $spec = [] ) {

		if ( isset( $spec[ 'errors' ] ) && is_array( $spec[ 'errors' ] ) ) {
			$element->set_errors( $spec[ 'errors' ] );
		}

		return $element;
	}

	/**
	 * @param ElementInterface $element
	 * @param array            $specs
	 *
	 * @return ElementInterface $element
	 */
	protected function configure_element( ElementInterface $element, array $specs = [] ) {

		$element->set_attributes( $specs[ 'attributes' ] );

		return $element;
	}
}
