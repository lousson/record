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
 *  Lousson\Record\Generic\GenericRecordParser class definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record\Generic;

/** Dependencies: */
use Lousson\Record\AnyRecordParser;
use Lousson\Record\AnyRecordException;
use Lousson\Record\Builtin\BuiltinRecordHandler;
use Lousson\Record\Error\RecordRuntimeError;
use Closure;
use Exception;

/**
 *  A generic record parser
 *
 *  The GenericRecordParser is an implementation of the parser interface
 *  that allows the definition of a closure to provide the actual parsing
 *  logic.
 *
 *  @since      lousson/Lousson_Record-0.1.0
 *  @package    org.lousson.record
 */
class GenericRecordParser
    extends BuiltinRecordHandler
    implements AnyRecordParser
{
    /**
     *  Create record parser instances
     *
     *  The constructor requires the caller to provide a closure that
     *  implements the same interface as the parseRecord() method and
     *  the actual parser logic.
     *
     *  @param  \Closure    $callback       The record parser callback
     */
    public function __construct(Closure $callback)
    {
        $this->callback = $callback;
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
        try {
            $callback = $this->callback;
            $data = $callback((string) $sequence);
        }
        catch (AnyRecordException $error) {
            /* Allowed by the AnyRecordParser interface */
            throw $error;
        }
        catch (Exception $error) {
            $errorClass = get_class($error);
            $message = "Failed to parse record; caught $errorClass";
            $code = RecordRuntimeError::E_INTERNAL_ERROR;
            throw new RecordRuntimeError($message, $code);
        }

        if (!is_array($data)) {
            $dataType = gettype($data);
            $message = "Failed to parse record; got $dataType";
            $code = RecordRuntimeError::E_INTERNAL_ERROR;
            throw new RecordRuntimeError($message, $code);
        }

        $record = $this->normalizeOutputData($data);
        return $record;
    }

    /**
     *  The record parser callback
     *
     *  @var \Closure
     */
    private $callback;
}

