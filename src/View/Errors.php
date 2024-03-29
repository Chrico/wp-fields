<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\View;

use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Element\ErrorAwareInterface;
use ChriCo\Fields\Exception\InvalidClassException;

/**
 * Class Errors
 *
 * @package ChriCo\Fields\View
 */
class Errors implements RenderableElementInterface
{

    use AttributeFormatterTrait;
    use AssertElementInstanceOfTrait;

    const WRAPPER_MARKUP = '<div class="form-errors">%s</div>';
    const ERROR_MARKUP = '<p class="form-errors__entry">%s</p>';

    /**
     * @var array
     */
    private array $options = [
        'wrapper' => self::WRAPPER_MARKUP,
        'error' => self::ERROR_MARKUP,
    ];

    /**
     * Errors constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge(
            $this->options,
            $options
        );
    }

    /**
     * @param ElementInterface|ErrorAwareInterface $element
     *
     * @throws InvalidClassException
     *
     * @return string
     */
    public function render(ElementInterface $element): string
    {
        $this->assertElementIsInstanceOf($element, ErrorAwareInterface::class);

        $errors = $element->errors();
        if (count($errors) < 1) {
            return '';
        }

        $html = array_reduce(
            $errors,
            function ($html, $error): string {
                $html .= sprintf(
                    $this->options['error'],
                    $error
                );

                return $html;
            },
            ''
        );

        return sprintf(
            $this->options['wrapper'],
            $html
        );
    }
}
