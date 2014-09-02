<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Rest_Response_Format_Json extends Rest_Response_Format {

    public function render()
    {
        return json_encode($this->_data);
    }
}