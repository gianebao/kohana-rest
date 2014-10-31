<?php defined('SYSPATH') or die('No direct script access.');

trait Trait_Resources_Controller_Metrics
{
    /**
     * @param string $function
     * @param mixed  $parameters ....
     */
    public function measure_runtime()
    {
        $arr = func_get_args();
        $fn = array_shift($arr);
        
        if (is_array($fn))
        {
            $cl_name = get_class($fn[0]);
            $fn_name = $fn[1];
        }
        elseif (0 === strpos(strtolower($fn), 'self::'))
        {
            $fn_name = explode('::', $fn);
            $cl_name = get_class();
            $fn_name = $fn_name[1];
        }
        elseif (0 === strpos(strtolower($fn), 'parent::'))
        {
            $fn_name = explode('::', $fn);
            $cl_name = get_parent_class(get_class());
            $fn_name = $fn_name[1];
        }
        else
        {
            $fn_name = explode('::', $fn);
            $cl_name = $fn_name[0];
            $fn_name = $fn_name[1];
        }

        $time = microtime(true);
        
        if (Kohana::$profiling === TRUE)
        {
            $benchmark = Profiler::start($cl_name, $fn_name);
        }
        
        $response = call_user_func_array($fn, $arr);
        $time = (microtime(true) - $time) * 1000;
        if (isset($benchmark))
        {
            // Stop the benchmark
            Profiler::stop($benchmark);
        }

        ORM::factory('Rest_Metric')->millisec($cl_name . '/' . $fn_name, ceil($time));
        
        return $response;
    }
}