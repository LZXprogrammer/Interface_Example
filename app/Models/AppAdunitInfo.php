<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppAdunitInfo extends Model
{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'promote_app_adunit_info';

    /**
     * 不返回的字段。
     *
     * @var array
     */
    protected $hidden = [
      'id',
      'update_time',
    ];

    /**
     * 数据表的关联
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function appadunitinfo()
    {
        return $this->belongsToMany('App\Models\AdInfo');
    }

}
