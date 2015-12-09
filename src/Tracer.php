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
{

    /**
     * Tracer Class
     *
     * This class is to gear up PHP Developer for Backtrace the PHP Process
     *
     * @package        O2System
     * @subpackage     core/gears
     * @category       core class
     * @author         Circle Creative Dev Team
     * @link           http://o2system.center/wiki/#GearsTracer
     */
    class Tracer
    {
        /**
         * Class Name
         *
         * @access  protected
         * @type    string name of called class
         */
        const PROVIDE_OBJECT = DEBUG_BACKTRACE_PROVIDE_OBJECT;
        /**
         * Class Name
         *
         * @access  protected
         * @type    string name of called class
         */
        const IGNORE_ARGS = DEBUG_BACKTRACE_IGNORE_ARGS;
        /**
         * Class Name
         *
         * @access  protected
         * @type    string name of called class
         */
        private $_trace = NULL;
        /**
         * Class Name
         *
         * @access  protected
         * @type    string name of called class
         */
        private $_chronology = array();
        /**
         * Class Name
         *
         * @access  protected
         * @type    string name of called class
         */
        private $_benchmark = array();

        /**
         * Class Constructor
         *
         * @access public
         *
         * @param string $flag tracer option
         */
        public function __construct( $trace = array(), $flag = Tracer::PROVIDE_OBJECT )
        {
            $this->_benchmark = array(
                'time'   => time() + microtime(),
                'memory' => memory_get_usage()
            );

            if( ! empty( $trace ) )
            {
                $this->_trace = $trace;
            }
            else
            {
                $this->_trace = debug_backtrace( $flag );
            }

            // reverse array to make steps line up chronologically
            $this->_trace = array_reverse( $this->_trace );

            // Generate Lines
            $this->__generate_chronology();
        }

        // ------------------------------------------------------------------------

        /**
         * Generate Chronology Method
         *
         * Generate array of Backtrace Chronology
         *
         * @access           private
         * @return           void
         */
        private function __generate_chronology()
        {
            foreach( $this->_trace as $trace )
            {
                if( in_array( $trace[ 'function' ], [ 'error_handler', 'shutdown_handler' ] ) OR
                    ( isset( $trace[ 'class' ] ) AND $trace[ 'class' ] === 'O2System\Gears\Tracer' )
                )
                {
                    continue;
                }

                $line = new Tracer\Chronology();

                if( isset( $trace[ 'class' ] ) && isset( $trace[ 'type' ] ) )
                {
                    $line->call = $trace[ 'class' ] . $trace[ 'type' ] . $trace[ 'function' ] . '()';
                    $line->type = $trace[ 'type' ] === '->' ? 'non-static' : 'static';
                }
                else
                {
                    $line->call = $trace[ 'function' ] . '()';
                    $line->type = 'non-static';
                }

                if( ! empty( $trace[ 'args' ] ) AND $line->call !== 'print_out()' ) $line->args = $trace[ 'args' ];

                if( ! isset( $trace[ 'file' ] ) )
                {
                    $current_trace = current( $this->_trace );
                    $line->file = @$current_trace[ 'file' ];
                    $line->line = @$current_trace[ 'line' ];
                }
                else
                {
                    $line->file = @$trace[ 'file' ];
                    $line->line = @$trace[ 'line' ];
                }

                $line->time = ( time() + microtime() ) - $this->_benchmark[ 'time' ];
                $line->memory = memory_get_usage() - $this->_benchmark[ 'memory' ];

                $this->_chronology[ ] = $line;

                if( in_array( $trace[ 'function' ], [ 'print_out', 'print_line' ] ) ) break;
            }
        }

        // ------------------------------------------------------------------------

        /**
         * Chronology Method
         *
         * Backtrace chronology
         *
         * @access public
         *
         * @param   bool $reset option for resetting the chronology data
         *
         * @return  array
         */
        public function chronology( $reset = TRUE )
        {
            $chronology = $this->_chronology;

            if( $reset === TRUE )
            {
                $this->_chronology = array();
            }

            return $chronology;
        }
    }
}

namespace O2System\Gears\Tracer;
{
    /**
     * Chronology
     *
     * @package        O2System
     * @subpackage     core/gears
     * @category       core class
     * @author         Circle Creative Dev Team
     * @link           http://o2system.center/wiki/#GearsDebug
     */
    class Chronology
    {
        public $call;
        public $type;
        public $line;
        public $time;
        public $memory;
        public $args;
    }
}