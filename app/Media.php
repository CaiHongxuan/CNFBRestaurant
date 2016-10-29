<?php

/**
 * @Author: Hongxuan
 * @Date:   2016-10-08 15:06:57
 * @Last Modified by:   Hongxuan
 * @Last Modified time: 2016-10-27 10:41:38
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['url', 'thumbnail_url', 'type'];
}
