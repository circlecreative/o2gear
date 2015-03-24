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
 * Console Class
 *
 * This class is to gear up PHP Developer to send output to browser console
 *
 * @package        O2Gears
 * @category       Drivers
 * @author         Steeven Andrian Salim
 * @link           http://o2system.center/standalone/o2gears/user-guide/console.html
 */
class Console
{
    /**
     * Constants of Console Types
     *
     * @access  public
     * @var     integer
     */
    const LOG = 1;
    const INFO = 2;
    const WARNING = 3;
    const ERROR = 4;

    /**
     * Log Method
     *
     * Send output to browser log console
     *
     * @access           public
     * @param   $title   String of output title
     *          $vars    Mixed type variables of data
     * @return           void
     */
    public static function log( $title, $vars )
    {
        static::debug( static::LOG, $title, $vars );
    }

    // ------------------------------------------------------------------------

    /**
     * Info Method
     *
     * Send output to browser info console
     *
     * @access           public
     * @param   $title   String of output title
     *          $vars    Mixed type variables of data
     * @return           void
     */
    public static function info( $title, $vars )
    {
        static::debug( static::INFO, $title, $vars );
    }

    // ------------------------------------------------------------------------

    /**
     * Warning Method
     *
     * Send output to browser warning console
     *
     * @access           public
     * @param   $title   String of output title
     *          $vars    Mixed type variables of data
     * @return           void
     */
    public static function warning( $title, $vars )
    {
        static::debug( static::WARNING, $title, $vars );
    }

    // ------------------------------------------------------------------------

    /**
     * Log Method
     *
     * Send output to browser error console
     *
     * @access           public
     * @param   $title   String of output title
     *          $vars    Mixed type variables of data
     * @return           void
     */
    public static function error( $title, $vars )
    {
        static::debug( static::ERROR, $title, $vars );
    }

    // ------------------------------------------------------------------------

    /**
     * Debug Method
     *
     * Send output to browser console log
     *
     * @access           public
     * @param   $type    Integer type of console output
     *          $title   String of output title
     *          $vars    Mixed type variables of data
     * @return           void
     */
    public static function debug( $type, $title, $vars )
    {
        $vars = Printer::prepare_data( $vars );

        echo '<script type="text/javascript">' . PHP_EOL;
        switch ( $type )
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

        if ( ! empty( $vars ) )
        {
            if ( is_object( $vars ) || is_array( $vars ) )
            {
                $object = json_encode( $vars );
                echo 'var object' . preg_replace( '~[^A-Z|0-9]~i', "_", $title ) . ' = \'' . str_replace( "'", "\'",
                                                                                                          $object ) . '\';' . PHP_EOL;
                echo 'var val' . preg_replace( '~[^A-Z|0-9]~i', "_",
                                               $title ) . ' = eval("(" + object' . preg_replace( '~[^A-Z|0-9]~i', "_",
                                                                                                 $title ) . ' + ")" );' . PHP_EOL;
                switch ( $type )
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
                switch ( $type )
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
}

/* End of file Console.php */
/* Location: ./O2Gears/drivers/Console.php */