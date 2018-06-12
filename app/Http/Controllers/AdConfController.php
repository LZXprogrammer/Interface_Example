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

            // 判断传来的参数是否在正确范围内
            if (! $ad_units['ad_unit_id']) {
                throw new ApiException('ad_unit_id is required');
            }
            if ($max_request_count < 0 || $max_request_count == '' || ! is_numeric($max_request_count)) {
                throw new ApiException("The max_request_count of '{$ad_units['ad_unit_id']}' in the ad_unit_id is only be positive, not empty, negative, and other");
            }
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
                // 为下面返随机数做准备
                $id_max[$value['ad_unit_id']] = $value['max_request_count'];

                // 查询传来的包名，数据库中是否存在
                $app_adunits = AppAdunitInfo::where('package_name', $package_name)->get();
                if(count($app_adunits) == 0){
                    throw new ApiException('Package_name is Non-existent');
                }else{

                    // 判断此包名下有没有传来的版位

                    foreach ($app_adunits as $key => $app_adunit) {
                        $ad_unit_ids[] = $app_adunit->ad_unit_id;
                    }
                    if(! in_array($value['ad_unit_id'], $ad_unit_ids)){
                        throw new ApiException("This package_name does not have this '{$value['ad_unit_id']}'");
                    }

                }

                // 根据传来的参数获取数据
                $app_adunit_infos[] = AppAdunitInfo::where('package_name', $package_name)
                                    ->where('ad_unit_id', $value['ad_unit_id'])
                                    ->with(['adinfo' => function ($query) use ($where) {
                                            $query->where($where)->get();
                                    }])
                                    ->first()->toArray();
            }

            // 根据参数max_request_count 来决定是否随机返回
            foreach ($app_adunit_infos as $key => $value) {

                $max_request_count = $id_max[$value['ad_unit_id']];

                if ( ! empty($value['adinfo'])) {
                    $rand_adInfo_num = collect($value['adinfo'])->count();

                    if($max_request_count == 0){

                        $app_adunit_infos[$key]['adinfo'] = [];
                    }else{

                        // 传来的最大请求数量大于数据库查询的数量，全返回，否则，按请求数量随机返回数据
                        $max_request_count = $rand_adInfo_num >= $max_request_count
                                                        ? $max_request_count
                                                        : $rand_adInfo_num;

                        $rand_adInfo = collect($value['adinfo'])->random($max_request_count);

                        if(is_array($rand_adInfo)) {
                            $rand_adInfo = [$rand_adInfo,];
                        }

                        $app_adunit_infos[$key]['adinfo'] = is_array($rand_adInfo) ? $rand_adInfo : $rand_adInfo->toArray();
                    }
                }
            }

            // return $app_adunit_infos;

            if (is_null($app_adunit_infos)) {
                throw new ApiException('Data is empty');
            }

            // 拼接图片地址
            $pre = $request->getOSSPrefix($country_code);

            foreach ($app_adunit_infos as $key => $adinfos) {

                foreach ($adinfos['adinfo'] as $k => $adinfo) {

                    if(strncasecmp($adinfo['iconUrl'], 'http://', 7) !== 0 ){

                        $app_adunit_infos[$key]['adinfo'][$k]['iconUrl'] = $pre.$adinfo['iconUrl'];
                        $app_adunit_infos[$key]['adinfo'][$k]['imageUrl'] = $pre.$adinfo['imageUrl'];
                        $app_adunit_infos[$key]['adinfo'][$k]['adImageUrl'] = $pre.$adinfo['adImageUrl'];
                        $app_adunit_infos[$key]['adinfo'][$k]['bannerImageUrl'] = $pre.$adinfo['bannerImageUrl'];
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
