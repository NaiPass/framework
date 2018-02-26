<?php
/**
 * Request Interface
 */
namespace NaiPass\Http;

interface RequestInterface
{
    /**
     * 从请求参数中取值
     * @param $name string|null 参数名
     * @param $filters string|null 过滤器
     * @param $defaultValue mixed 默认值
     * @return mixed
     * */
    public function get($name = null, $filters = null, $defaultValue = null);

    /**
     * 从$_POST中取值
     * @param $name string|null 参数名
     * @param $filters string|null 过滤器
     * @param $defaultValue mixed 默认值
     * @return mixed
     * */
    public function getPost($name = null, $filters = null, $defaultValue = null);

    /**
     * 从$_GET中取值
     * @param $name string|null 参数名
     * @param $filters string|null 过滤器
     * @param $defaultValue mixed 默认值
     * @return mixed
     * */
    public function getQuery($name = null, $filters = null, $defaultValue = null);

    /**
     * 从$_SERVER中获取值
     * @param $name string|null 参数名
     * @param $filters string|null 过滤器
     * @param $defaultValue mixed 默认值
     * @return mixed
     * */
    public function getServer($name = null, $filters = null, $defaultValue = null);

    /**
     * 获取PUT请求的参数值
     * @param $name string|null 参数名
     * @param $filters string|null 过滤器
     * @param $defaultValue mixed 默认值
     * @return mixed
     * */
    public function getPut($name = null, $filters = null, $defaultValue = null);

    /**
     * 获取DELETE请求的参数值
     * @param $name string|null 参数名
     * @param $filters string|null 过滤器
     * @param $defaultValue mixed 默认值
     * @return mixed
     * */
    public function getDelete($name = null, $filters = null, $defaultValue = null);

    /**
     * 获取请求的原始数据
     * @return mixed
     * */
    public function getRawBody();

    /**
     * 获取请求的头数据
     * @param $name string|null 参数名
     * @param $filters string|null 过滤器
     * @param $defaultValue mixed 默认值
     * @return mixed
     * */
    public function getHeader($name = null, $filters = null, $defaultValue = null);

    /**
     * 获取请求方法
     * @return string
     * */
    public function getMethod();

    /**
     * 判断当前的请求方法是否为指定的方法
     * @param $name string
     * @return boolean
     * */
    public function isMethod($name);

    /**
     * 判断当前请求是否为post请求
     * @return boolean
     * */
    public function isPost();

    /**
     * 判断当前请求是否为get请求
     * @return boolean
     * */
    public function isGet();

    /**
     * 判断当前请求是否为put请求
     * @return boolean
     * */
    public function isPut();

    /**
     * 判断当前请求是否为Delete请求
     * @return boolean
     * */
    public function isDelete();

    /**
     * 判断当前请求是否为ajax请求
     * @return boolean
     * */
    public function isAjax();

    /**
     * 获取当前的请求协议
     * @return string HTTP or HTTPS
     * */
    public function getScheme();

    /**
     * 判断当前请求是否为http请求
     * @return boolean
     * */
    public function isHttp();

    /**
     * 判断当前请求是否为https请求
     * @return boolean
     * */
    public function isHttps();

    /**
     * 判断是否存在上传的文件
     * @param $onlySuccessful boolean
     * @return boolean
     * */
    public function hasFiles($onlySuccessful = false);

    /**
     * 获取已上传的文件
     * @param $onlySuccessful boolean
     * @return mixed
     * */
    public function getFiles($onlySuccessful = false);

}