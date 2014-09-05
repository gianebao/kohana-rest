<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Rest_Route {
    
    public static function filter($route, $params, $request)
    {
        $alias = array('Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', '.' => 'Point');
        
        $version = strtr($params['version'], $alias);

        $resource = str_replace(' ', '_', ucwords(trim(str_replace('/', ' ', strtolower($params['resource'])))));
        $params['controller'] = 'Resources_' . $version . '_' . $resource;
        $params['action'] = strtolower($request->method());
        
        var_dump($params);
        return $params;
    }
}
