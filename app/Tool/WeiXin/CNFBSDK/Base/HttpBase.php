<?php 
include __dir__."/Json.php";
class HttpBase
{

    const GET     = 'GET';
    const POST    = 'POST';
    const PUT     = 'PUT';
    const PATCH   = 'PATCH';
    const DELETE  = 'DELETE';

    protected $curl;

    public function __construct()
    {
        $this->curl = curl_init();
    }

    public function __destruct()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }

    public function getCurl()
    {
        return $this->curl;
    }

    public function get($url, $params = array(), $options = array())
    {
        return $this->request($url, self::GET, $params, $options);
    }

    public function post($url, $params = array(), $options = array())
    {
        return $this->request($url, self::POST, $params, $options);
    }

    public function put($url, $params = array(), $options = array())
    {
        return $this->request($url, self::PUT, $params, $options);
    }

    public function patch($url, $params = array(), $options = array())
    {
        return $this->request($url, self::PATCH, $params, $options);
    }
	
    public function delete($url, $params = array(), $options = array())
    {
        return $this->request($url, self::DELETE, $params, $options);
    }

    protected function request($url, $method = self::GET, $params = array(), $options = array())
    {
        if ($method === self::GET || $method === self::DELETE) {
            $url .= (stripos($url, '?') ? '&' : '?').http_build_query($params);
			
            $params = array();
        }

        curl_setopt($this->curl, CURLOPT_HEADER, 1);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($this->curl, CURLOPT_URL, $url);


        //使用证书情况
        if(isset($options['sslcert_path']) && isset($options['sslkey_path']) ){
            if (!file_exists($options['sslcert_path']) ||!file_exists($options['sslkey_path'])  ) {
                throw new Exception('Certfile is not correct');
            }
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($this->curl,CURLOPT_SSL_VERIFYPEER,TRUE);
            curl_setopt($this->curl,CURLOPT_SSL_VERIFYHOST,2);//严格校验
            curl_setopt($this->curl,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($this->curl,CURLOPT_SSLCERT, $options['sslcert_path']);
            curl_setopt($this->curl,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($this->curl,CURLOPT_SSLKEY, $options['sslkey_path']);
        }
        else
        {
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);

        }

        // Check for files
        if (isset($options['files']) && count($options['files'])) {
            foreach ($options['files'] as $index => $file) {
                $params[$index] = $this->createCurlFile($file);
            }

            version_compare(PHP_VERSION, '5.5', '<') || curl_setopt($this->curl, CURLOPT_SAFE_UPLOAD, false);

            curl_setopt($this->curl, CURLOPT_POST, 1);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);
        } else {
            if (isset($options['json'])) {
                $params = JSON::encode($params);
                $options['headers'][] = 'content-type:application/json';
            }

            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);
        }

        // Check for custom headers
        if (isset($options['headers']) && count($options['headers'])) {
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $options['headers']);
        }

        // Check for basic auth
        if (isset($options['auth']['type']) && 'basic' === $options['auth']['type']) {
            curl_setopt($this->curl, CURLOPT_USERPWD, $options['auth']['username'].':'.$options['auth']['password']);
        }

        $response = $this->doCurl();

        // Separate headers and body
        $headerSize = $response['curl_info']['header_size'];
        $header     = substr($response['response'], 0, $headerSize);
        $body       = substr($response['response'], $headerSize);

        $results = array(
                    'curl_info'    => $response['curl_info'],
                    'content_type' => $response['curl_info']['content_type'],
                    'status'       => $response['curl_info']['http_code'],
                    'headers'      => $this->splitHeaders($header),
                    'data'         => $body,
                   );

        return $results;
    }

    protected function createCurlFile($filename)
    {
        if (function_exists('curl_file_create')) {
            return curl_file_create($filename);
        }

        return "@$filename;filename=".basename($filename);
    }

    protected function splitHeaders($rawHeaders)
    {
        $headers = array();

        $lines = explode("\n", trim($rawHeaders));
        $headers['HTTP'] = array_shift($lines);

        foreach ($lines as $h) {
            $h = explode(':', $h, 2);

            if (isset($h[1])) {
                $headers[$h[0]] = trim($h[1]);
            }
        }

        return $headers;
    }

    protected function doCurl()
    {
        $response = curl_exec($this->curl);
		
        if (curl_errno($this->curl)) {
            throw new Exception(curl_error($this->curl), 1);
        }

        $curlInfo = curl_getinfo($this->curl);

        $results = array(
                    'curl_info' => $curlInfo,
                    'response'  => $response,
                   );

        return $results;
    }
}
?>