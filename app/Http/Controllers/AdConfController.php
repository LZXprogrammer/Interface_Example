<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiRequest;
use App\Helpers\ApiResponse;

use App\Models\AdInfo;
use App\Models\AppAdunitInfo;

use App\Exceptions\ApiException;

class AdConfController extends Controller
{
    /**
     * 获取广告配置.
     *
     * @param  Illuminate\Http\Request  $request
     * @return Illuminate\Http\Response
     */
    public function getAdinfo(Request $request)
    {
        // 构造请求参数
        $request = new ApiRequest($request);

        $request_json = $request->getContent();
        $request_arr  = json_decode($request_json, true);

        // 判断必要参数是否传值
        if( ! array_key_exists('package_name', $request_arr)){

            throw new ApiException('package_name is required');
        } else if( empty($request_arr['package_name']) ) {

            throw new ApiException('package_name is required');
        } else if( ! array_key_exists('ad_units', $request_arr) ) {

            throw new ApiException('ad_units is required');
        } else if( empty($request_arr['ad_units']) ) {

            throw new ApiException('ad_units is required');
        }

        // 接受传来的参数
        $package_name = $request_arr['package_name'];
        $status = $request_arr['status'] ? $request_arr['status'] : 1;

        foreach ($request_arr['ad_units'] as $key => $ad_units) {

            $ad_unit_id[] = $ad_units['ad_unit_id'];
            $max_request_count = $ad_units['max_request_count'];
        }

        // 判断传来的参数是否在正确范围内
        if (! $ad_unit_id) {
            throw new ApiException('ad_unit_id is required');
        }
        if (empty($max_request_count) || ($max_request_count < 0)) {
            throw new ApiException('max_request_count is not null or negative');
        }

        // 判断是否传来 country_code
        if ($request->has('country_code')) {

            $country_code = $request->country_code;

        } else {

            $country_code = is_null($request->getClientCountry()) ? NULL : $request->getClientCountry()->isoCode;
        }
        // 查询条件构造
        $where = [
          'status' => $status,
          'country_code' => 'default',
        ];

        if($status == 1) {
            unset($where['status']);
        }
        // 获取数据
        try {
            $id_max = [];
            $app_adunit_infos = [];

            foreach ($request_arr['ad_units'] as $key => $value) {

                $id_max[$value['ad_unit_id']] = $value['max_request_count'];

                $app_adunit_info_num = AppAdunitInfo::where('package_name', $package_name)->count();

                if($app_adunit_info_num == 0){
                    throw new ApiException('package_name is Non-existent');
                }

                $app_adunit_infos[] = AppAdunitInfo::where('package_name', $package_name)
                                ->where('ad_unit_id', $value['ad_unit_id'])
                                ->with(['adinfo' => function ($query) use ($where) {
                                        $query->where($where)->get();
                                }])
                                ->first()->toArray();
            }

            foreach ($app_adunit_infos as $key => $value) {

                $max_request_count = $id_max[$value['ad_unit_id']];

                $rand_adInfo_num = collect($value['adinfo'])->count();

                $max_request_count = $rand_adInfo_num >= $max_request_count
                                                      ? $max_request_count
                                                      : $rand_adInfo_num;

                $rand_adInfo = collect($value['adinfo'])->random($max_request_count);

                if(is_array($rand_adInfo)) {
                    $rand_adInfo = [$rand_adInfo,];
                }

                $app_adunit_infos[$key]['adinfo'] = $rand_adInfo;
            }

            // return $app_adunit_infos;

            if (is_null($app_adunit_infos)) {
                throw new ApiException('Data is empty');
            }

            $pre = $request->getOSSPrefix($country_code);

            foreach ($app_adunit_infos as $key => $adinfos) {

                foreach ($adinfos['adinfo'] as $k => $adinfo) {

                    if(strncasecmp($adinfo['iconUrl'], 'http://', 7) !== 0 ){
                        $adinfo['iconUrl'] = $pre.$adinfo['iconUrl'];
                        $adinfo['imageUrl'] = $pre.$adinfo['imageUrl'];
                        $adinfo['adImageUrl'] = $pre.$adinfo['adImageUrl'];
                        $adinfo['bannerImageUrl'] = $pre.$adinfo['bannerImageUrl'];
                    }
                }
            }

        } catch (\Exception $e) {
            throw new ApiException($e->getMessage());
        }

        // 数据处理
        $rst = ApiResponse::responseData($app_adunit_infos);
        $rst['country_code'] = $country_code;

        return $rst;
    }
}
