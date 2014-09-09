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

if (!defined('REST_LOG_DIR'))
{
    define('REST_LOG_DIR', Arr::get($_SERVER, 'REST_LOG_DIR', LOG_TMP_DIR));
}

if (!Kohana::$errors && 'cli' !== php_sapi_name())
{
    set_exception_handler(array('Rest_Exception', 'handler'));
}

/**
 * Cache Routes.
 */
if ( ! Route::cache())
{

    include __DIR__ . DIRECTORY_SEPARATOR . 'routes.php';

   // Cache the routes in Production
    Route::cache(Kohana::PRODUCTION === Kohana::$environment || Kohana::STAGING === Kohana::$environment);
}