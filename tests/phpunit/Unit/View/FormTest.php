<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\View;

use ChriCo\Fields\View\Form;
use ChriCo\Fields\View\RenderableElementInterface;

class FormTest extends AbstractViewTestCase {

	/**
	 * Basic test to check the default behavior of the class.
	 */
	public function test_basic() {

		$testee = new Form();
		static::assertInstanceOf( RenderableElementInterface::class, $testee );
	}

}