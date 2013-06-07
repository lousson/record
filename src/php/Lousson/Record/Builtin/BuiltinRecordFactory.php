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
 *  Lousson\Record\Builtin\BuiltinRecordFactory class definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record\Builtin;

/** Dependencies: */
use Lousson\Record\AnyRecordFactory;
use Lousson\Record\Builtin\BuiltinRecordUtil;
use Lousson\Record\Error\RuntimeRecordError;
use Lousson\Record\Generic\GenericRecordHandler;

/**
 *  The builtin record factory
 *
 *  The BuiltinRecordFactory class provides an implementation of the
 *  AnyRecordFactory interface that is aware of all builtin record parsers
 *  and builders by default.
 *
 *  @since      lousson/Lousson_Record-0.1.0
 *  @package    org.lousson.record
 */
class BuiltinRecordFactory implements AnyRecordFactory
{
    /**
     *  Obtain a record parser
     *
     *  The getRecordParser() method either returns a record parser that
     *  is associated with the given media $type or, in case no parser is
     *  available, raises an exception.
     *
     *  @param  string              $type       The media type
     *
     *  @return \Lousson\Record\AnyRecordParser
     *          A record parser instance is returned on success
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case no parser is available for the given $type
     */
    public function getRecordParser($type)
    {
        $normalizedType = BuiltinRecordUtil::normalizeType($type);

        if (isset($this->parsers[$normalizedType])) {
            $class = $this->parsers[$normalizedType];
            $parser = new $class();
        }
        else {
            $parser = $this->getRecordHandler($type);
        }

        return $parser;
    }

    /**
     *  Obtain a record builder
     *
     *  The getRecordBuilder() method either returns a record builder that
     *  is associated with the given media $type or, in case no builder is
     *  available, raises an exception.
     *
     *  @param  string              $type       The media type
     *
     *  @return \Lousson\Record\AnyRecordBuilder
     *          A record builder instance is returned on success
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case no builder is available for the given $type
     */
    public function getRecordBuilder($type)
    {
        $builder = $this->getRecordHandler($type);
        return $builder;
    }

    /**
     *  Obtain a record handler
     *
     *  The getRecordHandler() method either returns a record handler that
     *  is associated with the given media $type or, in case no handler is
     *  available, raises an exception.
     *
     *  @param  string              $type       The media type
     *
     *  @return \Lousson\Record\AnyRecordHandler
     *          A record handler instance is returned on success
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          Raised in case no handler is available for the given $type
     */
    public function getRecordHandler($type)
    {
        $normalizedType = BuiltinRecordUtil::normalizeType($type);

        if (isset($this->handlers[$normalizedType])) {
            $class = $this->handlers[$normalizedType];
            $handler = new $class();
        }
        else {
            $message = "Could not provide \"$normalizedType\" handler";
            $code = RuntimeRecordError::E_NOT_SUPPORTED;
            throw new RuntimeRecordError($message, $code);
        }

        return $handler;
    }

    /**
     *  Determine the availability of a parser
     *
     *  The hasRecordBuilder() method determines whether the a record
     *  parser associated with the given media $type is available.
     *
     *  @param  string              $type       The media type
     *
     *  @return bool
     *          TRUE is returned if a parser for the given $type is
     *          available, FALSE otherwise
     */
    public function hasRecordParser($type)
    {
        $normalizedType = BuiltinRecordUtil::normalizeType($type);
        $hasRecordParser =
            isset($this->handlers[$normalizedType]) ||
            isset($this->parsers[$normalizedType]);

        return $hasRecordParser;
    }

    /**
     *  Determine the availability of a builder
     *
     *  The hasRecordBuilder() method determines whether the a record
     *  builder associated with the given media $type is available.
     *
     *  @param  string              $type       The media type
     *
     *  @return bool
     *          TRUE is returned if a builder for the given $type is
     *          available, FALSE otherwise
     */
    public function hasRecordBuilder($type)
    {
        $normalizedType = BuiltinRecordUtil::normalizeType($type);
        $hasRecordBuilder =
            isset($this->handlers[$normalizedType]) ||
            isset($this->builders[$normalizedType]);

        return $hasRecordBuilder;
    }

    /**
     *  Determine the availability of a handler
     *
     *  The hasRecordHandler() method determines whether the a record
     *  handler associated with the given media $type is available.
     *
     *  @param  string              $type       The media type
     *
     *  @return bool
     *          TRUE is returned if a handler for the given $type is
     *          available, FALSE otherwise
     */
    public function hasRecordHandler($type)
    {
        $hasRecordParser = $this->hasRecordParser($type);
        $hasRecordBuilder = $this->hasRecordBuilder($type);
        $hasRecordHandler = $hasRecordParser && $hasRecordBuilder;

        return $hasRecordHandler;
    }

    /**
     *  A register of builtin parser classes
     *
     *  @var array
     */
    private $parsers = array(
        "application/textedit" =>
            "Lousson\\Record\\Builtin\\Parser\\BuiltinRecordParserINI",
        "zz-application/zz-winassoc-ini" =>
            "Lousson\\Record\\Builtin\\Parser\\BuiltinRecordParserINI",
    );

    /**
     *  A register of builtin handler classes
     *
     *  @var array
     */
    private $handlers = array(
        "application/json" =>
            "Lousson\\Record\\Builtin\\Handler\\BuiltinRecordHandlerJSON",
        "application/vnd.php.serialized" =>
            "Lousson\\Record\\Builtin\\Handler\\BuiltinRecordHandlerPHP",
        "text/json" =>
            "Lousson\\Record\\Builtin\\Handler\\BuiltinRecordHandlerJSON",
        "text/x-json" =>
            "Lousson\\Record\\Builtin\\Handler\\BuiltinRecordHandlerJSON",
    );
}

