<?php defined('SYSPATH') or die('No direct script access.');

if (!class_exists('RestHelper'))
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'Helpers' . DIRECTORY_SEPARATOR . 'RestHelper.php';
}

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
     * Test if the Metrics and Logging.
     *
     * @group rest.config.value
     */
    function testMetricsAndLoggingAreDefined()
    {
        $this->assertNotEmpty(REST_LOG_DIR,
            '`REST_LOG_DIR` documentation site URL for the API\'s errors');
        
        $this->assertArrayHasKey('REST_PRODUCT_CONFIG_DIR', $_SERVER,
            '`REST_PRODUCT_CONFIG_DIR` configs for products');
        
        $this->assertNotEmpty(REST_PRODUCT_CONFIG_DIR,
            '`REST_PRODUCT_CONFIG_DIR` configs for products.');
        
        if (Kohana::DEVELOPMENT !== Kohana::$environment)
        {
            $this->assertArrayHasKey('REST_LOG_DIR', $_SERVER,
                '`REST_LOG_DIR` environment variable detailed logs.');

            $this->assertNotEquals(LOG_TMP_DIR, REST_LOG_DIR,
                '`REST_LOG_DIR` must not be equal to `LOG_TMP_DIR`.');

            $this->assertArrayHasKey('REST_METRIC_NAMESPACE', $_SERVER,
                '`REST_METRIC_NAMESPACE` namespace for metric logging (cloudwatch)');

            $this->assertNotEmpty(REST_METRIC_NAMESPACE,
                '`REST_METRIC_NAMESPACE` namespace for metric logging (cloudwatch)');
        }
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