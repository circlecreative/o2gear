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
use O2System\Glob\Singleton\Abstracts;

/**
 * Logging Class
 *
 * @package        O2System
 * @subpackage     core/gears
 * @category       core class
 * @author         Circle Creative Dev Team
 * @link           http://o2system.center/wiki/#GearsLogging
 *
 * @static         static class
 */
class Logger
{
    use Abstracts;

    /**
     * Constants of Logger Types
     *
     * @access  public
     * @type    integer
     */
    const DISABLED  = 0;
    const DEBUG     = 1;
    const INFO      = 2;
    const NOTICE    = 3;
    const WARNING   = 4;
    const ALERT     = 5;
    const ERROR     = 6;
    const EMERGENCY = 7;
    const CRITICAL  = 8;
    const ALL       = 9;

    /**
     * Class config
     *
     * @access protected
     *
     * @type array
     */
    protected $_config = array(
        'path' => NULL,
        'threshold' => Logger::ALL,
        'date_format' => 'Y-m-d H:i:s'
    );

    /**
     * List of logging levels
     *
     * @access protected
     * @type array
     */
    protected $_levels = array(
        0 => 'DISABLED',
        1 => 'DEBUG',
        2 => 'INFO',
        3 => 'NOTICE',
        4 => 'WARNING',
        5 => 'ALERT',
        6 => 'ERROR',
        7 => 'EMERGENCY',
        8 => 'CRITICAL',
        9 => 'ALL'
    );

    // --------------------------------------------------------------------

    /**
     * Class Initialize
     *
     * @throws \Exception
     */
    public function __construct( array $config = array() )
    {
        $this->_config = array_merge($this->_config, $config);

        if( ! is_dir( $this->_config[ 'path' ] ) )
        {
            if( ! mkdir( $this->_config[ 'path' ], 0775, TRUE ) )
            {
                throw new \Exception( "Logger: Logs path '" . $this->_config[ 'path' ] . "' is not a directory, doesn't exist or cannot be created." );
            }
        }
        elseif( ! is_writable( $this->_config[ 'path' ] ) )
        {
            throw new \Exception( "Logger: Logs path '" . $this->_config[ 'path' ] . "' is not writable by the PHP process." );
        }

        // Make it global
        $this->__reconstruct();
    }

    // --------------------------------------------------------------------

    /**
     * Interesting events.
     *
     * @param string $message
     *
     * @return bool
     */
    public function info( $message )
    {
        return $this->write( Logger::INFO, $message );
    }

    // --------------------------------------------------------------------

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     *
     * @return bool
     */
    public function error( $message )
    {
        return $this->write( Logger::ERROR, $message );
    }

    // --------------------------------------------------------------------

    /**
     * Detailed debug information.
     *
     * @param string $message
     *
     * @return bool
     */
    public function debug( $message )
    {
        return $this->write( Logger::DEBUG, $message );
    }

    // --------------------------------------------------------------------

    /**
     * Normal but significant events.
     *
     * @param string $message
     *
     * @return bool
     */
    public function notice( $message )
    {
        return $this->write( Logger::NOTICE, $message );
    }

    // --------------------------------------------------------------------

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     *
     * @return bool
     */
    public function warning( $message )
    {
        return $this->write( Logger::WARNING, $message );
    }

    // --------------------------------------------------------------------

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     *
     * @return bool
     */
    public function alert( $message )
    {
        return $this->write( Logger::ALERT, $message );
    }

    // --------------------------------------------------------------------

    /**
     * System is unusable.
     *
     * @param string $message
     *
     * @return bool
     */
    public function emergency( $message )
    {
        return $this->write( Logger::EMERGENCY, $message );
    }

    // --------------------------------------------------------------------

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     *
     * @return bool
     */
    public function critical( $message )
    {
        return $this->write( Logger::CRITICAL, $message );
    }

    // --------------------------------------------------------------------

    /**
     * Write logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     *
     * @return bool
     */
    public function write( $level, $message )
    {
        if( $this->_config[ 'threshold' ] == 0 )
        {
            return FALSE;
        }

        if( is_array( $this->_config[ 'threshold' ] ) )
        {
            if( ! in_array( $level, $this->_config[ 'threshold' ] ) )
            {
                return FALSE;
            }
        }
        elseif( $this->_config[ 'threshold' ] !== Logger::ALL )
        {
            if( ! is_string( $level ) && $level > $this->_config[ 'threshold' ] )
            {
                return FALSE;
            }
        }

        if( is_numeric( $level ) )
        {
            $level = $this->_levels[ $level ];
        }
        else
        {
            $level = strtoupper( $level );
        }

        $filepath = $this->_config[ 'path' ] . 'log-' . date( 'd-m-Y' ) . '.log';
        $log = '';

        if( ! file_exists( $filepath ) )
        {
            $newfile = TRUE;
        }

        if( ! $fp = @fopen( $filepath, 'ab' ) )
        {
            return FALSE;
        }

        $log .= $level . ' - ' . date( 'r' ) . ' --> ' . $message . "\n";

        flock( $fp, LOCK_EX );

        for( $written = 0, $length = strlen( $log ); $written < $length; $written += $result )
        {
            if( ( $result = fwrite( $fp, substr( $log, $written ) ) ) === FALSE )
            {
                break;
            }
        }

        flock( $fp, LOCK_UN );
        fclose( $fp );

        if( isset( $newfile ) AND $newfile === TRUE )
        {
            chmod( $filepath, 0664 );
        }

        return is_int( $result );
    }
}