<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Rest_Response_Format {
    
    public static function is_accepted_content_type($content_type)
    {
        return in_array($content_type, array(Rest_Response_Format_Json::HEAD_CONTENT_TYPE));
    }
    
    public static function factory($content_type)
    {
        // Turn this to a switch..case to support multiple content type
        // But for know we only support, JSON
        
        if (Rest_Response_Format_Json::HEAD_CONTENT_TYPE !== $content_type)
        {
            throw new HTTP_Exception_406('Content_Type :content_type not acceptable.', array(':content_type' => $content_type));
        }
        
        return new Rest_Response_Format_Json();
    }
    
    abstract public function render(array $data = array());
    
}