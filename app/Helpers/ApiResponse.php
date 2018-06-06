<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use App\Facades\GeoIPFacade as GeoIP;

class ApiResponse
{
    /**
     * 创建响应数据.
     *
     * @param  array  $data
     * @param  string $msg
     * @param  int  $status
     * @return array
     */
    public static function responseData($data, $msg = '', $status = 1)
    {
        $response['status'] = $status;
        $response['msg'] = $msg;
        $response['server_time'] = time();
        $response['data'] = $data;

        return $response;
    }

    /**
     * 拼接 OSS 前缀.
     *
     * @param  object  $data
     * @param  array  $need
     * @param  string $prefix
     * @return array
     */
    public static function connectOSSPrefix($data, $need, $prefix)
    {
        if(is_array($data)) {
            foreach ( (array) $need as $value) {
                // 是否为空, 是否已经有前缀
                if( ! empty($data[$value] && strncasecmp($data[$value], 'http://', 7))) {
                    $data[$value] = $prefix.$data[$value];
                }
            }

            return $data;
        }

        if(is_object($data)) {
            foreach ( (array) $need as $value) {
                // 是否为空, 是否已经有前缀
                if( ! empty($data->$value && strncasecmp($data->$value, 'http://', 7))) {
                    $data->$value = $prefix.$data->$value;
                }
            }

            return $data;
        }
    }
}
