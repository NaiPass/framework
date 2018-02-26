<?php
/**
 * 过滤器 接口
 */
namespace NaiPass\Filter;

interface FilterInterface
{

    /**
     * 数据过滤方法
     * @param $var mixed
     * @return mixed
     * */
    public function filter($var);

}