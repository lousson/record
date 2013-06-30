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
 *  Lousson\Record\AbstractRecordManagerTest class definition
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

/**
 *  An abstract test case for record managers
 *
 *  @since      lousson/Lousson_Record-0.5.0
 *  @package    org.lousson.record
 */
abstract class AbstractRecordManagerTest extends AbstractRecordTest
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
    abstract public function getRecordManager();

    /**
     *  Provide smoke test parameters
     *
     *  The provideSmokeTestParameters() method returns a list of multiple
     *  items, each of whose is an array of either one or two items:
     *
     *- A data record array and
     *- A media type string, if any
     *
     *  @return array
     *          A list of smoke test parameters is returned on success
     */
    public function provideSmokeTestParameters()
    {
        $dataParameters = $this->provideValidData();
        $typeParameters = $this->provideValidMediaTypes();

        $parameters = $dataParameters;

        foreach ($typeParameters as $item) {
            foreach ($dataParameters as $data) {
                $parameters[] = array($data[0], $item[0]);
            }
        }

        return $parameters;
    }

    /**
     *  Perform a smoke test
     *
     *  The smokeTest() method tests the basic functionality of the record
     *  manager: It uses the saveRecord() method to store the given $data,
     *  and loadRecord() to retrieve it afterwards. Finally, a comparision
     *  between the stored and retrieved records is made, in order to check
     *  that the information hasn't been corrupted.
     *
     *  @param  array               $data           The record test data
     *  @param  string              $type           The record type, if any
     *
     *  @dataProvider               provideSmokeTestParameters
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function smokeTest(array $data, $type = null)
    {
        $manager = $this->getRecordManager();
        $managerClass = get_class($manager);

        $this->assertInstanceOf(
            self::I_MANAGER, $manager, sprintf(
            "The %s::getRecordManager() method must return an instance ".
            "of the %s interface",
            get_class($this), self::I_MANAGER
        ));

        $directory = sys_get_temp_dir();
        $prefix = "lousson-test";
        $location = tempnam($directory, $prefix);

        $status = $manager->saveRecord($location, $data, $type);
        $this->assertEquals(
            null, $status, sprintf(
            "The %s::saveRecord() method must not return a value",
            $managerClass
        ));

        $record = $manager->loadRecord($location, $type);
        $this->assertEquals(
            $data, $record, sprintf(
            "The %s::loadRecord() method must return the same value ".
            "formerly saved via saveRecord()", $managerClass
        ));
    }
}

