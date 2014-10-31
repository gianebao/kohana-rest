<?php defined('SYSPATH') or die('No direct script access.');

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

/**
 * Include this in application/bootsrap.php for this to be triggered once all routes
 * have been evaluated.
 *
 * 
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
 *
 * End of check
 */