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
}
