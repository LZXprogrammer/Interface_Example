<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiRequest;
use App\Helpers\ApiResponse;

use App\Models\AdAppCtry;

use App\Exceptions\ApiException;

class AdConfController extends Controller
{
    /**
     * 获取广告配置.
     *
     * @param  Illuminate\Http\Request  $request
     * @return Illuminate\Http\Response
     */
    public function getAdConf(Request $request)
    {
        // 构造请求参数
        $request = new ApiRequest($request);

        if( ! $request->has('package_name')) {
            throw new ApiException('package_name is required');
        }

        $package_name = $request->package_name;

        // 判断是否传来 country_code
        if ($request->has('country_code')) {

            $country_code = $request->country_code;

        } else {

            $country_code = is_null($request->getClientCountry()) ? NULL : $request->getClientCountry()->isoCode;
        }
        // 查询条件构造
        $where = [
          'package_name' => $package_name,
          'country_code' => $country_code,
        ];

        $others = [
          'package_name' => $package_name,
          'country_code' => 'others',
        ];

        // 获取数据
        try {

            $appCtry = $request->isImpCountry() ? (AdAppCtry::where($where)->first()) :
                       AdAppCtry::where($others)->first();

            if(is_null($appCtry)) {
                throw new ApiException('Data is empty');
            }

            $group = $appCtry->group;

            $pre = $request->getOSSPrefix($country_code);
            $group = ApiResponse::connectOSSPrefix($group, ['iconUrl', 'adImageUrl'], $pre);

        } catch (\Exception $e) {
            throw new ApiException($e->getMessage());
        }
        // 数据处理
        $rst = ApiResponse::responseData($group);
        $rst['country_code'] = $country_code;

        return $rst;
    }

}
