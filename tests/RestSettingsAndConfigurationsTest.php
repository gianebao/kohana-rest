<?php defined('SYSPATH') or die('No direct script access.');

if (!class_exists('RestHelper'))
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'Helpers' . DIRECTORY_SEPARATOR . 'RestHelper.php';
}

if (!class_exists('RestSettingsAndConfigurationsTest'))
{

/**
 * Rest Configurations Check.
 *
 * @group rest
 * @group rest.config
 */
class RestSettingsAndConfigurationsTest extends Unittest_TestCase
{
    public function documentationsURLDataProvider()
    {
        return array(
            array(REST_DOCUMENTATION_URL, RestHelper::sanitizeUrl(REST_DOCUMENTATION_URL)),
            array(REST_DOCUMENTATION_ERROR_URL, RestHelper::sanitizeUrl(strtr(REST_DOCUMENTATION_ERROR_URL, array(':status' => 400))))
        );
    }
    
    /**
     * Test if the Application has reference to it's Documentation Site.
     *
     * @group rest.config.value
     */
    function testReferenceDocumentionsAreDefined()
    {
        $this->assertArrayHasKey('REST_DOCUMENTATION_URL', $_SERVER,
            '`REST_DOCUMENTATION_URL` environment variable documentation site URL for the API');
        $this->assertArrayHasKey('REST_DOCUMENTATION_ERROR_URL', $_SERVER,
            '`REST_DOCUMENTATION_ERROR_URL` environment variable documentation site URL for the API\'s errors');
        $this->assertNotEmpty(REST_DOCUMENTATION_URL,
            '`REST_DOCUMENTATION_URL` documentation site URL for the API');
        $this->assertNotEmpty(REST_DOCUMENTATION_ERROR_URL,
            '`REST_DOCUMENTATION_ERROR_URL` documentation site URL for the API\'s errors');
    }
    
    /**
     * Test if Application has reference can connect to the Documentation Site.
     *
     * @dataProvider documentationsURLDataProvider
     * @group rest.config.connection
     */
    function testReferenceDocumentationsMustBeAccessible($url, $sanitized)
    {
        $acceptable_http_status = array(200, 301, 302, 304);
        $acceptable_http_status_text = implode(', ', $acceptable_http_status);
        
        $request = RestHelper::get($sanitized);
        
        $this->assertContains($request['head']['http_code'], $acceptable_http_status,
            'HTTP Status when connecting to `' . $url . '`. Allows ' . $acceptable_http_status_text);
    }
}
    
}