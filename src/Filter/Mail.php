<?php
/**
 * mail过滤器
 */
namespace NaiPass\Filter;

class Mail implements FilterInterface
{


    public function filter($var)
    {
        return filter_var($var, FILTER_SANITIZE_EMAIL);
    }


}