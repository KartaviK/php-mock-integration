<?php

namespace Kartavik\PHPMock\Integration\Tests;

use PHPUnit\Framework\TestCase;
use Kartavik\PHPMock\Integration\MockDelegateFunctionBuilder;

/**
 * Tests MockDelegateFunctionBuilder.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @author Roman Varkuta <roman.varkuta@gmail.com>
 */
class MockDelegateFunctionBuilderTest extends TestCase
{
    public function testBuild()
    {
        $builder = new MockDelegateFunctionBuilder();
        $builder->build();
        $this->assertTrue(class_exists($builder->getFullyQualifiedClassName()));
    }

    public function testDiverseSignaturesProduceDifferentClasses()
    {
        $builder = new MockDelegateFunctionBuilder();

        $builder->build('time');
        $class1 = $builder->getFullyQualifiedClassName();

        $builder->build('microtime');
        $class2 = $builder->getFullyQualifiedClassName();

        $builder2 = new MockDelegateFunctionBuilder();
        $builder2->build('array_flip');
        $class3 = $builder2->getFullyQualifiedClassName();

        $this->assertNotEquals($class1, $class2);
        $this->assertNotEquals($class1, $class3);
        $this->assertNotEquals($class2, $class3);
    }

    public function testSameSignaturesProduceSameClass()
    {
        $builder = new MockDelegateFunctionBuilder();

        $builder->build('time');
        $class1 = $builder->getFullyQualifiedClassName();

        $builder->build('time');
        $class2 = $builder->getFullyQualifiedClassName();

        $this->assertEquals($class1, $class2);
    }

    /**
     * @backupStaticAttributes enabled
     * @dataProvider provideTestBackupStaticAttributes
     */
    public function testBackupStaticAttributes()
    {
        $builder = new MockDelegateFunctionBuilder();
        $builder->build("min");
        $this->assertTrue(true);
    }

    /**
     * Just repeat testBackupStaticAttributes a few times.
     *
     * @return array Test cases.
     */
    public function provideTestBackupStaticAttributes()
    {
        return [
            [],
            []
        ];
    }

    /**
     * @runInSeparateProcess
     * @dataProvider provideTestDeserializationInNewProcess
     */
    public function testDeserializationInNewProcess($data)
    {
        unserialize($data);
        $this->assertTrue(true);
    }

    public function provideTestDeserializationInNewProcess(): array
    {
        $builder = new MockDelegateFunctionBuilder();
        $builder->build("min");

        return [
            [serialize($this->getMockForAbstractClass($builder->getFullyQualifiedClassName()))]
        ];
    }
}
