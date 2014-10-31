<?php defined('SYSPATH') or die('No direct script access.');

trait Trait_Resources_Model
{
    /**
     * @var array   Valid sort methods
     */
    protected $_sort_method = array('-' => 'DESC', '+' => 'ASC');

    /**
     * Return the link using routename.
     *
     * @param   string     $rel         Relationship of the route name to the resource
     * @param   string     $route_name  Route Name
     * @param   array      $parameters  Parameters required for the route
     * @return  array      Standard ReST HATEOAS
     */
    public function to_rest_href($rel, $route_name, $parameters = array(), $query = array(), $method = Controller_Resources::METHOD_GET)
    {
        return Controller_Resources::to_href($rel, $route_name, $parameters, $query, $method);
    }
    
    /**
     * Converts a valid time format or timestamp to ISO 8601 Date
     *
     * @see http://en.wikipedia.org/wiki/ISO_8601
     * @param   mixed     $time    valid time format or timestamp
     * @param   boolean   $format  date format
     * @return  string    ISO 8601 date
     */
    public function to_rest_time($time, $format = 'c')
    {
        return date($format, is_int($time) ? $time: strtotime($time));
    }
    
    /**
     * Converts a field value to rest_time format
     *
     * @param   string    $field   property of an orm
     * @param   boolean   $format  date format
     * @return  string    rest_time format
     */
    public function field_to_rest_time($field, $format = 'c')
    {
        return $this->to_rest_time($this->{$field}, $format);
    }
    
    /**
     * Converts the sort parameter
     *
     * @param   string    $sort             sort parameter from request
     * @param   array     $sortable_fields  list of sortable parameters
     * @return  array     parsed
     */
    public function parse_sort($sort, $sortable_fields)
    {
        $result = array();
        $sort_fields = explode(',', trim($sort));
        foreach($sort_fields as $sort)
        {
            $method = substr($sort, 0, 1);
            $field = substr($sort, 1);
            
            if (!in_array($method, array_keys($this->_sort_method)))
            {
                throw Rest_Exception::factory(400, 'resource_card_fund_transfer_sort_method_invalid');
            }
         
            if (!in_array($field, $sortable_fields))
            {
                throw Rest_Exception::factory(
                    400,
                    'resource_card_fund_transfer_sort_fields_invalid',
                    array(':fields' => implode(',', $sortable_fields))
                );
            }
            
            array_push($result, array('field' => $field, 'method' => $this->_sort_method[$method]));
        }
        
        return $result;
    }
}