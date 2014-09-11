<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Model_Rest_Metric extends AWS_Watch {
    
    public function add($name, $unit = Model_Rest_Metric::UNIT_COUNT, $value = 1)
    {
        // Available units are in AWS/Service/Watch.php
        $name = str_replace(array('::', '_'), '/', strtoupper($name));
        
        if (false !== Kohana::$errors)
        {
            return Kohana::$log->add(Log::DEBUG, $name . ':' . $value . $unit);
        }
        
        
        Model_Rest_Metric::push(REST_METRIC_NAMESPACE, $name, $unit, $value);
    }
    
    public function millisec($name, $value)
    {
        $this->add($name, Model_Rest_Metric::UNIT_MILLISECONDS, $value);
    }
}