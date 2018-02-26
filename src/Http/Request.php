<?php
/**
 * NaiPass FrameWork Http Request Class
 * Http请求类
 * @author NaiZui
 * @mail chenxi2511@qq.com
 */
namespace NaiPass\Http;

use NaiPass\Di\DiManageInterface;
use NaiPass\Di\DiInterface;
use NaiPass\Traits\DiTool;

class Request implements RequestInterface, DiManageInterface
{

    use DiTool;

    protected $_PUT = '';
    protected $_DELETE = '';
    protected $_headers = '';
    protected $_method = null;
    // http 1.1 all method
    protected $_all_method = [
        'GET', 'POST', 'PUT', 'DELETE', 'HEAD', 'OPTIONS', 'TRACE', 'CONNECT'
    ];
    protected $_di = null;

    public function get($name = null, $filters = null, $defaultValue = null)
    {

        if ($this->isPut()) {
            return $this->variableFrom(array_merge($_REQUEST, $this->getPut()),
                $name, $filters, $defaultValue);
        }

        if ($this->isDelete()) {
            return $this->variableFrom(array_merge($_REQUEST, $this->getDelete()),
                $name, $filters, $defaultValue);
        }

        return $this->variableFrom($_REQUEST, $name, $filters, $defaultValue);
    }


    public function getPost($name = null, $filters = null, $defaultValue = null)
    {
        return $this->variableFrom($_POST, $name, $filters, $defaultValue);
    }


    public function getQuery($name = null, $filters = null, $defaultValue = null)
    {
        return $this->variableFrom($_GET, $name, $filters, $defaultValue);
    }


    public function getServer($name = null, $filters = null, $defaultValue = null)
    {
        return $this->variableFrom($_SERVER, $name, $filters, $defaultValue);
    }


    public function getPut($name = null, $filters = null, $defaultValue = null)
    {
        if ($this->_PUT === '') {
            $this->isPut() ?
                parse_str($this->getRawBody(), $this->_PUT)
                : $this->_PUT = array();
        }

        return $this->variableFrom($this->_PUT, $name, $filters, $defaultValue);
    }


    public function getDelete($name = null, $filters = null, $defaultValue = null)
    {
        if ($this->_DELETE === '') {
            $this->isDelete() ? parse_str($this->getRawBody(), $this->_DELETE)
                : $this->_DELETE = array();
        }

        return $this->variableFrom($this->_PUT, $name, $filters, $defaultValue);
    }


    public function getHeader($name = null, $filters = null, $defaultValue = null)
    {
        return $this->variableFrom($this->getAllHeaders(), $name, $filters, $defaultValue);
    }


    public function getRawBody()
    {
        return file_get_contents('php://input', false , null, -1 , $_SERVER['CONTENT_LENGTH']);
    }

    /**
     * 从变量中取出相应的值
     * @param $from mixed 用来取值的变量
     * @param $name mixed 取值键
     * @param $filters mixed 过滤器
     * @param $defaultValue mixed 默认值
     * @return mixed
     * */
    protected function variableFrom($from, $name, $filters, $defaultValue)
    {

        if (empty($name)) {
            $_data = $from;
        }else{
            $_data = isset($from[$name]) ? $from[$name] : null;
        }

        if ($_data === null) {
            return $defaultValue;
        }else{

            if (!empty($filters)) {
                return $this->filter
                    ->sanitize($_data, $filters);
            }else{
                return $_data;
            }

        }
    }


    public function getMethod()
    {
        if ($this->_method === null) {
            $this->_method = strtoupper($_SERVER['REQUEST_METHOD']);
        }

        return $this->_method;
    }


    public function isMethod($name)
    {
        $name = strtoupper($name);
        return $this->getMethod() == $name;
    }


    public function isPost()
    {
        return $this->isMethod('POST');
    }


    public function isGet()
    {
        return $this->isMethod('GET');
    }


    public function isPut()
    {
        return $this->isMethod('PUT');
    }


    public function isDelete()
    {
        return $this->isMethod('DELETE');
    }


    public function getScheme()
    {
        if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off') {
            return 'HTTPS';
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https' ) {
            return 'HTTPS';
        } elseif (isset($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) != 'off') {
            return 'HTTPS';
        }else{
            return 'HTTP';
        }
    }


    public function isHttp()
    {
        return $this->getScheme() == 'HTTP';
    }


    public function isHttps()
    {
        return $this->getScheme() == 'HTTPS';
    }


    public function isAjax()
    {
        if ($this->isPost() || strtolower($this->getHeader('X-Requested-With')) == 'xmlhttprequest') {
            return true;
        }else{
            return false;
        }
    }


    public function hasFiles($onlySuccessful = false)
    {
        if ($onlySuccessful) {
            $file_array = array_filter($_FILES, function ($file) {
                return $file['error'] == 0;
            });
        }else{
            $file_array = $_FILES;
        }

        return $file_array == [];
    }


    public function getFiles($onlySuccessful = false)
    {
        if ($onlySuccessful) {
            $file_array = array_filter($_FILES, function ($file) {
                return $file['error'] == 0;
            });
        }else{
            $file_array = $_FILES;
        }

        return $file_array;
    }

    /**
     * 返回客户端请求的头信息
     * @return array
     * */
    protected function getAllHeaders()
    {
        if ($this->_headers === '') {

            if (function_exists('getallheaders')) {
                $this->_headers = getallheaders();
            }else{
                $headers = [];
                foreach ($_SERVER as $name => $value) {
                    if (substr($name, 0, 5) == 'HTTP_') {
                        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                    }
                }
                $this->_headers = $headers;
            }

        }

        return $this->_headers;
    }


    public function setDi(DiInterface $di)
    {
        $this->_di = $di;
    }


    public function getDi()
    {
        return $this->_di;
    }

}