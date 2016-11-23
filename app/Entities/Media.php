<?php

namespace App\Entities;

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
