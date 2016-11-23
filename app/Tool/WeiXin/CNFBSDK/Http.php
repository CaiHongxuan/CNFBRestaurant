<?php
include __dir__ . "/Base/HttpBase.php";

class Http extends HttpBase
{
    protected $json = false;

    public function __construct($accesskey)
    {
        $this->accesskey = $accesskey;
        parent::__construct();
    }

    public function getUrl($url)
    {
        if ($this->accesskey) {
            $url .= (stripos($url, '?') ? '&' : '?') . $this->accesskey;
        }
        return $url;
    }
    /**
     * 发起一个HTTP/HTTPS的请求
     *
     * @param string $url     接口的URL
     * @param string $method  请求类型   GET | POST
     * @param array  $params  接口参数
     * @param array  $options 其它选项
     *
     * @return array | boolean
     */

    public function request($url, $method = self::GET, $params = array(), $options = array())
    {
        if ($this->accesskey) {
            $url .= (stripos($url, '?') ? '&' : '?') . $this->accesskey;
        }

        $method = strtoupper($method);

        if ($this->json) {
            $options['json'] = true;
        }

        $response = parent::request($url, $method, $params, $options);

        $this->json = false;

        if (empty($response['data'])) {
            throw new Exception('服务器无响应');
        }

        if (!preg_match('/^[\[\{]\"/', $response['data'])) {
            return $response['data'];
        }

        $contents = json_decode(substr(str_replace(array('\"', '\\/', "\\\\"), array('"', '/', "\\"), json_encode($response['data'])), 1, -1), true);

        // while the response is an invalid JSON structure, returned the source data
        if (JSON_ERROR_NONE !== json_last_error()) {
            return $response['data'];
        }

        if (isset($contents['errcode']) && 0 !== $contents['errcode']) {
            if (empty($contents['errmsg'])) {
                $contents['errmsg'] = 'Unknown';
            }

            throw new Exception("[{$contents['errcode']}] " . $contents['errmsg'], $contents['errcode']);
        }

        if ($contents === array('errcode' => '0', 'errmsg' => 'ok')) {
            return true;
        }

        return $contents;
    }

    public function __call($method, $args)
    {
        if (stripos($method, 'json') === 0) {
            $method = strtolower(substr($method, 4));
            $this->json = true;
        }

        $result = call_user_func_array(array($this, $method), $args);

        return $result;
    }

    public function currentUrl2()
    {
        $protocol = (!empty($_SERVER['HTTPS'])
            && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] === 443) ? 'https://' : 'http://';

        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
        } else {
            $host = $_SERVER['HTTP_HOST'];
        }
        return $protocol . $host . $_SERVER['REQUEST_URI'];
    }

    public function currentUrl()
    {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
        return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
    }
}
