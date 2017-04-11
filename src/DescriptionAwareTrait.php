<?php declare( strict_types=1 );

namespace ChriCo\Fields;

trait DescriptionAwareTrait {

	/**
	 * @var string
	 */
	protected $description = '';

	/**
	 * @return string $description
	 */
	public function get_description(): string {

		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function set_description( string $description ) {

		$this->description = $description;
	}

}