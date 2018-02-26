<?php
/**
 * 数据过滤处理 接口
 */
namespace NaiPass\Filter;

interface FilterHandleInterface
{

    /**
     * 添加一个过滤器
     * @param $filterName string
     * @param $handler mixed
     * */
    public function add($filterName, $handler);

    /**
     * 对一个数据进行过滤消毒
     * @param $var mixed
     * @param $filters string
     * */
    public function sanitize($var, $filters);

    /**
     * 获取所有过滤器
     * */
    public function getFilters();

}