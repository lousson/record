<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 textwidth=75: *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Copyright (c) 2013, The Lousson Project                               *
 *                                                                       *
 * All rights reserved.                                                  *
 *                                                                       *
 * Redistribution and use in source and binary forms, with or without    *
 * modification, are permitted provided that the following conditions    *
 * are met:                                                              *
 *                                                                       *
 * 1) Redistributions of source code must retain the above copyright     *
 *    notice, this list of conditions and the following disclaimer.      *
 * 2) Redistributions in binary form must reproduce the above copyright  *
 *    notice, this list of conditions and the following disclaimer in    *
 *    the documentation and/or other materials provided with the         *
 *    distribution.                                                      *
 *                                                                       *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS   *
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT     *
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS     *
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE        *
 * COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,            *
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES    *
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR    *
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)    *
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,   *
 * STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)         *
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED   *
 * OF THE POSSIBILITY OF SUCH DAMAGE.                                    *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/**
 *  Lousson\Record\AbstractRecordFactoryTest class definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record;

/** Dependencies: */
use Lousson\Record\AbstractRecordTest;
use Lousson\Record\AnyRecordFactory;

/**
 *  An abstract test case for record factories
 *
 *  @since      lousson/Lousson_Record-0.1.0
 *  @package    org.lousson.record
 *  @link       http://www.phpunit.de/manual/current/en/
 */
abstract class AbstractRecordFactoryTest extends AbstractRecordTest
{
    /**
     *  Obtain the record factory to test
     *
     *  The getRecordFactory() method returns the record factory instance
     *  that is used in the tests.
     *
     *  @return \Lousson\Record\AnyRecordFactory
     *          A record factory instance is returned on success
     */
    abstract protected function getRecordFactory();

    /**
     *  Provide supported media type parameters
     *
     *  The provideBuilderMediaTypes() method returns an array of multiple
     *  items, each of whose is an array with one string item representing
     *  a media type the factory is supposed to provide a builder for.
     *
     *  @return array
     *          A list of media type parameters is returned on success
     */
    abstract public function provideBuilderMediaTypes();

    /**
     *  Provide supported media type parameters
     *
     *  The provideParserMediaTypes() method returns an array of multiple
     *  items, each of whose is an array with one string item representing
     *  a media type the factory is supposed to provide a parser for.
     *
     *  @return array
     *          A list of media type parameters is returned on success
     */
    abstract public function provideParserMediaTypes();

    /**
     *
     */
    public function provideHandlerMediaTypes()
    {
        $parameters = $this->provideBuilderMediaTypes();
        return $parameters;
    }

    /**
     *  Test the getRecordBuilder() method
     *
     *  The testGetRecordBuilder() method is a test for implementations of
     *  getRecordBuilder() as defined by the AnyRecordFactory interface.
     *
     *  @param  string              $type       The media type
     *
     *  @dataProvider               provideBuilderMediaTypes
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case getRecordFactory() does not return a record
     *          factory or getRecordBuilder() does not return a builder
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the media $type is invalid or not supported
     *
     *  @throws \Exception
     *          Raised in case of internal errors
     */
    public function testGetRecordBuilder($type)
    {
        $builder = $this->getRealRecordBuilder($type);
    }

    /**
     *  Test the getRecordParser() method
     *
     *  The testGetRecordParser() method is a test case for implementations
     *  of getRecordParser() as defined by the AnyRecordFactory interface.
     *
     *  @param  string              $type       The media type
     *
     *  @dataProvider               provideParserMediaTypes
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case getRecordFactory() does not return a record
     *          factory or getRecordParser() does not return a parser
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the media $type is invalid or not supported
     *
     *  @throws \Exception
     *          Raised in case of internal errors
     */
    public function testGetRecordParser($type)
    {
        $parser = $this->getRealRecordParser($type);
    }

    /**
     *  Test the getRecordHandler() method
     *
     *  The testGetRecordHandler() method is a test case for implementations
     *  of getRecordHandler() as defined by the AnyRecordFactory interface.
     *
     *  @param  string              $type       The media type
     *
     *  @dataProvider               provideHandlerMediaTypes
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case getRecordFactory() does not return a record
     *          factory or getRecordHandler() does not return a handler
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the media $type is invalid or not supported
     *
     *  @throws \Exception
     *          Raised in case of internal errors
     */
    public function testGetRecordHandler($type)
    {
        $handler = $this->getRealRecordHandler($type);
    }

