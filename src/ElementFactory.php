<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\Element as Element;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\Exception\MissingAttributeException;
use ChriCo\Fields\Exception\UnknownTypeException;

/**
 * Class ElementFactory
 *
 * @package ChriCo\Fields
 */
class ElementFactory extends AbstractFactory
{
    /**
     * @param array $spec
     *
     * @return Element\ElementInterface $element
     * @throws UnknownTypeException|MissingAttributeException
     *
     * @throws InvalidClassException
     */
    public static function create(array $spec = [])
    {
        static::ensureRequiredAttributes($spec);

        $type = $spec['attributes']['type'];
        $name = $spec['attributes']['name'];

        $element = static::buildElement($type, $name);

        if ($element instanceof Element\ChoiceElementInterface) {
            $element = static::configureChoiceElement($element, $spec);
        }

        if ($element instanceof Element\LabelAwareInterface) {
            $element = static::configureLabel($element, $spec);
        }

        if ($element instanceof Element\ErrorAwareInterface) {
            $element = static::configureErrors($element, $spec);
        }

        if ($element instanceof Element\CollectionElementInterface) {
            $element = static::configureCollection($element, $spec);
        }

        if ($element instanceof Element\DescriptionAwareInterface) {
            $element = static::configureDescription($element, $spec);
        }

        return static::configureElement($element, $spec);
    }

    /**
     * @param string $type
     * @param string $name
     *
     * @return Element\ElementInterface
     * @throws InvalidClassException
     * @throws UnknownTypeException
     */
    private static function buildElement(string $type, string $name): Element\ElementInterface
    {
        if (class_exists($type)) {
            $element = new $type($name);

            if (!$element instanceof Element\ElementInterface) {
                throw new InvalidClassException(
                    sprintf(
                        'The given type "%s" does not implement %s.',
                        $type,
                        Element\ElementInterface::class
                    )
                );
            }

            return $element;
        }

        if (isset(self::$typeToElement[$type])) {
            return new self::$typeToElement[$type]($name);
        }

        throw new UnknownTypeException(
            sprintf(
                'The given type "%s" will not create a valid class which implements "%s"',
                $type,
                Element\ElementInterface::class
            )
        );
    }

    /**
     * Internal function to ensure, that all required attributes are available.
     *
     * @param array $spec
     *
     * @throws MissingAttributeException
     */
    protected static function ensureRequiredAttributes(array $spec = [])
    {
        if (!isset($spec['attributes'])) {
            throw new MissingAttributeException(
                sprintf('The attribute "attributes" is not defined, but required.')
            );
        }

        $required = ['type', 'name'];
        foreach ($required as $key) {
            if (!isset($spec['attributes'][$key])) {
                throw new MissingAttributeException(
                    sprintf('The attribute "%s" is not defined, but required.', $key)
                );
            }
        }
    }

    /**
     * @param Element\ChoiceElementInterface $element
     * @param array $spec
     *
     * @return Element\ChoiceElementInterface $element
     */
    protected static function configureChoiceElement(
        Element\ChoiceElementInterface $element,
        array $spec = []
    ): Element\ChoiceElementInterface {
        if (!isset($spec['choices'])) {
            return $element;
        }

        if (is_array($spec['choices'])) {
            return $element->withChoices(new ArrayChoiceList($spec['choices']));
        }

        if (is_callable($spec['choices'])) {
            return $element->withChoices(new ChoiceList\CallbackChoiceList($spec['choices']));
        }

        return $element;
    }

    /**
     * @param Element\LabelAwareInterface $element
     * @param array $spec
     *
     * @return Element\LabelAwareInterface $element
     */
    protected static function configureLabel(
        Element\LabelAwareInterface $element,
        array $spec = []
    ): Element\LabelAwareInterface {
        if (isset($spec['label'])) {
            $element->withLabel($spec['label']);
        }
        if (isset($spec['label_attributes']) && is_array($spec['label_attributes'])) {
            $element->withLabelAttributes($spec['label_attributes']);
        }

        return $element;
    }

    /**
     * @param Element\ErrorAwareInterface $element
     * @param array $spec
     *
     * @return Element\ErrorAwareInterface $element
     */
    protected static function configureErrors(Element\ErrorAwareInterface $element, array $spec = [])
    {
        if (isset($spec['errors']) && is_array($spec['errors'])) {
            $element->withErrors($spec['errors']);
        }

        return $element;
    }

    /**
     * @param Element\CollectionElementInterface $element
     * @param array $spec
     *
     * @return Element\CollectionElementInterface $element
     */
    protected static function configureCollection(
        Element\CollectionElementInterface $element,
        array $spec = []
    ): Element\CollectionElementInterface {
        if (isset($spec['elements']) && is_array($spec['elements'])) {
            $element->withElement(...static::createMultiple($spec['elements']));
        }

        return $element;
    }

    /**
     * @param array $specs
     *
     * @return Element\ElementInterface[] $elements
     */
    public static function createMultiple(array $specs = []): array
    {
        return array_map([__CLASS__, 'create'], $specs);
    }

    /**
     * @param Element\DescriptionAwareInterface $element
     * @param array $spec
     *
     * @return Element\DescriptionAwareInterface $element
     */
    protected static function configureDescription(
        Element\DescriptionAwareInterface $element,
        array $spec = []
    ): Element\DescriptionAwareInterface {
        if (isset($spec['description'])) {
            $element->withDescription($spec['description']);
        }

        return $element;
    }

    /**
     * @param Element\ElementInterface $element
     * @param array $specs
     *
     * @return Element\ElementInterface $element
     */
    protected static function configureElement(Element\ElementInterface $element, array $specs = [])
    {
        $element->withAttributes($specs['attributes']);

        if (!empty($specs['options'])) {
            $element->withOptions($specs['options']);
        }

        if (!empty($specs['validator']) && is_callable($specs['validator'])) {
            $element->withValidator($specs['validator']);
        }
        if (!empty($specs['filter']) && is_callable($specs['filter'])) {
            $element->withFilter($specs['filter']);
        }

        return $element;
    }
}
