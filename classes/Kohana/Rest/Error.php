<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Rest_Error {
    
    public static function handler($errno, $errstr, $errfile = null, $errline = null, $errcontext = null)
    {
        $data = array(
            'number'   => $errno,
            'message'  => $errstr,
            'file'     => $errfile,
            'line'     => $errline,
            'context'  => $errcontext
        );
        
        try
        {
            ORM::factory('Rest_Log')->add('ERROR', $data);
        }
        catch (Exception $e)
        {
            Kohana::$log->add(Log::DEBUG, strtr("number\tmessage\tfile\tline", $data));
        }
        
        header(HTTP::$protocol . ' 500 Service Error');
        return true;
    }
    
    public static function shutdown_handler()
    {
        if ($error = error_get_last())
        {
            // Clean the output buffer
            ob_get_level() AND ob_clean();
            
            // Fake an exception for nice debugging
            Rest_Error::handler($error['type'], $error['message'], $error['file'], $error['line']);

            // Shutdown now to avoid a "death loop"
            exit(1);
        }
    }
}