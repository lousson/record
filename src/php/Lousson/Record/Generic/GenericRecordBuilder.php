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
 *  Lousson\Record\Generic\GenericRecordBuilder class definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record\Generic;

/** Dependencies: */
use Lousson\Record\AnyRecordBuilder;
use Lousson\Record\AnyRecordException;
use Lousson\Record\Builtin\BuiltinRecordHandler;
use Lousson\Record\Error\RecordRuntimeError;
use Closure;
use Exception;

/**
 *  A generic record builder
 *
 *  The GenericRecordBuilder is an implementation of the builder interface
 *  that allows the definition of a closure to provide the actual building
 *  logic.
 *
 *  @since      lousson/Lousson_Record-0.1.0
 *  @package    org.lousson.record
 */
class GenericRecordBuilder
    extends BuiltinRecordHandler
    implements AnyRecordBuilder
{
    /**
     *  Create record builder instances
     *
     *  The constructor requires the caller to provide a closure that
     *  implements the same interface as the buildRecord() method and
     *  the actual builder logic.
     *
     *  @param  \Closure    $callback       The record builder callback
     */
    public function __construct(Closure $callback)
    {
        $this->callback = $callback;
    }

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

        try {
            $callback = $this->callback;
            $sequence = $callback($record);
        }
        catch (AnyRecordException $error) {
            /* Allowed by the AnyRecordBuilder interface */
            throw $error;
        }
        catch (Exception $error) {
            $errorClass = get_class($error);
            $message = "Failed to build record; caught $errorClass";
            $code = RecordRuntimeError::E_INTERNAL_ERROR;
            throw new RecordRuntimeError($message, $code);
        }

        if (!is_string($sequence)) {
            $sequenceType = gettype($sequence);
            $message = "Failed to build record; got $sequenceType";
            $code = RecordRuntimeError::E_INTERNAL_ERROR;
            throw new RecordRuntimeError($message, $code);
        }

        return $sequence;
    }

    /**
     *  The record builder callback
     *
     *  @var \Closure
     */
    private $callback;
}

