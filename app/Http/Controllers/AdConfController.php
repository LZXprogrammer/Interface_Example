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
        // var_dump($request->country_code);die;
        if(! $request_arr['package_name']) {
            throw new ApiException('package_name is required');
        }

        $package_name = $request_arr['package_name'];

        $statue = $request_arr['statue'] ? $request_arr['statue'] : 1;

        foreach ($request_arr['ad_units'] as $key => $ad_units) {

            $ad_unit_id[] = $ad_units['ad_unit_id'];
            $max_request_count = $ad_units['max_request_count'];
        }

        if (! $ad_unit_id) {
            throw new ApiException('ad_unit_id is required');
        }
// var_dump($ad_unit_id);
        // 判断是否传来 country_code
        if ($request->has('country_code')) {

            $country_code = $request->country_code;

        } else {

            $country_code = is_null($request->getClientCountry()) ? NULL : $request->getClientCountry()->isoCode;
        }
        // 查询条件构造
        // $where = [
        //   'package_name' => $package_name,
        //   'ad_unit_id' => $ad_unit_id,
        // ];

        // $others = [
        //   'package_name' => $package_name,
        //   'country_code' => 'others',
        // ];
        // var_dump($request->all());die;

        // with(['adinfo' => function ($query) {
        //     $query->where('ad_id', '112');
        // }])

        // 获取数据
        try {

            $app_adunit_infos = AppAdunitInfo::where('package_name', $package_name)
                                ->whereIn('ad_unit_id', $ad_unit_id)
                                ->with('adinfo')
                                ->get();

            // $app_adunit_infos = AdInfo::find(2)->appadunitinfo;
            // var_dump($app_adunit_infos);die;
            // return $app_adunit_infos;


            if (is_null($app_adunit_infos)) {
                throw new ApiException('Data is empty');
            }

            $pre = $request->getOSSPrefix($country_code);

            foreach ($app_adunit_infos as $key => $adinfos) {
                // var_dump($adinfos->adinfo);
                foreach ($adinfos->adinfo as $k => $adinfo) {
                    // var_dump($adinfo->iconUrl);


                    if(strncasecmp($adinfo->iconUrl, 'http://', 7) !== 0 ){
                        $adinfo->iconUrl = $pre.$adinfo->iconUrl;
                        $adinfo->imageUrl = $pre.$adinfo->imageUrl;
                        $adinfo->adImageUrl = $pre.$adinfo->adImageUrl;
                        $adinfo->bannerImageUrl = $pre.$adinfo->bannerImageUrl;
                    }

                }
            }
            // var_dump($adinfo->iconUrl, $adinfo->imageUrl, $adinfo->adImageUrl, $adinfo->bannerImageUrl);

            // $ad_infos = $app_adunit_infos

            // return $app_adunit_infos;

        } catch (\Exception $e) {
            throw new ApiException($e->getMessage());
        }
        // die;
        // 数据处理
        $rst = ApiResponse::responseData($app_adunit_infos);
        $rst['country_code'] = $country_code;

        return $rst;
    }

}