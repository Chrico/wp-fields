<?php # -*- coding: utf-8 -*-

namespace ChriCo\Fields\Tests\Unit\Element;

use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Element\ElementInterface;
use ChriCo\Fields\Element\ErrorAwareInterface;
use ChriCo\Fields\Element\Form;
use ChriCo\Fields\Element\FormInterface;
use ChriCo\Fields\Exception\LogicException;
use ChriCo\Fields\Tests\Unit\AbstractTestCase;
use Mockery;

class FormTest extends AbstractTestCase
{
    /**
     * Basic test to check the default behavior of the class.
     *
     * @test
     */
    public function testBasic(): void
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

    /**
     * @test
     */
    public function testIsValid(): void
    {
        $expected_key = 'foo';
        $expected_value = 'bar';
        $expected_error = ['bam'];

        $expected_key2 = 'bam';
        $expected_value2 = 'baz';

        $expected_key3 = 'baz';

        // element which has additionally a validator which fails.
        $element = Mockery::mock(ElementInterface::class . ',' . ErrorAwareInterface::class);
        $element->allows('withValue')
            ->once()
            ->with($expected_value);
        $element->allows('isDisabled')
            ->andReturn(false);
        $element->allows('name')
            ->andReturn($expected_key);
        $element->allows('value')
            ->andReturn($expected_value);
        $element->allows('withErrors')
            ->with($expected_error);
        $element->allows('hasErrors')
            ->andReturnTrue();
        $element->allows('validate')
            ->andReturnFalse();
        $element->allows('withParent')
            ->andReturn($element);

        // element which has no validator assigned.
        $not_validated_element2 = $this->getElementStub($expected_key2);
        $not_validated_element2->allows('value')
            ->andReturns($expected_value2);
        $not_validated_element2->allows('validate')
            ->andReturnTrue();
        $not_validated_element2->allows('withParent')
            ->andReturn($not_validated_element2);

        // element which is disabled shouldn't't be validated
        $disabled_element = $this->getElementStub($expected_key3, true);
        $disabled_element->allows('validate')->never();
        $disabled_element->allows('withParent')
            ->andReturn($disabled_element);

        $testee = (new Form(''))
            ->withElement($element, $not_validated_element2, $disabled_element);

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

    private function getElementStub(string $name, $disabled = false)
    {
        $element = Mockery::mock(ElementInterface::class);
        $element->allows('withValue')
            ->with(Mockery::type('string'));
        $element->allows('name')
            ->once()
            ->andReturn($name);
        $element->allows('isDisabled')
            ->andReturn($disabled);

        return $element;
    }

    /**
     * @test
     */
    public function testIsValidNotSubmitted(): void
    {
        static::expectException(LogicException::class);
        $testee = new Form('');
        $testee->isValid();
    }

    /**
     * @test
     */
    public function testSetData(): void
    {
        $expected_key = 'foo';
        $expected_value = 'bar';

        $element = Mockery::mock(ElementInterface::class);
        $element->allows('withValue')
            ->with(Mockery::type('string'));
        $element->allows('name')
            ->once()
            ->andReturn($expected_key);
        $element->allows('value')
            ->andReturn($expected_value);
        $element->allows('isDisabled')
            ->andReturn(false);
        $element->allows('withParent')
            ->andReturn($element);

        $testee = new Form('');
        $testee->withElement($element)
            ->withData(
                [
                    $expected_key => $expected_value,
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
     * @test
     */
    public function testSubmitWithSubNode(): void
    {
        $expectedValue = 'my-value';

        $element = new Element('my-element');

        $testee = new Form('my-form');
        $testee->withElement($element);
        $testee->submit(
            [
                'my-form' => [
                    'my-element' => $expectedValue,
                ],
            ]
        );

        static::assertTrue($testee->isSubmitted());
        static::assertSame($expectedValue, $testee->element('my-element')->value());
    }

    /**
     * @test
     */
    public function testSetDataAlreadySubmitted(): void
    {
        static::expectException(LogicException::class);
        $testee = new Form('');
        $testee->submit();
        $testee->withData(['foo' => 'bar']);
    }

    /**
     * @test
     */
    public function testSubmitMultipleTimes(): void
    {
        static::expectException(LogicException::class);
        $testee = new Form('');
        $testee->submit();
        $testee->submit();
    }

    /**
     * @test
     */
    public function testSubmitDisabledForm(): void
    {
        $expectedValue = 'foo';
        $element = new Element('my-element');
        $element->withValue($expectedValue);

        $testee = new Form('');
        $testee->withElement($element);
        $testee->withAttribute('disabled', true);

        static::assertTrue($testee->isDisabled());
        static::assertFalse($testee->isSubmitted());

        static::assertTrue($element->isDisabled());
        static::assertFalse($element->isSubmitted());

        $testee->submit(['my-element' => 'data']);

        static::assertTrue($testee->isSubmitted());
        static::assertTrue($testee->isValid());
        static::assertSame($expectedValue, $element->value());
    }

    /**
     * @test
     */
    public function testSubmit(): void
    {
        $expected_key = 'foo';
        $expected_value = 'bar';

        $element = $this->getElementStub($expected_key);
        $element->allows('value')
            ->andReturn($expected_value);
        $element->allows('validate')
            ->andReturnTrue();
        $element->allows('withParent')
            ->andReturn($element);

        $testee = new Form('');
        $testee->withElement($element);

        static::assertFalse($testee->isSubmitted());
        static::assertNull($testee->submit([$expected_key => $expected_value]));
        static::assertTrue($testee->isSubmitted());
    }

    /**
     * @test
     */
    public function testSetAttribute(): void
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
}
