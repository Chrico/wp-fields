<?php

namespace ChriCo\Fields\ChoiceList;

class CallbackChoiceList extends ArrayChoiceList {

	/**
	 * @var bool
	 */
	protected $is_loaded = FALSE;

	/**
	 * @var callable
	 */
	protected $callback;

	/**
	 * ArrayChoiceList constructor.
	 *
	 * @param $callback $callback
	 */
	public function __construct( callable $callback ) {

		$this->callback  = $callback;
		$this->is_loaded = FALSE;
		$this->choices   = [];

		parent::__construct( [] );
	}

	/**
	 * @return bool
	 */
	public function is_loaded() {

		return $this->is_loaded;
	}

	/**
	 * Internal function to ensure, that the choices are loaded the right time.
	 *
	 * @return bool
	 */
	private function maybe_load_choices() {

		if ( ! $this->is_loaded() ) {
			$this->choices   = call_user_func( $this->callback );
			$this->is_loaded = TRUE;

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_choices() {

		$this->maybe_load_choices();

		return parent::get_choices();
	}
}