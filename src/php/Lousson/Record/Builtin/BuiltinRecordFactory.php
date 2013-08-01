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

/** Interfaces: */
use Lousson\Container\AnyContainer;
use Lousson\Record\AnyRecordBuilder;
use Lousson\Record\AnyRecordHandler;
use Lousson\Record\AnyRecordFactory;
use Lousson\Record\AnyRecordParser;

/** Dependencies: */
use Lousson\Container\Generic\GenericContainerDecorator;
use Lousson\Container\Generic\GenericContainer;
use Lousson\Record\Builtin\BuiltinRecordUtil;

/** Exceptions: */
use Lousson\Record\Error\RecordRuntimeError;

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
     *  Create a container instance
     *
     *  The constructor allows the caller to provide a custom container
     *  instance to be used as root when loading plugins - instead of the
     *  default, empty one.
     *
     *  @param  AnyContainer        $container      The factory container
     */
    public function __construct(AnyContainer $container = null)
    {
        if (null === $container) {
            $container = new GenericContainer();
        }

        $this->root = $container;
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
        $parser = $this->getRecordEntity($type, "record.parser");

        if (!$parser instanceof AnyRecordParser) {
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
        $builder = $this->getRecordEntity($type, "record.builder");

        if (!$builder instanceof AnyRecordBuilder) {
            $builder = $this->getRecordHandler($type);
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
        $handler = $this->getRecordEntity($type, "record.handler");

        if (!$handler instanceof AnyRecordHandler) {
            $message = "Could not provide \"$type\" handler";
            $code = RecordRuntimeError::E_NOT_SUPPORTED;
            throw new RecordRuntimeError($message, $code);
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
        $parser = $this->getRecordEntity($type, "record.parser");
        $hasParser = $parser instanceof AnyRecordParser;

        if (!$hasParser) {
            $hasParser = $this->hasRecordHandler($type);
        }

        return $hasParser;
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
        $builder = $this->getRecordEntity($type, "record.builder");
        $hasBuilder = $builder instanceof AnyRecordBuilder;

        if (!$hasBuilder) {
            $hasBuilder = $this->hasRecordHandler($type);
        }

        return $hasBuilder;
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
        $handler = $this->getRecordEntity($type, "record.handler");
        $hasHandler = $handler instanceof AnyRecordHandler;
        return $hasHandler;
    }

    /**
     *  Obtain an entity instance
     *
     *  The getRecordEntity() method is used internally to retrieve the
     *  object that is associated with the given $name from the container
     *  associated with the given mime $type.
     *
     *  @param  string              $type       The media type
     *  @param  string              $name       The entity name
     *
     *  @return object
     *          An object is returned on success, NULL otherwise
     *
     *  @throws \Lousson\Record\AnyRecordException
     *          All exceptions raised implement this interface
     *
     *  @throws \InvalidArgumentException
     *          Raised in case the $type parameter is malformed
     */
    protected function getRecordEntity($type, $name)
    {
        $type = BuiltinRecordUtil::normalizeType($type);
        $entity = null;
        $index = "$name.$type";

        if (!isset($this->containers)) {
            $this->loadRecordContainers();
        }

        foreach ($this->containers as $container) {
            if ($entity = $container->get($index)->orNull()->asObject()) {
                break;
            }
        }

        return $entity;
    }

    /**
     *  Populate the $containers member
     *
     *  The loadRecordContainers() method is used internally to load the
     *  plugins to be bound to the factory, populating the $containers for
     *  later use via getRecordContainer().
     */
    private function loadRecordContainers()
    {
        $plugins = $this->root
            ->get("record.plugins")
            ->orFallback(self::$plugins)
            ->asArray();

        $plugins = array_filter(
            $plugins, function($className) {
                return class_exists($className) && is_subclass_of(
                    $className, "Lousson\\Record\\AnyRecordPlugin"
                );
            }
        );

        foreach ($plugins as $className) try {
            $child = new GenericContainerDecorator($this->root);
            call_user_func(array($className, "bootstrap"), $child);
            $this->containers[] = $child;
        }
        catch (\Exception $error) {
            $message = "While loading $className plugin: Caught $error";
            trigger_error($message, E_USER_WARNING);
        }

        $this->containers[] = $this->root;
    }

    /**
     *  The default plugins that ship with the package
     *
     *  @var array
     */
    private static $plugins = array(
        "Lousson\\Record\\Plugin\\INI",
        "Lousson\\Record\\Plugin\\JSON",
        "Lousson\\Record\\Plugin\\PHP",
    );

    /**
     *  The containers loaded from plugins
     *
     *  @var array
     */
    private $containers;

    /**
     *  The root container provided at construction time
     *
     *  @var \Lousson\Container\AnyContainer
     */
    private $root;
}

