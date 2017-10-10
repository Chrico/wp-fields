<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Element;

use ChriCo\Fields\DescriptionAwareInterface;
use ChriCo\Fields\DescriptionAwareTrait;
use ChriCo\Fields\ErrorAwareInterface;
use ChriCo\Fields\ErrorAwareTrait;
use ChriCo\Fields\LabelAwareInterface;
use ChriCo\Fields\LabelAwareTrait;

class Element extends BaseElement implements LabelAwareInterface, ErrorAwareInterface, DescriptionAwareInterface {

	use LabelAwareTrait;
	use ErrorAwareTrait;
	use DescriptionAwareTrait;

}