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
 * Printer Class
 *
 * @package        O2System
 * @subpackage     core/gears
 * @category       core class
 * @author         Circle Creative Dev Team
 * @link           http://o2system.center/wiki/#GearsPrinter
 */
class Output
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
     * @access  public
     * @static
     *
     * @param   mixed $vars any types of variables string|array|object|integer|boolean
     * @param   bool  $halt halt the PHP Process
     */
    public static function code( $vars, $halt = FALSE )
    {
        $vars = static::prepare_data( $vars );
        $vars = htmlentities( $vars );
        $vars = htmlspecialchars( htmlspecialchars_decode( $vars, ENT_QUOTES ), ENT_QUOTES, 'UTF-8' );
        $vars = str_replace( '&nbsp;', '', $vars );
        $vars = trim( $vars );

        echo '<pre>' . $vars . '</pre>';

        if( $halt === TRUE ) die;
    }

    // ------------------------------------------------------------------------

    /**
     * Prepare Data Method
     *
     * Prepare variables data for output and re-format the output data based on type of data
     *
     * @access  public
     * @static
     *
     * @param   mixed $vars any types of variables string|array|object|integer|boolean
     *
     * @return mixed
     */
    public static function prepare_data( $vars )
    {
        if( is_bool( $vars ) )
        {
            if( $vars === TRUE )
            {
                $vars = '(bool) TRUE';
            }
            else
            {
                $vars = '(bool) FALSE';
            }
        }
        elseif( is_resource( $vars ) )
        {
            $vars = '(resource) ' . get_resource_type( $vars );
        }
        elseif( is_array( $vars ) || is_object( $vars ) )
        {
            $vars = print_r( $vars, TRUE );
        }
        elseif(is_int($vars) OR is_numeric($vars))
        {
            $vars = '(int) ' . $vars;
        }

        $vars = str_replace( '&nbsp;', '', $vars );
        $vars = trim( $vars );

        return $vars;
    }

    // ------------------------------------------------------------------------

    /**
     * Line Printing Method
     *
     * Add line for future output or output the lines to the browser
     *
     * @access  public
     * @static
     *
     * @param   mixed $line any types of line variables string|array|object|integer|boolean
     * @param   bool  $halt halt the PHP Process
     */
    public static function line( $line, $halt = FALSE )
    {
        if( strtoupper( $halt ) === 'FLUSH' )
        {
            static::$_lines = array();
            static::$_lines[ ] = $line;
        }

        if( is_array( $line ) || is_object( $line ) )
        {
            static::$_lines[ ] = print_r( $line, TRUE );
        }
        else
        {
            static::$_lines[ ] = static::prepare_data( $line );
        }

        if($halt === TRUE OR $line === '---')
        {
            $vars = implode( PHP_EOL, array_unique( static::$_lines ) );
            static::$_lines = array();
            static::screen( $vars, $halt );
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Screen Printing Method
     *
     * Print output to browser screen on screen view template
     *
     * @access  public
     * @static
     *
     * @param   mixed $vars any types of variables string|array|object|integer|boolean
     * @param   bool  $halt halt the PHP Process
     */
    public static function screen( $vars, $halt = TRUE )
    {
        $vars = static::prepare_data( $vars );
        $vars = htmlentities( $vars );
        $vars = htmlspecialchars( htmlspecialchars_decode( $vars, ENT_QUOTES ), ENT_QUOTES, 'UTF-8' );
        $vars = str_replace( '&nbsp;', '', $vars );
        $vars = trim( $vars );

        $tracer = new Tracer();

        ob_start();

        // Load print out template
        include dirname(__FILE__) . '/views/output.php';
        $output = ob_get_contents();
        ob_end_clean();

        echo $output;

        if( $halt === TRUE ) ;
        exit( 0 );
    }

    // ------------------------------------------------------------------------

    /**
     * Dump Printing Method
     *
     * Dump variables equipped with variables properties information
     *
     * @access  public
     * @static
     *
     * @param   mixed $vars any types of variables string|array|object|integer|boolean
     * @param   bool  $halt halt the PHP Process
     */
    public static function dump( $vars, $halt = TRUE )
    {
        ob_start();
        var_dump( $vars );
        $output = ob_get_contents();
        ob_end_clean();

        static::screen( $output, $halt );
    }
}