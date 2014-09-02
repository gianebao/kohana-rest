<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Controller_Resource extends Controller {

    public static function format(Array $data = array())
    {
        return new Rest_Response_Format_Json($data);
    }

    public function action_error()
    {
        $status = $this->request->param('status');
        
        $this->response
            ->status($status)
            ->body(Controller_Resource::format(array(
                'more_info' => DOCUMENTATION_ERROR_URL . '/' . $status
            )));
    }
}