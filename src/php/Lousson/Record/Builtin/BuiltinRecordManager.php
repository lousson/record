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
 *  Lousson\Record\Builtin\BuiltinRecordManager class definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record\Builtin;

/** Interfaces: */
use Lousson\Record\AnyRecordFactory;
use Lousson\Record\AnyRecordManager;

/** Dependencies: */
use Lousson\Record\Builtin\BuiltinRecordFactory;

/** Exceptions: */
use Lousson\Record\Error\RecordArgumentError;

/**
 *  The default record manager implementation
 *
 *  The Lousson\Record\Builtin\BuiltinRecordManager class is the default
 *  implementation of the AnyRecordManager interface.
 *
 *  @since      lousson/Lousson_Record-0.5.0
 *  @package    org.lousson.record
 */
class BuiltinRecordManager implements AnyRecordManager
{
    /**
     *  The default record media type assumed
     *
     *  @var string
     */
    const DEFAULT_TYPE = "application/json";

    /**
     *  Create a manager instance
     *
     *  The constructor allows the caller to provide a record factory for
     *  the manager to operate with (instead of the builtin one) and, also,
     *  a default media type to assume in case no media type is specified.
     *
     *  The optional $typeMap allows the caller to provide a 1-dimensional,
     *  associative array that maps file extensions (keys) to internet
     *  media (or MIME) types. This map (or the internal default) is used
     *  when a) the media type has not been given along with the file path
     *  and b) the internal lookup (using FILEINFO_MIME_TYPE) either fails
     *  or returns "text/plain".
     *
     *  @param  AnyRecordFactory    $recordFactory  The record factory
     *  @param  string              $defaultType    The default record type
     *  @param  array               $typeMap        The mime type mapping
     */
    public function __construct(
        AnyRecordFactory $recordFactory = null,
        $defaultType = null,
        array $typeMap = null
    ) {
        if (null === $recordFactory) {
            $recordFactory = new BuiltinRecordFactory();
        }

        if (null === $defaultType) {
            $defaultType = self::DEFAULT_TYPE;
        }

        if (empty($typeMap)) {
            $typeMap = self::$map;
        }

        $this->recordFactory = $recordFactory;
        $this->defaultType = $defaultType;
        $this->typeMap = array_map("strval", $typeMap);
    }

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
    public function loadRecord($location, $type = null)
    {
        $content = $this->loadContent($location, $type);
        $parser = $this->recordFactory->getRecordParser($type);
        $record = $parser->parseRecord($content);

        return $record;
    }

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
    public function saveRecord($location, array $data, $type = null)
    {
        if (null === $type) {
            $type = $this->defaultType;
        }

        $builder = $this->recordFactory->getRecordBuilder($type);
        $content = $builder->buildRecord($data);

        $this->saveContent($location, $content);
    }

    /**
     *  Load content byte sequences
     *
     *  The loadContent() method is used internally to load the content at
     *  the given $location, returning it as a byte sequence or string.
     *  If the $type reference is NULL, an attempt is made to determine the
     *  content type - based on PHP's builtin mechanisms.
     *
     *  @param  string              $location       The content location
     *  @param  string              $type           The content type
     *
     *  @return string
     *          A byte sequence is returned on success
     *
     *  @throws \Lousson\Record\Error\RecordArgumentError
     *          Raised in case the $location's content could not get loaded
     */
    private function loadContent($location, &$type)
    {
        if (null === $type) {
            $type = $this->determineMimeType($location);
        }

        $setup = ini_set("track_errors", true);
        $php_errormsg = "UKNOWN ERROR";
        $content = @file_get_contents($location);
        $error = $php_errormsg;

        ini_set("track_errors", $setup);

        if (false === $content) {
            $message = "Could not load record: $error";
            throw new RecordArgumentError($message);
        }

        return $content;
    }

    /**
     *  Save content byte sequences
     *
     *  The saveContent() method is used internally to save the $content
     *  provided at the given $location.
     *
     *  @param  string              $location       The content location
     *  @param  string              $content        The content data
     *
     *  @throws \Lousson\Record\Error\RecordArgumentError
     *          Raised in case the $content could not get saved
     */
    private function saveContent($location, $content)
    {
        $setup = ini_set("track_errors", true);
        $php_errormsg = "UKNOWN ERROR";
        $status = @file_put_contents($location, $content);
        $error = $php_errormsg;

        ini_set("track_errors", $setup);

        if (false === $status) {
            $message = "Could not save record: $error";
            throw new RecordArgumentError($message);
        }

    }

    /**
     *  Determine a file's mime type
     *
     *  The determineMimeType() method is used internally to determine the
     *  internet media type of record files when the information has not
     *  been provided along with the file path.
     *
     *  @param  string              $path           The path to the file
     *
     *  @return string
     *          A mime type identifier is returned on success
     */
    protected function determineMimeType($path)
    {
        $info = finfo_open(FILEINFO_MIME_TYPE);
        $type = @finfo_file($info, (string) $path)?: null;
        finfo_close($info);

        if ("text/plain" === $type || !$type) {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $extension = strtolower((string) $extension);
            $type = @$this->typeMap[$extension]?: $this->defaultType;
        }

        return $type;
    }

    /**
     *  A map of file extensions and internet media types
     *
     *  @var array
     */
    private static $map = array(
        "ini" => "zz-application/zz-winassoc-ini",
        "json" => "application/json",
        "yaml" => "application/yaml",
        "phpd" => "application/vnd.php.serialized",
        "pearrc" => "application/vnd.php.serialized",
    );

    /**
     *  The record factory in use
     *
     *  @var \Lousson\Record\AnyRecordFactory
     */
    private $recordFactory;

    /**
     *  The default mime type to use as fallback
     *
     *  @var string
     */
    private $defaultType;

    /**
     *  A per-instance map of file extensions and internet media types
     *
     *  @var array
     */
    private $typeMap;
}

