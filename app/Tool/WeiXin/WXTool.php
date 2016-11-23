<?php

/**
 * @Author: Hongxuan
 * @Date:   2016-11-02 13:53:58
 * @Last Modified by:   Hongxuan
 * @Last Modified time: 2016-11-19 19:17:49
 */

namespace App\Tool\WeiXin;

use App\Http\Controllers\Controller;
use DB;
use Log;

class WXTool extends Controller
{
    // private $appid = 'wxe3ab8ff4359517ff';
    private $appid = 'wxce880daaccc9c542';

    // private $secret = 'f91af7f72044b8b52c85e9ebe1917137';
    private $secret = '7c312edeaf34a1d4c0d7dbfb5e584742';

    private $mpid = 'gh_800e6323fd33';
    private $mptoken = 'gh_800e6323fd33';

    /**
     * 微信登录
     * @return [type] [description]
     */
    public function wxLogin()
    {
        // session(['user' => null]);die;
        $user = session('user');
        if (empty($user) || empty($user['nickname'])) {
            $user = $this->getUserInfo();
        }
        print_r($user);



        // 1.用户同意，获取code
        // https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect

        // 2.通过code换取access_token
        // https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
        // 存储access_token到cache，有效期7200秒，Cache::('access_token')

        // 3.刷新access_token（如果有需要的话）

        // 4.通过access_token调用接口，获取用户信息
    }

