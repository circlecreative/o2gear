<?php
<<<<<<< HEAD

/**
 * A very simple php code profiler. No extensions required.
 *
 * Example usage:
 * <code>
 * declare(ticks=1);
 * require_once('./SimpleProfiler.class.php');
 * SimpleProfiler::start_profile();
 * // your code here
 * SimpleProfiler::stop_profile();
 * print_r(SimpleProfiler::get_profile());
 * </code>
 *
 * Consider combining with auto_prepend_file and auto_append_file
 *
 * @package SimpleProfiler
 * @author Kurt Payne
 * @copyright 2012 Go Daddy
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @version Release: 1.0
 * @link http://inside.godaddy.com/write-code-profiler-php
 */
namespace O2System\Gears;

class Profiler 
{

    /**
     * Profile information
     * [file] => (
     *     [function] => runtime in microseconds
     * )
     * @access protected
     * @var array
     */
    protected static $_profile = array();

    /**
     * Remember the last time a tickable event was encountered
     * @access protected
     * @var float
     */
    protected static $_last_time = 0;

    /**
     * Return profile information
     * [file] => (
     *     [function] => runtime in microseconds
     * )
     * @access public
     * @return array
     */
    public static function get_profile() 
    {
        return self::$_profile;
    }

    /**
     * Attempt to disable any detetected opcode caches / optimizers
     * @access public
     * @return void
     */
    public static function disable_opcode_cache() {
        if ( extension_loaded( 'xcache' ) ) {
            @ini_set( 'xcache.optimizer', false ); // Will be implemented in 2.0, here for future proofing
            // XCache seems to do some optimizing, anyway.
            // The recorded number of ticks is smaller with xcache.cacher enabled than without.
        } elseif ( extension_loaded( 'apc' ) ) {
            @ini_set( 'apc.optimization', 0 ); // Removed in APC 3.0.13 (2007-02-24)
            apc_clear_cache();
        } elseif ( extension_loaded( 'eaccelerator' ) ) {
            @ini_set( 'eaccelerator.optimizer', 0 );
            if ( function_exists( 'eaccelerator_optimizer' ) ) {
                @eaccelerator_optimizer( false );
            }
            // Try setting eaccelerator.optimizer = 0 in a .user.ini or .htaccess file
        } elseif (extension_loaded( 'Zend Optimizer+' ) ) {
            @ini_set('zend_optimizerplus.optimization_level', 0);
        }
    }

    /**
     * Start profiling
     * @access public
     * @return void
     */
    public static function start_profile() {
        if (0 === self::$_last_time) {
            self::$_last_time = microtime(true);
            self::disable_opcode_cache();
        }
        register_tick_function(array(__CLASS__, 'do_profile'));
    }

    /**
     * Stop profiling
     * @access public
     * @return void
     */
    public static function stop_profile() {
        unregister_tick_function(array(__CLASS__, 'do_profile'));
    }

