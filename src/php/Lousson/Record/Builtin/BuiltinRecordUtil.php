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
 *  Lousson\Record\Builtin\BuiltinRecordUtil class definition
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record\Builtin;

/** Dependencies: */
use Lousson\Record\Error\RecordArgumentError;

/**
 *  A utility for record entities
 *
 *  The BuiltinRecordUtil eases the implementation of many record entity
 *  interfaces, by providing common functionaliy like validation.
 *
 *  Note that the facilities provided by this class will, most likely, be
 *  provided by Traits in a future release. For now, the form of a static
 *  method container has been choosen, in oder to keep the builtin and
 *  generic classes clean and increase the overall performance.
 *
 *  @since      lousson/Lousson_Record-0.1.0
 *  @package    org.lousson.record
 */
final class BuiltinRecordUtil
{
    /**
     *  Determine whether record data is valid
     *
     *  The isValidData() method returns a boolean indicating whether the
     *  given $data is valid according to the constraints associated with
     *  records.
     *  The optional $message reference is left untouched in case the data
     *  is valid, otherwise it is populated with a text indicating why the
     *  validation has failed.
     *
     *  @param  array               $data       The record data
     *  @param  string              $message    The failure message
     *
     *  @return bool
     *          TRUE is returned if the $data is valid, FALSE otherwise
     */
    public static function isValidData(array $data, &$message = null)
    {
        $isValidData = true;

        try {
            self::validateData($data);
        }
        catch (RecordArgumentError $error) {
            $message = $error->getMessage();
            $isValidData = false;
        }

        return $isValidData;
    }

    /**
     *  Determine whether record names are valid
     *
     *  The isValidName() method returns a boolean indicating whether the
     *  given $name is a valid name according to the constraints that are
     *  associated with records and record-names.
     *  The optional $record reference is left untouched in the the name
     *  is valid, otherwise it is populated with a text indicating why the
     *  validation has failed.
     *
     *  @param  string              $name       The record name
     *  @param  string              $message    The failure message
     *
     *  @return bool
     *          TRUE is returned if the $name is valid, FALSE otherwise
     */
    public static function isValidName($name, &$message = null)
    {
        $isValidName = true;

        try {
            self::validateName($name);
        }
        catch (RecordArgumentError $error) {
            $message = $error->getMessage();
            $isValidName = false;
        }

        return $isValidName;
    }

    /**
     *  Determine whether record items are valid
     *
     *  The isValidOte,() method returns a boolean indicating whether the
     *  given $item is a valid item according to the constraints that are
     *  associated with records and record-items.
     *  The optional $record reference is left untouched in the the item
     *  is valid, otherwise it is populated with a text indicating why the
     *  validation has failed.
     *
     *  @param  mixed               $item       The record item
     *  @param  string              $message    The failure message
     *
     *  @return bool
     *          TRUE is returned if the $item is valid, FALSE otherwise
     */
    public static function isValidItem($item, &$message = null)
    {
        $isValidItem = true;

        try {
            self::validateItem($item);
        }
        catch (RecordArgumentError $error) {
            $message = $error->getMessage();
            $isValidItem = false;
        }

        return $isValidItem;
    }

    /**
     *  Determine whether a media type is valid
     *
     *  The isValidType() method returns a boolean indicating whether the
     *  given media $type is valid (according to RFC 2046).
     *  The optional $message reference is left untouched in case the type
     *  is valid, otherwise it is populated with a text indicating why the
     *  validation has failed.
     *
     *  @param  string              $type       The media type
     *  @param  string              $message    The failure message
     *
     *  @return bool
     *          TRUE is returned if the $type is valid, FALSE otherwise
     */
    public static function isValidType($type, &$message = null)
    {
        static $pattern = "/^
            [a-z]+ ([+_.\\-]? [a-z0-9]+)* \\/
            [a-z]+ ([+_.\\-]? [a-z0-9]+)* \$/ix";

        $isValidType = true;

        if (!preg_match($pattern, $type)) {
            $message = "Invalid media type: $type";
            $isValidType = false;
        }

        return $isValidType;
    }

    /**
     *  Normalize record data
     *
     *  The normalizeData() method returns an array that represents the
     *  normalized form of the given record $data.
     *
     *  @param  array               $data       The record data
     *  @param  array               $i          The record index
     *
     *  @return array
     *          The normalized record data is returned on success
     *
     *  @throws \Lousson\Record\Error\RecordArgumentError
     *          Raised in case the record $data is invalid
     */
    public static function normalizeData(array $data, array $i = array())
    {
        $normalized = array();

        foreach ($data as $name => $item) {
            self::validateName($name);
            $i[] = $name;
            $normalized[$name] = self::normalizeItem($item, $i);
            array_pop($i);
        }

        return $normalized;
    }

    /**
     *  Normalize record names
     *
     *  The normalizeName() method is used to normalize thea record
     *  $name at the given index $i.
     *
     *  @param  string              $name       The record name
     *  @param  array               $i          The record index
     *
     *  @return mixed
     *          The normalized name is returned on success
     *
     *  @throws \Lousson\Record\Error\RecordArgumentError
     *          Raised in case the record $name is invalid
     */
    public static function normalizeName($name, array $i = array())
    {
        $normalized = (string) $name;
        self::validateName($normalized);
        return $normalized;
    }

