<?php
/**
 * IPv4过滤器
 */
namespace NaiPass\Filter;

class Ipv4 implements FilterInterface
{


    public function filter($var)
    {
        return filter_var($var, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }


}