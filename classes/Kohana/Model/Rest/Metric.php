<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Model_Rest_Metric {
    
    public static function add($message)
    {
        if (Kohana::DEVELOPMENT === Kohana::$environment)
        AWS_Watch::push(CLOUDWATCH_NAMESPACE, $metric_name, AWS_Watch::UNIT_COUNT);
    }
}