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
 *  Lousson\Record\Builtin\Handler\BuiltinRecordHandlerYAML definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Attila G. Levai <sgnl19 at gmail.com>
 *  @filesource
 */
namespace Lousson\Record\Builtin\Handler;

/** Interfaces: */
use Lousson\Record\AnyRecordHandler;

/** Dependencies: */
use Lousson\Record\Builtin\BuiltinRecordHandler;
use Symfony\Component\Yaml;

/** Exceptions: */
use Lousson\Record\Error\RecordArgumentError;
use Lousson\Record\Error\RecordRuntimeError;

/**
 *  A YAML record handler
 *
 *  @since      lousson/Lousson_Record-0.6.0
 *  @package    org.lousson.record
 */
class BuiltinRecordHandlerYAML
    extends BuiltinRecordHandler
    implements AnyRecordHandler
{
    /**
     *  Create a handler instance
     *
     *  The constructor allows the caller to provide YAML parser and
     *  dumper instances to be used instead of those that would otherwise
     *  be created at runtime.
     *
     *  @param  Yaml\Parser         $parser         The YAML parser
     *  @param  Yaml\Dumper         $dumper         The YAML dumper
     */
    public function __construct(
        Yaml\Parser $parser = null,
        Yaml\Dumper $dumper = null
    ) {
        $this->parser = $parser;
        $this->dumper = $dumper;
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

        if (!isset($this->dumper)) {
            $this->dumper = new Yaml\Dumper();
        }

        try {
            $sequence = $this->dumper->dump($record);
        }
        catch (\Exception $error) {
            $class = get_class($error);
            $message = "Failed to build YAML record: Caught $class";
            $code = RecordRuntimeError::E_UNKNOWN;
            throw new RecordRuntimeError($message, $code, $error);
        }

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
        if (!isset($this->parser)) {
            $this->parser = new Yaml\Parser();
        }

        try {
            $data = $this->parser->parse($sequence);
        }
        catch (\Exception $error) {
            $class = get_class($error);
            $message = "Could not parse YAML record: Caught $class";
            $code = RecordArgumentError::E_UNKNOWN;
            throw new RecordArgumentError($message, $code, $error);
        }

        $record = $this->normalizeOutputData($data);
        return $record;
    }

    /**
     *  The YAML parser instance in use
     *
     *  @var \Symfony\Component\Yaml\Parser
     */
    private $parser;

    /**
     *  The YAML dumper instance in use
     *
     *  @var \Symfony\Component\Yaml\Dumper
     */
    private $dumper;
}

