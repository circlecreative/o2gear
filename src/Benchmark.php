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
    protected $_start_time;
    protected $_start_memory;
	protected $_start_cpu;
	
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

    public function __construct()
    {
        /*
         *--------------------------------------------------------------
         * Define the start time of the application, used for profiling.
         *--------------------------------------------------------------
         */
        $this->_start_time = microtime( TRUE );


        /*
         *-----------------------------------------------------------------------------
         * Define the memory usage at the start of the application, used for profiling.
         *-----------------------------------------------------------------------------
         */
        $this->_start_memory = memory_get_usage( TRUE );
		
		/*
         *-----------------------------------------------------------------------------
         * Define the cpu usage at the start of the application, used for profiling.
         *-----------------------------------------------------------------------------
         */
		$this->_start_cpu = $this->_get_cpu_usage();
    }

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
        $this->_marker[ $marker ] = array( 
			'time' => $this->_start_time, 
			'memory' => $this->_start_memory,
			'cpu' => $this->_start_cpu
		);
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
		
		return $this->_elapsed[ $marker ][ 'time' ] . ' seconds';
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
			'cpu'    => $this->_get_cpu_usage()
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

        return round( $memory / 1024 / 1024, 2 ) . ' MB';
    }
    // ------------------------------------------------------------------------
	
	public function cpu_usage( $marker = 'total_execution' )
	{
		if( empty( $this->_elapsed[ $marker ] ) )
        {
            $this->stop( $marker );
        }

        return $this->_elapsed[ $marker ][ 'cpu' ];
	}

    /**
     * Memory Peak Usage
     *
     * @access    public
     *
     * @return  string  memory usage in MB
     */
    public function memory_peak_usage()
    {
        return round( memory_get_peak_usage( TRUE ) / 1024 / 1024, 2 ) . ' MB';
    }
    // ------------------------------------------------------------------------
	
	/**
     * CPU Usage
     *
     * @access	protected
     *
     * @return  int
     */
	protected function _get_cpu_usage() 
	{
        if (stristr(PHP_OS, 'win')) 
		{
			if( class_exists('COM', FALSE) )
			{
				$wmi = new \COM("Winmgmts://");
				$server = $wmi->execquery("SELECT LoadPercentage FROM Win32_Processor");
				
				$cpu_num = 0;
				$usage_total = 0;
				
				foreach($server as $cpu)
				{
					$cpu_num++;
					$usage_total += $cpu->loadpercentage;
				}
				
				$cpu_usage = round($usage_total/$cpu_num);
			}
            else
			{
				$cpu_usage = 0;
			}
        } 
		else 
		{
            $sys_load = sys_getloadavg();
            $cpu_usage = $sys_load[0];
        }
        
        return (int) $cpu_usage . ' hertz';
    }

    /**
     * Get Elapsed
     * Get all Benchmark elapsed markers
     *
     * @access    public
     *
     * @return  array   list of elapsed markers
     */
    public function elapsed( $marker = 'total_execution' )
    {
		$elapsed = new \stdClass;
		$elapsed->time = $this->elapsed_time( $marker );
		$elapsed->memory = $this->memory_usage( $marker );
		$elapsed->memory_peak = $this->memory_peak_usage();
		$elapsed->cpu = $this->cpu_usage( $marker );
		
		return $elapsed;
    }
}