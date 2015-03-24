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
 * Printer Class
 *
 * This class is to gear up PHP Developer for beautifier the browser output
 *
 * @package        O2Gears
 * @category       Drivers
 * @author         Steeven Andrian Salim
 * @link           http://o2system.center/standalone/o2gears/user-guide/printer.html
 */
class Printer
{
    /**
     * Lines Collections
     *
     * @access  private
     * @var     array
     */
    private static $_lines = array();

    /**
     * Code Printing Method
     *
     * Print output with html pre tag
     *
     * @access           public
     * @param   $vars    Mixed type variables of data
     *          $halt    Boolean option for halt the PHP Process or not
     * @return           void
     */
    public static function code( $vars, $halt = TRUE )
    {
        $vars = static::prepare_data( $vars );
        $vars = htmlentities( $vars );
        $vars = htmlspecialchars( htmlspecialchars_decode( $vars, ENT_QUOTES ), ENT_QUOTES, 'UTF-8' );
        $vars = str_replace( '&nbsp;', '', $vars );
        $vars = trim( $vars );

        echo '<pre>' . $vars . '</pre>';

        if ( $halt === TRUE ) ;
        exit( 0 );
    }

    // ------------------------------------------------------------------------

    /**
     * Screen Printing Method
     *
     * Print output to browser screen on screen view template
     *
     * @access           public
     * @param   $vars    Mixed type variables of data
     *          $halt    Boolean option for halt the PHP Process or not
     * @return           void
     */
    public static function screen( $vars, $halt = TRUE )
    {
        $vars = static::prepare_data( $vars );
        $vars = htmlentities( $vars );
        $vars = htmlspecialchars( htmlspecialchars_decode( $vars, ENT_QUOTES ), ENT_QUOTES, 'UTF-8' );
        $vars = str_replace( '&nbsp;', '', $vars );
        $vars = trim( $vars );

        $asset_url = str_replace( DIRECTORY_SEPARATOR, '/', GEAR_PATH );
        $asset_url = str_replace( array( $_SERVER[ 'CONTEXT_DOCUMENT_ROOT' ], $_SERVER[ 'REQUEST_URI' ] ), '',
                                  $asset_url );

        if ( isset( $_SERVER[ 'HTTP_HOST' ] ) )
        {
            $base_url = isset( $_SERVER[ 'HTTPS' ] ) && strtolower( $_SERVER[ 'HTTPS' ] ) !== 'off' ? 'https' : 'http';
            $base_url .= '://' . $_SERVER[ 'HTTP_HOST' ];
            $base_url .= str_replace( basename( $_SERVER[ 'SCRIPT_NAME' ] ), '', $_SERVER[ 'SCRIPT_NAME' ] );
        }
        else
        {
            $base_url = 'http://localhost/';
        }

        $asset_url = $base_url . $asset_url . 'assets/';

        ob_start();

        // Load print out template
        include GEAR_PATH . 'views/screen.php';
        $output = ob_get_contents();
        ob_end_clean();

        echo $output;

        if ( $halt === TRUE ) ;
        exit( 0 );
    }

    // ------------------------------------------------------------------------

    /**
     * Line Printing Method
     *
     * Add line for future output or output the lines to the browser
     *
     * @access             public
     * @param   $vars      Mixed type variables of data
     *          $end_line  Boolean option for ending the line collections and force to send the output to the browser
     *          $halt      Boolean option for halt the PHP Process or not
     * @return             void
     */
    public static function line( $line, $end_line = FALSE, $halt = TRUE )
    {
        if ( $end_line === TRUE OR strtoupper( $line ) === 'END' OR $line === '---' )
        {
            $vars = implode( PHP_EOL, array_unique( static::$_lines ) );

            static::$_lines = array();

            static::screen( $vars, $halt );
        }
        elseif ( strtoupper( $end_line ) === 'FLUSH' )
        {
            static::$_lines    = array();
            static::$_lines[ ] = $line;
        }
        else
        {
            static::$_lines[ ] = static::prepare_data( $line );
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Dump Printing Method
     *
     * Dump variables equipped with variables properties information
     *
     * @access             public
     * @param   $vars      Mixed type variables of data
     *          $halt      Boolean option for halt the PHP Process or not
     * @return             void
     */
    public static function dump( $vars, $halt = TRUE )
    {
        $vars = var_export( $vars, TRUE );
        static::screen( $vars, $halt );
    }

    // ------------------------------------------------------------------------

    /**
     * Prepare Data Method
     *
     * Prepare variables data for output and re-format the output data based on type of data
     *
     * @access             public
     * @param   $vars      Mixed type variables of data
     * @return             void
     */
    public static function prepare_data( $vars )
    {
        if ( is_bool( $vars ) )
        {
            if ( $vars === TRUE )
            {
                $vars = '(bool) TRUE';
            }
            else
            {
                $vars = '(bool) FALSE';
            }
        }
        elseif ( is_resource( $vars ) )
        {
            $vars = '(resource) ' . get_resource_type( $vars );
        }
        elseif ( is_array( $vars ) OR is_object( $vars ) )
        {
            $vars = print_r( $vars, TRUE );
        }

        $vars = str_replace( '&nbsp;', '', $vars );
        $vars = trim( $vars );

        return $vars;
    }
}

/* End of file Printer.php */
/* Location: ./O2Gears/drivers/Printer.php */