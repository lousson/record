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
 *  Lousson\Record\Generic\GenericRecordFactoryBasicTest class definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record\Generic;

/** Dependencies: */
use Lousson\Record\Generic\GenericRecordFactoryTest;
use Lousson\Record\AnyRecordBuilder;
use Lousson\Record\AnyRecordParser;
use Lousson\Record\Builtin\BuiltinRecordFactory;
use Lousson\Record\Builtin\BuiltinRecordHandlerJSON;
use Lousson\Record\Builtin\BuiltinRecordParserINI;
use Lousson\Record\Generic\GenericRecordFactory;
use Lousson\Record\Generic\GenericRecordHandler;

/**
 *  A test case for the generic record factory
 *
 *  @since      lousson/Lousson_Record-0.1.0
 *  @package    org.lousson.record
 *  @link       http://www.phpunit.de/manual/current/en/
 */
class GenericRecordFactoryBasicTest extends GenericRecordFactoryTest
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
    public function getGenericRecordFactory()
    {
        $factory = new GenericRecordFactory();

        $iniParser = new BuiltinRecordParserINI();
        $factory->setRecordParser("application/textedit", $iniParser);

        $jsonHandler = new BuiltinRecordHandlerJSON();
        $factory->setRecordBuilder("application/json", $jsonHandler);
        $factory->setRecordParser("application/json", $jsonHandler);

        $handler = new GenericRecordHandler($jsonHandler, $jsonHandler);
        $factory->setRecordHandler("text/json", $handler);
        $factory->setRecordBuilder("text/json", $jsonHandler);

        return $factory;
    }

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
    public function provideBuilderMediaTypes()
    {
        return array(
            array("application/json"),
            array("text/json"),
        );
    }

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
    public function provideParserMediaTypes()
    {
        return array(
            array("application/json"),
            array("application/textedit"),
        );
    }
}

