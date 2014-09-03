<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Controller_Resources extends Controller {

    protected $_content_type = null;
    
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->_content_type = Rest_Response_Format::factory($this->request->headers('Accept'));
    }
    
    public function after()
    {
        $content_type = $this->_content_type;
        
        $header = new Rest_Response_Header();
        $header->secure($this->response->headers(array('Content-Type' => $content_type::HEAD_CONTENT_TYPE)));
    }
    
    public function action_test()
    {
        echo 'kamote';
    }
}