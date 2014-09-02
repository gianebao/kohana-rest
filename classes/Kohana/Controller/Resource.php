<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Controller_Resource extends Controller {

    protected $_content_format = null;
    
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        
        //$this->_content_format = Controller_Resource::_get_response_format($this->request->headers('Accept'));
    }
    
    public function after()
    {
        $content_type = $this->_content_format;
    }
    
    public function action_error()
    {
        $this->response->error($this->request->param('status'));
    }
}