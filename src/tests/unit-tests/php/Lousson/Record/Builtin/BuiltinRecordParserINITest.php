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
 *  Lousson\Record\Builtin\BuiltinRecordParserINITest definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record\Builtin;

/** Dependencies: */
use Lousson\Record\AbstractRecordHandlerTest;
use Lousson\Record\Builtin\BuiltinRecordParserINI;
use ReflectionException;
use ReflectionMethod;

/**
 *  A test case for the builtin INI record parser
 *
 *  @since      lousson/Lousson_Record-0.1.0
 *  @package    org.lousson.record
 *  @link       http://www.phpunit.de/manual/current/en/
 */
class BuiltinRecordParserINITest
    extends AbstractRecordHandlerTest
{
    /**
     *  Obtain the record parser to test
     *
     *  The getRecordParser() method returns the record parser instance
     *  that is used in the tests.
     *
     *  @return \Lousson\Record\AnyRecordParser
     *          A record parser instance is returned on success
     */
    public function getRecordParser()
    {
        $parser = new BuiltinRecordParserINI();
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
        $data[][] = 'foo = bar';
        $data[][] = 'foo[bar] = baz';
        $data[][] = "[intern]
            foo = baz
            bar = baz
        ";

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
        $data[][] = "foo[] = 1\nfoo[bar] = baz";
        $data[][] = "foo[bar] = 1\nfoo[b a z] = 1";

        return $data;
    }

    /**
     *  Test the error handling
     *
     *  The testCheckRecordData() method is a test case for scenarios
     *  where the INI parsing in parseRecord() fails.
     *
     *  @expectedException          \Lousson\Record\AnyRecordException
     *  @test
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the test is successful
     *
     *  @throws \ReflectionException
     *          Raised in case of an internal error
     */
    public function testCheckRecordData()
    {
        try {
            $builder = $this->getRecordParser();
            $method = new ReflectionMethod($builder, "checkRecordData");
            $method->setAccessible(true);
            $method->invoke($builder, false, "UNKNOWN ERROR");
        }
        catch (ReflectionException $error) {
            $this->markTestSkipped();
        }
    }
}

