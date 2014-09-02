<?php defined('SYSPATH') or die('No direct script access.');


if (!defined('DOCUMENTATION_URL'))
{
    define('DOCUMENTATION_URL', $_SERVER['DOCUMENTATION_URL']);
}

if (!defined('DOCUMENTATION_ERROR_URL'))
{
    define('DOCUMENTATION_ERROR_URL', $_SERVER['DOCUMENTATION_ERROR_URL']);
}



Route::set('RestfulDefault', '(<product>(/<version>(/<resource>)))')
 ->filter(function ($route, $params, $request)
    {
        
        if (empty($params['product']) || empty($params['version']) || empty($params['resource']))
        {
            $params['controller'] = 'Resource';
            $params['action'] = 'error';
            $params['status'] = 400;
        }
        
        return $params;
    });