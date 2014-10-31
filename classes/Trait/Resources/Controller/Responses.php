<?php defined('SYSPATH') or die('No direct script access.');

trait Trait_Resources_Controller_Responses
{
    
    protected function _respond($data = null, $callback = null)
    {
        if (null !== $data)
        {
            $this->_render_as_pages($data, $callback);
            $this->_contents = is_bool($data) ? array('status' => $data): $data;
        }
        
        return $this->_contents;
    }
    
    protected function _get_query_option(& $data, $option, $value)
    {
        if (false === ctype_digit((string)$value))
        {
            throw Rest_Exception::factory(400, 'resource_query_' . $option . '_notnumber');
        }
        
        $data->{$option}($value);
        
        return $value;
    }
    
    public static function to_href($rel, $route_name, $parameters = array(), $query = array(), $method = Controller_Resources::METHOD_GET)
    {
        $product = Rest_Config::product_id();
        $parameters = array_merge(array('product' => $product), $parameters);
        
        return array(
            'rel'    => strtolower($rel),
            'href'   => rtrim(URL::base(true), '/') . URL::href($route_name, $parameters) . (!empty($query) ? '?' . http_build_query($query): ''),
            'method' => $method
        );
    }
    
    protected function _get_page_links($count, $offset, $limit)
    {
        $links = array();
        
        $route = Route::name($this->request->route());
        $query = $this->request->query();
        
        $internal = array(
            'kohana_uri' => null,
            'oauth_consumer_key' => null,
            'oauth_nonce' => null,
            'oauth_signature_method' => null,
            'oauth_timestamp' => null,
            'oauth_token' => null,
            'oauth_version' => null,
            'oauth_signature' => null
        );
        
        $query = array_diff_key($query, $internal);
        
        $query['limit'] = $limit;
        
        if ($count > ($query['offset'] = $offset + $limit))
        {
            $links[] = Controller_Resources::to_href('next', $route, $this->request->param(), $query);
        }
        
        if (0 <= ($query['offset'] = $offset - $limit))
        {
            $links[] = Controller_Resources::to_href('previous', $route, $this->request->param(), $query);
        }
        
        return $links;
    }
    
    protected function _render_as_pages(& $data, $callback)
    {
        if (empty($callback))
        {
            return false;
        }
        
        $url = parse_url($_SERVER['REQUEST_URI']);
        parse_str($url['query'], $query);
        
        $count = clone $data;
        $count = $this->measure_runtime(array($count, 'count_all'));
        
        $offset = Arr::get($query, 'offset', 0);
        $limit = Arr::get($query, 'limit', RESOURCE_QUERY_LIMIT_DEFAULT);
        
        if ($limit > RESOURCE_QUERY_LIMIT_MAX)
        {
            throw Rest_Exception::factory(400, 'resource_query_limit_exceeded', array(':max' => RESOURCE_QUERY_LIMIT_MAX));
        }
        
        $limit = $this->_get_query_option($data, 'limit', $limit);
        $offset = $this->_get_query_option($data, 'offset', $offset);
        
        $records = $this->measure_runtime('Rest_Response_Format::get_object_fields', $data, $callback);
        
        $class_name = get_class($this);
        
        $data = array(
            strtolower(substr($class_name, strrpos($class_name, '_') + 1)) => $records,
            'count' => array(
                'total' => $count
            ),
            'link' => $this->_get_page_links($count, $offset, $limit)
        );
    }
}