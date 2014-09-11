<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Rest_Config {
    
    const FILE_EXT = '.json';
    protected static $_product_id = null;
    
    public static function product_id($name = null)
    {
        if (!empty($name) && empty(Rest_Config::$_product_id))
        {
            Rest_Config::$_product_id = $name;
        }
        
        return Rest_Config::$_product_id;
    }
    
    public static function file($name, $extension = Rest_Config::FILE_EXT)
    {
        $product = Rest_Config::product_id();
        
        if (empty($product))
        {
            throw new Kohana_Exception('No Product ID was found.');
        }
        
        $file = REST_PRODUCT_CONFIG_DIR
            . DIRECTORY_SEPARATOR . $product
            . DIRECTORY_SEPARATOR . $name . $extension;
        
        if (!is_file($file))
        {
            throw new Kohana_Exception('Configuration `:config` not found.', array(':config' => $file));
        }
        
        $config = json_decode(file_get_contents($file), true);
        
        if (false === $config || null === $config)
        {
            throw new Kohana_Exception('Configuration `:config` is empty.', array(':config' => $file));
        }
        
        return $config;
    }
}