    /**
     * Profile.
     * This records the source class / function / file of the current tickable event
     * and the time between now and the last tickable event. This information is
     * stored in self::$_profile
     * @access public
     * @return void
     */
    public static function do_profile() {

        // Get the backtrace, keep the object in case we need to reflect
        // upon it to find the original source file
        if ( version_compare( PHP_VERSION, '5.3.6' ) < 0 ) {
            $bt = debug_backtrace( true );
        } elseif ( version_compare( PHP_VERSION, '5.4.0' ) < 0 ) {
            $bt = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS | DEBUG_BACKTRACE_PROVIDE_OBJECT );
        } else {
            // Examine the last 2 frames
            $bt = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS | DEBUG_BACKTRACE_PROVIDE_OBJECT, 2 );
        }

        // Find the calling function $frame = $bt[0];
        if ( count( $bt ) >= 2 ) {
            $frame = $bt[1];
        }

        // If the calling function was a lambda, the original file is stored here.
        // Copy this elsewhere before unsetting the backtrace
        $lambda_file = @$bt[0]['file'];

        // Free up memory
        unset( $bt );

        // Include/require
        if ( in_array( strtolower( $frame['function'] ), array( 'include', 'require', 'include_once', 'require_once' ) ) ) {
            $file = $frame['args'][0];

        // Object instances
        } elseif ( isset( $frame['object'] ) && method_exists( $frame['object'], $frame['function'] ) ) {
            try {
                $reflector = new ReflectionMethod( $frame['object'], $frame['function'] );
                $file = $reflector->getFileName();
            } catch ( Exception $e ) {
            }

        // Static method calls
        } elseif ( isset( $frame['class'] ) && method_exists( $frame['class'], $frame['function'] ) ) {
            try {
                $reflector = new ReflectionMethod( $frame['class'], $frame['function'] );
                $file = $reflector->getFileName();
            } catch ( Exception $e ) {
            }

        // Functions
        } elseif ( !empty( $frame['function'] ) && function_exists( $frame['function'] ) ) {
            try {
                $reflector = new ReflectionFunction( $frame['function'] );
                $file = $reflector->getFileName();
            } catch ( Exception $e ) {
            }

        // Lambdas / closures
        } elseif ( '__lambda_func' == $frame['function'] || '{closure}' == $frame['function'] ) {
            $file = preg_replace( '/\(\d+\)\s+:\s+runtime-created function/', '', $lambda_file );

        // File info only
        } elseif ( isset( $frame['file'] ) ) {
            $file = $frame['file'];

        // If we get here, we have no idea where the call came from.
        // Assume it originated in the script the user requested.
        } else {
            $file = $_SERVER['SCRIPT_FILENAME'];
        }

        // Function
        $function = $frame['function'];
        if (isset($frame['object'])) {
            $function = get_class($frame['object']) . '::' . $function;
        }

        // Create the entry for the file
        if (!isset(self::$_profile[$file])) {
            self::$_profile[$file] = array();
        }

        // Create the entry for the function
        if (!isset(self::$_profile[$file][$function])) {
            self::$_profile[$file][$function] = 0;
        }

        // Record the call
        self::$_profile[$file][$function] += (microtime(true) - self::$_last_time);
        self::$_last_time = microtime(true);
    }
}
=======
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
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
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
 * Profiler Class
 *
 * This class enables you to display benchmark, query, and other data
 * in order to help with debugging and optimization.
 *
 * Note: At some point it would be good to move all the HTML in this class
 * into a set of template files in order to allow customization.
 *
 * @package        CodeIgniter
 * @subpackage     Libraries
 * @category       Libraries
 * @author         EllisLab Dev Team
 * @link           http://codeigniter.com/user_guide/general/profiling.html
 */
class Profiler
{

    /**
     * List of profiler sections available to show
     *
     * @var array
     */
    protected $_available_sections = array(
        'benchmarks',
        'get',
        'memory_usage',
        'post',
        'uri_string',
        'controller_info',
        'queries',
        'http_headers',
        'session_data',
        'config'
    );

    /**
     * Number of queries to show before making the additional queries togglable
     *
     * @var int
     */
    protected $_query_toggle_count = 25;

    /**
     * Reference to the O2System singleton
     *
     * @var object
     */
    protected $system;

    // --------------------------------------------------------------------

    /**
     * Class constructor
     *
     * Initialize Profiler
     *
     * @param    array $config Parameters
     */
    public function __construct( $config = array() )
    {
        $this->system =& \O2System::instance();
        $this->system->load->lang( 'profiler' );

        if( isset( $config[ 'query_toggle_count' ] ) )
        {
            $this->_query_toggle_count = (int)$config[ 'query_toggle_count' ];
            unset( $config[ 'query_toggle_count' ] );
        }

        // default all sections to display
        foreach( $this->_available_sections as $section )
        {
            if( ! isset( $config[ $section ] ) )
            {
                $this->_compile_{$section} = TRUE;
            }
        }

        $this->set_sections( $config );
        Logger::info( 'Profiler Class Initialized' );
    }

    // --------------------------------------------------------------------

