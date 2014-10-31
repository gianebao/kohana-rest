<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Rest_Response_Encoding_Gzip extends Rest_Response_Encoding{
    
    const TRANSFER_ENCODING = 'gzip';
    
    public function encode($data, $level = null)
    {
        $level = is_null($level) ? 9: (int) $level;
    
        return "\x1f\x8b\x08\x00\x00\x00\x00\x00"
         . substr(gzcompress($data, $level), 0, -4) // substr -4 isn't needed
         . pack('V', crc32($data))             // crc32 and
         . pack('V', strlen($data));
    }
}