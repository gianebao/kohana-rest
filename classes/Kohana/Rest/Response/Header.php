<?php defined('SYSPATH') OR die('No direct script access.');

class Kohana_Rest_Response_Header extends HTTP_Header {
    
    public function secure(Response $response = null, $replace = false)
    {
        $secure_headers = array('Server' => $_SERVER['SERVER_NAME']);
        
        if (!empty($response))
        {
            
            return $response->headers($secure_headers);
        }
        
        $processed_headers = array();
        
        // Get the headers array
        $headers = array_merge($this->getArrayCopy(), $secure_headers);
        
        foreach ($headers as $header => $value)
        {
            if (is_array($value))
            {
                $value = implode(', ', $value);
            }
     
            $processed_headers[] = Text::ucfirst($header).': '.$value;
        }
        
        $this->_send_headers_to_php($processed_headers, $replace);
        
        return $this;
    }
}