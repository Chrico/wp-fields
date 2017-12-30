<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields;

/**
 * Trait LabelAwareTrait
 *
 * @package ChriCo\Fields
 */
trait LabelAwareTrait {

	/**
	 * @var string
	 */
	protected $label = '';

	/**
	 * @var array
	 */
	protected $label_attributes = [];

	/**
	 * @return string
	 */
	public function get_label(): string {

		return (string) $this->label;
	}

	/**
	 * @param string $label
	 */
	public function set_label( string $label ) {

		$this->label = $label;
	}

	/**
	 * @return array
	 */
	public function get_label_attributes(): array {

		return $this->label_attributes;
	}

	/**
	 * @param array $label_attributes
	 */
	public function set_label_attributes( array $label_attributes = [] ) {

		$this->label_attributes = $label_attributes;
	}

}
