<?php defined('SYSPATH') or die('No direct script access.');

class RestHelper {
    
    /**
     * Performs a GET to a specific url.
     *
     */
    public static function get($url, $header = array())
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        if (!empty($header))
        {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        
        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        
        return array('head' => $info, 'body' => $response);
    }
    
    /**
     * Performs a POST to a specific url.
     *
     */
    public static function post($url, $data, $header = array())
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        if (!empty($header))
        {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        
        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        
        return array('head' => $info, 'body' => $response);
    }
    
    /**
     * Performs a PUT to a specific url.
     *
     */
    public static function put($url, $data, $header = array())
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        if (!empty($header))
        {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        
        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        
        return array('head' => $info, 'body' => $response);
    }
    
    /**
     * Performs a DELETE to a specific url.
     *
     */
    public static function delete($url, $data, $header = array())
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        if (!empty($header))
        {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        
        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        
        return array('head' => $info, 'body' => $response);
    }
    
    /**
     * Strips the URL to the basic location.
     *
     */
    public static function sanitizeUrl($url)
    {
        ;
        $url = substr($url, 0, false === ($pos = strpos($url, '?'))? strlen($url): $pos);
        $url = substr($url, 0,false === ($pos = strpos($url, '#'))? strlen($url): $pos);
        
        return $url;
    }
}