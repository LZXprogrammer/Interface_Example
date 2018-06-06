<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdInfo extends Model
{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'promote_ad_info';

    /**
     * 不返回的字段。
     *
     * @var array
     */
    protected $hidden = [
      'id',
      'statue',
      'country_code',
      'update_time',
    ];

    /**
     * 数据表的关联
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function appadunitinfo()
    {
        return $this->belongsToMany('App\Models\AppAdunitInfo', 'promote_adunit_adinfo_relation', 'ad_id', 'ad_unit_id');
    }

}
