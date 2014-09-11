<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Rest_Response_Encoding_Gzip extends Rest_Response_Encoding{
    
    const TRANSFER_ENCODING = 'gzip';
    
    public function encode($data, $level = null)
    {
        $level = is_null($level) ? -1: (int) $level;
        
        return -1 <= $level && 9 >= $level
            ? gzcompress($data, $level)
            : $data;
    }
}