<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Rest_Response_Format {
    const DEFAULT_HEAD_CONTENT_TYPE = '*/*';
    
    public static function is_accepted_content_type($content_type)
    {
        return in_array($content_type, array(Rest_Response_Format_Json::HEAD_CONTENT_TYPE));
    }
    
    public static function factory($accepts)
    {
        if (empty($accepts) || '*/*' == $accepts || 'application/*' == $accepts)
        {
            $accepts = Rest_Response_Format_Json::HEAD_CONTENT_TYPE;
        }
        
        // Turn this to a switch..case to support multiple content type
        // But for know we only support, JSON
        
        if (Rest_Response_Format_Json::HEAD_CONTENT_TYPE !== $accepts)
        {
            throw Rest_Exception::factory(406, 'request_header_accept_invalid', array(':content_type' => $accepts));
        }
        
        return new Rest_Response_Format_Json();
    }
    
    abstract public function render(array $data = array());
    
}