<?php

include __dir__ . "/Http.php";

class Api
{
    protected $TOKEN;

    protected $APITOKEN;

    protected $Http;

    protected $Domain;

    public function __construct($TOKEN, $MPID, $token = null)
    {
        $this->Domain = "http://www.cainiaofabu.com/Api";
        $token = $token ? $token : $this->apiToken($TOKEN, $MPID);
        $this->Http = new Http("token=" . $token);
    }

    const API_TOKEN = '/Token/api_token';
    protected function apiToken($TOKEN, $MPID)
    {
        $http = new Http("token=" . $TOKEN);

        $param["mpid"] = $MPID;
        $param["token"] = $TOKEN;

        $token = $http->get($this->apiUrl(self::API_TOKEN), $param);

        return $this->APITOKEN = $token["token"];
    }

    public function getApiToken()
    {
        return $this->APITOKEN;
    }
    public function currentUrl()
    {
        return $this->Http->currentUrl();
    }

    protected function apiUrl($action)
    {
        return $this->Domain . $action;
    }

    const WX_AUTH_OPENID = '/WXAuth/openid_auth';
    const WX_AUTH_USERINFO = '/WXAuth/userinfo_auth';
    const WX_AUTH_USER = '/WXAuth/user';

    public function auth_openid($url = null)
    {

        if (empty($_GET["state"])) {

            $url = $url ? $url : $this->Http->currentUrl();
            // file_put_contents(__dir__ . "/test.txt", var_export($url, true));
            $url = $this->Http->getUrl($this->apiUrl(self::WX_AUTH_OPENID) . "?url=" . urlencode($url));
            // file_put_contents(__dir__ . "/openid_url.txt", var_export($url, true), FILE_APPEND);
            header("Location:" . $url);
            exit;
        } else {

            $url = $this->apiUrl(self::WX_AUTH_USER) . "?state=" . $_GET["state"];
            // file_put_contents(__dir__ . "/openid_url1.txt", var_export($url, true), FILE_APPEND);

            $user = $this->Http->jsonPost($url, array());
            // file_put_contents(__dir__ . "/openid.txt", var_export($user, true), FILE_APPEND);

            unset($_GET["code"]);
            unset($_GET["state"]);
            return $user;
        }

    }

    public function auth_userinfo($url = null)
    {
        if (empty($_GET["state"])) {

            $url = $url ? $url : $this->Http->currentUrl();

            // file_put_contents(__dir__ . "/userinfo_currentUrl.txt", var_export($url, true), FILE_APPEND);

            $url = $this->Http->getUrl($this->apiUrl(self::WX_AUTH_USERINFO) . "?url=" . urlencode($url));

            // file_put_contents(__dir__ . "/userinfo_url.txt", var_export($url, true), FILE_APPEND);

            header("Location:" . $url);
            exit;
        } else {

            $url = $this->apiUrl(self::WX_AUTH_USER) . "?state=" . $_GET["state"];
            // file_put_contents(__dir__ . "/userinfo_url1.txt", var_export($url, true), FILE_APPEND);

            $user = $this->Http->jsonPost($url, array());
            // file_put_contents(__dir__ . "/userinfo.txt", var_export($user, true), FILE_APPEND);

            unset($_GET["code"]);
            unset($_GET["state"]);
            return $user;
        }
    }

    const CUSTOM_SERVER_SEND = '/CustomService/send';
    const CUSTOM_SERVER_SENDALL = '/CustomService/sendAll';

    public function custom_server_send($openid, $message, $type = "text")
    {
        $data["message"] = $message;
        $data["type"] = $type;
        $data["openid"] = $openid;

        return $this->Http->jsonPost($this->apiUrl(self::CUSTOM_SERVER_SEND), $data);
    }
    public function custom_server_sendAll($message, $type = "text")
    {
        $data["message"] = $message;
        $data["type"] = $type;

        return $this->Http->jsonPost($this->apiUrl(self::CUSTOM_SERVER_SENDALL), $data);
    }

    const QRCODE_CREATE_FOREVER = '/QRCode/createforever';
    const QRCODE_URL = '/QRCode/url';
    const QRCODE_LISTS = '/QRCode/lists';
    const QRCODE_DELETE = '/QRCode/delete';

    public function qrcode_create($dataArray)
    {

        return $this->Http->jsonPost($this->apiUrl(self::QRCODE_CREATE_FOREVER), $dataArray);

    }
    public function qrcode_url($ticket)
    {

        $dataArray["ticket"] = $ticket;
        return $this->Http->jsonPost($this->apiUrl(self::QRCODE_URL), $dataArray);

    }
    public function qrcode_lists()
    {

        return $this->Http->jsonPost($this->apiUrl(self::QRCODE_LISTS), array());

    }
    public function qrcode_delete($scene)
    {

        $dataArray["scene"] = $scene;
        return $this->Http->jsonPost($this->apiUrl(self::QRCODE_DELETE), $dataArray);

    }

    const JSSDK_PACKAGE = '/JS/package';
    const JSSDK_GET_IMAGE = '/JS/get_image';
    public function jssdk_package($url = null)
    {

        $url = $url ? $url : $this->Http->currentUrl();
        $dataArray["url"] = $url;
        return $this->Http->jsonPost($this->apiUrl(self::JSSDK_PACKAGE), $dataArray);

    }

    public function jssdk_getWechatImage($media_id)
    {

        $dataArray["media_id"] = $media_id;
        return $this->Http->jsonPost($this->apiUrl(self::JSSDK_GET_IMAGE), $dataArray);

    }

    const KEYWORD_CREATE_SERVICE = '/Keyword/service';
    public function keyword_service($keyword, $title, $thumb_url, $url, $description)
    {

        $dataArray["keyword"] = $keyword;
        $dataArray["title"] = $title;
        $dataArray["thumb_url"] = $thumb_url;
        $dataArray["url"] = $url;
        $dataArray["description"] = $description;
        return $this->Http->jsonPost($this->apiUrl(self::KEYWORD_CREATE_SERVICE), $dataArray);

    }
    const STAT_FANS_COUNT = '/Stat/getFansCount';
    public function stat_getFansCount()
    {
        return $this->Http->jsonPost($this->apiUrl(self::STAT_FANS_COUNT), $dataArray);
    }

    const QUAN_GET_MEMBER = '/Quan/getMember';
    public function quan_get_member($quan_token)
    {
        $dataArray["quan_token"] = $quan_token;
        return $this->Http->jsonPost($this->apiUrl(self::QUAN_GET_MEMBER), $dataArray);
    }

}
