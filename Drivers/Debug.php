<?php
/**
 * O2Gears
 *
 * An open source PHP Developer tools for PHP 5.3 or newer
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
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package        O2Gears
 * @author         Steeven Andrian Salim
 * @copyright      Copyright (c) 2015, PT. Lingkar Kreasi (Circle Creative).
 *
 * @license        http://circle-creative.com/products/o2gears/license.html
 * @license        http://opensource.org/licenses/MIT	MIT License
 *
 * @link           http://circle-creative.com/products/o2gears.html
 *                 http://o2system.center/standalone/o2gears.html
 *
 * @filesource
 */
// ------------------------------------------------------------------------

namespace O2Gears;
defined( 'GEAR_PATH' ) OR exit( 'No direct script access allowed' );

/**
 * Debug Class
 *
 * This class is to gear up PHP Developer for manual debugging line by line
 *
 * @package        O2Gears
 * @category       Driver Class
 * @author         Steeven Andrian Salim
 * @link           http://o2system.center/standalone/o2gears/user-guide/debug.html
 */
class Debug
{
    /**
     * Debug Chronology
     *
     * @access  private
     * @var     array
     */
    private static $_chronos = array();

    /**
     * Start
     *
     * Start Debug Process
     *
     * @access           public
     * @return           void
     */
    public static function start()
    {
        static::$_chronos    = array();
        static::$_chronos[ ] = static::__where_call( 'O2Gears\Debug::start()' );
    }

    // ------------------------------------------------------------------------

    /**
     * Line
     *
     * Add debug line
     *
     * @access           public
     * @param   $vars    Mixed type variables of data
     *          $export  Export vars option
     * @return           void
     */
    public static function line( $vars, $export = FALSE )
    {
        $trace = static::__where_call( 'O2Gears\Debug::line()' );

        if ( $export === TRUE )
        {
            $trace->data = var_export( $vars, TRUE );
        }
        else
        {
            $trace->data = Printer::prepare_data( $vars );
        }

        static::$_chronos[ ] = $trace;
    }

    // ------------------------------------------------------------------------

    /**
     * Stop
     *
     * Stop Debug Process
     *
     * @access          public
     * @param   $halt   Boolean option for halt the Debug Process or not
     * @return          void
     */
    public static function stop( $halt = TRUE )
    {
        static::$_chronos[ ] = static::__where_call( 'O2Gears\Debug::stop()' );
        $chronos             = static::$_chronos;
        static::$_chronos    = array();

        Printer::screen( $chronos, $halt );
    }

    // ------------------------------------------------------------------------

    /**
     * Where Call Method
     *
     * Finding where the call is made
     *
     * @access          private
     * @param   $call   String Call Method
     * @return          \O2Gears\Tracer\Chronos Object
     */
    private static function __where_call( $call )
    {
        $tracer = new Tracer();

        foreach ( $tracer->chronos() as $trace )
        {
            if ( $trace->call === $call )
            {
                return $trace;
                break;
            }
        }
    }
}

/* End of file Debug.php */
/* Location: ./O2Gears/drivers/Debug.php */