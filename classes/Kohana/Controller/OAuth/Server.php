<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Controller_OAuth_Server extends Kohana_Controller_Server
{
    use Trait_Resources_Controller_Metrics;
    
    protected function _log($type, $data)
    {
        ORM::factory('Rest_Log')->add($type, $data);
    }
    
    public function action_request_token()
    {
        $this->measure_runtime('parent::action_request_token');
    }
    
    public function action_access_token()
    {
        $this->measure_runtime('parent::action_access_token');
    }
}