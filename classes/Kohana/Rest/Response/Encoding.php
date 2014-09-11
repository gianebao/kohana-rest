<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Rest_Response_Encoding {
    const NS = 'Rest_Response_Encoding_';
    
    public static function factory($accepts)
    {
        $accepts = ucfirst(strtolower($accepts));
        
        // This allows extendability to support other Encoding format in the future.
        $class = Rest_Response_Encoding::NS . $accepts;
        
        switch ($accepts)
        {
            case 'Gzip': return new Rest_Response_Encoding_Gzip();
            
            case 'Identity': return new Rest_Response_Encoding_Identity();
        }
        
        return null;
    }
    
    abstract public function encode($data, $level = null);
}