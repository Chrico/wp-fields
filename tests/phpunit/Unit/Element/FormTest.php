<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\Element;

use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Element\Form;
use ChriCo\Fields\Element\FormInterface;
use ChriCo\Fields\ErrorAwareInterface;
use ChriCo\Fields\Tests\Unit\AbstractTestCase;
use Inpsyde\Filter\FilterInterface;
use Inpsyde\Validator\ValidatorInterface;
use Mockery;

class FormTest extends AbstractTestCase
{

    /**
     * Basic test to check the default behavior of the class.
     */
    public function test_basic()
    {
        $expected_name = 'foo';
        $testee = new Form($expected_name);

        static::assertInstanceOf(FormInterface::class, $testee);

        static::assertSame($expected_name, $testee->name());

        static::assertEmpty($testee->value());
        static::assertEmpty($testee->data());

        static::assertCount(0, $testee->errors());
        static::assertFalse($testee->hasErrors());
        static::assertFalse($testee->isSubmitted());
    }

    public function test_is_valid()
    {
        $expected_key = 'foo';
        $expected_value = 'bar';
        $expected_error = ['bam'];

        $expected_key2 = 'bam';
        $expected_value2 = 'baz';

        $expected_key3 = 'baz';

        // element which has additionally a validator which fails.
        $element = Mockery::mock(ElementInterface::class.','.ErrorAwareInterface::class);
        $element->shouldReceive('withValue')
            ->once()
            ->with($expected_value);
        $element->shouldReceive('isDisabled')
            ->andReturn(false);
        $element->shouldReceive('name')
            ->andReturn($expected_key);
        $element->shouldReceive('value')
            ->andReturn($expected_value);
        $element->shouldReceive('withErrors')
            ->once()
            ->with($expected_error);

        // element which has no validator assigned.
        $not_validated_element2 = $this->get_element_stub($expected_key2);
        $not_validated_element2->shouldReceive('value')
            ->andReturn($expected_value2);

        // element which is disabled shouldn be validated
        $disabled_element = $this->get_element_stub($expected_key3, true);

        $validator = Mockery::mock(ValidatorInterface::class);
        $validator->shouldReceive('is_valid')
            ->once()
            ->with($expected_value)
            ->andReturn(false);
        $validator->shouldReceive('get_error_messages')
            ->once()
            ->andReturn($expected_error);

        $testee = (new Form(''))
            ->withElement($element, $not_validated_element2, $disabled_element)
            ->withValidator($expected_key, $validator);

        $testee->submit(
            [
                $expected_key => $expected_value,
                $expected_key2 => $expected_value2,
                $expected_key3 => 'foo',
                'non existing element key' => 'foo',
            ]
        );

        static::assertFalse($testee->isValid());
    }

    private function get_element_stub(string $name, $disabled = false)
    {
        $element = Mockery::mock(ElementInterface::class);
        $element->shouldReceive('withValue')
            ->with(Mockery::type('string'));
        $element->shouldReceive('name')
            ->once()
            ->andReturn($name);
        $element->shouldReceive('isDisabled')
            ->andReturn($disabled);

        return $element;
    }

    /**
     * @expectedException \ChriCo\Fields\Exception\LogicException
     */
    public function test_is_valid__not_submitted()
    {
        $testee = new Form('');
        $testee->isValid();
    }

    public function test_set_data()
    {
        $expected_key = 'foo';
        $expected_value = 'bar';
        $expected_key2 = 'baz';

        $element = Mockery::mock(ElementInterface::class);
        $element->shouldReceive('withValue')
            ->with(Mockery::type('string'));
        $element->shouldReceive('name')
            ->once()
            ->andReturn($expected_key);
        $element->shouldReceive('value')
            ->once()
            ->andReturn($expected_value);
        $element->shouldReceive('isDisabled')
            ->andReturn(false);

        $element2 = $this->get_element_stub($expected_key2, true);

        $testee = new Form('');
        $testee->withElement($element)
            ->withElement($element2)
            ->withData(
                [
                    $expected_key => $expected_value,
                    $expected_key2 => 'foo',
                    'undefined key' => 'foo',
                ]
            );

        static::assertFalse($testee->isSubmitted());

        static::assertSame(
            $expected_value,
            $testee->element($expected_key)
                ->value()
        );
    }

    /**
     * @expectedException \ChriCo\Fields\Exception\LogicException
     */
    public function test_set_data__already_submitted()
    {
        $testee = new Form('');
        $testee->submit();
        $testee->withData(['foo' => 'bar']);
    }

    public function test_submit()
    {
        $expected_key = 'foo';
        $expected_value = 'bar';

        $element = $this->get_element_stub($expected_key);
        $element->shouldReceive('value')
            ->andReturn($expected_value);

        $filter = Mockery::mock(FilterInterface::class);
        $filter->shouldReceive('filter')
            ->once()
            ->with($expected_value)
            ->andReturn($expected_value);

        $testee = new Form('');
        $testee->withElement($element);
        $testee->withFilter($expected_key, $filter);

        static::assertFalse($testee->isSubmitted());
        static::assertNull($testee->submit([$expected_key => $expected_value]));
        static::assertTrue($testee->isSubmitted());
    }

    public function test_set_attribute()
    {
        $expected_key = 'foo';
        $expected_value = 'bar';
        $testee = new Form('');

        // this writes directly to the form-element attributes.
        $testee->withAttribute($expected_key, $expected_value);
        static::assertSame($expected_value, $testee->attribute($expected_key));

        // this triggers a Form::bind_data
        $testee->withAttribute('value', ['baz' => 'bam']);
    }

    public function test_add_filter_validator()
    {
        $expected_key = 'foo';

        $element = $this->get_element_stub($expected_key);

        $testee = new Form('');
        $testee->withElement($element);

        static::assertInstanceOf(
            Form::class,
            $testee->withFilter($expected_key, Mockery::mock(FilterInterface::class))

        );
        static::assertInstanceOf(
            Form::class,
            $testee->withValidator($expected_key, Mockery::mock(ValidatorInterface::class))
        );
    }
}
