<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Tests\Component\DependencyInjection;

use Symfony\Component\DependencyInjection\Definition;

class DefinitionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Symfony\Component\DependencyInjection\Definition::__construct
     */
    public function testConstructor()
    {
        $def = new Definition('stdClass');
        $this->assertEquals('stdClass', $def->getClass(), '__construct() takes the class name as its first argument');

        $def = new Definition('stdClass', array('foo'));
        $this->assertEquals(array('foo'), $def->getArguments(), '__construct() takes an optional array of arguments as its second argument');
    }

    /**
     * @covers Symfony\Component\DependencyInjection\Definition::setFactoryMethod
     * @covers Symfony\Component\DependencyInjection\Definition::getFactoryMethod
     */
    public function testSetGetConstructor()
    {
        $def = new Definition('stdClass');
        $this->assertSame($def, $def->setFactoryMethod('foo'), '->setFactoryMethod() implements a fluent interface');
        $this->assertEquals('foo', $def->getFactoryMethod(), '->getFactoryMethod() returns the factory method name');
    }

    public function testSetGetFactoryService()
    {
        $def = new Definition('stdClass');
        $this->assertNull($def->getFactoryService());
        $this->assertSame($def, $def->setFactoryService('stdClass2'), "->setFactoryService() implements a fluent interface.");
        $this->assertEquals('stdClass2', $def->getFactoryService(), "->getFactoryService() returns current service to construct this service.");
    }

    /**
     * @covers Symfony\Component\DependencyInjection\Definition::setClass
     * @covers Symfony\Component\DependencyInjection\Definition::getClass
     */
    public function testSetGetClass()
    {
        $def = new Definition('stdClass');
        $this->assertSame($def, $def->setClass('foo'), '->setClass() implements a fluent interface');
        $this->assertEquals('foo', $def->getClass(), '->getClass() returns the class name');
    }

    /**
     * @covers Symfony\Component\DependencyInjection\Definition::setArguments
     * @covers Symfony\Component\DependencyInjection\Definition::getArguments
     * @covers Symfony\Component\DependencyInjection\Definition::addArgument
     */
    public function testArguments()
    {
        $def = new Definition('stdClass');
        $this->assertSame($def, $def->setArguments(array('foo')), '->setArguments() implements a fluent interface');
        $this->assertEquals(array('foo'), $def->getArguments(), '->getArguments() returns the arguments');
        $this->assertSame($def, $def->addArgument('bar'), '->addArgument() implements a fluent interface');
        $this->assertEquals(array('foo', 'bar'), $def->getArguments(), '->addArgument() adds an argument');
    }

    /**
     * @covers Symfony\Component\DependencyInjection\Definition::setMethodCalls
     * @covers Symfony\Component\DependencyInjection\Definition::addMethodCall
     * @covers Symfony\Component\DependencyInjection\Definition::hasMethodCall
     * @covers Symfony\Component\DependencyInjection\Definition::removeMethodCall
     */
    public function testMethodCalls()
    {
        $def = new Definition('stdClass');
        $this->assertSame($def, $def->setMethodCalls(array(array('foo', array('foo')))), '->setMethodCalls() implements a fluent interface');
        $this->assertEquals(array(array('foo', array('foo'))), $def->getMethodCalls(), '->getMethodCalls() returns the methods to call');
        $this->assertSame($def, $def->addMethodCall('bar', array('bar')), '->addMethodCall() implements a fluent interface');
        $this->assertEquals(array(array('foo', array('foo')), array('bar', array('bar'))), $def->getMethodCalls(), '->addMethodCall() adds a method to call');
        $this->assertTrue($def->hasMethodCall('bar'), '->hasMethodCall() returns true if first argument is a method to call registered');
        $this->assertFalse($def->hasMethodCall('no_registered'), '->hasMethodCall() returns false if first argument is not a method to call registered');
        $this->assertSame($def, $def->removeMethodCall('bar'), '->removeMethodCall() implements a fluent interface');
        $this->assertEquals(array(array('foo', array('foo'))), $def->getMethodCalls(), '->removeMethodCall() removes a method to call');
    }

    /**
     * @covers Symfony\Component\DependencyInjection\Definition::setFile
     * @covers Symfony\Component\DependencyInjection\Definition::getFile
     */
    public function testSetGetFile()
    {
        $def = new Definition('stdClass');
        $this->assertSame($def, $def->setFile('foo'), '->setFile() implements a fluent interface');
        $this->assertEquals('foo', $def->getFile(), '->getFile() returns the file to include');
    }

    /**
     * @covers Symfony\Component\DependencyInjection\Definition::setShared
     * @covers Symfony\Component\DependencyInjection\Definition::isShared
     */
    public function testSetIsShared()
    {
        $def = new Definition('stdClass');
        $this->assertTrue($def->isShared(), '->isShared() returns true by default');
        $this->assertSame($def, $def->setShared(false), '->setShared() implements a fluent interface');
        $this->assertFalse($def->isShared(), '->isShared() returns false if the instance must not be shared');
    }

    /**
     * @covers Symfony\Component\DependencyInjection\Definition::setPublic
     * @covers Symfony\Component\DependencyInjection\Definition::isPublic
     */
    public function testSetIsPublic()
    {
        $def = new Definition('stdClass');
        $this->assertTrue($def->isPublic(), '->isPublic() returns true by default');
        $this->assertSame($def, $def->setPublic(false), '->setPublic() implements a fluent interface');
        $this->assertFalse($def->isPublic(), '->isPublic() returns false if the instance must not be public.');
    }

    /**
     * @covers Symfony\Component\DependencyInjection\Definition::setConfigurator
     * @covers Symfony\Component\DependencyInjection\Definition::getConfigurator
     */
    public function testSetGetConfigurator()
    {
        $def = new Definition('stdClass');
        $this->assertSame($def, $def->setConfigurator('foo'), '->setConfigurator() implements a fluent interface');
        $this->assertEquals('foo', $def->getConfigurator(), '->getConfigurator() returns the configurator');
    }

    /**
     * @covers Symfony\Component\DependencyInjection\Definition::clearTags
     */
    public function testClearTags()
    {
        $def = new Definition('stdClass');
        $this->assertSame($def, $def->clearTags(), '->clearTags() implements a fluent interface');
        $def->addTag('foo', array('foo' => 'bar'));
        $def->clearTags();
        $this->assertEquals(array(), $def->getTags(), '->clearTags() removes all current tags');
    }

    /**
     * @covers Symfony\Component\DependencyInjection\Definition::addTag
     * @covers Symfony\Component\DependencyInjection\Definition::getTag
     * @covers Symfony\Component\DependencyInjection\Definition::getTags
     */
    public function testTags()
    {
        $def = new Definition('stdClass');
        $this->assertEquals(array(), $def->getTag('foo'), '->getTag() returns an empty array if the tag is not defined');
        $this->assertSame($def, $def->addTag('foo'), '->addTag() implements a fluent interface');
        $this->assertEquals(array(array()), $def->getTag('foo'), '->getTag() returns attributes for a tag name');
        $def->addTag('foo', array('foo' => 'bar'));
        $this->assertEquals(array(array(), array('foo' => 'bar')), $def->getTag('foo'), '->addTag() can adds the same tag several times');
        $def->addTag('bar', array('bar' => 'bar'));
        $this->assertEquals($def->getTags(), array(
            'foo' => array(array(), array('foo' => 'bar')),
            'bar' => array(array('bar' => 'bar')),
        ), '->getTags() returns all tags');
    }
}
