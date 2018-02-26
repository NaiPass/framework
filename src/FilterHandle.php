<?php
/**
 * NaiPass框架数据过滤处理
 * @author NaiZui
 * @mail chenxi2511@qq.com
 * @Implements \NaiPass\Interfaces\FilterHandleInterface
 */
namespace NaiPass;

use NaiPass\Filter\FilterHandleInterface;
use NaiPass\Filter\FilterInterface;

class FilterHandle implements FilterHandleInterface
{

    protected $_filters = [];

    public function add($filterName, $handler)
    {
        if (!($handler instanceof \Closure || $handler instanceof FilterInterface)) {
            throw new \Exception('Illegal filter!');
        }

        $filterName = strtolower($filterName);
        $this->_filters[$filterName] = $handler;
        return $this;
    }


    public function getFilters()
    {
        return array_keys($this->_filters);
    }


    public function sanitize($var, $filters)
    {
        $filtersArray = [];
        $filters = strtolower($filters);

        if (strpos($filters, ',')) {
            $filtersArray = explode(',', $filters);
        }else{
            $filtersArray[] = $filters;
        }

        $filters = $this->getFilters();

        if (array_diff($filtersArray, $filters) != []) {
            throw new \Exception('Undefined filters were used!');
        }

        foreach ($filtersArray as $filter) {
            if ($this->_filters[$filter] instanceof \Closure) {
                $var = $this->_filters[$filter]($var);
            }else{
                $var = $this->_filters[$filter]->filter($var);
            }
        }

        return $var;
    }

}