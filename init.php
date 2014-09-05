<?php defined('SYSPATH') or die('No direct script access.');


if (!defined('REST_DOCUMENTATION_URL'))
{
    define('REST_DOCUMENTATION_URL', Arr::get($_SERVER, 'REST_DOCUMENTATION_URL', null));
}

if (!defined('REST_DOCUMENTATION_ERROR_URL'))
{
    define('REST_DOCUMENTATION_ERROR_URL', Arr::get($_SERVER, 'REST_DOCUMENTATION_ERROR_URL', null));
}

if (!defined('REST_URL_FORMAT_PRODUCT'))
{
    define('REST_URL_FORMAT_PRODUCT', '[a-z][a-z0-9]{0,7}');
}

if (!defined('REST_URL_FORMAT_VERSION'))
{
    define('REST_URL_FORMAT_VERSION', '[0-9]+\.[0-9]+');
}

if (!defined('REST_URL_FORMAT_RESOURCE'))
{
    define('REST_URL_FORMAT_RESOURCE', '[a-zA-Z][a-zA-Z0-9_\./]*');
}

if (!Kohana::$errors && 'cli' !== php_sapi_name())
{
    set_exception_handler(array('Rest_Exception', 'handler'));
}

if (Kohana::PRODUCTION !== Kohana::$environment)
{
    Route::set('RestfulPing', 'test')
        ->defaults(array(
            'controller' => 'Resources',
            'action' => 'test'
        ));
}

Route::set('RestfulDefault', '<product>/<version>/<resource>', array(
        'product' => REST_URL_FORMAT_PRODUCT,
        'version' => REST_URL_FORMAT_VERSION,
        'resource' => REST_URL_FORMAT_RESOURCE
    ))
    ->filter('Rest_Route::filter');

Route::set('RestfulMissingResource', '<product>/<version>(/<dummy>)', array(
        'product' => REST_URL_FORMAT_PRODUCT,
        'version' => REST_URL_FORMAT_VERSION,
        'dummy' => '.*'
    ))
    ->filter(function ($route, $params, $request)
    {
        throw Rest_Exception::factory(400, 'route_resource_format_invalid');
    });

Route::set('RestfulMissingVersion', '<product>(/<dummy>)', array(
        'product' => REST_URL_FORMAT_PRODUCT,
        'dummy' => '.*'
    ))
    ->filter(function ($route, $params, $request)
    {
        throw Rest_Exception::factory(400, 'route_version_format_invalid');
    });
    
Route::set('RestfulMissingProduct', '<dummy>', array('dummy' => '.*'))
    ->filter(function ($route, $params, $request)
    {
        throw Rest_Exception::factory(400, 'route_product_format_invalid');
    });