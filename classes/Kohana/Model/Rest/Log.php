<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Model_Rest_Log {
    
    const DIRECTORY_PERMISSION = 0755;
    
    public static $server_trace = array(
        'SERVER_PROTOCOL',
        'REQUEST_METHOD',
        'REQUEST_TIME_FLOAT',
        'QUERY_STRING',
        'HTTP_ACCEPT',
        'HTTP_ACCEPT_CHARSET',
        'HTTP_ACCEPT_ENCODING',
        'HTTP_ACCEPT_LANGUAGE',
        'HTTP_CONNECTION',
        'HTTP_HOST',
        'HTTP_REFERER',
        'HTTP_USER_AGENT',
        'HTTPS',
        'REMOTE_ADDR',
        'REMOTE_HOST',
        'REMOTE_PORT',
        'REDIRECT_REMOTE_USER',
        'REQUEST_URI',
        'PHP_AUTH_DIGEST',
        'PATH_INFO',
        'ORIG_PATH_INFO'
    );
    
    public function add($name, array $data)
    {
        // Available units are in AWS/Service/Watch.php
        $name = strtoupper($name);
        
        if (false !== Kohana::$errors)
        {
            $message = json_encode($data);
            return Kohana::$log->add(Log::DEBUG, "[$name]\t$message");
        }
        
        $this->_write($name, $data);
        
        return $this;
    }
    
    protected function _get_server_trace()
    {
        $traces = array();
        
        if ('cli' === php_sapi_name())
        {
            return 'CLI';
        }
        
        foreach (Model_Rest_Log::$server_trace as $trace)
        {
            if (!empty($_SERVER[$trace]))
            {
                $traces[$trace] = $_SERVER[$trace];
            }
        }
        
        $traces['CLIENT_IP'] = Request::$client_ip;
        
        return $traces;
    }
    
    public static function get_filename($name)
    {
        return rtrim(REST_LOG_DIR, '/') . '/' . date('Y/m/d/H/i/s-')
            . Request::$client_ip . '-' . str_replace('/', '-', $name) . '-' . microtime(true) . '.json';
    }
    
    protected function _write($name, array $data)
    {
        $data['server'] = $this->_get_server_trace();
        
        $filname = Model_Rest_Log::get_filename($name);
        
        $dir = dirname($filename);
        
        if (!is_dir($dir) && !mkdir($dir, Model_Rest_Log::DIRECTORY_PERMISSION, true))
        {
            throw new Kohana_Exception('Failed to create: :dir.', array(':dir' => $dir));
        }
        
        file_put_contents($filename, json_encode($data));
    }
}