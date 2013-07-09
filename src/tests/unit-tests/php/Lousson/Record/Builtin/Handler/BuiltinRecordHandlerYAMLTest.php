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
 *  Lousson\Record\Builtin\Handler\BuiltinRecordHandlerYAMLTest definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Attila G. Levai <sgnl19 at gmail.com>
 *  @filesource
 */
namespace Lousson\Record\Builtin\Handler;

/** Dependencies: */
use Lousson\Record\AbstractRecordHandlerTest;
use Lousson\Record\Builtin\Handler\BuiltinRecordHandlerYAML;
use ReflectionException;
use ReflectionMethod;

/**
 *  A test case for the builtin YAML record builder
 *
 *  @since      lousson/Lousson_Record-0.6.0
 *  @package    org.lousson.record
 */
final class BuiltinRecordHandlerYAMLTest
    extends AbstractRecordHandlerTest
{
    /**
     *  Obtain the record builder to test
     *
     *  The getRecordBuilder() method returns the record builder instance
     *  that is used in the tests or NULL, in case the test does not have
     *  an associated builder.
     *
     *  @return \Lousson\Record\AnyRecordBuilder
     *          A record builder instance is returned on success
     */
    public function getRecordBuilder()
    {
        $builder = new BuiltinRecordHandlerYAML();
        return $builder;
    }

    /**
     *  Obtain the record parser to test
     *
     *  The getRecordParser() method returns the record parser instance
     *  that is used in the tests or NULL, in case the test does not have
     *  an associated parser.
     *
     *  @return \Lousson\Record\AnyRecordBuilder
     *          A record builder instance is returned on success
     */
    public function getRecordParser()
    {
        $parser = new BuiltinRecordHandlerYAML();
        return $parser;
    }

    /**
     *  Provide valid parseRecord() parameters
     *
     *  The provideValidRecordBytes() method returns an array of multiple
     *  items, each of whose is an array with one item; a sequence of bytes
     *  representing valid record data.
     *
     *  @return array
     *          A list of parseRecord() parameters is returned on success
     */
    public function provideValidRecordBytes()
    {
        $data[][] = '{"foo":"bar","baz":[0,1,2,3,4,5]}';
        $data[][] = '{"foo":{"bar":"baz"}}';
        $data[][] = '{"foobar":null}';

        return $data;
    }

    /**
     *  Provide invalid parseRecord() parameters
     *
     *  The provideInvalidRecordBytes() method returns an array of multiple
     *  items, each of whose is an array with one item; a sequence of bytes
     *  representing invalid record data.
     *
     *  @return array
     *          A list of parseRecord() parameters is returned on success
     */
    public function provideInvalidRecordBytes()
    {
        $data[][] = '{"foo":"bar","0 1 2":"baz"}';

        return $data;
    }

    /**
     *  Test the buildRecord() method
     *
     +  The testBuildRecordException() method is a test case to verify
     *  that exceptions raised by the Yaml\Dumper are handled properly.
     *
     *  @expectedException          Lousson\Record\AnyRecordException
     *  @test
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the test is successful
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testBuildRecordException()
    {
        $dumper = $this->getMock("Symfony\Component\Yaml\Dumper");
        $dumper
            ->expects($this->once())
            ->method("dump")
            ->will($this->throwException(new \DomainException));

        $handler = new BuiltinRecordHandlerYAML(null, $dumper);
        $handler->buildRecord(array());
    }

    /**
     *  Test the parseRecprd() method
     *
     +  The testBuildRecordException() method is a test case to verify
     *  that exceptions raised by the Yaml\Parser are handled properly.
     *
     *  @expectedException          Lousson\Record\AnyRecordException
     *  @test
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the test is successful
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testParseRecordException()
    {
        $parser = $this->getMock("Symfony\Component\Yaml\Parser");
        $parser
            ->expects($this->once())
            ->method("parse")
            ->will($this->throwException(new \DomainException));

        $handler = new BuiltinRecordHandlerYAML($parser, null);
        $handler->parseRecord("foo. bar? baz!");
    }
}

