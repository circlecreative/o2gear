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

namespace O2Gears
{
    defined( 'GEAR_PATH' ) OR exit( 'No direct script access allowed' );

    use O2Gears\Tracer\Chronos;

    /**
     * Tracer Class
     *
     * This class is to gear up PHP Developer for Backtrace the PHP Process
     *
     * @package        O2Gears
     * @category       Drivers
     * @author         Steeven Andrian Salim
     * @link           http://o2system.center/standalone/o2gears/user-guide/tracer.html
     */
    class Tracer
    {
        /**
         * Class Name
         *
         * @access  protected
         * @var     string name of called class
         */
        private static $_trace = NULL;

        /**
         * Class Name
         *
         * @access  protected
         * @var     string name of called class
         */
        private static $_chronos = array();

        /**
         * Class Name
         *
         * @access  protected
         * @var     string name of called class
         */
        private static $_benchmark = array();

        /**
         * Class Name
         *
         * @access  protected
         * @var     string name of called class
         */
        const PROVIDE_OBJECT = DEBUG_BACKTRACE_PROVIDE_OBJECT;

        /**
         * Class Name
         *
         * @access  protected
         * @var     string name of called class
         */
        const IGNORE_ARGS = DEBUG_BACKTRACE_IGNORE_ARGS;

        /**
         * Class Constructor
         *
         * @access           public
         * @param   $option  Ignore Arguments or Provide Object
         * @return           void
         */
        public function __construct( $option = Tracer::IGNORE_ARGS )
        {
            static::$_benchmark = array(
                'time'   => time() + microtime(),
                'memory' => memory_get_usage()
            );

            static::$_trace = debug_backtrace( $option );

            // reverse array to make steps line up chronologically
            static::$_trace = array_reverse( static::$_trace );

            // Generate Lines
            $this->__generate_chronos();
        }

        // ------------------------------------------------------------------------

        /**
         * Generate Chronos Method
         *
         * Generate array of Backtrace Chronology
         *
         * @access           private
         * @return           void
         */
        private function __generate_chronos()
        {
            foreach ( static::$_trace as $trace )
            {
                $line = new Chronos();

                if ( isset( $trace[ 'class' ] ) AND isset( $trace[ 'type' ] ) )
                {
                    $line->call = $trace[ 'class' ] . $trace[ 'type' ] . $trace[ 'function' ] . '()';
                    $line->type = $trace[ 'type' ] === '->' ? 'non-static' : 'static';
                }
                else
                {
                    $line->call = $trace[ 'function' ] . '()';
                    $line->type = 'non-static';
                }

                if ( ! empty( $trace[ 'args' ] ) ) $line->args = $trace[ 'args' ];

                $line->file = $trace[ 'file' ];
                $line->line = $trace[ 'line' ];

                $line->time   = ( time() + microtime() ) - static::$_benchmark[ 'time' ];
                $line->memory = memory_get_usage() - static::$_benchmark[ 'memory' ];

                static::$_chronos[ ] = $line;
            }
        }

        // ------------------------------------------------------------------------

        /**
         * Chronos Method
         *
         * Backtrace chronology
         *
         * @access           public
         *          $flush   Flush option for chronology data
         * @return           array
         */
        public function chronos( $flush = TRUE )
        {
            $chronos = static::$_chronos;

            if ( $flush === TRUE )
            {
                static::$_chronos = array();
            }

            return $chronos;
        }
    }
}

namespace O2Gears\Tracer
{
    /**
     * Chronos
     *
     * Class for create chronos object
     *
     * @package        O2Gears
     * @category       Drivers/Object
     */
    class Chronos
    {

    }
}

/* End of file Tracer.php */
/* Location: ./O2Gears/drivers/Tracer.php */