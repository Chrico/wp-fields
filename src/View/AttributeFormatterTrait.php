<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

trait AttributeFormatterTrait {

	/**
	 * @var array
	 */
	protected $boolean_attributes = [
		'autofocus',
		'checked',
		'disabled',
		'multiple',
		'readonly',
		'required',
		'selected',
	];

	/**
	 * Formatting a given array into a key="value"-string for each entry.
	 *
	 * @param array $attributes
	 *
	 * @return string $html
	 */
	public function get_attributes_as_string( array $attributes = [] ): string {

		$html = [];
		foreach ( $attributes as $key => $value ) {

			if ( is_array( $value ) ) {
				$value = json_encode( $value );
			}

			if ( in_array( $key, $this->boolean_attributes, TRUE ) ) {
				$value = $this->prepare_boolean_attribute( $key, $value );
				if ( $value === '' ) {

					continue;
				}
			}

			$html[] = sprintf(
				'%s="%s"',
				$this->esc_attr( $key ),
				$this->esc_attr( $value )
			);
		}

		return implode( ' ', $html );
	}

	protected function prepare_boolean_attribute( string $key, $value ): string {

		if ( $value === $key || ( is_bool( $value ) && $value ) ) {

			return $key;
		}

		return '';
	}

	/**
	 * Wrapper for WordPress function esc_attr().
	 *
	 * @param string|int $value
	 *
	 * @return string $value
	 */
	public function esc_attr( $value ): string {

		return esc_attr( (string) $value );
	}

	/**
	 * Wrapper for WordPress function esc_html().
	 *
	 * @param string|int $value
	 *
	 * @return string $value
	 */
	public function esc_html( $value ): string {

		return esc_html( (string) $value );
	}
}