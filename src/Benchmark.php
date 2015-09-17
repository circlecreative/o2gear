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

namespace O2System\O2Gears;

// ------------------------------------------------------------------------

/**
 * Benchmark Class
 *
 * This class enables you to mark points and calculate the time difference
 * between them.  Memory consumption can also be displayed.
 *
 * @package        O2System
 * @subpackage     core/gears
 * @category       core class
 * @author         Circle Creative Dev Team
 * @link           http://o2system.center/wiki/#GearsBenchmark
 */
class Benchmark
{
    /**
     * List of all benchmark markers
     *
     * @access  protected
     * @type    array
     */
    protected $_marker = array();

    /**
     * List of all benchmark time elapsed markers
     *
     * @access  protected
     * @type    array
     */
    protected $_elapsed = array();

    // ------------------------------------------------------------------------

    /**
     * Start
     * Benchmark start timer marker
     *
     * @access  public
     *
     * @property-write  $_marker
     *
     * @param    string $marker marker name
     */
    public function start( $marker = 'total_execution' )
    {
        $this->_marker[ $marker ] = array( 'time' => SYSTEM_START_TIME, 'memory' => SYSTEM_START_MEMORY );
    }
    // ------------------------------------------------------------------------

    /**
     * Elapsed
     * Benchmark elapsed timer marker
     *
     * @access    public
     *
     * @property-write  $_elapsed
     *
     * @method  $this->stop()
     *
     * @param    string $marker marker name
     *
     * @return    int   time of elapsed time
     */
    public function elapsed_time( $marker = 'total_execution' )
    {
        if( empty( $this->_elapsed[ $marker ] ) )
        {
            $this->stop( $marker );
        }

        return $this->_elapsed[ $marker ][ 'time' ];
    }
    // ------------------------------------------------------------------------

    /**
     * Stop
     * Benchmark stop timer marker
     *
     * @access    public
     *
     * @property-write  $_elapsed
     *
     * @param   string  $marker   marker name
     * @param   int     $decimals time number format decimals
     */
    public function stop( $marker = 'total_execution', $decimals = 4 )
    {
        $this->_elapsed[ $marker ] = array(
            'time'   => number_format( ( time() + microtime( TRUE ) ) - $this->_marker[ $marker ][ 'time' ],
                                       $decimals ),
            'memory' => ( memory_get_usage( TRUE ) - $this->_marker[ $marker ][ 'memory' ] ),
        );
    }
    // ------------------------------------------------------------------------

    /**
     * Memory Usage
     * Benchmark Memory Usage
     *
     * @access    public
     *
     * @property-read  $_elapsed
     *
     * @param   string $marker marker name
     *
     * @return  string  memory usage in MB
     */
    public function memory_usage( $marker = 'total_execution' )
    {
        if( empty( $this->_elapsed[ $marker ] ) )
        {
            $this->stop( $marker );
        }

        $memory = $this->_elapsed[ $marker ][ 'memory' ];

        return round( $memory / 1024 / 1024, 2 ) . 'MB';
    }
    // ------------------------------------------------------------------------

    /**
     * Memory Peak Usage
     *
     * @access    public
     *
     * @return  string  memory usage in MB
     */
    public function memory_peak_usage()
    {
        return round( memory_get_peak_usage( TRUE ) / 1024 / 1024, 2 ) . 'MB';
    }
    // ------------------------------------------------------------------------

    /**
     * Get Elapsed
     * Get all Benchmark elapsed markers
     *
     * @access    public
     *
     * @return  array   list of elapsed markers
     */
    public function get_elapsed()
    {
        return $this->_elapsed;
    }
}