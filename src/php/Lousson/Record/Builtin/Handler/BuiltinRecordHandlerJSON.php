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
 *  Lousson\Record\Builtin\Handler\BuiltinRecordHandlerJSON definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record\Builtin\Handler;

/** Dependencies: */
use Lousson\Record\AnyRecordHandler;
use Lousson\Record\Builtin\BuiltinRecordHandler;
use Lousson\Record\Error\InvalidRecordError;
use Lousson\Record\Error\RecordRuntimeError;

/**
 *  A JSON record handler
 *
 *  @since      lousson/Lousson_Record-0.2.0
 *  @package    org.lousson.record
 */
class BuiltinRecordHandlerJSON
    extends BuiltinRecordHandler
    implements AnyRecordHandler
{
    /**
     *  Build record content
     *
     *  The buildRecord() method returns a byte sequence representing the
     *  given $record in its serialized form.
     *
     *  @param  array               $data       The record's data
     *
     *  @return string
     *          The serialized record is returned on success
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case of malformed $data or internal errors
     */
    public function buildRecord(array $data)
    {
        $record = $this->normalizeInputData($data);
        $setup = ini_set("track_errors", true);
        $php_errormsg = "UKNOWN ERROR";
        $sequence = json_encode($record, JSON_HEX_TAG|JSON_HEX_APOS);
        $error = $php_errormsg;
        ini_set("track_errors", $setup);
        $this->checkRecordSequence($sequence, $error);
        return $sequence;
    }

    /**
     *  Parse record content
     *
     *  The parseRecord() method returns an array representing the given
     *  byte $sequence in its unserialized form.
     *
     *  @param  string              $sequence   The record's byte sequence
     *
     *  @return array
     *          The unserialized record is returned on success
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Indicates a malformed $sequence or an internal error
     */
    public function parseRecord($sequence)
    {
        $setup = ini_set("track_errors", true);
        $php_errormsg = "UNKNOWN ERROR";
        $data = json_decode($sequence, true, 512);
        $error = $php_errormsg;
        ini_set("track_errors", $setup);

        if (!is_array($data)) {
            $message = "Could not parse JSON record: $error";
            $code = InvalidRecordError::E_INTERNAL_ERROR;
            throw new InvalidRecordError($message, $code);
        }

        $record = $this->normalizeOutputData($data);
        return $record;
    }

    /**
     *  Verify byte sequences built
     *
     *  The checkRecordSequence() method is used internally to check the
     *  byte sequence built by buildRecord(). This used to be done inline,
     *  but since it's tricky to actually trigger a scenario where the
     *  operation fails, which made the snippet hard to test, it has been
     *  moved into it's own method.
     *
     *  @param  string              $sequence       The byte sequence
     *  @param  string              $error          The error message
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case $sequence is FALSE
     */
    private function checkRecordSequence($sequence, $error)
    {
        if (false === $sequence) {
            $message = "Failed to build JSON record: $error";
            $code = RecordRuntimeError::E_INTERNAL_ERROR;
            throw new RecordRuntimeError($message, $code);
        }
    }
}