    /**
     * 获得code
     * @param  [type] $APPID        [description]
     * @param  [type] $REDIRECT_URI [description]
     * @param  [type] $SCOPE        [snsapi_base, snsapi_userinfo, snsapi_login]
     * @param  [type] $STATE        [description]
     * @return [type]               [description]
     */
    public function getCode($appid, $redirect_uri, $scope = 'snsapi_base', $state = '')
    {
        // $appid = $this->appid;
        // $redirect_uri = $this->redirect_uri;
        // $scope = 'snsapi_base';
        // $state = '231';
        $redirect_uri = urlencode($redirect_uri);

        // https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe3ab8ff4359517ff&redirect_uri=http%3A%2F%2Fdcb.ngrok.cc%2Fadmin&response_type=code&scope=snsapi_base&state=123#wechat_redirect

        header("Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appid . "&redirect_uri=" . $redirect_uri . "&response_type=code&scope=" . $scope . "&state=" . $state . "#wechat_redirect");

        exit;
    }

    /**
     * 获取access_token，有效期7200秒
     * @param  [type] $APPID  [description]
     * @param  [type] $SECRET [description]
     * @param  [type] $CODE   [description]
     * @return [type]         [description]
     */
    public function getAccessToken($appid, $secret, $code = null)
    {
        // 开放平台——获取网页授权的access_token的URL
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $secret . "&code=" . $code . "&grant_type=authorization_code";
    }

    /**
     * 使用refresh_token刷新access_token，refresh_token的有效期30天
     * @return [type] [description]
     */
    public function refreshAccessToken($appid, $refresh_token)
    {

        $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=" . $appid . "&grant_type=refresh_token&refresh_token=" . $refresh_token;
    }

    /**
     * 获取用户信息
     * @param  [type] $ACCESS_TOKEN [description]
     * @param  [type] $OPENID       [description]
     * @return [type]               [description]$access_token, $openid
     */
    public function getUserInfo1()
    {
        // 获取网页应用的用户信息URL
        // $url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $openid;

        // 从session中查找用户的信息
        if ($user = session('user')) {
            if (empty($user['nickname'])) {
                $userEx = (Array) DB::table('users')->where('openid', $user['openid'])->first();
                $user = empty($userEx['nickname']) ? $user : $userEx;
            }
        }

        include __dir__ . "/CNFBSDK/Api.php";
        $api = new \Api($this->mptoken, $this->mpid);

        // 若session中没有保存用户的信息，获取用户openid，并从数据库找对应用户信息
        if (empty($user)) {
            session(['user' => null]);

            // if(!session("callBack")){
            //     unset($_GET["state"]);
            //     unset($_REQUEST["state"]);
            //     session(["callBack" => true]);
            // }else{
            //     session(["callBack" => null]);
            // }

            // if (!session("lastUrl")) {
            //     session(["lastUrl" => $api->currentUrl()]);
            // }

            // 先获取用户openid
            try {
                $user = $api->auth_openid();
            } catch (Exception $e) {
                if ($e->getCode == -2001) {
                    Log::error("openid获取失败：" . $e->getMessage());
                }
            }
            $userEx = (Array) DB::table('users')->where('openid', $user['openid'])->first();
            if ($userEx) {
                $openid = $userEx["openid"];
            } elseif (strlen($user["openid"]) == 28) {
                // 从数据库找不到用户时（即，用户还没授权过），则让用户授权，再获取用户信息，并将用户信息存到数据库和session
                //     try{
                //         // 这里可能有问题
                //         // Log::info("用户信息：");
                //         // $user = $api->auth_userinfo();
                //         // Log::info("用户信息：" . var_export($user, true));
                //         // file_put_contents(__dir__ . "/userurl.txt", var_export($user, true));
                //     }catch(Exception $e){
                //         if($e->getCode == -2001){
                //             Log::error("授权失败：" . $e->getMessage());
                //         }
                //     }
                $adduser = array(
                    'openid'       => $user['openid'],
                    'nickname'     => isset($user['nickname']) ? $user['nickname'] : '',
                    'sex'          => isset($user['sex']) ? $user['sex'] : 0,
                    'province'     => isset($user['province']) ? $user['province'] : '',
                    'city'         => isset($user['city']) ? $user['city'] : '',
                    'country'      => isset($user['country']) ? $user['country'] : '',
                    'head_img_url' => isset($user['headimgurl']) ? $user['headimgurl'] : '',
                );
                DB::table('users')->insert($adduser);

                $openid = $user["openid"];
            } else {
                Log::error("授权失败：" . var_export($user, true));
                throw new Exception("openid获取失败！");
            }
            // 从数据库查找用户
            $user = (Array) DB::table('users')->where('openid', $openid)->first();

            // $lastUrl = session("lastUrl");
            // session(["lastUrl" => null]);
            session(["user" => $user]);

            // Log::info("lastUrl = " . $lastUrl);
            // header("Location: " . $lastUrl);
            // exit;
        }

        // 从数据库找不到用户时（即，用户还没授权过），则让用户授权，再获取用户信息，并将用户信息存到数据库和session
        if (empty($user['nickname'])) {
            if(!session("call")){
                unset($_GET["state"]);
                unset($_REQUEST["state"]);
                session(["call" => true]);
            }else{
                session(["call" => null]);
            }

            try{
                $auth_user = $api->auth_userinfo();
                Log::info("用户信息：" . var_export($auth_user, true));
            }catch(Exception $e){
                if($e->getCode == -2001){
                    Log::error("授权失败：" . $e->getMessage());
                }
            }
            $adduser = array(
                'openid'       => $auth_user['openid'],
                'nickname'     => $auth_user['nickname'],
                'sex'          => $auth_user['sex'],
                'province'     => $auth_user['province'],
                'city'         => $auth_user['city'],
                'country'      => $auth_user['country'],
                'head_img_url' => $auth_user['headimgurl']
            );
            // 数据库中有openid记录则更新，否则新增用户
            if (DB::table('users')->where('openid', $user['openid'])->first()) {
                DB::table('users')->where('openid', $user['openid'])->update($adduser);
            } else {
                DB::table('users')->insert($adduser);
            }
            session(['user' => ((Array) DB::table('users')->where('openid', $auth_user['openid'])->first())]);
            return $auth_user;
        }

        return $user;
    }

    public function getUserInfo()
    {
        // 获取网页应用的用户信息URL
        // $url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $openid;

        // 从session中查找用户的信息
        if ($user = session('user')) {
            if (empty($user['nickname'])) {
                $userEx = (Array) DB::table('users')->where('openid', $user['openid'])->first();
                $user = empty($userEx['nickname']) ? $user : $userEx;
            }
        }

        // 若session中没有保存用户的信息，获取用户openid，并从数据库找对应用户信息
        if (empty($user)) {
            session(['user' => null]);

            // 先获取用户openid
            try {
                $api = new \Api($this->mptoken, $this->mpid);
                $user = $api->auth_openid();
            } catch (Exception $e) {
                if ($e->getCode == -2001) {
                    Log::error("openid获取失败：" . $e->getMessage());
                }
            }
            $userEx = (Array) DB::table('users')->where('openid', $user['openid'])->first();
            if ($userEx) {
                $openid = $userEx["openid"];
            } elseif (strlen($user["openid"]) == 28) {
                $adduser = array(
                    'openid'       => $user['openid'],
                    'nickname'     => isset($user['nickname']) ? $user['nickname'] : '',
                    'sex'          => isset($user['sex']) ? $user['sex'] : 0,
                    'province'     => isset($user['province']) ? $user['province'] : '',
                    'city'         => isset($user['city']) ? $user['city'] : '',
                    'country'      => isset($user['country']) ? $user['country'] : '',
                    'head_img_url' => isset($user['headimgurl']) ? $user['headimgurl'] : '',
                );
                DB::table('users')->insert($adduser);

                $openid = $user["openid"];
            } else {
                Log::error("授权失败：" . var_export($user, true));
                throw new Exception("openid获取失败！");
            }
            // 从数据库查找用户
            $user = (Array) DB::table('users')->where('openid', $openid)->first();

            session(["user" => $user]);
            Cache::put("user", $user, 1);
        }

        return $user;
    }

    public function getAccessUser()
    {
        if (!Cache::has('user')) {
            $user = $this->getUserInfo();
        }

        $user = Cache::get('user');

        if (!Cache::has('callBack')) {
            unset($_GET["state"]);
            unset($_REQUEST["state"]);
            Cache::put("callBack", true, 1);
        } else {
            Cache::forget('callBack');
        }

        if (empty($user['nickname']) || empty($user['head_img_url'])) {
            // 昵称或头像为空，获取微信用户信息
            $api = new \Api($this->mptoken, $this->mpid);

            try{
                $auth_user = $api->auth_userinfo();
                Log::info("用户信息：" . var_export($auth_user, true));
            }catch(Exception $e){
                if($e->getCode == -2001){
                    Log::error("授权失败：" . $e->getMessage());
                }
            }
            $adduser = array(
                'openid'       => $auth_user['openid'],
                'nickname'     => $auth_user['nickname'],
                'sex'          => $auth_user['sex'],
                'province'     => $auth_user['province'],
                'city'         => $auth_user['city'],
                'country'      => $auth_user['country'],
                'head_img_url' => $auth_user['headimgurl']
            );

            // 数据库中有openid记录则更新，否则新增用户
            if (DB::table('users')->where('openid', $user['openid'])->first()) {
                DB::table('users')->where('openid', $user['openid'])->update($adduser);
            } else {
                DB::table('users')->insert($adduser);
            }
            session(['user' => null]);
            session(['user' => ((Array) DB::table('users')->where('openid', $auth_user['openid'])->first())]);
            $user = session('user');
        }
        return $user;
    }

}
