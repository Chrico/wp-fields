<?php
namespace ChriCo\Fields\Tests\Unit;

use Brain\Monkey;
use PHPUnit_Framework_TestCase;

abstract class AbstractTestCase extends PHPUnit_Framework_TestCase {

	/**
	 * Sets up the environment.
	 *
	 * @return void
	 */
	protected function setUp() {

		parent::setUp();
		Monkey::setUpWP();
	}

	/**
	 * Tears down the environment.
	 *
	 * @return void
	 */
	protected function tearDown() {

		Monkey::tearDownWP();
		parent::tearDown();
	}

}