<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields;

interface LabelAwareInterface {

	/**
	 * @return string $label
	 */
	public function get_label(): string;

	/**
	 * @param string $label
	 */
	public function set_label( string $label );

	/**
	 * @return  array $label_attributes
	 */
	public function get_label_attributes(): array;

	/**
	 * @param array $label_attributes
	 */
	public function set_label_attributes( array $label_attributes = [] );

}
