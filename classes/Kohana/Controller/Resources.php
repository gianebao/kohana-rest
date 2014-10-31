<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Controller_Resources extends Controller {
    
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    
    use Trait_Resources_Controller_Responses,
        Trait_Resources_Controller_Metrics;
    
    protected $_content_format = null;
    private $_contents = null;
    
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->_content_format = Rest_Response_Format::factory($this->request->headers('accept'));
    }
    
    public function action_test()
    {
        $this->_respond(true);
    }
    
    public function execute()
    {
        $namespace = 'action_';
        $time = microtime(true);
        $this->before();

        $action = $namespace . $this->request->action();

        if (!method_exists($this, $action))
        {
            $methods = array(
                Controller_Resources::METHOD_GET,
                Controller_Resources::METHOD_POST,
                Controller_Resources::METHOD_PUT,
                Controller_Resources::METHOD_DELETE
            );
            
            for ($i = 0, $count = count($methods); $i < $count; $i ++)
            {
                if (!method_exists($this, $namespace . strtolower($methods[$i])))
                {
                    unset($methods[$i]);
                }
            }
            
            $methods = array_merge($methods);
            
            $exception = Rest_Exception::factory(405, 'method_not_supported', array(':method' => strtoupper($this->request->method())));
            
            throw $exception->headers('allow', implode(',', $methods));
        }

        $this->{$action}();

        $this->after();
        $time = (microtime(true) - $time) * 1000;
        
        ORM::factory('Rest_Metric')->millisec(get_class($this) . '/' . $this->request->method(), ceil($time));
        
        return $this->response;
    }
    
    public function after()
    {
        
        $content_format = $this->_content_format;
        
        $this->response->headers('Content-Type', $content_format::HEAD_CONTENT_TYPE);
        
        $body = $this->_respond();
        
        if (!empty($body))
        {
            $this->measure_runtime(array($content_format, 'render'), $body);
            
            $encoding = $this->measure_runtime(array($content_format, 'encode'), $this->request->headers('accept-encoding'));
            
            if (!empty($encoding) && is_object($encoding))
            {
                $this->response
                    ->headers('Vary', 'Accept-Encoding')
                    ->headers('Content-Encoding', $encoding::TRANSFER_ENCODING);
            }
            
            $this->response->headers('Content-Length', strlen($content_format));
            
            $this->measure_runtime(array($this->response, 'body'), $content_format);
        }
        
        // Profiling
        if (TRUE === Kohana::$profiling)
        {
            $view = View::factory('profiler/stats');
            
            $this->response
                ->headers('Content-Encoding', 'text/html')
                ->headers('Content-Length', strlen($view))
                ->body($view);
        }
    }

}