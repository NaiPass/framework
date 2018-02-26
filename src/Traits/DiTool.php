<?php
/**
 * 依赖容器助手
 */
namespace NaiPass\Traits;

trait DiTool
{

    /**
     * 返回一个依赖容器
     * For Ide
     * @return \NaiPass\Di\DiInterface
     */
    public function getDi(){}

    public function __get($name)
    {

        if ($this->getDi()->has($name)) {
            return $this->getDi()
                ->get($name);
        }else{
            throw new \UnexpectedValueException('The '.get_called_class().' class is not has {$'.$name.'} attribute!');
        }

    }

}