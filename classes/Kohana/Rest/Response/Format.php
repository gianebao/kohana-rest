<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Rest_Response_Format {
    const NS = 'Rest_Response_Format_';
    const DEFAULT_HEAD_CONTENT_TYPE = '*/*';
    const ACCEPT_REGEX = '/(([a-z]+(\/[a-z]+)?)|(\*\/\*))(;q=(\d+(\.\d+)?))?/';
    
    protected $_data = null;
    
    public static function is_accepted_content_type($content_type)
    {
        return in_array($content_type, array(Rest_Response_Format_Json::HEAD_CONTENT_TYPE));
    }
    
    protected static function parse_accept($string, & $matches)
    {
        return preg_match_all(Rest_Response_Format::ACCEPT_REGEX, str_replace(' ', '', strtolower($string)), $matches, PREG_SET_ORDER);
    }
    
    public static function factory($accepts)
    {
        if (empty($accepts) || '*/*' == $accepts)
        {
            $accepts = Rest_Response_Format_Json::HEAD_CONTENT_TYPE;
        }
        
        $status = Rest_Response_Format::parse_accept($accepts, $matches);
        
        // Turn this to a switch..case to support multiple content type
        // But for know we only support, JSON
        if (!empty($status))
        {
            foreach ($matches as $match)
            {
                if (Rest_Response_Format_Json::HEAD_CONTENT_TYPE === $match[1])
                {
                    // Provides flexibility. Hardcoded since json is the onlyone supported now.
                    return new Rest_Response_Format_Json();
                    break;
                }
            }
        }

        throw Rest_Exception::factory(406, 'request_header_accept_invalid', array(':content_type' => $accepts));
    }
    
    abstract public function render(array $data, $encoding = null);
    
    public function __toString()
    {
        return $this->_data;
    }
    
    public function encode($encoding)
    {
        if (empty($encoding))
        {
            return false;
        }
        
        $status = Rest_Response_Format::parse_accept($encoding, $matches);
        
        if (!empty($status))
        {
            foreach ($matches as $match)
            {
                $class = Rest_Response_Encoding::factory($match[1]);
                
                if (!empty($class))
                {
                    // Perform only one type of encoding.
                    $this->_data = $class->encode($this->_data, Arr::get($match, 6, null));
                    return $this;
                }
            }
        }

        throw Rest_Exception::factory(406, 'request_header_accept_encoding_invalid', array(':content_encoding' => $encoding));
    }
    
}