    /**
     *  Test the hasRecordBuilder() method
     *
     *  The testHasRecordBuilder() method is a smoke test for the factory's
     *  hasRecordBuilder() implementation that verifies the consistency of
     *  the return value with the behavior of getRecordBuilder().
     *
     *  @param  string              $type       The media type
     *
     *  @dataProvider               provideValidMediaTypes
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case getRecordFactory() does not return a record
     *          factory or getRecordBuilder() does not return a builder
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the media $type is invalid or not supported
     *
     *  @throws \Exception
     *          Raised in case of internal errors
     */
    public function testHasRecordBuilder($type)
    {
        $factory = $this->getRealRecordFactory();
        $factoryHasBuilder = $factory->hasRecordBuilder($type);
        $factoryClass = get_class($factory);

        $this->assertInternalType(
            "bool", $factoryHasBuilder,
            "The {$factoryClass}::hasRecordBuilder() method must return ".
            "either TRUE or FALSE"
        );

        if (!$factoryHasBuilder) {
            $exceptionInterface = "Lousson\\Record\\AnyRecordException";
            $this->setExpectedException($exceptionInterface);
        }

        $this->fetchRecordBuilder($factory, $type);
    }

    /**
     *  Test the hasRecordParser() method
     *
     *  The testHasRecordParser() method is a smoke test for the factory's
     *  hasRecordParser() implementation that verifies the consistency of
     *  the return value with the behavior of getRecordParser().
     *
     *  @param  string              $type       The media type
     *
     *  @dataProvider               provideValidMediaTypes
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case getRecordFactory() does not return a record
     *          factory or getRecordParser() does not return a parser
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the media $type is invalid or not supported
     *
     *  @throws \Exception
     *          Raised in case of internal errors
     */
    public function testHasRecordParser($type)
    {
        $factory = $this->getRealRecordFactory();
        $factoryHasParser = $factory->hasRecordParser($type);
        $factoryClass = get_class($factory);

        $this->assertInternalType(
            "bool", $factoryHasParser,
            "The {$factoryClass}::hasRecordParser() method must return ".
            "either TRUE or FALSE"
        );

        if (!$factoryHasParser) {
            $exceptionInterface = "Lousson\\Record\\AnyRecordException";
            $this->setExpectedException($exceptionInterface);
        }

        $this->fetchRecordParser($factory, $type);
    }

    /**
     *  Test the hasRecordHandler() method
     *
     *  The testHasRecordHandler() method is a smoke test for the factory's
     *  hasRecordHandler() implementation that verifies the consistency of
     *  the return value with the behavior of getRecordHandler().
     *
     *  @param  string              $type       The media type
     *
     *  @dataProvider               provideValidMediaTypes
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case getRecordFactory() does not return a record
     *          factory or getRecordHandler() does not return a handler
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the media $type is invalid or not supported
     *
     *  @throws \Exception
     *          Raised in case of internal errors
     */
    public function testHasRecordHandler($type)
    {
        $factory = $this->getRealRecordFactory();
        $factoryHasHandler = $factory->hasRecordHandler($type);
        $factoryClass = get_class($factory);

        $this->assertInternalType(
            "bool", $factoryHasHandler,
            "The {$factoryClass}::hasRecordHandler() method must return ".
            "either TRUE or FALSE"
        );

        if (!$factoryHasHandler) {
            $exceptionInterface = "Lousson\\Record\\AnyRecordException";
            $this->setExpectedException($exceptionInterface);
        }

        $this->fetchRecordHandler($factory, $type);
    }

    /**
     *  Obtain a verified record factory
     *
     *  The getRealRecordFactory() method is used internally to obtain an
     *  instance of the record factory interface. It verfies that the value
     *  returned by getRecordFactory() refers to an instance of the record
     *  factory interface before passing it back to the caller.
     *
     *  @return \Lousson\Record\AnyRecordFactory
     *          A record factory instance is returned on success
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     */
    final protected function getRealRecordFactory()
    {
        $factory = $this->getRecordFactory();
        $this->assertInstanceOf(
            "Lousson\\Record\\AnyRecordFactory", $factory, sprintf(
            "The %s::getRecordFactory() method must return an intance of ".
            "the AnyRecordFactory interface", get_class($this)
        ));

        return $factory;
    }

    /**
     *  Obtain a verified record builder
     *
     *  The getRealRecordBuilder() method is used to obtain a record
     *  builder instance for the given $type from the factory that is
     *  returned by the getRealRecordFactory() method.
     *
     *  @param  string              $type       The type to pass on
     *
     *  @return \Lousson\Record\AnyRecordBuilder
     *          A record builder instance is returned on success
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case getRecordFactory() does not return a record
     *          factory or getRecordBuilder() does not return a builder
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the media $type is invalid or not supported
     *
     *  @throws \Exception
     *          Raised in case of internal errors
     */
    final protected function getRealRecordBuilder($type)
    {
        $factory = $this->getRealRecordFactory();
        $builder = $this->fetchRecordBuilder($factory, $type);
        return $builder;
    }

