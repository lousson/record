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
 *  Lousson\Record\Generic\GenericRecordBuilderTest definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record\Generic;

/** Dependencies: */
use Lousson\Record\Builtin\Handler\BuiltinRecordHandlerPHP;
use Lousson\Record\Builtin\Handler\BuiltinRecordHandlerPHPTest;
use Lousson\Record\Error\RuntimeRecordError;
use Lousson\Record\Generic\GenericRecordBuilder;
use Closure;
use OutOfBoundsException;

/**
 *  A test case for the generic record builder
 *
 *  @since      lousson/Lousson_Record-0.1.0
 *  @package    org.lousson.record
 *  @link       http://www.phpunit.de/manual/current/en/
 */
final class GenericRecordBuilderTest
    extends BuiltinRecordHandlerPHPTest
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
        $callback = function(array $data) {
            $builder = new BuiltinRecordHandlerPHP();
            $sequence = $builder->buildRecord($data);
            return $sequence;
        };

        $builder = new GenericRecordBuilder($callback);
        return $builder;
    }

    /**
     *  Provide faulty callbacks
     *
     *  The provideInvalidCallbacks() method is a data provider for the
     *  testCallbackError() method that returns an array of multiple items,
     *  each of whose is an array with one item: A Closure that either
     *  raises an exception or behaves not compliant with interface.
     *
     *  @return array
     *          A list of callback paramters is returned on success
     */
    public function provideInvalidCallbacks()
    {
        $cb[][] = function(array $data) {
            throw new OutOfBoundsException("FOO");
        };

        $cb[][] = function(array $data) {
            throw new RuntimeRecordError("BAR");
        };

        $cb[][] = function(array $data) {
            return array("BAZ");
        };

        return $cb;
    }

    /**
     *  Test the buildRecord() method
     *
     *  The testCallbackError() method verifies that the behavior of
     *  the record builder is compliant with the builder interface even
     *  in case the callback is not.
     *
     *  @param  \Closure            $callback   The builder callback
     *
     *  @dataProvider               provideInvalidCallbacks
     *  @expectedException          \Lousson\Record\AnyRecordException
     *  @test
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case the test is successful
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testCallbackError(Closure $callback)
    {
        $builder = new GenericRecordBuilder($callback);
        $builder->buildRecord(array("foo" => "bar"));
    }
}