    /**
     *  Normalize record items
     *
     *  The normalizeItem() method is used to normalize thea record
     *  $item at the given index $i.
     *
     *  @param  mixed               $item       The record item
     *  @param  array               $i          The record index
     *
     *  @return mixed
     *          The normalized item is returned on success
     *
     *  @throws \Lousson\Record\Error\RecordArgumentError
     *          Raised in case the record $item is invalid
     */
    public static function normalizeItem($item, array $i = array())
    {
        if (!is_array($item)) {
            self::validateItem($item, $i);
            $normalized = $item;
        }
        else if (self::isNumericIndexed($item)) {
            $normalized = self::normalizeList($item, $i);
        }
        else {
            $normalized = self::normalizeData($item, $i);
        }

        return $normalized;
    }

    /**
     *  Normalize media types
     *
     *  The normalizeType() method returns a string that represents the
     *  normalized form of the given media $type.
     *
     *  @param  string              $type       The media type
     *
     *  @return string
     *          The normalized media type name is returned on success
     *
     *  @throws \Lousson\Record\Error\RecordArgumentError
     *          Raised in case the media $type is invalid
     */
    public static function normalizeType($type)
    {
        self::validateType($type);
        $normalized = strtolower($type);
        return $normalized;
    }

    /**
     *  Validate record data
     *
     *  The validateData() method is used to validate record $data at
     *  the given index $i, which must either be an array of record names
     *  or absent.
     *
     *  @param  array               $data       The record data
     *  @param  array               $i          The record index
     *
     *  @throws \Lousson\Record\Error\RecordArgumentError
     *          Raised in case the record $data is invalid
     */
    public static function validateData(array $data, array $i = array())
    {
        foreach ($data as $name => $item) {
            self::validateName($name, $i);
            $i[] = $name;
            self::validateItem($item, $i);
            array_shift($i);
        }
    }

    /**
     *  Validate item names
     *
     *  The validateName() method is used to validate the item $name at
     *  the given index $i.
     *
     *  @param  string              $name       The item name
     *  @param  array               $i          The record index
     *
     *  @throws \Lousson\Record\Error\RecordArgumentError
     *          Raised in case the item $name is invalid
     */
    public static function validateName($name, array $i = array())
    {
        $string = (string) $name;

        if (strcspn($string, " \t\r\n") !== strlen($string) ||
                is_int($string) || ctype_digit((string) $string) ||
                "" === $string) {
            $path = $i? implode("/", $i): "";
            $message = "Invalid record key at /$path: \"$name\"";
            $code = RecordArgumentError::E_INVALID_RECORD;
            throw new RecordArgumentError($message, $code);
        }
    }

    /**
     *  Validate record items
     *
     *  The validateItem() method is used to validate the record $item
     *  at the given index $i.
     *
     *  @param  mixed               $item       The record item
     *  @param  array               $i          The record index
     *
     *  @throws \Lousson\Record\Error\RecordArgumentError
     *          Raised in case the record $item is invalid
     */
    public static function validateItem($item, array $i = array())
    {
        if (is_array($item)) {
            self::isNumericIndexed($item)
                ? self::validateList($item, $i)
                : self::validateData($item, $i);
        }
        else if (null !== $item && !is_scalar($item)) {
            $path = implode("/", $i);
            $type = is_object($item)? get_class($item): gettype($item);
            $message = "Invalid record item at /$path: $type";
            $code = RecordArgumentError::E_INVALID_RECORD;
            throw new RecordArgumentError($message, $code);
        }
    }

    /**
     *  Validate media types
     *
     *  The validateType() method is used to validate the media $type
     *  provided.
     *
     *  @param  string              $type       The media type
     *
     *  @throws \Lousson\Record\Error\RecordArgumentError
     *          Raised in case the media $type is invalid
     */
    public static function validateType($type)
    {
        if (!self::isValidType($type, $message)) {
            $code = RecordArgumentError::E_NOT_SUPPORTED;
            throw new RecordArgumentError($message, $code);
        }
    }

    /**
     *  Normalize item lists
     *
     *  The normalizeList() method is used internally to normalize the
     *  item $list at the given index $i.
     *
     *  @param  array               $list       The item list
     *  @param  array               $i          The record index
     *
     *  @return array
     *          The normalized item list is returned on success
     *
     *  @throws \Lousson\Record\Error\RecordArgumentError
     *          Raised in case the item $list is invalid
     */
    public static function normalizeList(array $list, array $i = array())
    {
        $normalized = array();

        foreach ($list as $item) {
            $normalized[] = self::normalizeItem($item, $i);
        }

        return $normalized;
    }

    /**
     *  Validate item lists
     *
     *  The validateList() method is used internally to validate the
     *  item $list at the given index $i.
     *
     *  @param  array               $list       The item list
     *  @param  array               $i          The record index
     *
     *  @throws \Lousson\Record\Error\RecordArgumentError
     *          Raised in case the item $list is invalid
     */
    public static function validateList(array $list, array $i = array())
    {
        $j = -1;

        foreach ($list as $item) {
            $i[] = ++$j;
            self::validateItem($item, $i);
            array_shift($i);
        }
    }

    /**
     *  Determine whether array indices are numeric only
     *
     *  The isNumericIndexed() method is used internally to determine
     *  whether an array has only numeric inidices, in which case it is
     *  considered as a list rather than a map.
     *
     *  @param  array               $data       The array to check
     *
     *  @return bool
     *          TRUE is returned if numeric indices are encountered
     *          exclusively, FALSE otherwise
     */
    private static function isNumericIndexed(array $data)
    {
        $isNumericIndexed = true;

        foreach (array_keys($data) as $key) {
            if (!is_int($key) && !ctype_digit($key)) {
                $isNumericIndexed = false;
                break;
            }
        }

        return $isNumericIndexed;
    }
}

