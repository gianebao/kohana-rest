<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Rest_Exception extends HTTP_Exception {
    
    public static function factory($status, $message = null, array $variables = array(), Exception $previous = NULL)
    {
        if (empty(Response::$messages[$status]))
        {
            throw new Kohana_Exception('Undefined status `:status`.', array(':status' => $status));
        }
        
        if (empty($message))
        {
            $message = Response::$messages[$status];
        }
        
        $message .= '; more_info: :more_info';
        
        $variables = array_merge(array(':more_info' => DOCUMENTATION_ERROR_URL . $status), $variables);
        
        return Kohana::$errors ? HTTP_Exception::factory($status, $message, $variables): new Rest_Exception($message, $variables, $previous, $status);
    }
    
    public function __construct($message = null, array $variables = array(), Exception $previous = NULL, $code = 500)
    {
        parent::__construct($message, $variables, $previous);
        $this->code = $code;
    }
    
    /**
     * Exception handler, logs the exception and generates a Response object
     * for display.
     * To support ReSTful, exception messages are added to the standard http status response.
     *
     * @uses    Kohana_Exception::response
     * @param   Exception  $e
     * @return  void
     */
    public static function handler(Exception $e)
    {
        Rest_Exception::_handler($e);
        exit(1);
    }
    
    public function get_response()
    {
        // Get the exception information
        $code    = $this->getCode();
        $message = $this->getMessage();
        // $file    = $this->getFile();
        // $line    = $this->getLine();
        // $trace   = $this->getTrace();
        
        header(HTTP::$protocol . ' ' . $code . ' ' . $message);
        $header = new Rest_Response_Header();
        $header->secure();
        
        exit(1);
    }
    
    public static function _handler(Exception $e)
    {
        try
        {
            $e->get_response();
        }
        catch (Exception $e)
        {
            /**
             * Things are going *really* badly for us, We now have no choice
             * but to bail. Hard.
             */
            // Clean the output buffer if one exists
            ob_get_level() AND ob_clean();

            // Set the Status code to 500, and Content-Type to text/plain.
            header('Content-Type: text/plain; charset='.Kohana::$charset, TRUE, 500);

            echo Kohana_Exception::text($e);
        }
    }
}