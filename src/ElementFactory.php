<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\Element\ChoiceElementInterface;
use ChriCo\Fields\Element\CollectionElementInterface;
use ChriCo\Fields\Element\ElementInterface;
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
     * @throws InvalidClassException
     * @throws UnknownTypeException|MissingAttributeException
     *
     * @return ElementInterface|LabelAwareInterface|DescriptionAwareInterface|ChoiceElementInterface|CollectionElementInterface $element
     */
    public function create(array $spec = [])
    {
        $this->ensureRequiredAttributes($spec);

        $type = $spec['attributes']['type'];
        $name = $spec['attributes']['name'];

        $element = $this->buildElement($type, $name);

        if ($element instanceof ChoiceElementInterface) {
            $element = $this->configureChoiceElement($element, $spec);
        }

        if ($element instanceof LabelAwareInterface) {
            $element = $this->configureLabel($element, $spec);
        }

        if ($element instanceof ErrorAwareInterface) {
            $element = $this->configureErrors($element, $spec);
        }

        if ($element instanceof CollectionElementInterface) {
            $element = $this->configureEollection($element, $spec);
        }

        if ($element instanceof DescriptionAwareInterface) {
            $element = $this->configureDescription($element, $spec);
        }

        $element = $this->configureElement($element, $spec);

        return $element;
    }

    /**
     * @param string $type
     * @param string $name
     *
     * @return ElementInterface
     * @throws InvalidClassException
     * @throws UnknownTypeException
     */
    private function buildElement(string $type, string $name): ElementInterface
    {
        if (class_exists($type)) {
            $element = new $type($name);

            if (! $element instanceof ElementInterface) {
                throw new InvalidClassException(
                    sprintf(
                        'The given type "%s" does not implement %s.',
                        $type,
                        ElementInterface::class
                    )
                );
            }

            return $element;
        }

        if (isset($this->typeToElement[$type])) {
            return new $this->typeToElement[$type]($name);
        }

        throw new UnknownTypeException(
            sprintf(
                'The given type "%s" will not create a valid class which implements "%s"',
                $type,
                ElementInterface::class
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
    protected function ensureRequiredAttributes(array $spec = [])
    {
        if (! isset($spec['attributes'])) {
            throw new MissingAttributeException(
                sprintf('The attribute "attributes" is not defined, but required.')
            );
        }

        $required = ['type', 'name'];
        foreach ($required as $key) {
            if (! isset($spec['attributes'][$key])) {
                throw new MissingAttributeException(
                    sprintf('The attribute "%s" is not defined, but required.', $key)
                );
            }
        }
    }

    /**
     * @param ChoiceElementInterface $element
     * @param array $spec
     *
     * @return ChoiceElementInterface $element
     */
    protected function configureChoiceElement(
        ChoiceElementInterface $element,
        array $spec = []
    ): ChoiceElementInterface {

        if (! isset($spec['choices'])) {
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
     * @param LabelAwareInterface $element
     * @param array $spec
     *
     * @return LabelAwareInterface $element
     */
    protected function configureLabel(
        LabelAwareInterface $element,
        array $spec = []
    ): LabelAwareInterface {

        if (isset($spec['label'])) {
            $element->withLabel($spec['label']);
        }
        if (isset($spec['label_attributes']) && is_array($spec['label_attributes'])) {
            $element->withLabelAttributes($spec['label_attributes']);
        }

        return $element;
    }

    /**
     * @param ErrorAwareInterface $element
     * @param array $spec
     *
     * @return ErrorAwareInterface $element
     */
    protected function configureErrors(ErrorAwareInterface $element, array $spec = [])
    {
        if (isset($spec['errors']) && is_array($spec['errors'])) {
            $element->withErrors($spec['errors']);
        }

        return $element;
    }

    /**
     * @param CollectionElementInterface $element
     * @param array $spec
     *
     * @return CollectionElementInterface $element
     */
    protected function configureEollection(
        CollectionElementInterface $element,
        array $spec = []
    ): CollectionElementInterface {

        if (isset($spec['elements']) && is_array($spec['elements'])) {
            $element->withElement(...$this->createMultiple($spec['elements']));
        }

        return $element;
    }

    /**
     * @param array $specs
     *
     * @return ElementInterface[] $elements
     */
    public function createMultiple(array $specs = []): array
    {
        return array_map([$this, 'create'], $specs);
    }

    /**
     * @param DescriptionAwareInterface $element
     * @param array $spec
     *
     * @return DescriptionAwareInterface $element
     */
    protected function configureDescription(
        DescriptionAwareInterface $element,
        array $spec = []
    ): DescriptionAwareInterface {

        if (isset($spec['description'])) {
            $element->withDescription($spec['description']);
        }

        return $element;
    }

    /**
     * @param ElementInterface $element
     * @param array $specs
     *
     * @return ElementInterface $element
     */
    protected function configureElement(ElementInterface $element, array $specs = [])
    {
        $element->withAttributes($specs['attributes']);

        return $element;
    }
}
