<?php

namespace App\Http\Controllers\Auth;

use itbdw\QiniuStorage\QiniuStorage;
use App\Entities\Admin;
use App\Entities\Media;
use Cache;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    // protected $redirectTo = '/home';
    protected $redirectTo = '/admin/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:admins',
            'password' => 'required|min:6|confirmed',
            'media' => 'image',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        Cache::forget('admins');

        // 若存在图片
        if (is_file($data['media'])) {

            $file = $data['media'];
            $file_size = round($file->getSize() / 1024);
            $file_ex = strtolower($file->getClientOriginalExtension());

            // 上传的图片不得大于2048kb，且图片应为指定格式
            if ($file_size <= 2048 && in_array($file_ex, array('jpg', 'jpeg', 'gif', 'png'))) {
                // 上传图片到七牛
                $store_name = $file->storeAs('admins', md5(date('ymdhis') . $file_size) . "." . $file_ex, 'qiniu');

                // 将保存到七牛上的图片地址存储到系统数据库
                $disk = QiniuStorage::disk('qiniu');
                $Media = new Media;
                $mediaData = array(
                    'url' => $disk->downloadUrl($store_name),
                    'thumbnail_url' => $disk->downloadUrl($store_name) . '?imageView2/0/w/200/h/200', // 缩略图地址
                    'type' => 'image',
                );
                if (!($media_id = $Media->create($mediaData)->id)) {
                    $disk->delete($store_name); // 保存图片地址到系统数据库失败时删除七牛上的对应图片
                    return redirect()->back()->withInput()->withErrors('保存失败！');
                }
            } else {
                return redirect()->back()->withInput()->withErrors('上传图片失败，请检查图片大小及格式是否正确！');
            }
        }

        return Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'media_id' => isset($media_id) ? $media_id : 0,
            'status' => intval($data['status'])
        ]);
    }
}
