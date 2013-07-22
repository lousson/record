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
 *  Lousson\Record\Builtin\BuiltinRecordHandler class definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record\Builtin;

/** Dependencies: */
use Lousson\Record\Builtin\BuiltinRecordUtil;
use Lousson\Record\Error\InvalidRecordError;
use Lousson\Record\Error\RecordRuntimeError;

/**
 *  An abstract record handler
 *
 *  The BuiltinRecordHandler class has been introduced in order to provide
 *  functionaliy common between record handlers, especially for the builtin
 *  and generic implementations that are available by default.
 *
 *  @since      lousson/Lousson_Record-0.2.0
 *  @package    org.lousson.record
 */
abstract class BuiltinRecordHandler
{
    /**
     *  Normalize data records
     *
     *  The normalizeData() method is used internally to verify and
     *  normalize record $data a record handler is provided with.
     *
     *  @param  array               $data       The data to process
     *
     *  @throws \Lousson\Record\Error\InvalidRecordError
     *          Raised in case the given $data is invalid or malformed
     */
    final protected function normalizeInputData(array $data)
    {
        $normalized = BuiltinRecordUtil::normalizeData($data);
        return $normalized;
    }

    /**
     *  Normalize data records
     *
     *  The normalizeData() method is used internally to verify and
     *  normalize record $data a record handler has generated.
     *
     *  @param  array               $data       The data to process
     *
     *  @throws \Lousson\Record\Error\RecordRuntimeError
     *          Raised in case the given $data is invalid or malformed
     */
    final protected function normalizeOutputData(array $data)
    {
        try {
            $normalized = BuiltinRecordUtil::normalizeData($data);
            return $normalized;
        }
        catch (InvalidRecordError $error) {
            $message = $error->getMessage();
            $code = $error->getCode();
            throw new RecordRuntimeError($message, $code);
        }
    }
}

