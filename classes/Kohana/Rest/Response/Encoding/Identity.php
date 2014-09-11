<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Rest_Response_Encoding_Identity extends Rest_Response_Encoding {
    
    const TRANSFER_ENCODING = 'identity';
    
    public function encode($data, $level = null)
    {
        return $data;
    }
}