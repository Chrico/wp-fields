<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Extension\Pimple;

use ChriCo\Fields\ElementFactory;
use ChriCo\Fields\ViewFactory;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * @package ChriCo\Fields\Extension\Pimple
 */
class Provider implements ServiceProviderInterface {

	/**
	 * {@inheritdoc}
	 */
	public function register( Container $plugin ) {

		$plugin[ 'ChriCo.Fields.ViewFactory' ] = function (): ViewFactory {

			return new ViewFactory();
		};

		$plugin[ 'ChriCo.Fields.ElementFactory' ] = function (): ElementFactory {

			return new ElementFactory();
		};
	}
}