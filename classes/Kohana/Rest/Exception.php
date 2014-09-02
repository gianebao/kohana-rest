<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Rest_Exception extends Kohana_Kohana_Exception {
    
    public static function response(Exception $e)
    {
        return $response = parent::response($e);
        
        /*
        // Prepare the response object.
		$response = Response::factory();
        
        // Get the exception information
        $class   = get_class($e);
        $code    = $e->getCode();
        $message = $e->getMessage();
        $file    = $e->getFile();
        $line    = $e->getLine();
        $trace   = $e->getTrace();
        */
    }
}