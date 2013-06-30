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
 *  Lousson\Record\AnyRecordManager interface definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record;

/**
 *  An interface for record managers
 *
 *  The Lousson\Record\AnyRecordManager interface defines an API for saving
 *  and loading of data records.
 *
 *  @since      lousson/Lousson_Record-0.5.0
 *  @package    org.lousson.record
 */
interface AnyRecordManager
{
    /**
     *  Load a data record
     *
     *  The loadRecord() method attempts to load a record from the given
     *  $location, deserialize it according to the given $type, and return
     *  an array representing the record's data.
     *
     *  If the optional $type parameter is omitted, the implementation may
     *  attempt to apply some detection mechanism or a common default.
     *
     *  @param  string              $location       The record location
     *  @param  string              $type           The record media type
     *
     *  @return array
     *          An array of record data is returned on success
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Any exception raised implements this interface
     *
     *  @throws \RuntimeException
     *          Raised in case of an internal error
     *
     *  @throws \InvalidArgumentException
     *          Raised in case a parameter is considered invalid
     */
    public function loadRecord($location, $type = null);

    /**
     *  Save a data record
     *
     *  The saveRecord() method attempts to serialize the record $data to
     *  be a representation of the given $type, before storing it at the
     *  given $location.
     *
     *  If the optional $type parameter is omitted, the implementation may
     *  attempt to apply some detection mechanism or a common default.
     *
     *  @param  string              $location       The record location
     *  @param  array               $data           The record data
     *  @param  string              $type           The record media type
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Any exception raised implements this interface
     *
     *  @throws \RuntimeException
     *          Raised in case of an internal error
     *
     *  @throws \InvalidArgumentException
     *          Raised in case a parameter is considered invalid
     */
    public function saveRecord($location, array $data, $type = null);
}

