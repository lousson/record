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
 *  Lousson\Record\Builtin\BuiltinRecordManagerTest class definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record\Builtin;

/** Dependencies: */
use Lousson\Record\AbstractRecordManagerTest;
use Lousson\Record\Builtin\BuiltinRecordManager;

/**
 *  A test case for the builtin record manager
 *
 *  @since      lousson/Lousson_Record-0.5.0
 *  @package    org.lousson.record
 */
final class BuiltinRecordManagerTest extends AbstractRecordManagerTest
{
    /**
     *  Obtain the record manager to test
     *
     *  The getRecordManager() method returns the record manager instance
     *  that is used in the tests.
     *
     *  @return \Lousson\Record\AnyRecordManager
     *          A record manager instance is returned on success
     */
    public function getRecordManager()
    {
        $manager = new BuiltinRecordManager();
        return $manager;
    }

    /**
     *  Provide valid media type parameters
     *
     *  The provideValidMediaTypes() method returns an array of multiple
     *  items, each of whose is an array with one string item representing
     *  a wellformed media type.
     *
     *  @return array
     *          A list of media type parameters is returned on success
     */
    public function provideValidMediaTypes()
    {
        $types[][] = "application/json";
        $types[][] = "application/vnd.php.serialized";

        return $types;
    }

    /**
     *  Test the saveRecord() method
     *
     *  The testSaveRecordError() method is a test case for the manager's
     *  saveRecord() method. It verifies that any attempt to save a record
     *  at an invalid location results in an exception.
     *
     *  @expectedException  Lousson\Record\Error\InvalidRecordError
     *  @test
     *
     *  @throws \Lousson\Record\Error\InvalidRecordError
     *          Raised in case the test is successful
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testSaveRecordError()
    {
        $manager = $this->getRecordManager();
        $manager->saveRecord("invalid://foo/bar/baz", array());
    }

    /**
     *  Test the loadRecord() method
     *
     *  The testLoadRecordError() method is a test case for the manager's
     *  loadRecord() method. It verifies that any attempt to load a record
     *  from an invalid location results in an exception.
     *
     *  @expectedException  Lousson\Record\Error\InvalidRecordError
     *  @test
     *
     *  @throws \Lousson\Record\Error\InvalidRecordError
     *          Raised in case the test is successful
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testLoadRecordError()
    {
        $manager = $this->getRecordManager();
        $manager->loadRecord("invalid://foo/bar/baz");
    }
}

