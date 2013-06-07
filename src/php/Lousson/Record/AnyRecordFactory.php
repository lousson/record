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
 *  Lousson\Record\AnyRecordFactory interface declaration
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record;

/**
 *  An interface for record factories
 *
 *  The AnyRecordFactory interface declares the API to be provided by any
 *  class that represents a factory for record builders and parsers:
 *  Callers request either entity by specifying the media type it shall
 *  support, and factories either return a concrete instance or raise an
 *  exception.
 *
 *  @since      lousson/Lousson_Record-0.1.0
 *  @package    org.lousson.record
 *  @link       http://en.wikipedia.org/wiki/Internet_media_type
 *  @link       http://www.iana.org/assignments/media-types
 */
interface AnyRecordFactory
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
    public function getRecordParser($type);

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
    public function getRecordBuilder($type);

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
    public function getRecordHandler($type);

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
    public function hasRecordParser($type);

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
    public function hasRecordBuilder($type);

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
    public function hasRecordHandler($type);
}

