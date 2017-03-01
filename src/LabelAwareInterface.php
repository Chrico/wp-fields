<?php
namespace ChriCo\Fields;

interface LabelAwareInterface {

	/**
	 * @return string $label
	 */
	public function get_label();

	/**
	 * @param string $title
	 */
	public function set_label( $title );

	/**
	 * @return  array $label_attributes
	 */
	public function get_label_attributes();

	/**
	 * @param array $label_attributes
	 */
	public function set_label_attributes( array $label_attributes = [] );

}