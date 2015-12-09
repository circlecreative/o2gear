<?php
/**
 * O2System
 *
 * An open source application development framework for PHP 5.4 or newer
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014, PT. Lingkar Kreasi (Circle Creative).
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS ||
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS || COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES || OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT || OTHERWISE, ARISING FROM,
 * OUT OF || IN CONNECTION WITH THE SOFTWARE || THE USE || OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package        O2System
 * @author         Steeven Andrian Salim
 * @copyright      Copyright (c) 2005 - 2014, PT. Lingkar Kreasi (Circle Creative).
 * @license        http://circle-creative.com/products/o2system/license.html
 * @license        http://opensource.org/licenses/MIT	MIT License
 * @link           http://circle-creative.com
 * @since          Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

namespace O2System\Gears;

// ------------------------------------------------------------------------

/**
 * Debug Class
 *
 * This class is to gear up PHP Developer for manual debugging line by line
 *
 * @package        O2System
 * @subpackage     core/gears
 * @category       core class
 * @author         Circle Creative Dev Team
 * @link           http://o2system.center/wiki/#GearsDebug
 *
 * @static         static class
 */
class Debug
{
    /**
     * List of Debug Chronology
     *
     * @access  private
     * @static
     *
     * @type    array
     */
    private static $_chronology = array();

    /**
     * Start
     *
     * Start Debug Process
     *
     * @access  public
     * @static  static method
     */
    public static function start()
    {
        static::$_chronology = array();
        static::$_chronology[ ] = static::__where_call( __CLASS__ . '::start()' );
    }

    // ------------------------------------------------------------------------

    /**
     * Where Call Method
     *
     * Finding where the call is made
     *
     * @access          private
     *
     * @param   $call   String Call Method
     *
     * @return          Tracer Object
     */
    private static function __where_call( $call )
    {
        $tracer = new Tracer();

        foreach( $tracer->chronology() as $trace )
        {
            if( $trace->call === $call )
            {
                return $trace;
                break;
            }
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Line
     *
     * Add debug line
     *
     * @access           public
     *
     * @param   $vars    Mixed type variables of data
     *                   $export  Export vars option
     *
     * @return           void
     */
    public static function line( $vars, $export = FALSE )
    {
        $trace = static::__where_call( __CLASS__ . '::line()' );

        if( $export === TRUE )
        {
            $trace->data = var_export( $vars, TRUE );
        }
        else
        {
            $trace->data = Output::prepare_data( $vars );
        }

        static::$_chronology[ ] = $trace;
    }

    // ------------------------------------------------------------------------

    /**
     * Line
     *
     * Add debug line
     *
     * @access           public
     *
     * @param   $vars    Mixed type variables of data
     *                   $export  Export vars option
     *
     * @return           void
     */
    public static function marker()
    {
        $trace = static::__where_call( __CLASS__ . '::marker()' );
        static::$_chronology[ ] = $trace;
    }

    // ------------------------------------------------------------------------

    /**
     * Stop
     *
     * Stop Debug Process
     *
     * @access          public
     *
     * @param   $halt   Boolean option for halt the Debug Process or not
     *
     * @return          void
     */
    public static function stop( $halt = TRUE )
    {
        static::$_chronology[ ] = static::__where_call( __CLASS__ . '::stop()' );
        $chronology = static::$_chronology;
        static::$_chronology = array();

        Output::screen( $chronology, $halt );
    }
}