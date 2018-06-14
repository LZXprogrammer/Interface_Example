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

    // 自定义主键
    protected $primaryKey = 'ad_unit_id';

    // 去除主键自增属性
    public $incrementing = false;

    /**
     * 不返回的字段。
     *
     * @var array
     */
    protected $hidden = [
      'id',
      'update_time',
      'pivot',
      'app_name',
      'desc',
    ];

    /**
     * 数据表的关联
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function adinfo()
    {
        return $this->belongsToMany('App\Models\AdInfo', 'promote_adunit_adinfo_relation', 'ad_unit_id', 'ad_id');
    }

}
