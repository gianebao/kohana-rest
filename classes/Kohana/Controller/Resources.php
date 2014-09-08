<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Controller_Resources extends Controller {

    protected $_content_type = null;
    private $_contents = null;
    
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->_content_type = Rest_Response_Format::factory($this->request->headers('Accept'));
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
    
    public function after()
    {
        $content_type = $this->_content_type;
        
        $this->response->headers(array('Content-Type' => $content_type::HEAD_CONTENT_TYPE));
        
        $body = $this->_respond();
        
        if (!empty($body))
        {
            $this->response->body($this->_content_type->render($body));
        }
    }
}