<?php
/**
 * NaiPass FrameWork Dependency Injection
 * ToSimple框架依赖注入容器
 * @author NaiZui
 * @mail chenxi2511@qq.com
 * @Implements \NaiPass\Interfaces\DiInterface, \ArrayAccess
 * */
namespace NaiPass;

use NaiPass\Di\DiInterface;
use NaiPass\Di\DiManageInterface;

class Di implements DiInterface, \ArrayAccess
{

    protected $_service = [];
    protected $_sharedService = [];
    protected $_instantiate = [];

    public function get($name)
    {
        if (isset($this->_service[$name])) {
            return $this->makeServer($this->_service[$name]);
        }

        return $this->getShared($name);
    }


    public function getShared($name)
    {
        if (!isset($this->_sharedService[$name])) {
            throw new \Exception('Service '.$name.' was not found in the dependency injection container!');
        }

        if (!isset($this->_instantiate[$name])) {
            $server = $this->makeServer($this->_sharedService[$name]);
            $this->_sharedService[$name] = $server;
        }

        return $this->_sharedService[$name];
    }


    public function remove($name)
    {
        unset($this->_service[$name]);
    }


    public function set($name, $server)
    {
        if ($this->has($name)) {
            throw new \Exception('Do not inject the same service!');
        }

        if (is_object($server)) {
            $this->setShared($name, $server);
        }else{
            $this->_service[$name] = $server;
        }

        return $this;
    }


    public function setShared($name, $server)
    {
        if ($this->has($name)) {
            throw new \Exception('Do not inject the same service!');
        }

        $this->_sharedService[$name] = $server;
        return $this;
    }


    public function removeShared($name)
    {
        unset($this->_sharedService[$name]);
    }


    protected function makeServer($var)
    {
        $server = null;

        if ($var instanceof \Closure) {
            $server = $var();
        }else if (is_string($var)) {

            if (!class_exists($var)) {
                throw new \Exception('The service class does not exist, when the container instantiate service!');
            }

            $server = new $var;
        }else if (is_object($var)) {
            $server = $var;
        }

        if (!is_object($server)) {
            throw new \Exception('Service class exception, when the container instantiate service!');
        }

        if ($server instanceof DiManageInterface) {
            $server->setDi($this);
        }

        return $server;
    }


    public function has($name)
    {
        if (isset($this->_service[$name])) {
            return true;
        }else{
            return isset($this->_sharedService[$name]);
        }
    }


    public function sharedExists($name)
    {
        return isset($this->_sharedService[$name]);
    }


    public function exists($name)
    {
        return isset($this->_service[$name]);
    }


    public function offsetExists($offset)
    {
        return $this->has($offset);
    }


    public function offsetGet($offset)
    {
        return $this->get($offset);
    }


    public function offsetSet($offset, $value)
    {
        return $this->set($offset, $value);
    }


    public function offsetUnset($offset)
    {
        unset($this->_sharedService[$offset]);
        unset($this->_service[$offset]);
    }

}