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

/*
 * ------------------------------------------------------
 *  Define O2Gears Path
 * ------------------------------------------------------
*/
    $gear_path = pathinfo( __FILE__, PATHINFO_DIRNAME );
    $gear_path = realpath( $gear_path );
    define( 'GEAR_PATH', $gear_path . '/' );

/*
 * ------------------------------------------------------
 *  O2Gears Driver Autoload
 * ------------------------------------------------------
*/
    function __gear_autoload( $class )
    {
        $class = pathinfo( $class, PATHINFO_FILENAME );
        $class = ucfirst( $class );

        if ( file_exists( GEAR_PATH . 'Drivers/' . $class . '.php' ) )
        {
            require_once( GEAR_PATH . 'Drivers/' . $class . '.php' );
        }
    }

    // Register SPL Autoload
    spl_autoload_register('__gear_autoload', TRUE, TRUE);

/* End of file Gears.php */
/* Location: ./O2Gears/Gears.php */