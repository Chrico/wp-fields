<?php
namespace ChriCo\Fields\Tests\Unit;

use Brain\Monkey;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase {

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