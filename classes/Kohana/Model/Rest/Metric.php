<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Model_Rest_Metric {
    
    public static function add($name, $unit = AWS_Watch::UNIT_COUNT)
    {
        // Available units are in AWS/Service/Watch.php
        $name = strtoupper($name);
        
        if (Kohana::DEVELOPMENT === Kohana::$environment)
        {
            return Kohana::$log->add(Log::DEBUG, $name);
        }
        
        AWS_Watch::push(CLOUDWATCH_NAMESPACE, $name, $unit);
    }
}