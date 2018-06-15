<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Extension\Pimple;

use ChriCo\Fields\ElementFactory;
use ChriCo\Fields\ViewFactory;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * @package ChriCo\Fields\Extension\Pimple
 */
class Provider implements ServiceProviderInterface
{

    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container['ChriCo.Fields.ViewFactory'] = function (): ViewFactory {
            return new ViewFactory();
        };

        $container['ChriCo.Fields.ElementFactory'] = function (): ElementFactory {
            return new ElementFactory();
        };
    }
}
