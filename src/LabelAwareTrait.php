<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields;

trait LabelAwareTrait {

	/**
	 * @var string
	 */
	protected $label = '';

	/**
	 * @var array
	 */
	protected $label_attributes = [];

	public function get_label(): string {

		return (string) $this->label;
	}

	public function set_label( $label ) {

		$this->label = $label;
	}

	public function get_label_attributes(): array {

		return $this->label_attributes;
	}

	public function set_label_attributes( array $label_attributes = [] ) {

		$this->label_attributes = $label_attributes;
	}

}