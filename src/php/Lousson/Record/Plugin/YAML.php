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
 *  Lousson\Record\Plugin\YAML class declaration
 *
 *  @package    org.lousson.record
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Record\Plugin;

/** Interfaces: */
use Lousson\Container\AnyContainer;
use Lousson\Record\AnyRecordPlugin;

/** Dependencies: */
use Lousson\Container\Generic\GenericContainer;
use Lousson\Record\Builtin\BuiltinRecordHandlerYAML;

/**
 *  A YAML record plugin
 *
 *  The Lousson\Record\Plugin\YAML class is a plugin for e.g. the builtin
 *  record factory that ships with the Lousson_Record package, providing a
 *  YAML record handler - including a set of associated mime-types.
 *
 *  @since      lousson/Lousson_Record-2.0.0
 *  @package    org.lousson.record
 */
class YAML implements AnyRecordPlugin
{
    /**
     *  Set up and register the YAML plugin
     *
     *  The bootstrap() method is used by e.g. the BuiltinRecordFactory,
     *  in order to load, set up and register the plugin with the factory's
     *  plugin $container.
     *
     *  @param  GenericContainer    $container      The plugin container
     */
    public static function bootstrap(GenericContainer $container)
    {
        $callback = function(AnyContainer $container, $name) {
            $handler = new BuiltinRecordHandlerYAML();
            return $handler;
        };

        $aliases = array(
            "record.handler.application/yaml",
            "record.handler.application/x-yaml",
            "record.handler.text/yaml",
            "record.handler.text/x-yaml",
        );

        $container->share("record.handler.yaml", $callback);
        $container->alias("record.handler.yaml", $aliases);
    }
}

