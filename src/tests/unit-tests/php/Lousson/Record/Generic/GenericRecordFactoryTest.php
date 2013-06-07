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
 *  Lousson\Record\Generic\GenericRecordFactoryTest class definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record\Generic;

/** Dependencies: */
use Lousson\Record\AbstractRecordFactoryTest;
use Lousson\Record\AnyRecordBuilder;
use Lousson\Record\AnyRecordParser;
use Lousson\Record\Builtin\BuiltinRecordFactory;
use Lousson\Record\Builtin\Handler\BuiltinRecordHandlerJSON;
use Lousson\Record\Builtin\Parser\BuiltinRecordParserINI;
use Lousson\Record\Generic\GenericRecordFactory;

/**
 *  A test case for the generic record factory
 *
 *  @since      lousson/Lousson_Record-0.1.0
 *  @package    org.lousson.record
 *  @link       http://www.phpunit.de/manual/current/en/
 */
abstract class GenericRecordFactoryTest extends AbstractRecordFactoryTest
{
    /**
     *  Obtain the record factory to test
     *
     *  The getRecordFactory() method returns the record factory instance
     *  that is used in the tests.
     *
     *  @return \Lousson\Record\Generic\GenericRecordFactory
     *          A generic record factory instance is returned on success
     */
    abstract public function getGenericRecordFactory();

    /**
     *  Obtain the record factory to test
     *
     *  The getRecordFactory() method returns the record factory instance
     *  that is used in the tests.
     *
     *  @return \Lousson\Record\AnyRecordFactory
     *          A record factory instance is returned on success
     */
    final public function getRecordFactory()
    {
        $factory = $this->getGenericRecordFactory();
        $testClass = get_class($this);
        $this->assertInstanceOf(
            "Lousson\\Record\\Generic\\GenericRecordFactory", $factory,
            "The $testClass::getGenericRecordFactory() method must ".
            "return an instance of the GenericRecordFactory class"
        );

        return $factory;
    }

    /**
     *  Provide setRecordBuilder() parameters
     *
     *  The provideRecordBuilders() method returns an array of multiple
     *  items, each of whose is an array of two: a media type string and
     *  a record builder instance.
     *
     *  @return array
     *          A setRecordBuilder() parameter list is returned on success
     */
    public function provideRecordBuilders()
    {
        return array(
            array("text/x-json", new BuiltinRecordHandlerJSON()),
        );
    }

    /**
     *  Provide setRecordParser() parameters
     *
     *  The provideRecordParsers() method returns an array of multiple
     *  items, each of whose is an array of two: a media type string and
     *  a record parser instance.
     *
     *  @return array
     *          A setRecordParser() parameter list is returned on success
     */
    public function provideRecordParsers()
    {
        return array(
            array("text/x-json", new BuiltinRecordHandlerJSON()),
        );
    }

    /**
     *  Test the setRecordBuilder() method
     *
     *  The testSetRecordBuilder() method is a test case for the factory's
     *  setRecordBuilder() implementation.
     *
     *  @param  string              $type       The media type
     *  @param  AnyRecordBuilder    $builder    The record builder
     *
     *  @dataProvider               provideRecordBuilders
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case getRecordFactory() does not return a record
     *          factory or getRecordBuilder() does not return a builder or
     *          it isn't the parser formerly set via setRecordParser()
     *
     *  @throws \Exception
     *          Raised in case of internal errors
     */
    public function testSetRecordBuilder($type, AnyRecordBuilder $builder)
    {
        $factory = $this->getRealRecordFactory();
        $factoryClass = get_class($factory);
        $factory->setRecordBuilder($type, $builder);

        $this->assertTrue(
            $factory->hasRecordBuilder($type),
            "$factoryClass::hasRecordBuilder() must return TRUE after ".
            "a builder has been assigned using setRecordBuilder()"
        );

        $this->assertSame(
            $builder, $factory->getRecordBuilder($type),
            "$factoryClass::getRecordBuilder() must return the same ".
            "builder formerly assigned using setRecordBuilder()"
        );
    }

    /**
     *  Test the setRecordBuilder() method
     *
     *  The testSetRecordParser() method is a test case for the factory's
     *  setRecordParser() implementation.
     *
     *  @param  string              $type       The media type
     *  @param  AnyRecordParser     $parser     The record parser
     *
     *  @dataProvider               provideRecordParsers
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case getRecordFactory() does not return a record
     *          factory or getRecordParser() does not return a parser or it
     *          is not the parser set in the former setRecordParser() call
     *
     *  @throws \Exception
     *          Raised in case of internal errors
     */
    public function testSetRecordParser($type, AnyRecordParser $parser)
    {
        $factory = $this->getRealRecordFactory();
        $factoryClass = get_class($factory);
        $factory->setRecordParser($type, $parser);

        $this->assertTrue(
            $factory->hasRecordParser($type),
            "$factoryClass::hasRecordParser() must return TRUE after ".
            "a parser has been assigned using setRecordParser()"
        );

        $this->assertSame(
            $parser, $factory->getRecordParser($type),
            "$factoryClass::getRecordParser() must return the same ".
            "parser formerly assigned using setRecordParser()"
        );
    }
}

