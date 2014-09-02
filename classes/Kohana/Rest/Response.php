<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Rest_Response extends Kohana_Response {
    protected $_format = null;
    
    public function __construct(array $config = array())
    {
        parent::__construct($config);
        
        $format = $this->_format = self::_get_format(HTTP::request_headers()['Accept']);
        
        $this->headers(array(
            'Content-Type' => $format::HEAD_CONTENT_TYPE,
            'Server'       => $_SERVER['SERVER_NAME']
        ));
        
        
    }
    
    private static function _get_format($accepts)
    {
        if (empty($accepts) || '*/*' == $accepts || 'application/*' == $accepts)
        {
            $accepts = Rest_Response_Format_Json::HEAD_CONTENT_TYPE;
        }
        
        return Rest_Response_Format::factory($accepts);
    }
    
    public function error($status)
    {
        $this->status($status)
            ->body($this->_content_format->render(array(
                'status'    => $status,
                'message'   => Response::$messages[$status],
                'more_info' => DOCUMENTATION_ERROR_URL . '/' . $status
            )));
    }
}