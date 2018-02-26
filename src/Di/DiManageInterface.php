<?php
/**
 * Di容器管理 接口
 */
namespace NaiPass\Di;

interface DiManageInterface
{

    /**
     * 注入依赖容器
     * @param $di DiInterface Object
     * */
    public function setDi(DiInterface $di);

    /**
     * 获取依赖容器
     * @return $di DiInterface Object
     * */
    public function getDi();

}