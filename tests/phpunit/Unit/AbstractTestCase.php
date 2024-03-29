<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit;

use Brain\Monkey;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    /**
     * Sets up the environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();
    }

    /**
     * Tears down the environment.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }
}