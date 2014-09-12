<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Rest_Route {
    
    public static function filter($route, $params, $request)
    {
        Rest_Config::product_id($params['product']);
        
        $version = strtoupper($params['version']);

        $resource = str_replace(' ', '_', ucwords(trim(str_replace('/', ' ', strtolower($params['resource'])))));
        $params['controller'] = 'Resources_' . $version . '_' . $resource;
        $params['action'] = strtolower($request->method());
        
        $methods = get_class_methods('Controller_' . $params['controller']);
        
        if (empty($methods))
        {
            throw Rest_Exception::factory(404, 'resource_not_found');
        }
        
        return $params;
    }
}