    /**
     *  Obtain a verified record parser
     *
     *  The getRealRecordParser() method is used to obtain a record
     *  parser instance for the given $type from the factory that is
     *  returned by the getRealRecordFactory() method.
     *
     *  @param  string              $type       The type to pass on
     *
     *  @return \Lousson\Record\AnyRecordParser
     *          A record parser instance is returned on success
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case getRecordFactory() does not return a record
     *          factory or getRecordParser() does not return a parser
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the media $type is invalid or not supported
     *
     *  @throws \Exception
     *          Raised in case of internal errors
     */
    final protected function getRealRecordParser($type)
    {
        $factory = $this->getRealRecordFactory();
        $parser = $this->fetchRecordParser($factory, $type);
        return $parser;
    }

    /**
     *  Obtain a verified record handler
     *
     *  The getRealRecordHandler() method is used to obtain a record
     *  handler instance for the given $type from the factory that is
     *  returned by the getRealRecordFactory() method.
     *
     *  @param  string              $type       The type to pass on
     *
     *  @return \Lousson\Record\AnyRecordHandler
     *          A record handler instance is returned on success
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case getRecordFactory() does not return a record
     *          factory or getRecordHandler() does not return a handler
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the media $type is invalid or not supported
     *
     *  @throws \Exception
     *          Raised in case of internal errors
     */
    final protected function getRealRecordHandler($type)
    {
        $factory = $this->getRealRecordFactory();
        $handler = $this->fetchRecordHandler($factory, $type);
        return $handler;
    }

    /**
     *  Fetch a record builder instance
     *
     *  The fetchRecordBuilder() method is used internally to invoke the
     *  $factory's getRecordBuilder() method. It verifies that the return
     *  value is a record builder instance before returning the object.
     *
     *  @param  AnyRecordFactory    $factory    The factory to use
     *  @param  string              $type       The type to pass on
     *
     *  @return \Lousson\Record\AnyRecordBuilder
     *          A record builder instance is returned on success
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case getRecordBuilder() does not return a builder
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the media $type is invalid or not supported
     *
     *  @throws \Exception
     *          Raised in case the $factory caused an internal error
     */
    private function fetchRecordBuilder(AnyRecordFactory $factory, $type)
    {
        $builder = $factory->getRecordBuilder($type);
        $factoryClass = get_class($factory);
        $this->assertInstanceOf(
            "Lousson\\Record\\AnyRecordBuilder", $builder,
            "The $factoryClass::getRecordBuilder() method must return an ".
            "instance of the AnyRecordBuilder interface"
        );

        return $builder;
    }

    /**
     *  Fetch a record parser instance
     *
     *  The fetchRecordParser() method is used internally to invoke the
     *  $factory's getRecordParser() method. It verifies that the return
     *  value is a record parser instance before returning the object.
     *
     *  @param  AnyRecordFactory    $factory    The factory to use
     *  @param  string              $type       The type to pass on
     *
     *  @return \Lousson\Record\AnyRecordParser
     *          A record parser instance is returned on success
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case getRecordParser() does not return a parser
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the media $type is invalid or not supported
     *
     *  @throws \Exception
     *          Raised in case the $factory caused an internal error
     */
    private function fetchRecordParser(AnyRecordFactory $factory, $type)
    {
        $parser = $factory->getRecordParser($type);
        $factoryClass = get_class($factory);
        $this->assertInstanceOf(
            "Lousson\\Record\\AnyRecordParser", $parser,
            "The $factoryClass::getRecordParser() method must return an ".
            "instance of the AnyRecordParser interface"
        );

        return $parser;
    }

    /**
     *  Fetch a record handler instance
     *
     *  The fetchRecordHandler() method is used internally to invoke the
     *  $factory's getRecordHandler() method. It verifies that the return
     *  value is a record handler instance before returning the object.
     *
     *  @param  AnyRecordFactory    $factory    The factory to use
     *  @param  string              $type       The type to pass on
     *
     *  @return \Lousson\Record\AnyRecordHandler
     *          A record handler instance is returned on success
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case getRecordHandler() does not return a handler
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the media $type is invalid or not supported
     *
     *  @throws \Exception
     *          Raised in case the $factory caused an internal error
     */
    private function fetchRecordHandler(AnyRecordFactory $factory, $type)
    {
        $handler = $factory->getRecordHandler($type);
        $factoryClass = get_class($factory);
        $this->assertInstanceOf(
            "Lousson\\Record\\AnyRecordHandler", $handler,
            "The $factoryClass::getRecordHandler() method must return an ".
            "instance of the AnyRecordHandler interface"
        );

        return $handler;
    }
}

