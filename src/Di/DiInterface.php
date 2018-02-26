<?php
/**
 * 依赖注入容器 接口
 */
namespace NaiPass\Di;

interface DiInterface
{

    /**
     * 向容器中注入服务组件
     * Registers a service in the services container
     * @param $name string
     * @param $server callable|object|string
     * */
    public function set($name, $server);

    /**
     * 向容器中注入一个始终共享的服务 (既单例模式，防止重复的实例化对象造成的资源浪费)
     * Registers an “always shared” service in the services container
     * @param $name string
     * @param $server callable|object|string
     * */
    public function setShared($name, $server);

    /**
     * 删除容器中中的服务
     * remove a service in the container
     * @param $name string
     * */
    public function remove($name);

    /**
     * 获取容器中的服务实例
     * Get the service instance in the container
     * @param $name string
     * */
    public function get($name);

    /**
     * 获取容器中的共享服务实例
     * Getting an instance of a shared service in the container
     * @param $name string
     * */
    public function getShared($name);

    /**
     * 删除容器中的共享服务
     * remove the shared service in the container
     * @param $name string
     * */
    public function removeShared($name);

    /**
     * 判断容器中是否存在指定的服务(共享服务)
     * @param $name string
     * @return boolean
     * */
    public function has($name);

    /**
     * 判断是否存在指定的共享服务
     * @param $name string
     * @return boolean
     * */
    public function sharedExists($name);

    /**
     * 判断是否存在指定的服务(不含共享服务)
     * @param $name string
     * @return boolean
     * */
    public function exists($name);

}