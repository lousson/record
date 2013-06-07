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
 *  Lousson\Record\Generic\GenericRecordFactory class definition
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
use Lousson\Record\AnyRecordFactory;
use Lousson\Record\AnyRecordHandler;
use Lousson\Record\AnyRecordParser;
use Lousson\Record\Builtin\BuiltinRecordUtil;
use Lousson\Record\Error\RuntimeRecordError;
use Lousson\Record\Generic\GenericRecordHandler;

/**
 *  A generic record factory
 *
 *  The GenericRecordFactory class is the default implementation of the
 *  AnyRecordFactory interface. It is a register that associates record
 *  builders and parsers according to the media types they support.
 *  Furthermore, it allows to specify a nested factory to be used as a
 *  fallback in case a requested media type is not associated with an
 *  entity yet.
 *
 *  @since      lousson/Lousson_Record-0.1.0
 *  @package    org.lousson.record
 *  @link       http://en.wikipedia.org/wiki/Internet_media_type
 *  @link       http://www.iana.org/assignments/media-types
 */
class GenericRecordFactory implements AnyRecordFactory
{
    /**
     *  Create a factory instance
     *
     *  The constructor allows the definition of a nested $base factory,
     *  to be used whenever a requested media type is not associated with
     *  an entity yet.
     *
     *  @param  AnyRecordFactory    $base       The nested factory
     */
    public function __construct(AnyRecordFactory $base = null)
    {
        $this->base = $base;
    }

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
            $parser = $this->parsers[$normalizedType];
        }
        else if (null !== $this->base) {
            $parser = $this->base->getRecordParser($type);
        }
        else {
            $message = "Could not provide \"$normalizedType\" parser";
            $code = RuntimeRecordError::E_NOT_SUPPORTED;
            throw new RuntimeRecordError($message, $code);
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
        $normalizedType = BuiltinRecordUtil::normalizeType($type);

        if (isset($this->builders[$normalizedType])) {
            $builder = $this->builders[$normalizedType];
        }
        else if (null !== $this->base) {
            $builder = $this->base->getRecordBuilder($type);
        }
        else {
            $message = "Could not provide \"$normalizedType\" builder";
            $code = RuntimeRecordError::E_NOT_SUPPORTED;
            throw new RuntimeRecordError($message, $code);
        }

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
            $handler = $this->handlers[$normalizedType];
        }
        else if (null !== $this->base &&
                $this->base->hasRecordHandler($normalizedType)) {
            $handler = $this->base->getRecordHandler($normalizedType);
        }
        else {
            $parser = $this->getRecordParser($normalizedType);
            $builder = $this->getRecordBuilder($normalizedType);
            $handler = new GenericRecordHandler($parser, $builder);
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
        $hasRecordParser = false;

        if (isset($this->parsers[$normalizedType])) {
            $hasRecordParser = true;
        }
        else if (null !== $this->base) {
            $hasRecordParser = $this->base->hasRecordParser($type);
        }

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
        $hasRecordBuilder = false;

        if (isset($this->builders[$normalizedType])) {
            $hasRecordBuilder = true;
        }
        else if (null !== $this->base) {
            $hasRecordBuilder = $this->base->hasRecordBuilder($type);
        }

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
        if ($this->hasRecordParser($type) &&
                $this->hasRecordBuilder($type)) {
            $hasRecordHandler = true;
        }
        else if (null !== $this->base) {
            $hasRecordHandler = $this->base->hasRecordHandler($type);
        }
        else {
            $hasRecordHandler = false;
        }

        return $hasRecordHandler;
    }

    /**
     *  Assign a record parser
     *
     *  The setRecordParser() method associates the given media $type
     *  with the record $parser provided.
     *
     *  @param  string              $type       The media type
     *  @param  AnyRecordParser     $parser     The record parser
     */
    public function setRecordParser($type, AnyRecordParser $parser)
    {
        $normalizedType = BuiltinRecordUtil::normalizeType($type);
        $this->parsers[$normalizedType] = $parser;
    }

    /**
     *  Assign a record builder
     *
     *  The setRecordBuilder() method associates the given media $type
     *  with the record $builder provided.
     *
     *  @param  string              $type       The media type
     *  @param  AnyRecordBuilder    $builder    The record builder
     */
    public function setRecordBuilder($type, AnyRecordBuilder $builder)
    {
        $normalizedType = BuiltinRecordUtil::normalizeType($type);
        $this->builders[$normalizedType] = $builder;
    }

    /**
     *  Assign a record handler
     *
     *  The setRecordHandler() method associated the given media $type
     *  with the record $handler provided. Note that this method will also
     *  associate the handler as a parser and builder for the media $type,
     *  unless either entity has been assigned before.
     *
     *  @param  string              $type       The media type
     *  @param  AnyRecordHandler    $handler    The record handler
     */
    public function setRecordHandler($type, AnyRecordHandler $handler)
    {
        $normalizedType = BuiltinRecordUtil::normalizeType($type);
        $this->handlers[$normalizedType] = $handler;

        if (!isset($this->parsers[$normalizedType])) {
            $this->parsers[$normalizedType] = $handler;
        }

        if (!isset($this->builders[$normalizedType])) {
            $this->builders[$normalizedType] = $handler;
        }
    }

    /**
     *  The nested factory, if any
     *
     *  @var \Lousson\Record\AnyRecordFactory
     */
    private $base;

    /**
     *  A register for record parsers
     *
     *  @var array
     */
    private $parsers = array();

    /**
     *  A register for record builders
     *
     *  @var array
     */
    private $builders = array();
}

