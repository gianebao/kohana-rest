<?php defined('SYSPATH') or die('No direct script access.');

if (!class_exists('RestHelper'))
{
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'Helpers' . DIRECTORY_SEPARATOR . 'RestHelper.php';
}

/**
 * Rest parsing the URL and headers.
 *
 * @group rest
 * @group rest.url
 * @group rest.url.default
 */
class RestDefaultUrlTest extends Unittest_TestCase
{
    protected static function getExceptionMessage($status, $message)
    {
        try
        {
            throw Rest_Exception::factory($status, $message);
        }
        catch (HTTP_Exception $e)
        {
            return $e->getMessage();
        }
    }
    
    /**
     * Sends 400 when requesting with an Empty Product
     *
     * @group rest.url.default.product
     */
    function testSends400WhenRequestingResourcesWithAnEmptyProduct()
    {
        $this->setExpectedException('HTTP_Exception', self::getExceptionMessage(400, 'route_product_format_invalid'), 400);
        
        $url = '';
        Request::factory($url)->execute();
    }

    /**
     * Sends 400 when requesting with an Invalid Product
     *
     * @group rest.url.default.product
     */
    function testSends400WhenRequestingResourcesWithAnInvalidProductFormat()
    {
        $this->setExpectedException('HTTP_Exception', self::getExceptionMessage(400, 'route_product_format_invalid'), 400);
        
        $url = '!#Invalid';
        Request::factory($url)->execute();
    }
    
    /**
     * Sends 400 when requesting with an Empty Version
     *
     * @group rest.url.default.version
     */
    function testSends400WhenRequestingResourcesWithAnEmptyVersion()
    {
        $this->setExpectedException('HTTP_Exception', self::getExceptionMessage(400, 'route_version_format_invalid'), 400);
        
        $url = 'sg';
        Request::factory($url)->execute();
    }

    /**
     * Sends 400 when requesting with an Invalid Version
     *
     * @group rest.url.default.version
     */
    function testSends400WhenRequestingResourcesWithAnInvalidVersionFormat()
    {
        $this->setExpectedException('HTTP_Exception', self::getExceptionMessage(400, 'route_version_format_invalid'), 400);
        
        $url = 'sg/a.A';
        Request::factory($url)->execute();
    }
    
    /**
     * Sends 400 when requesting with an Empty Resource
     *
     * @group rest.url.default.resource
     */
    function testSends400WhenRequestingResourcesWithAnEmptyResource()
    {
        $this->setExpectedException('HTTP_Exception', self::getExceptionMessage(400, 'route_resource_format_invalid'), 400);
        
        $url = 'sg/1.0';
        Request::factory($url)->execute();
    }

    /**
     * Sends 400 when requesting with an Invalid Resource
     *
     * @group rest.url.default.resource
     */
    function testSends400WhenRequestingResourcesWithAnInvalidResourceFormat()
    {
        $this->setExpectedException('HTTP_Exception', self::getExceptionMessage(400, 'route_resource_format_invalid'), 400);
        
        $url = 'sg/1.0/@!$';
        Request::factory($url)->execute();
    }
    
    /**
     * Sends 406 when requesting with an Unrecognized Content-Type
     *
     * @expectedException     HTTP_Exception
     * @expectedExceptionCode 406
     * @group rest.url.default
     */
    function testSends406WhenRequestingResourceWithAnInvalidContentType()
    {
        Rest_Response_Format::factory('text');
    }
}