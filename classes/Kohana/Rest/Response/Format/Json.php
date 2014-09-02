<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Rest_Response_Format_Json extends Rest_Response_Format {
    
    const HEAD_CONTENT_TYPE = 'application/json';
    
    public function render(array $data = array())
    {
        return json_encode($data);
    }
}