<?php
/**
 * NaiPass FrameWork Http Response Class
 * Http响应类
 * @author NaiZui
 * @mail chenxi2511@qq.com
 */
namespace NaiPass\Http;

use NaiPass\Di\DiManageInterface;
use NaiPass\Di\DiInterface;
use NaiPass\Traits\DiTool;

class Response implements ResponseInterface, DiManageInterface
{

    use DiTool;

    /**
     * @var \NaiPass\Di\DiInterface
     * */
    protected $_di = null;

    /**
     * @var int
     * */
    protected $_http_status;

    /**
     * @var string
     * */
    protected $_http_status_text;

    /**
     * @var string
     * */
    protected $_http_version;

    /**
     * @var string
     * */
    protected $_content;

    /**
     * @var array
     * */
    protected $_headers;


    public function setHeader($name, $values, $replace = true)
    {
        $name = strtolower($name);
        $values = array_values((array) $values);

        if ($replace) {
            $this->_headers[$name] = $values;
        }else{
            $this->_headers[$name] = array_merge($this->_headers[$name], $values);
        }

        return $this;
    }


    public function setStatusCode($code, $statusText = null)
    {
        $code = (int) $code;

        if (!isset(Status::$statusTexts[$code])) {
            throw new \UnexpectedValueException('Illegal HTTP state!');
        }

        if (is_string($statusText) && !empty($statusText)) {
            $this->_http_status_text = $statusText;
        }else{
            $this->_http_status_text = Status::$statusTexts[$code];
        }

        $this->_http_status = $code;
        return $this;
    }


    public function hasHeader($name)
    {
        $name = strtolower($name);

        return isset($this->_headers[$name]);
    }


    public function getHeader($name, $onlyFirst = true)
    {
        $name = strtolower($name);

        if (!$this->hasHeader($name)) {
            return false;
        }

        if ($onlyFirst) {
            return $this->_headers[$name][0];
        }else{
            return $this->_headers[$name];
        }

    }


    public function setContent($content)
    {
        if (null !== $content && !is_string($content) && !is_numeric($content) && !is_callable(array($content, '__toString'))) {
            throw new \UnexpectedValueException(sprintf('The Response content must be a string or object implementing __toString(), "%s" given.', gettype($content)));
        }

        $this->_content = (string) $content;

        return $this;
    }


    public function appEndContent($content)
    {
        if (null !== $content && !is_string($content) && !is_numeric($content) && !is_callable(array($content, '__toString'))) {
            throw new \UnexpectedValueException(sprintf('The Response content must be a string or object implementing __toString(), "%s" given.', gettype($content)));
        }

        $this->_content .= (string) $content;

        return $this;
    }


    public function getStatusCode()
    {
        return $this->_http_status;
    }


    public function getContent()
    {
        return $this->_content;
    }


    public function getHttpVersion()
    {
        return $this->_http_version;
    }


    public function setHttpVersion($version)
    {
        $this->_http_version = $version;
        return $this;
    }


    public function getRedirectUrl()
    {
        if ($this->hasHeader('location')) {
            return $this->getHeader('location', true);
        }else{
            return false;
        }
    }


    public function resetHeaders()
    {
        $this->_headers = [];
        return $this;
    }


    public function removeHeader($name)
    {
        $name = strtolower($name);

        unset($this->_headers[$name]);

        return $this;
    }


    public function redirect($location, $statusCode = 302)
    {
        $this->setStatusCode($statusCode);
        $this->setContent(
            sprintf('<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="refresh" content="1;url=%1$s" />

        <title>Redirecting to %1$s</title>
    </head>
    <body>
        Redirecting to <a href="%1$s">%1$s</a>.
    </body>
</html>', htmlspecialchars($location, ENT_QUOTES, 'UTF-8')));
        $this->setHeader('location', $location, true);

        return $this;
    }


    // http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
    /**
     * 判断是否为客户端错误
     * Is there a client error?
     * @return bool
     * @api
     */
    public function isClientError()
    {
        return $this->_http_status >= Status::HTTP_BAD_REQUEST
            && $this->_http_status < Status::HTTP_INTERNAL_SERVER_ERROR;
    }

    /**
     * Is response informative?
     * @return bool
     * @api
     */
    public function isInformational()
    {
        return $this->_http_status >= Status::HTTP_CONTINUE
            && $this->_http_status < Status::HTTP_OK;
    }

    /**
     * 判断是否为服务器内部错误
     * Was there a server side error?
     * @return bool
     * @api
     */
    public function isServerError()
    {
        return $this->_http_status >= Status::HTTP_INTERNAL_SERVER_ERROR
            && $this->_http_status < 600;
    }

    /**
     * 判断当前响应是否为200
     * Is the response OK?
     * @return bool
     * @api
     */
    public function isOk()
    {
        return Status::HTTP_OK === $this->_http_status;
    }

    /**
     * 判断当前响应是否为禁止访问
     * Is the response forbidden?
     * @return bool
     * @api
     */
    public function isForbidden()
    {
        return Status::HTTP_FORBIDDEN === $this->_http_status;
    }

    /**
     * 判断当前响应是否为404
     * Is the response a not found error?
     * @return bool
     * @api
     */
    public function isNotFound()
    {
        return Status::HTTP_NOT_FOUND === $this->_http_status;
    }

    /**
     * 判断响应是否为重定向
     * Is the response a redirect?
     * @return bool
     * @api
     */
    public function isRedirect()
    {
        return in_array($this->_http_status, array(
            Status::HTTP_CREATED, Status::HTTP_MOVED_PERMANENTLY, Status::HTTP_FOUND,
                Status::HTTP_SEE_OTHER, Status::HTTP_TEMPORARY_REDIRECT, Status::HTTP_PERMANENTLY_REDIRECT
            )) && ($this->getHeader('location'));
    }

    /**
     * 判断响应是否为空内容响应
     * Is the response empty?
     * @return bool
     * @api
     */
    public function isEmpty()
    {
        return in_array($this->_http_status, array(
            Status::HTTP_NO_CONTENT, Status::HTTP_NOT_MODIFIED
        ));
    }


    public function setDi(DiInterface $di)
    {
        $this->_di = $di;
        return $this;
    }


    public function getDi()
    {
        return $this->_di;
    }

}