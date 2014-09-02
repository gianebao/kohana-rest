<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Rest_Response_Format {
    
    protected $_data = array();
    
    public function __construct(Array $data = array())
    {
        $this->_data = $data;
    }
    
    public function __toString()
    {
        return $this->render();
    }
    
    abstract public function render();
}