<?php
namespace ChriCo\Fields\View;

trait AttributeFormatterTrait {

	/**
	 * Formatting a given array into a key="value"-string for each entry.
	 *
	 * @param array $attributes
	 *
	 * @return string $html
	 */
	public function get_attributes_as_string( array $attributes = [] ) {

		$html = [];
		foreach ( $attributes as $key => $value ) {
			if ( is_array( $value ) ) {
				$value = json_encode( $value );
			}
			$html[] = sprintf(
				'%s="%s"',
				$this->esc_attr( $key ),
				$this->esc_attr( $value )
			);
		}

		return implode( ' ', $html );
	}

	/**
	 * Wrapper for WordPress function esc_attr().
	 *
	 * @param string $value
	 *
	 * @return string $value
	 */
	public function esc_attr( $value ) {

		return esc_attr( (string) $value );
	}

	/**
	 * Wrapper for WordPress function esc_html().
	 *
	 * @param string $value
	 *
	 * @return string $value
	 */
	public function esc_html( $value ) {

		return esc_html( (string) $value );
	}
}