    /**
     * Set Sections
     *
     * Sets the private _compile_* properties to enable/disable Profiler sections
     *
     * @param    mixed $config
     *
     * @return    void
     */
    public function set_sections( $config )
    {
        if( isset( $config[ 'query_toggle_count' ] ) )
        {
            $this->_query_toggle_count = (int)$config[ 'query_toggle_count' ];
            unset( $config[ 'query_toggle_count' ] );
        }

        foreach( $config as $method => $enable )
        {
            if( in_array( $method, $this->_available_sections ) )
            {
                $this->_compile_{$method} = ( $enable !== FALSE );
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Run the Profiler
     *
     * @return    string
     */
    public function run()
    {
        $output = '<div id="o2gears_profiler" style="clear:both;background-color:#fff;padding:10px;">';
        $fields_displayed = 0;

        foreach( $this->_available_sections as $section )
        {
            if( $this->_compile_{$section} !== FALSE )
            {
                $func = '_compile_' . $section;
                $output .= $this->{$func}();
                $fields_displayed++;
            }
        }

        if( $fields_displayed === 0 )
        {
            $output .= '<p style="border:1px solid #5a0099;padding:10px;margin:20px 0;background-color:#eee;">'
                       . $this->system->lang->line( 'profiler_no_profiles' ) . '</p>';
        }

        return $output . '</div>';
    }

    // --------------------------------------------------------------------

    /**
     * Auto Profiler
     *
     * This function cycles through the entire array of mark points and
     * matches any two points that are named identically (ending in "_start"
     * and "_end" respectively).  It then compiles the execution times for
     * all points and returns it as an array
     *
     * @return    array
     */
    protected function _compile_benchmarks()
    {
        $profile = array();
        foreach( $this->system->benchmark->marker as $key => $val )
        {
            // We match the "end" marker so that the list ends
            // up in the order that it was defined
            if( preg_match( '/(.+?)_end$/i', $key, $match )
                && isset( $this->system->benchmark->marker[ $match[ 1 ] . '_end' ], $this->system->benchmark->marker[ $match[ 1 ] . '_start' ] )
            )
            {
                $profile[ $match[ 1 ] ] = $this->system->benchmark->elapsed_time( $match[ 1 ] . '_start', $key );
            }
        }

        // Build a table containing the profile data.
        // Note: At some point we should turn this into a template that can
        // be modified. We also might want to make this data available to be logged

        $output = "\n\n"
                  . '<fieldset id="o2gears_profiler_benchmarks" style="border:1px solid #900;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee;">'
                  . "\n"
                  . '<legend style="color:#900;">&nbsp;&nbsp;' . $this->system->lang->line( 'profiler_benchmarks' ) . "&nbsp;&nbsp;</legend>"
                  . "\n\n\n<table style=\"width:100%;\">\n";

        foreach( $profile as $key => $val )
        {
            $key = ucwords( str_replace( array( '_', '-' ), ' ', $key ) );
            $output .= '<tr><td style="padding:5px;width:50%;color:#000;font-weight:bold;background-color:#ddd;">'
                       . $key . '&nbsp;&nbsp;</td><td style="padding:5px;width:50%;color:#900;font-weight:normal;background-color:#ddd;">'
                       . $val . "</td></tr>\n";
        }

        return $output . "</table>\n</fieldset>";
    }

    // --------------------------------------------------------------------

    /**
     * Compile Queries
     *
     * @return    string
     */
    protected function _compile_queries()
    {
        $dbs = array();

        // Let's determine which databases are currently connected to
        foreach( get_object_vars( $this->system ) as $name => $cobject )
        {
            if( is_object( $cobject ) )
            {
                if( $cobject instanceof \O2System\Libraries\Database )
                {
                    $dbs[ get_class( $this->system ) . ':$' . $name ] = $cobject;
                }
                elseif( $cobject instanceof \O2System\Core\Model )
                {
                    foreach( get_object_vars( $cobject ) as $mname => $mobject )
                    {
                        if( $mobject instanceof \O2System\Libraries\Database )
                        {
                            $dbs[ get_class( $cobject ) . ':$' . $mname ] = $mobject;
                        }
                    }
                }
            }
        }

        if( count( $dbs ) === 0 )
        {
            return "\n\n"
                   . '<fieldset id="o2gears_profiler_queries" style="border:1px solid #0000FF;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee;">'
                   . "\n"
                   . '<legend style="color:#0000FF;">&nbsp;&nbsp;' . $this->system->lang->line( 'profiler_queries' ) . '&nbsp;&nbsp;</legend>'
                   . "\n\n\n<table style=\"border:none; width:100%;\">\n"
                   . '<tr><td style="width:100%;color:#0000FF;font-weight:normal;background-color:#eee;padding:5px;">'
                   . $this->system->lang->line( 'profiler_no_db' )
                   . "</td></tr>\n</table>\n</fieldset>";
        }

        // Load the text helper so we can highlight the SQL
        $this->system->load->helper( 'text' );

        // Key words we want bolded
        $highlight = array(
            'SELECT', 'DISTINCT', 'FROM', 'WHERE', '&&', 'LEFT&nbsp;JOIN', 'ORDER&nbsp;BY', 'GROUP&nbsp;BY', 'LIMIT',
            'INSERT', 'INTO', 'VALUES', 'UPDATE', 'OR&nbsp;', 'HAVING', 'OFFSET', 'NOT&nbsp;IN', 'IN', 'LIKE',
            'NOT&nbsp;LIKE', 'COUNT', 'MAX', 'MIN', 'ON', 'AS', 'AVG', 'SUM', '(', ')'
        );

        $output = "\n\n";
        $count = 0;

        foreach( $dbs as $name => $db )
        {
            $hide_queries = ( count( $db->queries ) > $this->_query_toggle_count ) ? ' display:none' : '';
            $total_time = number_format( array_sum( $db->query_times ), 4 ) . ' ' . $this->system->lang->line( 'profiler_seconds' );

            $show_hide_js = '(<span style="cursor: pointer;" onclick="var s=document.getElementById(\'o2gears_profiler_queries_db_' . $count . '\').style;s.display=s.display==\'none\'?\'\':\'none\';this.innerHTML=this.innerHTML==\'' . $this->system->lang->line( 'profiler_section_hide' ) . '\'?\'' . $this->system->lang->line( 'profiler_section_show' ) . '\':\'' . $this->system->lang->line( 'profiler_section_hide' ) . '\';">' . $this->system->lang->line( 'profiler_section_hide' ) . '</span>)';

            if( $hide_queries !== '' )
            {
                $show_hide_js = '(<span style="cursor: pointer;" onclick="var s=document.getElementById(\'o2gears_profiler_queries_db_' . $count . '\').style;s.display=s.display==\'none\'?\'\':\'none\';this.innerHTML=this.innerHTML==\'' . $this->system->lang->line( 'profiler_section_show' ) . '\'?\'' . $this->system->lang->line( 'profiler_section_hide' ) . '\':\'' . $this->system->lang->line( 'profiler_section_show' ) . '\';">' . $this->system->lang->line( 'profiler_section_show' ) . '</span>)';
            }

            $output .= '<fieldset style="border:1px solid #0000FF;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee;">'
                       . "\n"
                       . '<legend style="color:#0000FF;">&nbsp;&nbsp;' . $this->system->lang->line( 'profiler_database' )
                       . ':&nbsp; ' . $db->database . ' (' . $name . ')&nbsp;&nbsp;&nbsp;' . $this->system->lang->line( 'profiler_queries' )
                       . ': ' . count( $db->queries ) . ' (' . $total_time . ')&nbsp;&nbsp;' . $show_hide_js . "</legend>\n\n\n"
                       . '<table style="width:100%;' . $hide_queries . '" id="o2gears_profiler_queries_db_' . $count . "\">\n";

            if( count( $db->queries ) === 0 )
            {
                $output .= '<tr><td style="width:100%;color:#0000FF;font-weight:normal;background-color:#eee;padding:5px;">'
                           . $this->system->lang->line( 'profiler_no_queries' ) . "</td></tr>\n";
            }
            else
            {
                foreach( $db->queries as $key => $val )
                {
                    $time = number_format( $db->query_times[ $key ], 4 );
                    $val = highlight_code( $val );

                    foreach( $highlight as $bold )
                    {
                        $val = str_replace( $bold, '<strong>' . $bold . '</strong>', $val );
                    }

                    $output .= '<tr><td style="padding:5px;vertical-align:top;width:1%;color:#900;font-weight:normal;background-color:#ddd;">'
                               . $time . '&nbsp;&nbsp;</td><td style="padding:5px;color:#000;font-weight:normal;background-color:#ddd;">'
                               . $val . "</td></tr>\n";
                }
            }

            $output .= "</table>\n</fieldset>";
            $count++;
        }

        return $output;
    }

    // --------------------------------------------------------------------

    /**
     * Compile $_GET Data
     *
     * @return    string
     */
    protected function _compile_get()
    {
        $output = "\n\n"
                  . '<fieldset id="o2gears_profiler_get" style="border:1px solid #cd6e00;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee;">'
                  . "\n"
                  . '<legend style="color:#cd6e00;">&nbsp;&nbsp;' . $this->system->lang->line( 'profiler_get_data' ) . "&nbsp;&nbsp;</legend>\n";

        if( count( $_GET ) === 0 )
        {
            $output .= '<div style="color:#cd6e00;font-weight:normal;padding:4px 0 4px 0;">' . $this->system->lang->line( 'profiler_no_get' ) . '</div>';
        }
        else
        {
            $output .= "\n\n<table style=\"width:100%;border:none;\">\n";

            foreach( $_GET as $key => $val )
            {
                is_int( $key ) OR $key = "'" . $key . "'";

                $output .= '<tr><td style="width:50%;color:#000;background-color:#ddd;padding:5px;">&#36;_GET['
                           . $key . ']&nbsp;&nbsp; </td><td style="width:50%;padding:5px;color:#cd6e00;font-weight:normal;background-color:#ddd;">'
                           . ( ( is_array( $val ) OR is_object( $val ) ) ? '<pre>' . htmlspecialchars( stripslashes( print_r( $val, TRUE ) ) ) . '</pre>' : htmlspecialchars( stripslashes( $val ) ) )
                           . "</td></tr>\n";
            }

            $output .= "</table>\n";
        }

        return $output . '</fieldset>';
    }

    // --------------------------------------------------------------------

    /**
     * Compile $_POST Data
     *
     * @return    string
     */
    protected function _compile_post()
    {
        $output = "\n\n"
                  . '<fieldset id="o2gears_profiler_post" style="border:1px solid #009900;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee;">'
                  . "\n"
                  . '<legend style="color:#009900;">&nbsp;&nbsp;' . $this->system->lang->line( 'profiler_post_data' ) . "&nbsp;&nbsp;</legend>\n";

        if( count( $_POST ) === 0 && count( $_FILES ) === 0 )
        {
            $output .= '<div style="color:#009900;font-weight:normal;padding:4px 0 4px 0;">' . $this->system->lang->line( 'profiler_no_post' ) . '</div>';
        }
        else
        {
            $output .= "\n\n<table style=\"width:100%;\">\n";

            foreach( $_POST as $key => $val )
            {
                is_int( $key ) OR $key = "'" . $key . "'";

                $output .= '<tr><td style="width:50%;padding:5px;color:#000;background-color:#ddd;">&#36;_POST['
                           . $key . ']&nbsp;&nbsp; </td><td style="width:50%;padding:5px;color:#009900;font-weight:normal;background-color:#ddd;">';

                if( is_array( $val ) OR is_object( $val ) )
                {
                    $output .= '<pre>' . htmlspecialchars( stripslashes( print_r( $val, TRUE ) ) ) . '</pre>';
                }
                else
                {
                    $output .= htmlspecialchars( stripslashes( $val ) );
                }

                $output .= "</td></tr>\n";
            }

            foreach( $_FILES as $key => $val )
            {
                is_int( $key ) OR $key = "'" . $key . "'";

                $output .= '<tr><td style="width:50%;padding:5px;color:#000;background-color:#ddd;">&#36;_FILES['
                           . $key . ']&nbsp;&nbsp; </td><td style="width:50%;padding:5px;color:#009900;font-weight:normal;background-color:#ddd;">';

                if( is_array( $val ) OR is_object( $val ) )
                {
                    $output .= '<pre>' . htmlspecialchars( stripslashes( print_r( $val, TRUE ) ) ) . '</pre>';
                }

                $output .= "</td></tr>\n";
            }

            $output .= "</table>\n";
        }

        return $output . '</fieldset>';
    }

    // --------------------------------------------------------------------

    /**
     * Show query string
     *
     * @return    string
     */
    protected function _compile_uri_string()
    {
        return "\n\n"
               . '<fieldset id="o2gears_profiler_uri_string" style="border:1px solid #000;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee;">'
               . "\n"
               . '<legend style="color:#000;">&nbsp;&nbsp;' . $this->system->lang->line( 'profiler_uri_string' ) . "&nbsp;&nbsp;</legend>\n"
               . '<div style="color:#000;font-weight:normal;padding:4px 0 4px 0;">'
               . ( $this->system->uri->uri_string === '' ? $this->system->lang->line( 'profiler_no_uri' ) : $this->system->uri->uri_string )
               . '</div></fieldset>';
    }

    // --------------------------------------------------------------------

    /**
     * Show the controller and function that were called
     *
     * @return    string
     */
    protected function _compile_controller_info()
    {
        return "\n\n"
               . '<fieldset id="o2gears_profiler_controller_info" style="border:1px solid #995300;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee;">'
               . "\n"
               . '<legend style="color:#995300;">&nbsp;&nbsp;' . $this->system->lang->line( 'profiler_controller_info' ) . "&nbsp;&nbsp;</legend>\n"
               . '<div style="color:#995300;font-weight:normal;padding:4px 0 4px 0;">' . $this->system->router->class . '/' . $this->system->router->method
               . '</div></fieldset>';
    }

    // --------------------------------------------------------------------

    /**
     * Compile memory usage
     *
     * Display total used memory
     *
     * @return    string
     */
    protected function _compile_memory_usage()
    {
        return "\n\n"
               . '<fieldset id="o2gears_profiler_memory_usage" style="border:1px solid #5a0099;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee;">'
               . "\n"
               . '<legend style="color:#5a0099;">&nbsp;&nbsp;' . $this->system->lang->line( 'profiler_memory_usage' ) . "&nbsp;&nbsp;</legend>\n"
               . '<div style="color:#5a0099;font-weight:normal;padding:4px 0 4px 0;">'
               . ( ( $usage = memory_get_usage() ) != '' ? number_format( $usage ) . ' bytes' : $this->system->lang->line( 'profiler_no_memory' ) )
               . '</div></fieldset>';
    }

    // --------------------------------------------------------------------

    /**
     * Compile header information
     *
     * Lists HTTP headers
     *
     * @return    string
     */
    protected function _compile_http_headers()
    {
        $output = "\n\n"
                  . '<fieldset id="o2gears_profiler_http_headers" style="border:1px solid #000;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee;">'
                  . "\n"
                  . '<legend style="color:#000;">&nbsp;&nbsp;' . $this->system->lang->line( 'profiler_headers' )
                  . '&nbsp;&nbsp;(<span style="cursor: pointer;" onclick="var s=document.getElementById(\'o2gears_profiler_httpheaders_table\').style;s.display=s.display==\'none\'?\'\':\'none\';this.innerHTML=this.innerHTML==\'' . $this->system->lang->line( 'profiler_section_show' ) . '\'?\'' . $this->system->lang->line( 'profiler_section_hide' ) . '\':\'' . $this->system->lang->line( 'profiler_section_show' ) . '\';">' . $this->system->lang->line( 'profiler_section_show' ) . "</span>)</legend>\n\n\n"
                  . '<table style="width:100%;display:none;" id="o2gears_profiler_httpheaders_table">' . "\n";

        foreach( array(
                     'HTTP_ACCEPT', 'HTTP_USER_AGENT', 'HTTP_CONNECTION', 'SERVER_PORT', 'SERVER_NAME', 'REMOTE_ADDR',
                     'SERVER_SOFTWARE', 'HTTP_ACCEPT_LANGUAGE', 'SCRIPT_NAME', 'REQUEST_METHOD', ' HTTP_HOST',
                     'REMOTE_HOST', 'CONTENT_TYPE', 'SERVER_PROTOCOL', 'QUERY_STRING', 'HTTP_ACCEPT_ENCODING',
                     'HTTP_X_FORWARDED_FOR', 'HTTP_DNT'
                 ) as $header )
        {
            $val = isset( $_SERVER[ $header ] ) ? $_SERVER[ $header ] : '';
            $output .= '<tr><td style="vertical-align:top;width:50%;padding:5px;color:#900;background-color:#ddd;">'
                       . $header . '&nbsp;&nbsp;</td><td style="width:50%;padding:5px;color:#000;background-color:#ddd;">' . $val . "</td></tr>\n";
        }

        return $output . "</table>\n</fieldset>";
    }

    // --------------------------------------------------------------------

    /**
     * Compile config information
     *
     * Lists developer config variables
     *
     * @return    string
     */
    protected function _compile_config()
    {
        $output = "\n\n"
                  . '<fieldset id="o2gears_profiler_config" style="border:1px solid #000;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee;">'
                  . "\n"
                  . '<legend style="color:#000;">&nbsp;&nbsp;' . $this->system->lang->line( 'profiler_config' ) . '&nbsp;&nbsp;(<span style="cursor: pointer;" onclick="var s=document.getElementById(\'o2gears_profiler_config_table\').style;s.display=s.display==\'none\'?\'\':\'none\';this.innerHTML=this.innerHTML==\'' . $this->system->lang->line( 'profiler_section_show' ) . '\'?\'' . $this->system->lang->line( 'profiler_section_hide' ) . '\':\'' . $this->system->lang->line( 'profiler_section_show' ) . '\';">' . $this->system->lang->line( 'profiler_section_show' ) . "</span>)</legend>\n\n\n"
                  . '<table style="width:100%;display:none;" id="o2gears_profiler_config_table">' . "\n";

        foreach( $this->system->config->config as $config => $val )
        {
            if( is_array( $val ) OR is_object( $val ) )
            {
                $val = print_r( $val, TRUE );
            }

            $output .= '<tr><td style="padding:5px;vertical-align:top;color:#900;background-color:#ddd;">'
                       . $config . '&nbsp;&nbsp;</td><td style="padding:5px;color:#000;background-color:#ddd;">' . htmlspecialchars( $val ) . "</td></tr>\n";
        }

        return $output . "</table>\n</fieldset>";
    }

    // --------------------------------------------------------------------

    /**
     * Compile session userdata
     *
     * @return    string
     */
    protected function _compile_session_data()
    {
        if( ! isset( $this->system->session ) )
        {
            return;
        }

        $output = '<fieldset id="o2gears_profiler_csession" style="border:1px solid #000;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee;">'
                  . '<legend style="color:#000;">&nbsp;&nbsp;' . $this->system->lang->line( 'profiler_session_data' ) . '&nbsp;&nbsp;(<span style="cursor: pointer;" onclick="var s=document.getElementById(\'o2gears_profiler_session_data\').style;s.display=s.display==\'none\'?\'\':\'none\';this.innerHTML=this.innerHTML==\'' . $this->system->lang->line( 'profiler_section_show' ) . '\'?\'' . $this->system->lang->line( 'profiler_section_hide' ) . '\':\'' . $this->system->lang->line( 'profiler_section_show' ) . '\';">' . $this->system->lang->line( 'profiler_section_show' ) . '</span>)</legend>'
                  . '<table style="width:100%;display:none;" id="o2gears_profiler_session_data">';

        foreach( $this->system->session->userdata() as $key => $val )
        {
            if( is_array( $val ) OR is_object( $val ) )
            {
                $val = print_r( $val, TRUE );
            }

            $output .= '<tr><td style="padding:5px;vertical-align:top;color:#900;background-color:#ddd;">'
                       . $key . '&nbsp;&nbsp;</td><td style="padding:5px;color:#000;background-color:#ddd;">' . htmlspecialchars( $val ) . "</td></tr>\n";
        }

        return $output . "</table>\n</fieldset>";
    }

}

/* End of file Profiler.php */
/* Location: ./o2system/core/gears/Profiler.php */
>>>>>>> origin/master
