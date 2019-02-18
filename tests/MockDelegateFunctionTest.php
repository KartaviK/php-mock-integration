<?php

namespace Kartavik\PHPMock\Integration\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Kartavik\PHPMock\Integration\MockDelegateFunctionBuilder;

/**
 * Tests MockDelegateFunction.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @author Roman Varkuta <roman.varkuta@gmail.com>
 */
class MockDelegateFunctionTest extends TestCase
{
    /** @var string The class name of a generated class. */
    private $className;

    protected function setUp(): void
    {
        parent::setUp();

        $builder = new MockDelegateFunctionBuilder();
        $builder->build();
        $this->className = $builder->getFullyQualifiedClassName();
    }

    public function testDelegateReturnsMockResult()
    {
        $expected = 3;
        $mock = $this->getMockForAbstractClass($this->className);

        $mock->expects($this->once())
            ->method(MockDelegateFunctionBuilder::METHOD)
            ->willReturn($expected);

        $result = call_user_func($mock->getClosure());
        $this->assertEquals($expected, $result);
    }

    public function testDelegateForwardsArguments()
    {
        /** @var MockDelegateFunctionBuilder|MockObject $mock */
        $mock = $this->getMockForAbstractClass($this->className);

        $mock->expects($this->once())
            ->method(MockDelegateFunctionBuilder::METHOD)
            ->with(1, 2)
            ->willReturn(1);

        $this->assertEquals(1, call_user_func($mock->getClosure(), 1, 2));
    }
}
