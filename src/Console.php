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
 * @license        http://opensource.org/licenses/MIT   MIT License
 * @link           http://circle-creative.com
 * @since          Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

namespace O2System\Gears;

// ------------------------------------------------------------------------

/**
 * Console Class
 *
 * This class is to gear up PHP Developer to send output to browser console
 *
 * @package        O2System
 * @subpackage     core/gears
 * @category       core class
 * @author         Circle Creative Dev Team
 * @link           http://o2system.center/wiki/#GearsConsole
 */
class Console
{
    /**
     * Constants of Console Types
     *
     * @access  public
     * @type     integer
     */
    const LOG     = 1;
    const INFO    = 2;
    const WARNING = 3;
    const ERROR   = 4;

    // ------------------------------------------------------------------------

    /**
     * Log
     *
     * Send output to browser log console
     *
     * @access  public
     * @static  static class method
     *
     * @param   string $title string of output title
     * @param   mixed  $vars  mixed type variables of data
     */
    public static function log( $title, $vars )
    {
        static::debug( static::LOG, $title, $vars );
    }
    // ------------------------------------------------------------------------

    /**
     * Debug
     *
     * Send output to browser debug console
     *
     * @access  public
     * @static  static class method
     *
     * @param   int    $type  console type
     * @param   string $title string of output title
     * @param   mixed  $vars  mixed type variables of data
     */
    public static function debug( $type, $title, $vars )
    {
        $vars = Output::prepare_data( $vars );

        echo '<script type="text/javascript">' . PHP_EOL;
        switch( $type )
        {
            default:
            case 1:
                echo 'console.log("' . $title . '");' . PHP_EOL;
                break;
            case 2:
                echo 'console.info("' . $title . '");' . PHP_EOL;
                break;
            case 3:
                echo 'console.warn("' . $title . '");' . PHP_EOL;
                break;
            case 4:
                echo 'console.error("' . $title . '");' . PHP_EOL;
                break;
        }

        if( ! empty( $vars ) )
        {
            if( is_object( $vars ) || is_array( $vars ) )
            {
                $object = json_encode( $vars );
                echo 'var object' . preg_replace( '~[^A-Z|0-9]~i', "_", $title ) . ' = \'' . str_replace( "'", "\'",
                                                                                                          $object ) . '\';' . PHP_EOL;
                echo 'var val' . preg_replace( '~[^A-Z|0-9]~i', "_",
                                               $title ) . ' = eval("(" + object' . preg_replace( '~[^A-Z|0-9]~i', "_",
                                                                                                 $title ) . ' + ")" );' . PHP_EOL;
                switch( $type )
                {
                    default:
                    case 1:
                        echo 'console.debug(val' . preg_replace( '~[^A-Z|0-9]~i', "_", $title ) . ');' . PHP_EOL;
                        break;
                    case 2:
                        echo 'console.info(val' . preg_replace( '~[^A-Z|0-9]~i', "_", $title ) . ');' . PHP_EOL;
                        break;
                    case 3:
                        echo 'console.warn(val' . preg_replace( '~[^A-Z|0-9]~i', "_", $title ) . ');' . PHP_EOL;
                        break;
                    case 4:
                        echo 'console.error(val' . preg_replace( '~[^A-Z|0-9]~i', "_", $title ) . ');' . PHP_EOL;
                        break;
                }
            }
            else
            {
                switch( $type )
                {
                    default:
                    case 1:
                        echo 'console.debug("' . str_replace( '"', '\\"', $vars ) . '");' . PHP_EOL;
                        break;
                    case 2:
                        echo 'console.info("' . str_replace( '"', '\\"', $vars ) . '");' . PHP_EOL;
                        break;
                    case 3:
                        echo 'console.warn("' . str_replace( '"', '\\"', $vars ) . '");' . PHP_EOL;
                        break;
                    case 4:
                        echo 'console.error("' . str_replace( '"', '\\"', $vars ) . '");' . PHP_EOL;
                        break;
                }
            }
        }
        echo '</script>' . PHP_EOL;
    }
    // ------------------------------------------------------------------------

    /**
     * Info
     *
     * Send output to browser info console
     *
     * @access  public
     * @static  static class method
     *
     * @param   string $title string of output title
     * @param   mixed  $vars  mixed type variables of data
     */
    public static function info( $title, $vars )
    {
        static::debug( static::INFO, $title, $vars );
    }
    // ------------------------------------------------------------------------

    /**
     * Warning
     *
     * Send output to browser warning console
     *
     * @access  public
     * @static  static class method
     *
     * @param   string $title string of output title
     * @param   mixed  $vars  mixed type variables of data
     */
    public static function warning( $title, $vars )
    {
        static::debug( static::WARNING, $title, $vars );
    }
    // ------------------------------------------------------------------------

    /**
     * Error
     *
     * Send output to browser error console
     *
     * @access  public
     * @static  static class method
     *
     * @param   string $title string of output title
     * @param   mixed  $vars  mixed type variables of data
     */
    public static function error( $title, $vars )
    {
        static::debug( static::ERROR, $title, $vars );
    }
}