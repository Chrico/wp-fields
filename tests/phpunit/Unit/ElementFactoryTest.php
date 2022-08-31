<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit;

use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\ChoiceList\CallbackChoiceList;
use ChriCo\Fields\Element\ChoiceElementInterface;
use ChriCo\Fields\Element\CollectionElementInterface;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\ElementFactory;
use ChriCo\Fields\Exception\InvalidClassException;
use ChriCo\Fields\Exception\MissingAttributeException;
use ChriCo\Fields\Exception\UnknownTypeException;

class ElementFactoryTest extends AbstractTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     * @test
     */
    public function test_basic(): void
    {

        $testee = new ElementFactory();
        static::assertInstanceOf(ElementFactory::class, $testee);
    }

    /**
     * Test if creating a View-class via valid type.
     *
     * @param $spec
     * @param $expected
     *
     * @dataProvider provide_create
     * @test
     */
    public function test_create($spec, $expected): void
    {

        $testee = new ElementFactory();
        static::assertInstanceOf($expected, $testee->create($spec));
    }

    /**
     * @return array
     */
    public function provide_create(): \Generator
    {

        yield  'element' => [
            [
                'attributes' => [
                    'type' => 'text',
                    'name' => 'foo',
                ]
            ],
            ElementInterface::class
        ];

        yield  'element_with_options' => [
            [
                'attributes' => [
                    'type' => 'text',
                    'name' => 'foo',
                ],
                'options' => [
                    uniqid() => uniqid(),
                ]
            ],
            ElementInterface::class
        ];

        yield 'choice' => [
            [
                'attributes' => [
                    'type' => 'checkbox',
                    'name' => 'foo'
                ]
            ],
            ChoiceElementInterface::class
        ];

        yield  'collection' => [
            [
                'attributes' => [
                    'type' => 'collection',
                    'name' => 'foo'
                ]
            ],
            CollectionElementInterface::class
        ];
    }

    /**
     * @param array $spec
     *
     * @dataProvider provide_create__missing_attributes
     * @test
     */
    public function test_create__missing_attributes(array $spec): void
    {
        static::expectException(MissingAttributeException::class);
        (new ElementFactory())->create($spec);
    }

    public function provide_create__missing_attributes(): \Generator
    {

        yield 'missing name' => [
            ['attributes' => ['type' => 'text']]
        ];

        yield 'missing type' => [
            ['attributes' => ['name' => 'foo']]
        ];

        yield 'type and name missing' => [
            ['label' => 'foo']
        ];

        yield 'empty specs' => [
            []
        ];
    }

    /**
     * Test if the creation of an invalid class fails.
     * @test
     */
    public function test_create__invalid_class(): void
    {
        static::expectException(InvalidClassException::class);
        (new ElementFactory())->create(['attributes' => ['type' => ElementFactory::class, 'name' => '']]);
    }

    /**
     * Test if the creation with an unknown type fails.
     * @test
     */
    public function test_create__unknown_type(): void
    {
        static::expectException(UnknownTypeException::class);
        (new ElementFactory())->create(['attributes' => ['type' => 'i am an unknown type', 'name' => '']]);
    }

    /**
     * Test if we can create a Text-Element which implements the LabelAwareInterface and set label and label_attributes.
     * @test
     */
    public function test_create__with_label(): void
    {

        $testee                    = new ElementFactory();
        $expected_label            = 'my label';
        $expected_label_attributes = ['class' => 'foo', 'for' => 'bar'];

        $spec = [
            'attributes'       => [
                'type' => 'text',
                'name' => 'test',
            ],
            'label'            => $expected_label,
            'label_attributes' => $expected_label_attributes
        ];

        $element = $testee->create($spec);

        static::assertSame($expected_label, $element->label());

        // order in array is not the same.
        static::assertEquals($expected_label_attributes, $element->labelAttributes());
    }

    /**
     * Test if we can create a Text-Element which implements the DescriptionAwareInterface and set description.
     * @test
     */
    public function test_create__with_description(): void
    {

        $testee   = new ElementFactory();
        $expected = 'lorum ipsum';

        $spec = [
            'attributes'  => [
                'type' => 'text',
                'name' => 'test',
            ],
            'description' => $expected
        ];

        $element = $testee->create($spec);

        static::assertSame($expected, $element->description());
    }

    /**
     * Test if we can create a Text-Element which implements the ErrorAwareInterface and set errors
     * @test
     */
    public function test_create__with_errors(): void
    {

        $testee   = new ElementFactory();
        $expected = ['foo' => 'bar'];

        $spec = [
            'attributes' => [
                'type' => 'text',
                'name' => 'test',
            ],
            'errors'     => $expected
        ];

        $element = $testee->create($spec);

        static::assertSame($expected, $element->errors());
    }

    /**
     * Test creation of a ChoiceElement.
     *
     * @dataProvider provide_create__with_choices
     *
     * @param array|callable $choices
     * @param array          $expected
     * @param string         $instance_of
     *
     * @test
     */
    public function test_create__with_choices($choices, array $expected, string $instance_of): void
    {

        $testee = new ElementFactory();

        $spec = [
            'attributes' => [
                'type' => 'select',
                'name' => 'test',
            ],
            'choices'    => $choices
        ];

        $element = $testee->create($spec);

        $choices = $element->choices();
        static::assertInstanceOf($instance_of, $choices);
        static::assertSame($expected, $choices->choices());
    }

    public function provide_create__with_choices(): \Generator
    {

        // normal choice list
        yield 'choice array' => [
            ['foo' => 'bar', 'baz' => 'bam'],
            ['foo' => 'bar', 'baz' => 'bam'],
            ArrayChoiceList::class
        ];

        // choice list with callback
        $expected = ['foo' => 'bar', 'baz' => 'bam'];

        yield 'choices via callable' => [
            function () use ($expected) {

                return $expected;
            },
            $expected,
            CallbackChoiceList::class
        ];
    }

    /**
     * Test creation of an Collection element with elements.
     * @test
     */
    public function test_create__with_collection(): void
    {

        $testee = new ElementFactory();

        $spec = [
            'attributes' => [
                'type' => 'collection',
                'name' => 'test',
            ],
            'elements'   => [
                [
                    'attributes' => [
                        'type' => 'text',
                        'name' => 'my-text'
                    ]
                ]
            ]
        ];

        $element = $testee->create($spec);
        static::assertInstanceOf(CollectionElementInterface::class, $element);

        $elements = $element->elements();
        static::assertNotEmpty($elements);
        static::assertInstanceOf(ElementInterface::class, reset($elements));
    }

    /**
     * Test creating of an element with additional attributes.
     * @test
     */
    public function test_create__with_attributes(): void
    {

        $testee = new ElementFactory();

        $expected = [
            'type'     => 'text',
            'name'     => 'test',
            'class'    => 'class-1 class-2',
            'data-foo' => 'foo',
            'data-bar' => 'bar',
            'id'       => 'test'
        ];

        $element = $testee->create(['attributes' => $expected]);
        // cannot use assertSame() because the order of the elements in array can differ.
        static::assertEquals($expected, $element->attributes());
    }

    /**
     * Test if we can create multiple Elements from a specification.
     * @test
     */
    public function test_create__multiple(): void
    {

        // Element
        $element = [
            'attributes' => [
                'type' => 'text',
                'name' => 'my-text',
                'id'   => 'my-id'
            ],
            'label'      => 'My label'
        ];
        // ChoiceElement
        $choice = [
            'attributes' => [
                'type' => 'select',
                'name' => 'my-select',
                'id'   => 'my-id'
            ]
        ];
        // Collection - with additional elements
        $collection = [
            'attributes' => [
                'type' => 'collection',
                'name' => 'my-collection',
                'id'   => 'my-collection'
            ],
            'elements'   => [$element, $choice]
        ];

        $elements = (new ElementFactory())->createMultiple([$element, $choice, $collection]);

        static::assertCount(3, $elements);
        static::assertInstanceOf(ElementInterface::class, $elements[ 0 ]);
        static::assertInstanceOf(ChoiceElementInterface::class, $elements[ 1 ]);
        static::assertInstanceOf(CollectionElementInterface::class, $elements[ 2 ]);
    }

    /**
     * Test options are stored in the Element when provided
     * @test
     */
    public function test_create__set_options_for_element(): void
    {
        $options = [
            uniqid() => uniqid(),
        ];
        $elementSpec = [
            'attributes' => [
                'type' => 'text',
                'name' => 'foo',
            ],
            'options' => $options
        ];

        $elements = (new ElementFactory())->create($elementSpec);

        static::assertEquals($options, $elements->options());
    }
}
