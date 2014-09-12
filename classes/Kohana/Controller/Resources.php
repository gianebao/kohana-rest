<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Controller_Resources extends Controller {
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
    
    protected function _respond($data = null)
    {
        if (null !== $data)
        {
            $this->_contents = is_bool($data) ? array('status' => $data): $data;
        }
        
        return $this->_contents;
    }
    
    public function execute()
    {
        $time = microtime(true);
        $this->before();

        $action = 'action_'.$this->request->action();

        if (!method_exists($this, $action))
        {
            throw Rest_Exception::factory(405, 'method_not_supported', array(':method' => strtoupper($this->request->method())));
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
        
        $this->response->headers(array('Content-Type' => $content_format::HEAD_CONTENT_TYPE));
        
        $body = $this->_respond();
        
        if (!empty($body))
        {
            
            $this->measure_runtime(array($content_format, 'render'), $body);
            
            $encoding = $this->measure_runtime(array($content_format, 'encode'), $this->request->headers('accept-encoding'));
            
            if (is_object(empty($encoding)))
            {
                $this->response->headers(array('Transfer-Encoding' => $encoding::TRANSFER_ENCODING));
            }
            
            $this->measure_runtime(array($this->response, 'body'), $content_format);
    
        }
    }
    
    /**
     * @param string $function
     * @param mixed  $parameters ....
     */
    public function measure_runtime()
    {
        $arr = func_get_args();
        $fn = array_shift($arr);
        
        if (is_array($fn))
        {
            $cl_name = get_class($fn[0]);
            $fn_name = $fn[1];
        }
        elseif (0 === strpos($fn, 'self::'))
        {
            $fn_name = explode('::', $fn);
            $cl_name = get_class();
            $fn_name = $fn_name[1];
        }
        else
        {
            $fn_name = explode('::', $fn);
            $cl_name = $fn_name[0];
            $fn_name = $fn_name[1];
        }

        $time = microtime(true);
        $response = call_user_func_array($fn, $arr);
        $time = (microtime(true) - $time) * 1000;
        
        ORM::factory('Rest_Metric')->millisec($cl_name . '/' . $fn_name, ceil($time));
        
        return $response;
    }
}