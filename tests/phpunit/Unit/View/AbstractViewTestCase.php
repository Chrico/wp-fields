<?php
namespace ChriCo\Fields\Tests\Unit\View;

use Brain\Monkey\Functions;
use ChriCo\Fields\Tests\Unit\AbstractTestCase;

/**
 * We're just setting the esc_attr() and esc_html() functions up to concentrate on writing Tests.
 */
abstract class AbstractViewTestCase extends AbstractTestCase {

	/**
	 * Sets up the environment.
	 *
	 * @return void
	 */
	protected function setUp() {

		parent::setUp();

		Functions::when( 'esc_attr' )
			->returnArg();
		Functions::when( 'esc_html' )
			->returnArg();
	}

}