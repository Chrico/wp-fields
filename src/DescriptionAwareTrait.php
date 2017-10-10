<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields;

trait DescriptionAwareTrait {

	/**
	 * @var string
	 */
	protected $description = '';

	public function get_description(): string {

		return $this->description;
	}

	public function set_description( string $description ) {

		$this->description = $description;
	}

}